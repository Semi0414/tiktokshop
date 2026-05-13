<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sales;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerWalletTransaction;
use Webkul\Notification\Repositories\NotificationRepository;

class SellerWalletRequestController extends Controller
{
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Seller deposit / withdraw requests (pending, approved, rejected).
     */
    public function index(Request $request): View
    {
        $type = $request->query('type');
        $statusFilter = $request->query('status');

        $query = SellerWalletTransaction::query()
            ->with(['seller:id,name,email'])
            ->whereIn('kind', [
                SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
                SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
            ])
            ->whereIn('status', [
                SellerWalletTransaction::STATUS_PENDING,
                SellerWalletTransaction::STATUS_COMPLETED,
                SellerWalletTransaction::STATUS_REJECTED,
            ]);

        if ($type === 'deposit') {
            $query->where('kind', SellerWalletTransaction::KIND_DEPOSIT_REQUEST);
        } elseif ($type === 'withdraw') {
            $query->where('kind', SellerWalletTransaction::KIND_WITHDRAW_REQUEST);
        }

        if ($statusFilter === 'pending') {
            $query->where('status', SellerWalletTransaction::STATUS_PENDING);
        } elseif ($statusFilter === 'approved') {
            $query->where('status', SellerWalletTransaction::STATUS_COMPLETED);
        } elseif ($statusFilter === 'rejected') {
            $query->where('status', SellerWalletTransaction::STATUS_REJECTED);
        }

        $requests = $query->latest()->paginate(25)->withQueryString();

        return view('superadmin::sales.wallet-requests.index', [
            'requests' => $requests,
            'type' => $type,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Reject a pending request. Deposits: status only. Withdrawals: return withheld funds to seller wallet.
     */
    public function reject(SellerWalletTransaction $transaction): RedirectResponse
    {
        if (
            $transaction->status !== SellerWalletTransaction::STATUS_PENDING
            || ! in_array($transaction->kind, [
                SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
                SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
            ], true)
        ) {
            return redirect()
                ->route('superadmin.sales.wallet-requests.index')
                ->with('error', trans('superadmin::app.sales.wallet-requests.invalid-request'));
        }

        try {
            DB::transaction(function () use ($transaction) {
                /** @var SellerWalletTransaction $locked */
                $locked = SellerWalletTransaction::query()
                    ->whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $locked->status !== SellerWalletTransaction::STATUS_PENDING
                    || ! in_array($locked->kind, [
                        SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
                        SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
                    ], true)
                ) {
                    throw ValidationException::withMessages([
                        'request' => [trans('superadmin::app.sales.wallet-requests.already-processed')],
                    ]);
                }

                if ($locked->kind === SellerWalletTransaction::KIND_WITHDRAW_REQUEST) {
                    /** @var Admin $seller */
                    $seller = Admin::query()
                        ->whereKey($locked->seller_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $refund = round((float) $locked->amount, 2);
                    $balanceBefore = round((float) ($seller->wallet_balance ?? 0), 2);
                    $balanceAfter = round($balanceBefore + $refund, 2);
                    $seller->wallet_balance = $balanceAfter;
                    $seller->save();

                    SellerWalletTransaction::create([
                        'seller_id' => $seller->id,
                        'amount' => $refund,
                        'type' => 'credit',
                        'status' => SellerWalletTransaction::STATUS_COMPLETED,
                        'kind' => SellerWalletTransaction::KIND_WITHDRAW_REJECTION_REFUND,
                        'description' => trans('superadmin::app.sales.wallet-requests.withdraw-rejection-remark'),
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'meta' => [
                            'refund_for_transaction_id' => $locked->id,
                        ],
                    ]);
                }

                $locked->status = SellerWalletTransaction::STATUS_REJECTED;
                $locked->save();
            });
        } catch (ValidationException $e) {
            return redirect()
                ->route('superadmin.sales.wallet-requests.index')
                ->withErrors($e->errors());
        }

        $transaction->refresh();

        if ($transaction->status === SellerWalletTransaction::STATUS_REJECTED) {
            $this->notifySellerWalletDecision($transaction, 'rejected');
        }

        return redirect()
            ->route('superadmin.sales.wallet-requests.index')
            ->with('success', trans('superadmin::app.sales.wallet-requests.rejected-success'));
    }

    /**
     * Approve a pending request: credit deposit, or finalize withdraw (funds already withheld when seller requested).
     */
    public function approve(Request $request, SellerWalletTransaction $transaction): RedirectResponse
    {
        if (
            $transaction->status !== SellerWalletTransaction::STATUS_PENDING
            || ! in_array($transaction->kind, [
                SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
                SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
            ], true)
        ) {
            return redirect()
                ->route('superadmin.sales.wallet-requests.index')
                ->with('error', trans('superadmin::app.sales.wallet-requests.invalid-request'));
        }

        $data = $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        $amount = round((float) $data['amount'], 2);

        try {
            DB::transaction(function () use ($transaction, $amount) {
                /** @var SellerWalletTransaction $locked */
                $locked = SellerWalletTransaction::query()
                    ->whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $locked->status !== SellerWalletTransaction::STATUS_PENDING
                    || ! in_array($locked->kind, [
                        SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
                        SellerWalletTransaction::KIND_WITHDRAW_REQUEST,
                    ], true)
                ) {
                    throw ValidationException::withMessages([
                        'request' => [trans('superadmin::app.sales.wallet-requests.already-processed')],
                    ]);
                }

                /** @var Admin $seller */
                $seller = Admin::query()
                    ->whereKey($locked->seller_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $balanceBefore = (float) ($seller->wallet_balance ?? 0);

                if ($locked->kind === SellerWalletTransaction::KIND_DEPOSIT_REQUEST) {
                    $balanceAfter = round($balanceBefore + $amount, 2);
                    $seller->wallet_balance = $balanceAfter;
                    $seller->save();

                    $locked->amount = $amount;
                    $locked->type = 'credit';
                    $locked->status = SellerWalletTransaction::STATUS_COMPLETED;
                    $locked->balance_before = $balanceBefore;
                    $locked->balance_after = $balanceAfter;
                    $locked->save();
                } else {
                    $requestedAmount = round((float) $locked->amount, 2);
                    $approvedAmount = $amount;

                    $diff = round($requestedAmount - $approvedAmount, 2);
                    if ($diff > 0) {
                        $seller->wallet_balance = round($balanceBefore + $diff, 2);
                        $seller->save();
                    } elseif ($diff < 0) {
                        $extra = abs($diff);
                        if ($balanceBefore < $extra) {
                            throw ValidationException::withMessages([
                                'amount' => [trans('superadmin::app.sales.wallet-requests.insufficient-seller-balance')],
                            ]);
                        }
                        $seller->wallet_balance = round($balanceBefore - $extra, 2);
                        $seller->save();
                    }

                    $seller->refresh();
                    $locked->amount = $approvedAmount;
                    $locked->type = 'debit';
                    $locked->status = SellerWalletTransaction::STATUS_COMPLETED;
                    $locked->balance_after = round((float) ($seller->wallet_balance ?? 0), 2);
                    $locked->save();
                }
            });
        } catch (ValidationException $e) {
            return redirect()
                ->route('superadmin.sales.wallet-requests.index')
                ->withErrors($e->errors());
        }

        $transaction->refresh();

        if ($transaction->status === SellerWalletTransaction::STATUS_COMPLETED) {
            $this->notifySellerWalletDecision($transaction, 'approved', $amount);
        }

        return redirect()
            ->route('superadmin.sales.wallet-requests.index')
            ->with('success', trans('superadmin::app.sales.wallet-requests.approved-success'));
    }

    /**
     * In-app notification for the seller admin when a wallet request is approved or rejected.
     */
    protected function notifySellerWalletDecision(SellerWalletTransaction $tx, string $decision, ?float $approvedAmount = null): void
    {
        if (! $tx->seller_id) {
            return;
        }

        $isDeposit = $tx->kind === SellerWalletTransaction::KIND_DEPOSIT_REQUEST;
        $walletTab = $isDeposit ? 'deposit' : 'withdraw';

        if ($decision === 'approved') {
            $amt = $approvedAmount ?? (float) $tx->amount;
            $type = $isDeposit ? 'wallet_deposit_approved' : 'wallet_withdraw_approved';
            $key = $isDeposit
                ? 'admin::app.notifications.seller-events.deposit-approved-summary'
                : 'admin::app.notifications.seller-events.withdraw-approved-summary';
            $summary = __($key, ['amount' => number_format($amt, 2)]);
        } else {
            $type = $isDeposit ? 'wallet_deposit_rejected' : 'wallet_withdraw_rejected';
            $key = $isDeposit
                ? 'admin::app.notifications.seller-events.deposit-rejected-summary'
                : 'admin::app.notifications.seller-events.withdraw-rejected-summary';
            $summary = __($key);
        }

        $this->notificationRepository->createForSellerAdmin([
            'type' => $type,
            'seller_id' => (int) $tx->seller_id,
            'summary' => $summary,
            'action_route' => 'admin.wallet.index',
            'action_params' => ['wallet_type' => $walletTab],
        ]);
    }
}
