<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Observers\SellerOrderCommissionObserver;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerWalletTransaction;

class FundRecordController extends Controller
{
    public function index(Request $request)
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();

        $orderType = $request->query('order_type', 'all');
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $q = SellerWalletTransaction::query()->where('seller_id', $seller->id);

        if ($orderType === 'credit') {
            $q->where('type', 'credit');
        } elseif ($orderType === 'debit') {
            $q->where('type', 'debit');
        } elseif ($orderType === 'deposit') {
            $q->where('kind', SellerWalletTransaction::KIND_DEPOSIT_REQUEST);
        } elseif ($orderType === 'withdraw') {
            $q->where('kind', SellerWalletTransaction::KIND_WITHDRAW_REQUEST);
        } elseif ($orderType === 'purchase') {
            $q->where('kind', SellerWalletTransaction::KIND_SELLER_PURCHASE);
        } elseif ($orderType === 'commission') {
            $q->where('kind', SellerWalletTransaction::KIND_ORDER_COMMISSION);
        }

        if ($start) {
            $q->whereDate('created_at', '>=', Carbon::parse($start)->startOfDay());
        }

        if ($end) {
            $q->whereDate('created_at', '<=', Carbon::parse($end)->endOfDay());
        }

        $transactions = $q
            ->latest()
            ->paginate((int) $request->get('limit', 20))
            ->withQueryString();

        $metrics = $this->buildSellerFundMetrics($seller);

        return view('admin::seller.fund-record.index', [
            'seller' => $seller,
            'transactions' => $transactions,
            'orderType' => $orderType,
            'startDate' => $start,
            'endDate' => $end,
            'metrics' => $metrics,
        ]);
    }

    /**
     * @return array<string, float|int>
     */
    protected function buildSellerFundMetrics(Admin $seller): array
    {
        $sellerId = $seller->id;

        $orderScope = Order::query()
            ->where('seller_id', $sellerId)
            ->where('seller_approval_status', 'approved');

        $pendingStatuses = [Order::STATUS_PENDING, Order::STATUS_PENDING_PAYMENT];

        $totalOrders = (clone $orderScope)->count();
        $totalPendingOrders = (clone $orderScope)->whereIn('status', $pendingStatuses)->count();
        $totalInProgressOrders = (clone $orderScope)->where('status', Order::STATUS_PROCESSING)->count();

        $pendingOrderAmount = (float) (clone $orderScope)
            ->whereIn('status', $pendingStatuses)
            ->sum('base_grand_total');

        $processingForSeller = (clone $orderScope)
            ->where('status', Order::STATUS_PROCESSING)
            ->whereNotNull('seller_make_order_at')
            ->where('seller_commission_credited', false)
            ->get();

        $inProgressProductAmount = (float) $processingForSeller->sum('base_grand_total');

        $pendingCommissionAmount = 0.0;
        foreach ($processingForSeller as $order) {
            $pendingCommissionAmount += SellerOrderCommissionObserver::calculateCommissionTotal($order);
        }

        $totalPendingIncome = $pendingOrderAmount + $inProgressProductAmount + $pendingCommissionAmount;

        $overallTotalIncome = (float) SellerWalletTransaction::query()
            ->where('seller_id', $sellerId)
            ->where('type', 'credit')
            ->where('status', SellerWalletTransaction::STATUS_COMPLETED)
            ->sum('amount');

        $totalProfitEarned = (float) SellerWalletTransaction::query()
            ->where('seller_id', $sellerId)
            ->whereIn('kind', [
                SellerWalletTransaction::KIND_ORDER_COMMISSION,
                SellerWalletTransaction::KIND_ORDER_REVENUE,
                SellerWalletTransaction::KIND_ORDER_REVENUE_APPROVAL,
            ])
            ->where('status', SellerWalletTransaction::STATUS_COMPLETED)
            ->sum('amount');

        return [
            'total_orders' => $totalOrders,
            'total_pending_orders' => $totalPendingOrders,
            'total_in_progress_orders' => $totalInProgressOrders,
            'pending_order_amount' => $pendingOrderAmount,
            'in_progress_product_amount' => $inProgressProductAmount,
            'pending_commission_amount' => $pendingCommissionAmount,
            'total_pending_income' => $totalPendingIncome,
            'overall_total_income' => $overallTotalIncome,
            'total_profit_earned' => $totalProfitEarned,
        ];
    }
}
