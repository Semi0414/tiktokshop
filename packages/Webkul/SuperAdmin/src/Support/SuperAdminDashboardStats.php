<?php

namespace Webkul\SuperAdmin\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Webkul\SuperAdmin\Helpers\Dashboard;
use Webkul\SuperAdmin\Helpers\Reporting\Visitor as VisitorReporting;

/**
 * Platform-wide metrics for the Super Admin dashboard (sellers, buyers, orders, catalog).
 */
class SuperAdminDashboardStats
{
    /**
     * @return array<string, mixed>
     */
    public static function build(Dashboard $dashboard, VisitorReporting $visitorReporting): array
    {
        $today = $dashboard->getTodayStats();
        $start = $dashboard->getStartDate();
        $end = $dashboard->getEndDate();

        $channelIds = self::resolveChannelIds();

        $totalProducts = (int) Product::query()->count();
        $activeSellers = self::countActiveSellers();
        $activeBuyers = self::countActiveBuyers();

        $visitorsToday = $visitorReporting->getTotalVisitors(now()->startOfDay(), now()->endOfDay());
        $visitors7 = $visitorReporting->getTotalVisitors(now()->subDays(7)->startOfDay(), now()->endOfDay());
        $visitors30 = $visitorReporting->getTotalVisitors(now()->subDays(30)->startOfDay(), now()->endOfDay());

        $orderStatus = [
            'total' => 0,
            'in_process' => 0,
            'completed' => 0,
            'canceled' => 0,
        ];

        $totalOrders = 0;
        $totalSales = 0.0;
        $pendingOrdersAmount = 0.0;

        if ($channelIds !== []) {
            $base = Order::query()
                ->whereIn('channel_id', $channelIds)
                ->whereBetween('created_at', [$start, $end]);

            $totalOrders = (clone $base)->count();

            $totalSales = (float) ((clone $base)->sum('base_grand_total') ?? 0);

            $pendingOrdersAmount = (float) (clone $base)->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PENDING_PAYMENT,
                Order::STATUS_PROCESSING,
            ])->sum('base_grand_total');

            $orderStatus['total'] = $totalOrders;
            $orderStatus['in_process'] = (clone $base)->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PENDING_PAYMENT,
                Order::STATUS_PROCESSING,
            ])->count();
            $orderStatus['completed'] = (clone $base)->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CLOSED])->count();
            $orderStatus['canceled'] = (clone $base)->where('status', Order::STATUS_CANCELED)->count();
        }

        $avgSale = $totalOrders > 0 ? $totalSales / $totalOrders : 0.0;

        $topSelling = self::topProductsByDistinctOrderCount($channelIds, $start, $end, 10);

        return [
            'period' => [
                'active_sellers' => $activeSellers,
                'active_buyers' => $activeBuyers,
                'total_orders' => $totalOrders,
                'total_sales' => $totalSales,
                'formatted_total_sales' => core()->formatBasePrice($totalSales),
                'total_products' => $totalProducts,
                'formatted_avg_sale' => core()->formatBasePrice($avgSale),
                'formatted_pending_orders' => core()->formatBasePrice($pendingOrdersAmount),
            ],
            'today' => [
                'orders' => (int) ($today['total_orders']['current'] ?? 0),
                'sales' => (float) ($today['total_sales']['current'] ?? 0),
                'formatted_sales' => core()->formatBasePrice((float) ($today['total_sales']['current'] ?? 0)),
                'customers' => (int) ($today['total_customers']['current'] ?? 0),
            ],
            'visitors' => [
                'today' => $visitorsToday,
                'last_7_days' => $visitors7,
                'last_30_days' => $visitors30,
            ],
            'order_status' => $orderStatus,
            'top_selling' => $topSelling,
        ];
    }

    /**
     * Same channel scope as reporting (optional ?channel= code).
     *
     * @return list<int>
     */
    protected static function resolveChannelIds(): array
    {
        $channelCode = request()->query('channel');
        $ids = core()->getAllChannels()
            ->filter(fn ($ch) => $channelCode ? $ch->code === $channelCode : true)
            ->pluck('id')
            ->values()
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($ids === []) {
            $ids = core()->getAllChannels()
                ->pluck('id')
                ->values()
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return $ids;
    }

    /**
     * Marketplace seller accounts with login enabled (status = 1).
     *
     * Super Admin users live on the `super_admins` guard/table — they are not counted here.
     * Optionally exclude rows whose {@see config('seller-panel.platform_admin_role_ids')} matches
     * real seller-panel accounts that should not appear in this KPI (via PLATFORM_ADMIN_ROLE_IDS).
     *
     * We intentionally do not exclude every role with permission_type = all: sellers commonly share
     * the default Administrator role (id 1), which would incorrectly yield zero active sellers.
     */
    protected static function countActiveSellers(): int
    {
        if (! Schema::hasTable('seller')) {
            return 0;
        }

        $excludeRoleIds = collect(config('seller-panel.platform_admin_role_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $q = DB::table('seller')->whereIn('status', [1, '1']);

        if ($excludeRoleIds !== []) {
            $q->where(function ($w) use ($excludeRoleIds) {
                $w->whereNull('role_id')
                    ->orWhereNotIn('role_id', $excludeRoleIds);
            });
        }

        return (int) $q->count();
    }

    /**
     * Active customers (buyers): enabled, not suspended; exclude guest group. Not scoped by channel (many rows have NULL channel_id).
     */
    protected static function countActiveBuyers(): int
    {
        if (! Schema::hasTable('customers')) {
            return 0;
        }

        $q = DB::table('customers')
            ->whereIn('status', [1, '1']);

        if (Schema::hasColumn('customers', 'is_suspended')) {
            $q->where('is_suspended', 0);
        }

        if (Schema::hasTable('customer_groups') && Schema::hasColumn('customers', 'customer_group_id')) {
            $q->where(function ($w) {
                $w->whereNull('customers.customer_group_id')
                    ->orWhereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('customer_groups as cg')
                            ->whereColumn('cg.id', 'customers.customer_group_id')
                            ->where('cg.code', '!=', 'guest');
                    });
            });
        }

        return (int) $q->count();
    }

    /**
     * Top products by number of distinct orders (not revenue).
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected static function topProductsByDistinctOrderCount(array $channelIds, $start, $end, int $limit): Collection
    {
        if ($channelIds === []) {
            return collect();
        }

        $prefix = DB::getTablePrefix();

        $rows = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->whereNull('oi.parent_id')
            ->whereIn('o.channel_id', $channelIds)
            ->whereBetween('o.created_at', [$start, $end])
            ->groupBy('oi.product_id')
            ->selectRaw(
                'oi.product_id,
                MAX(oi.name) as name,
                COUNT(DISTINCT '.$prefix.'o.id) as orders_count,
                AVG('.$prefix.'oi.base_price) as avg_base_price'
            )
            ->havingRaw('COUNT(DISTINCT '.$prefix.'o.id) > 0')
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get();

        return $rows->map(function ($row) {
            $ordersCount = (int) $row->orders_count;
            $price = (float) ($row->avg_base_price ?? 0);

            return [
                'product_id' => (int) $row->product_id,
                'name' => $row->name,
                'formatted_price' => core()->formatBasePrice($price),
                'formatted_sales_volume' => (string) number_format($ordersCount, 0, '.', ','),
                'orders_count' => $ordersCount,
            ];
        });
    }
}
