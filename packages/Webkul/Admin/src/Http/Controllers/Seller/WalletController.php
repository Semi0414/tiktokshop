<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\SuperAdmin\Models\CryptoPayoutAddress;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerDepositMethodConfig;
use Webkul\User\Models\SellerWalletTransaction;

class WalletController extends Controller
{
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Display wallet balance + recent transactions.
     */
    public function index()
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();
        $walletType = (string) request()->query('wallet_type', 'all');

        $totalCredits = SellerWalletTransaction::query()
            ->where('seller_id', $seller->id)
            ->where('type', 'credit')
            ->where('status', SellerWalletTransaction::STATUS_COMPLETED)
            ->sum('amount');

        $transactionsQuery = SellerWalletTransaction::query()
            ->where('seller_id', $seller->id)
            ->latest();

        if ($walletType === 'deposit') {
            $transactionsQuery->where('type', 'credit');
        } elseif ($walletType === 'withdraw') {
            $transactionsQuery->where('type', 'debit');
        } else {
            $walletType = 'all';
        }

        $transactions = $transactionsQuery
            ->paginate(10)
            ->withQueryString();

        $depositMethods = SellerDepositMethodConfig::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $resolvedDepositAddresses = $this->resolveDepositDisplayAddresses($depositMethods);

        return view('admin::seller.wallet.index', [
            'seller' => $seller,
            'totalCredits' => (float) $totalCredits,
            'transactions' => $transactions,
            'depositMethods' => $depositMethods,
            'resolvedDepositAddresses' => $resolvedDepositAddresses,
            'bankDepositMethodCodes' => config('seller-wallet.bank_deposit_method_codes', ['BANK_CARD']),
            'walletType' => $walletType,
        ]);
    }

    /**
     * Map each deposit method to display address from Super Admin crypto_payout_addresses when configured.
     *
     * @param  Collection<int, SellerDepositMethodConfig>  $depositMethods
     * @return array<string, string>
     */
    protected function resolveDepositDisplayAddresses($depositMethods): array
    {
        $map = config('seller-wallet.deposit_method_to_crypto_network', []);
        $bankCodes = config('seller-wallet.bank_deposit_method_codes', ['BANK_CARD']);

        $byNetworkType = CryptoPayoutAddress::query()
            ->orderBy('id')
            ->get()
            ->groupBy('network_type')
            ->map(fn ($group) => $group->first());

        $out = [];

        foreach ($depositMethods as $m) {
            if (in_array($m->code, $bankCodes, true)) {
                $out[$m->code] = '';

                continue;
            }

            $networkType = $map[$m->code] ?? null;

            if ($networkType !== null) {
                $row = $byNetworkType->get($networkType);
                $out[$m->code] = $row
                    ? (string) $row->address
                    : (string) __('admin::app.seller.wallet.deposit-address-not-configured');
            } else {
                $out[$m->code] = (string) ($m->address_text ?? '');
            }
        }

        return $out;
    }

    public function depositRequest(Request $request): RedirectResponse
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();

        $bankCodes = config('seller-wallet.bank_deposit_method_codes', ['BANK_CARD']);
        if (in_array((string) $request->input('payment_method'), $bankCodes, true)) {
            return back()->withErrors(['payment_method' => __('admin::app.seller.wallet.deposit-bank-not-online')]);
        }

        $data = $request->validate([
            'payment_method' => 'required|string|max:64',
            'amount' => 'required|numeric|gt:0',
            'receipt' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,webp',
        ]);

        $method = SellerDepositMethodConfig::query()
            ->where('code', $data['payment_method'])
            ->where('is_active', true)
            ->first();

        if (! $method) {
            return back()->withErrors(['payment_method' => __('admin::app.seller.wallet.invalid-deposit-method')]);
        }

        $receiptPath = $request->file('receipt')->store('seller-deposits', 'public');

        $balance = (float) ($seller->wallet_balance ?? 0);

        SellerWalletTransaction::create([
            'seller_id' => $seller->id,
            'amount' => (float) $data['amount'],
            'type' => 'credit',
            'status' => SellerWalletTransaction::STATUS_PENDING,
            'kind' => SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
            'payment_method' => $method->code,
            'meta' => [
                'method_name' => $method->name,
                'network' => $method->network,
            ],
            'receipt_path' => $receiptPath,
            'description' => __('admin::app.seller.wallet.deposit-request-desc', ['method' => $method->name]),
            'balance_before' => $balance,
            'balance_after' => $balance,
        ]);

        $this->notificationRepository->createForSellerAdmin([
            'type' => 'wallet_deposit_request',
            'seller_id' => $seller->id,
            'summary' => __('admin::app.notifications.seller-events.deposit-request-summary'),
            'action_route' => 'admin.wallet.index',
            'action_params' => ['wallet_type' => 'deposit'],
        ]);

        return back()->with('success', __('admin::app.seller.wallet.deposit-request-success'));
    }

    public function withdrawRequest(Request $request): RedirectResponse
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();
        $seller->refresh();

        $base = $request->validate([
            'withdraw_method' => 'required|string|max:64',
            'amount' => 'required|numeric|gt:0',
        ]);

        $method = SellerDepositMethodConfig::query()
            ->where('code', $base['withdraw_method'])
            ->where('is_active', true)
            ->first();

        if (! $method) {
            return back()->withErrors(['withdraw_method' => __('admin::app.seller.wallet.invalid-withdraw-method')]);
        }

        if ($base['withdraw_method'] === 'BANK_CARD') {
            $request->validate([
                'bank_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
            ]);
        } else {
            $request->validate([
                'destination_address' => 'required|string|max:512',
                'address_label' => 'nullable|string|max:255',
            ]);
        }

        if (! $seller->isSellerAccountActive()) {
            return back()->withErrors(['amount' => __('admin::app.seller.wallet.withdraw-account-inactive')]);
        }

        if ((int) $seller->credit_score !== 100) {
            return back()->withErrors(['amount' => __('admin::app.seller.wallet.withdraw-credit-not-100')]);
        }

        $amount = round((float) $base['amount'], 2);

        $meta = [
            'method_name' => $method->name,
            'network' => $method->network,
        ];

        if ($base['withdraw_method'] === 'BANK_CARD') {
            $meta['bank_name'] = (string) $request->input('bank_name', '');
            $meta['account_number'] = (string) $request->input('account_number', '');
        } else {
            $meta['destination_address'] = (string) $request->input('destination_address', '');
            $meta['address_label'] = (string) $request->input('address_label', '');
        }

        try {
            DB::transaction(function () use ($seller, $amount, $method, $meta) {
                /** @var Admin $locked */
                $locked = Admin::query()->whereKey($seller->id)->lockForUpdate()->firstOrFail();
                $balanceBefore = round((float) ($locked->wallet_balance ?? 0), 2);

                if ($balanceBefore < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => [__('admin::app.seller.wallet.withdraw-insufficient-balance')],
                    ]);
                }

                $balanceAfter = round($balanceBefore - $amount, 2);
                $locked->wallet_balance = $balanceAfter;
                $locked->save();

                SellerWalletTransaction::create([
                    'seller_id' => $locked->id,
                    'amount' => $amount,
                    'type' => 'debit',
                    'status' => SellerWalletTransaction::STATUS_PENDING,
                    'kind' => SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
                    'payment_method' => $method->code,
                    'meta' => $meta,
                    'description' => __('admin::app.seller.wallet.withdraw-request-desc', ['method' => $method->name]),
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                ]);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        $this->notificationRepository->createForSellerAdmin([
            'type' => 'wallet_withdraw_request',
            'seller_id' => $seller->id,
            'summary' => __('admin::app.notifications.seller-events.withdraw-request-summary'),
            'action_route' => 'admin.wallet.index',
            'action_params' => ['wallet_type' => 'withdraw'],
        ]);

        return back()->with('success', __('admin::app.seller.wallet.withdraw-request-success'));
    }
}
