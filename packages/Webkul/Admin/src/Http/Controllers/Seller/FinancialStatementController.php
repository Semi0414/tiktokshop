<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;

class FinancialStatementController extends Controller
{
    /**
     * Financial statement with period filters + daily breakdown (seller orders only).
     */
    public function index()
    {
        /** @var Admin $seller */
        $seller = Auth::guard('admin')->user();

        $period = request()->query('period', 'today');

        [$start, $end] = $this->resolvePeriodRange($period);

        $base = Order::query()->where('seller_id', $seller->id);

        if ($start && $end) {
            $base->whereBetween('created_at', [$start, $end]);
        }

        $pendingStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_PROCESSING,
        ];

        $completedStatuses = [
            Order::STATUS_COMPLETED,
            Order::STATUS_CLOSED,
        ];

        $pendingAmount = (float) (clone $base)->whereIn('status', $pendingStatuses)->sum('grand_total');
        $totalSales = (float) (clone $base)->whereIn('status', $completedStatuses)->sum('grand_total');
        $totalOrders = (int) (clone $base)->count();

        // Approximate seller profit (no COGS in core): configurable margin on completed revenue.
        $margin = (float) config('seller-panel.financial_profit_margin', 0.35);
        $totalProfit = round($totalSales * $margin, 2);

        $dailyRows = Order::query()
            ->where('seller_id', $seller->id)
            ->when($start && $end, fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->select([
                DB::raw('DATE(created_at) as statement_date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(grand_total) as day_revenue'),
            ])
            ->groupBy('statement_date')
            ->orderBy('statement_date', 'desc')
            ->get()
            ->map(function ($row) use ($margin) {
                $revenue = (float) $row->day_revenue;

                return (object) [
                    'date' => $row->statement_date,
                    'orders_count' => (int) $row->orders_count,
                    'profit' => round($revenue * $margin, 2),
                ];
            });

        return view('admin::seller.financial-statement.index', [
            'seller' => $seller,
            'period' => $period,
            'pendingAmount' => $pendingAmount,
            'totalSales' => $totalSales,
            'totalProfit' => $totalProfit,
            'totalOrders' => $totalOrders,
            'dailyRows' => $dailyRows,
        ]);
    }

    /**
     * @return array{0: ?Carbon, 1: ?Carbon}
     */
    protected function resolvePeriodRange(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'all' => [null, null],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
        };
    }
}
