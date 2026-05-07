<?php

namespace Webkul\Admin\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Helpers\Reporting\Product as ProductReporting;
use Webkul\Admin\Helpers\Reporting\Visitor as VisitorReporting;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;

/**
 * Aggregates dashboard figures for the logged-in seller (orders attributed to seller_id).
 */
class SellerDashboardStats
{
    public static function isPlatformAdmin(?Admin $user): bool
    {
        if (! $user) {
            return false;
        }

        $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);

        return $user->role
            && $user->role->permission_type === 'all'
            && $platformRoleIds !== []
            && in_array((int) $user->role_id, $platformRoleIds, true);
    }

    /**
     * @return array<string, mixed>
     */
    public static function forSeller(Admin $seller, Carbon $start, Carbon $end): array
    {
        $sellerId = (int) $seller->id;
        $profitMargin = (float) config('seller-panel.financial_profit_margin', 0.35);

        $baseOrder = Order::query()->where('seller_id', $sellerId);
        if (Schema::hasColumn('orders', 'seller_approval_status')) {
            $baseOrder->where('seller_approval_status', 'approved');
        }

        $ordersInPeriod = (clone $baseOrder)->whereBetween('created_at', [$start, $end]);

        $totalOrders = (clone $ordersInPeriod)->count();
        $totalSales = (float) ((clone $ordersInPeriod)->sum('base_grand_total') ?? 0);

        $completedStatuses = [Order::STATUS_COMPLETED, Order::STATUS_CLOSED];
        $completedSales = (float) ((clone $ordersInPeriod)->whereIn('status', $completedStatuses)->sum('base_grand_total') ?? 0);
        $estimatedProfit = $completedSales * $profitMargin;

        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();
        $ordersToday = (clone $baseOrder)->whereBetween('created_at', [$todayStart, $todayEnd]);
        $todayOrderCount = $ordersToday->count();
        $todaySales = (float) ($ordersToday->sum('base_grand_total') ?? 0);
        $todayCompleted = (float) ((clone $ordersToday)->whereIn('status', $completedStatuses)->sum('base_grand_total') ?? 0);
        $todayProfit = $todayCompleted * $profitMargin;

        $inProcessStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_PROCESSING,
        ];

        $orderStatusCounts = [
            'total' => (clone $baseOrder)->count(),
            'in_process' => (clone $baseOrder)->whereIn('status', $inProcessStatuses)->count(),
            'completed' => (clone $baseOrder)->whereIn('status', $completedStatuses)->count(),
            'canceled' => (clone $baseOrder)->where('status', Order::STATUS_CANCELED)->count(),
        ];

        $totalProducts = 0;
        if (Schema::hasTable('seller_store_products')) {
            $totalProducts = (int) DB::table('seller_store_products')
                ->where('seller_id', $sellerId)
                ->distinct()
                ->count('product_id');
        }

        $avgRating = null;
        if (Schema::hasTable('seller_store_products') && Schema::hasTable('product_reviews')) {
            $productIds = DB::table('seller_store_products')
                ->where('seller_id', $sellerId)
                ->pluck('product_id');
            if ($productIds->isNotEmpty()) {
                $avg = DB::table('product_reviews')
                    ->whereIn('product_id', $productIds)
                    ->where('status', 'approved')
                    ->avg('rating');
                $avgRating = $avg !== null ? round((float) $avg, 1) : null;
            }
        }

        $creditScore = $seller->credit_score;
        $storeFollowers = null;

        $topSelling = self::topSellingProducts($sellerId, $start, $end, 10);

        return [
            'period' => [
                'total_products' => $totalProducts,
                'total_sales' => $totalSales,
                'formatted_total_sales' => core()->formatBasePrice($totalSales),
                'total_orders' => $totalOrders,
                'estimated_profit' => $estimatedProfit,
                'formatted_estimated_profit' => core()->formatBasePrice($estimatedProfit),
            ],
            'shop' => [
                'overall_rating' => $avgRating,
                'credit_score' => $creditScore,
                'store_followers' => $storeFollowers,
                'seller_level' => $seller->seller_level ?? null,
            ],
            'today' => [
                'orders' => $todayOrderCount,
                'sales' => $todaySales,
                'formatted_sales' => core()->formatBasePrice($todaySales),
                'profit' => $todayProfit,
                'formatted_profit' => core()->formatBasePrice($todayProfit),
            ],
            'visitors' => [
                'note' => null,
                'today' => null,
                'last_7_days' => null,
                'last_30_days' => null,
            ],
            'order_status' => $orderStatusCounts,
            'top_selling' => $topSelling,
            'verified' => ($seller->seller_approval_status ?? 'approved') === 'approved',
        ];
    }

    /**
     * Store-wide dashboard payload for platform administrators (uses reporting helpers).
     *
     * @return array<string, mixed>
     */
    public static function forPlatform(Dashboard $dashboard, ProductReporting $productReporting, VisitorReporting $visitorReporting): array
    {
        $start = $dashboard->getStartDate();
        $end = $dashboard->getEndDate();
        $profitMargin = (float) config('seller-panel.financial_profit_margin', 0.35);

        $overall = $dashboard->getOverAllStats();
        $today = $dashboard->getTodayStats();

        $totalOrders = (int) ($overall['total_orders']['current'] ?? 0);
        $totalSales = (float) ($overall['total_sales']['current'] ?? 0);
        $estimatedProfit = $totalSales * $profitMargin;

        $totalProducts = (int) Product::query()->count();

        $topSelling = $productReporting->getTopSellingProductsByRevenue(10)->map(function (array $row) {
            $revenue = (float) ($row['revenue'] ?? 0);

            return [
                'product_id' => (int) ($row['id'] ?? 0),
                'name' => $row['name'] ?? '',
                'revenue' => $revenue,
                'formatted_revenue' => $row['formatted_revenue'] ?? core()->formatBasePrice($revenue),
                'formatted_price' => $row['formatted_price'] ?? core()->formatBasePrice($row['price'] ?? 0),
                'formatted_sales_volume' => $row['formatted_revenue'] ?? core()->formatBasePrice($revenue),
            ];
        });

        $channelCode = request()->query('channel');
        $channelIds = core()->getAllChannels()
            ->filter(fn ($ch) => $channelCode ? $ch->code === $channelCode : true)
            ->pluck('id')
            ->toArray();

        $orderStatus = [
            'total' => 0,
            'in_process' => 0,
            'completed' => 0,
            'canceled' => 0,
        ];

        if ($channelIds !== []) {
            $base = Order::query()
                ->whereIn('channel_id', $channelIds)
                ->whereBetween('created_at', [$start, $end]);
            $orderStatus['total'] = (clone $base)->count();
            $orderStatus['in_process'] = (clone $base)->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PENDING_PAYMENT,
                Order::STATUS_PROCESSING,
            ])->count();
            $orderStatus['completed'] = (clone $base)->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CLOSED])->count();
            $orderStatus['canceled'] = (clone $base)->where('status', Order::STATUS_CANCELED)->count();
        }

        $visitorsToday = $visitorReporting->getTotalVisitors(now()->startOfDay(), now()->endOfDay());
        $visitors7 = $visitorReporting->getTotalVisitors(now()->subDays(7)->startOfDay(), now()->endOfDay());
        $visitors30 = $visitorReporting->getTotalVisitors(now()->subDays(30)->startOfDay(), now()->endOfDay());

        return [
            'period' => [
                'total_products' => $totalProducts,
                'total_sales' => $totalSales,
                'formatted_total_sales' => core()->formatBasePrice($totalSales),
                'total_orders' => $totalOrders,
                'estimated_profit' => $estimatedProfit,
                'formatted_estimated_profit' => core()->formatBasePrice($estimatedProfit),
            ],
            'shop' => [
                'overall_rating' => null,
                'credit_score' => null,
                'store_followers' => null,
                'seller_level' => null,
            ],
            'today' => [
                'orders' => (int) ($today['total_orders']['current'] ?? 0),
                'sales' => (float) ($today['total_sales']['current'] ?? 0),
                'formatted_sales' => core()->formatBasePrice((float) ($today['total_sales']['current'] ?? 0)),
                'profit' => (float) ($today['total_sales']['current'] ?? 0) * $profitMargin,
                'formatted_profit' => core()->formatBasePrice((float) ($today['total_sales']['current'] ?? 0) * $profitMargin),
            ],
            'visitors' => [
                'today' => $visitorsToday,
                'last_7_days' => $visitors7,
                'last_30_days' => $visitors30,
            ],
            'order_status' => $orderStatus,
            'top_selling' => $topSelling,
            'verified' => true,
        ];
    }

    /**
     * Top products by revenue for this seller in the date range.
     */
    protected static function topSellingProducts(int $sellerId, Carbon $start, Carbon $end, int $limit): Collection
    {
        $q = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->where('o.seller_id', $sellerId)
            ->whereNull('oi.parent_id')
            ->whereBetween('oi.created_at', [$start, $end]);

        if (Schema::hasColumn('orders', 'seller_approval_status')) {
            $q->where('o.seller_approval_status', 'approved');
        }

        $prefix = DB::getTablePrefix();

        $rows = $q
            ->groupBy('oi.product_id')
            ->selectRaw('
                oi.product_id,
                MAX(oi.name) as name,
                SUM('.$prefix.'oi.base_total_invoiced - '.$prefix.'oi.base_amount_refunded) as revenue,
                SUM('.$prefix.'oi.qty_invoiced - '.$prefix.'oi.qty_refunded) as qty_sold
            ')
            ->havingRaw('SUM('.$prefix.'oi.base_total_invoiced - '.$prefix.'oi.base_amount_refunded) > 0')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();

        return $rows->map(function ($row) {
            $revenue = (float) $row->revenue;
            $qtySold = (float) $row->qty_sold;

            return [
                'product_id' => (int) $row->product_id,
                'name' => $row->name,
                'revenue' => $revenue,
                'formatted_revenue' => core()->formatBasePrice($revenue),
                'formatted_price' => core()->formatBasePrice($revenue / max($qtySold, 1)),
                'formatted_sales_volume' => number_format($qtySold, 0, '.', ','),
            ];
        });
    }
}
