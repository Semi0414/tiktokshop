<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sellers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Observers\SellerOrderCommissionObserver;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\SuperAdmin\DataGrids\Sellers\SellerOrderDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerWalletTransaction;
use Webkul\User\Support\SellerCommissionPercentRules;

class SellerOrderController extends Controller
{
    public function __construct(protected OrderRepository $orderRepository) {}

    public function index(): View|JsonResponse
    {
        return $this->superadminListing('superadmin::sellers.orders.index', [
            'sellerOrderStats' => $this->sellerOrderStats(),
        ], SellerOrderDataGrid::class);
    }

    /**
     * Modern seller orders dashboard using dynamic database data.
     */
    public function dashboard(): View
    {
        $prefix = DB::getTablePrefix();

        $sellerShopNameSelect = Schema::hasTable('seller_applications')
            ? DB::raw('(SELECT sa.shop_name FROM '.$prefix.'seller_applications sa WHERE sa.seller_id = '.$prefix.'orders.seller_id ORDER BY sa.id DESC LIMIT 1) as seller_shop_name')
            : DB::raw('NULL as seller_shop_name');

        $orders = DB::table('orders')
            ->leftJoin('seller', 'orders.seller_id', '=', 'seller.id')
            ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
                $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
                    ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
            })
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->whereNotNull('orders.seller_id')
            ->whereNotNull('seller.id')
            ->select(
                'orders.id',
                'orders.increment_id',
                'orders.base_grand_total',
                'orders.created_at',
                'orders.channel_name',
                'orders.channel_id',
                'orders.status',
                'orders.seller_approval_status',
                'seller.name as seller_name',
                'seller.email as seller_email',
                'seller.seller_level as seller_level',
                $sellerShopNameSelect,
                'orders.customer_email',
                DB::raw('COALESCE((SELECT COUNT(*) FROM '.$prefix.'order_items oi WHERE oi.order_id = '.$prefix.'orders.id AND oi.parent_id IS NULL), 0) as items_count'),
                DB::raw('GROUP_CONCAT('.$prefix.'order_payment.method SEPARATOR "|") as method'),
                DB::raw('CONCAT('.$prefix.'orders.customer_first_name, " ", '.$prefix.'orders.customer_last_name) as full_name'),
                DB::raw('CONCAT('.$prefix.'order_address_billing.city, ", ", '.$prefix.'order_address_billing.state,", ", '.$prefix.'order_address_billing.country) as location'),
                DB::raw('(SELECT COALESCE(SUM(ROUND(oi.base_total * COALESCE(ssp.commission_percent, 0) / 100, 2)), 0)
                    FROM '.$prefix.'order_items oi
                    LEFT JOIN '.$prefix.'seller_store_products ssp ON ssp.seller_id = '.$prefix.'orders.seller_id AND ssp.product_id = oi.product_id
                    WHERE oi.order_id = '.$prefix.'orders.id AND oi.parent_id IS NULL) as commission_total'),
                DB::raw('(SELECT pi.path
                    FROM '.$prefix.'order_items oi
                    LEFT JOIN '.$prefix.'product_images pi ON pi.product_id = oi.product_id
                    WHERE oi.order_id = '.$prefix.'orders.id AND oi.parent_id IS NULL
                    ORDER BY oi.id ASC, pi.position ASC, pi.id ASC
                    LIMIT 1) as first_image_path')
            )
            ->groupBy('orders.id')
            ->orderByDesc('orders.created_at')
            ->get();

        $rows = $orders->map(function ($order): array {
            $status = $this->mapStatusLabel((string) ($order->status ?? ''), $order->seller_approval_status);
            $sellerShop = trim((string) ($order->seller_shop_name ?? ''));
            $sellerName = trim((string) ($order->seller_name ?? ''));
            $customerName = trim((string) ($order->full_name ?? ''));
            $paymentMethods = collect(explode('|', (string) ($order->method ?? '')))
                ->map(fn ($method) => core()->getConfigData('sales.payment_methods.'.$method.'.title') ?: Str::headline($method))
                ->filter()
                ->unique()
                ->join(', ');

            return [
                'id' => (int) $order->id,
                'increment_id' => (string) ($order->increment_id ?: $order->id),
                'seller_store' => $sellerShop !== '' ? $sellerShop : ($sellerName !== '' ? $sellerName : '—'),
                'seller_name' => $sellerName !== '' ? $sellerName : '—',
                'seller_level' => SellerCommissionPercentRules::normalizeLevel($order->seller_level ?? null),
                'seller_email' => (string) ($order->seller_email ?: 'N/A'),
                'status' => $status,
                'db_status' => (string) ($order->status ?? ''),
                'status_raw' => (string) ($order->seller_approval_status ?? ''),
                'amount' => round((float) ($order->base_grand_total ?? 0), 2),
                'amount_formatted' => core()->formatPrice(round((float) ($order->base_grand_total ?? 0), 2)),
                'commission' => round((float) ($order->commission_total ?? 0), 2),
                'pay' => $paymentMethods !== '' ? $paymentMethods : 'N/A',
                'channel' => (string) ($order->channel_id ?: '—'),
                'store_name' => (string) ($order->channel_name ?: '—'),
                'customer' => $customerName !== '' ? $customerName : 'Guest',
                'customer_email' => (string) ($order->customer_email ?: 'N/A'),
                'buyer' => $customerName !== '' ? $customerName : 'Guest',
                'buyer_email' => (string) ($order->customer_email ?: 'N/A'),
                'location' => trim((string) ($order->location ?? '')) !== '' ? (string) $order->location : '—',
                'items' => (int) ($order->items_count ?? 0),
                'date' => $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') : null,
                'image' => ! empty($order->first_image_path) ? Storage::url($order->first_image_path) : null,
            ];
        })->values();

        $completedRevenue = $rows
            ->where('status', 'Completed')
            ->sum('amount');

        return view('superadmin::sellers.orders.dashboard', [
            'dashboardOrders' => $rows,
            'dashboardStats' => [
                'total' => $rows->count(),
                'completed' => $rows->where('status', 'Completed')->count(),
                'pending' => $rows->where('status', 'Pending')->count(),
                'processing' => $rows->where('status', 'Processing')->count(),
                'rejected' => $rows->where('status', 'Rejected')->count(),
                'revenue_formatted' => core()->formatPrice(round((float) $completedRevenue, 2)),
            ],
            'bulkUrl' => route('superadmin.sellers.orders.bulk-status'),
            'approveUrlTemplate' => route('superadmin.sellers.orders.approve', ['id' => '__id__']),
            'rejectUrlTemplate' => route('superadmin.sellers.orders.reject', ['id' => '__id__']),
            'viewUrlTemplate' => route('superadmin.sales.orders.view', ['id' => '__id__']),
            'placeholderImage' => bagisto_asset('images/product-placeholders/front.svg'),
            'currencySymbol' => core()->getBaseCurrency()->symbol,
        ]);
    }

    protected function mapStatusLabel(string $orderStatus, ?string $sellerApprovalStatus): string
    {
        if ($sellerApprovalStatus === 'rejected') {
            return 'Rejected';
        }

        return match (strtolower($orderStatus)) {
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_CANCELED => 'Canceled',
            Order::STATUS_CLOSED => 'Closed',
            Order::STATUS_FRAUD => 'Fraud',
            Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
            default => 'Pending',
        };
    }

    /**
     * Summary counts for seller-assigned orders (matches lifecycle: approved = completed payout, pending = awaiting action, rejected).
     *
     * @return array{total: int, completed: int, pending: int, rejected: int, revenue_formatted: string}
     */
    protected function sellerOrderStats(): array
    {
        $empty = [
            'total' => 0,
            'completed' => 0,
            'pending' => 0,
            'rejected' => 0,
            'revenue_formatted' => core()->formatPrice(0),
        ];

        if (! Schema::hasColumn((new Order)->getTable(), 'seller_id')) {
            return $empty;
        }

        $base = DB::table('orders')->whereNotNull('seller_id');

        $total = (clone $base)->count();

        if (! Schema::hasColumn((new Order)->getTable(), 'seller_approval_status')) {
            return array_merge($empty, [
                'total' => $total,
                'pending' => $total,
            ]);
        }

        $completed = (clone $base)->where('seller_approval_status', 'approved')->count();
        $rejected = (clone $base)->where('seller_approval_status', 'rejected')->count();
        $pending = (clone $base)->where(function ($q) {
            $q->whereNull('seller_approval_status')
                ->orWhereNotIn('seller_approval_status', ['approved', 'rejected']);
        })->count();

        $revenue = (clone $base)->where('seller_approval_status', 'approved')->sum('base_grand_total');

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'rejected' => $rejected,
            'revenue_formatted' => core()->formatPrice(round((float) $revenue, 2)),
        ];
    }

    /**
     * Single-order approve: same rules as bulk “completed” (wallet credit when eligible).
     */
    public function approve(int $id): JsonResponse|RedirectResponse
    {
        $order = $this->orderRepository->findOrFail($id);

        if (! $order->seller_id) {
            return $this->invalidSellerOrderResponse();
        }

        $updated = false;

        DB::transaction(function () use ($order, &$updated) {
            $updated = $this->processBulkComplete($order);
        });

        if (! $updated) {
            return $this->invalidSellerOrderResponse();
        }

        if ($this->wantsJsonResponse()) {
            return new JsonResponse(['message' => trans('superadmin::app.sellers.orders.index.approve-success')]);
        }

        session()->flash('success', trans('superadmin::app.sellers.orders.index.approve-success'));

        return redirect()->route('superadmin.sellers.orders.index');
    }

    public function reject(int $id): JsonResponse|RedirectResponse
    {
        $order = $this->orderRepository->findOrFail($id);

        if (! $order->seller_id) {
            return $this->invalidSellerOrderResponse();
        }

        DB::transaction(function () use ($order) {
            $this->refundSellerPurchaseIfNeeded($order);
            $order->status = Order::STATUS_CANCELED;
            $order->seller_approval_status = 'rejected';
            $order->save();
        });

        if ($this->wantsJsonResponse()) {
            return new JsonResponse(['message' => trans('superadmin::app.sellers.orders.index.reject-success')]);
        }

        session()->flash('success', trans('superadmin::app.sellers.orders.index.reject-success'));

        return redirect()->route('superadmin.sellers.orders.index');
    }

    /**
     * Bulk update seller-order lifecycle: completed (wallet credit), rejected (wallet refund of purchase hold), or pending.
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'indices' => 'required|array|min:1',
            'indices.*' => 'numeric',
            'value' => 'required|string|in:completed,rejected,pending,processing',
        ]);

        $value = (string) $request->input('value');
        $ids = array_values(array_filter(array_map('intval', $request->input('indices', []))));
        $updated = 0;

        DB::transaction(function () use ($ids, $value, &$updated) {
            foreach ($ids as $id) {
                $order = $this->orderRepository->find($id);

                if (! $order || ! $order->seller_id) {
                    continue;
                }

                $changed = match ($value) {
                    'completed' => $this->processBulkComplete($order),
                    'rejected' => $this->processBulkReject($order),
                    'pending' => $this->processBulkPending($order),
                    'processing' => $this->processBulkProcessing($order),
                    default => false,
                };

                if ($changed) {
                    $updated++;
                }
            }
        });

        if ($updated === 0) {
            return new JsonResponse([
                'message' => trans('superadmin::app.sellers.orders.index.bulk-status-none-updated'),
            ], 422);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.sellers.orders.index.bulk-status-result', [
                'count' => $updated,
                'status' => trans('superadmin::app.sellers.orders.index.bulk-status-labels.'.$value),
            ]),
        ]);
    }

    protected function processBulkComplete(Order $order): bool
    {
        if ($order->status === Order::STATUS_COMPLETED
            && ($order->seller_approval_status ?? '') === 'approved'
            && $order->seller_commission_credited) {
            return false;
        }

        $order->status = Order::STATUS_COMPLETED;
        $order->seller_approval_status = 'approved';
        $order->saveQuietly();

        $fresh = $this->orderRepository->findOrFail($order->id);
        $this->creditSellerWalletWithPurchaseAndCommission(
            $fresh,
            SellerWalletTransaction::KIND_ORDER_REVENUE,
            trans('superadmin::app.sellers.orders.index.wallet-revenue-complete-desc', [
                'increment' => $fresh->increment_id,
                'purchase' => core()->formatPrice(round((float) ($fresh->base_grand_total ?? 0), 2)),
                'commission' => core()->formatPrice(round(SellerOrderCommissionObserver::calculateCommissionTotal($fresh), 2)),
            ])
        );

        return true;
    }

    protected function processBulkReject(Order $order): bool
    {
        if (($order->seller_approval_status ?? '') === 'rejected') {
            return false;
        }

        $this->refundSellerPurchaseIfNeeded($order);
        $order->status = Order::STATUS_CANCELED;
        $order->seller_approval_status = 'rejected';
        $order->save();

        return true;
    }

    protected function processBulkPending(Order $order): bool
    {
        if ($order->status === Order::STATUS_PENDING && ($order->seller_approval_status ?? '') === 'pending') {
            return false;
        }

        $order->status = Order::STATUS_PENDING;
        $order->seller_approval_status = 'pending';
        $order->saveQuietly();

        return true;
    }

    protected function processBulkProcessing(Order $order): bool
    {
        if ($order->status === Order::STATUS_PROCESSING) {
            return false;
        }

        $order->status = Order::STATUS_PROCESSING;

        if (($order->seller_approval_status ?? '') !== 'approved') {
            $order->seller_approval_status = 'pending';
        }

        $order->saveQuietly();

        return true;
    }

    /**
     * Refund the seller wallet debit created when the seller paid for the order from their balance.
     */
    protected function refundSellerPurchaseIfNeeded(Order $order): void
    {
        if (! $order->seller_id) {
            return;
        }

        if (SellerWalletTransaction::query()
            ->where('order_id', $order->id)
            ->where('kind', SellerWalletTransaction::KIND_ORDER_REJECTION_REFUND)
            ->exists()) {
            return;
        }

        $purchase = SellerWalletTransaction::query()
            ->where('order_id', $order->id)
            ->where('kind', SellerWalletTransaction::KIND_SELLER_PURCHASE)
            ->where('type', 'debit')
            ->where('status', SellerWalletTransaction::STATUS_COMPLETED)
            ->orderByDesc('id')
            ->first();

        if (! $purchase) {
            return;
        }

        $amount = round((float) $purchase->amount, 2);

        if ($amount <= 0) {
            return;
        }

        $seller = Admin::query()->lockForUpdate()->find($order->seller_id);

        if (! $seller) {
            return;
        }

        $balanceBefore = round((float) ($seller->wallet_balance ?? 0), 2);
        $balanceAfter = round($balanceBefore + $amount, 2);

        SellerWalletTransaction::create([
            'seller_id' => $seller->id,
            'amount' => $amount,
            'type' => 'credit',
            'status' => SellerWalletTransaction::STATUS_COMPLETED,
            'kind' => SellerWalletTransaction::KIND_ORDER_REJECTION_REFUND,
            'order_id' => $order->id,
            'description' => trans('superadmin::app.sellers.orders.index.wallet-rejection-refund-desc', [
                'increment' => $order->increment_id,
            ]),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'meta' => [
                'refunds_seller_wallet_transaction_id' => $purchase->id,
            ],
        ]);

        $seller->wallet_balance = $balanceAfter;
        $seller->save();
    }

    /**
     * Credit seller wallet once per order: purchase total + commission. Skips if already credited (approval or completion).
     */
    protected function creditSellerWalletWithPurchaseAndCommission(Order $order, string $kind, string $description): void
    {
        if (! $order->seller_id) {
            return;
        }

        if ($order->seller_commission_credited) {
            return;
        }

        if (SellerWalletTransaction::query()
            ->where('order_id', $order->id)
            ->whereIn('kind', [
                SellerWalletTransaction::KIND_ORDER_REVENUE_APPROVAL,
                SellerWalletTransaction::KIND_ORDER_REVENUE,
            ])
            ->exists()) {
            return;
        }

        $order->loadMissing('items');

        $purchaseTotal = round((float) ($order->base_grand_total ?? 0), 2);
        $commissionTotal = round(SellerOrderCommissionObserver::calculateCommissionTotal($order), 2);
        $creditAmount = round($purchaseTotal + $commissionTotal, 2);

        if ($creditAmount <= 0) {
            return;
        }

        $seller = Admin::query()->lockForUpdate()->find($order->seller_id);

        if (! $seller) {
            return;
        }

        $balanceBefore = round((float) ($seller->wallet_balance ?? 0), 2);
        $balanceAfter = round($balanceBefore + $creditAmount, 2);

        SellerWalletTransaction::create([
            'seller_id' => $seller->id,
            'amount' => $creditAmount,
            'type' => 'credit',
            'status' => SellerWalletTransaction::STATUS_COMPLETED,
            'kind' => $kind,
            'order_id' => $order->id,
            'description' => $description,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'meta' => [
                'purchase_total' => $purchaseTotal,
                'commission_total' => $commissionTotal,
            ],
        ]);

        $seller->wallet_balance = $balanceAfter;
        $seller->save();

        // Commission is included in this credit; skip SellerOrderCommissionObserver on completion.
        $order->seller_commission_credited = true;
        $order->saveQuietly();
    }

    protected function invalidSellerOrderResponse(): JsonResponse|RedirectResponse
    {
        if ($this->wantsJsonResponse()) {
            return new JsonResponse(['message' => trans('superadmin::app.sellers.orders.index.invalid-state')], 422);
        }

        session()->flash('warning', trans('superadmin::app.sellers.orders.index.invalid-state'));

        return redirect()->route('superadmin.sellers.orders.index');
    }

    protected function wantsJsonResponse(): bool
    {
        $request = request();

        if ($request->expectsJson() || $request->ajax()) {
            return true;
        }

        return str_contains($request->header('Accept', ''), 'application/json');
    }
}
