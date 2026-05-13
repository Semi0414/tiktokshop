<?php

namespace Webkul\Admin\DataGrids\Sales;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\User\Models\Admin;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $prefix = DB::getTablePrefix();

        $queryBuilder = DB::table('orders')
            ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
                $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
                    ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
            })
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id');

        $selectColumns = [
            'orders.id',
            DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'order_payment.method SEPARATOR "|") as method'),
            'orders.increment_id',
            'orders.base_grand_total',
            'orders.created_at',
            'channel_name',
            'channel_id',
            'status',
            'customer_email',
            'orders.cart_id as items',
            'orders.seller_make_order_at',
            DB::raw('(SELECT COALESCE(AVG(ssp.commission_percent), 0) FROM '.$prefix.'order_items oi INNER JOIN '.$prefix.'seller_store_products ssp ON oi.product_id = ssp.product_id AND ssp.seller_id = '.$prefix.'orders.seller_id WHERE oi.order_id = '.$prefix.'orders.id AND oi.parent_id IS NULL) as seller_avg_commission'),
            DB::raw('(SELECT COALESCE(SUM(ROUND('.$prefix.'oi.base_total * ('.$prefix.'ssp.commission_percent / 100), 2)), 0) FROM '.$prefix.'order_items oi INNER JOIN '.$prefix.'seller_store_products ssp ON oi.product_id = ssp.product_id AND ssp.seller_id = '.$prefix.'orders.seller_id WHERE oi.order_id = '.$prefix.'orders.id AND oi.parent_id IS NULL) as seller_commission_expected'),
            DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name) as full_name'),
            DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.city, ", ", '.DB::getTablePrefix().'order_address_billing.state,", ", '.DB::getTablePrefix().'order_address_billing.country) as location'),
        ];

        if (Schema::hasColumn('orders', 'seller_approval_status')) {
            $selectColumns[] = 'orders.seller_approval_status';
        }

        $queryBuilder->select($selectColumns)
            ->groupBy('orders.id');

        $this->addFilter('full_name', DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name)'));
        $this->addFilter('created_at', 'orders.created_at');

        $seller = auth()->guard('admin')->user();

        if ($seller instanceof Admin) {
            $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);
            $seeAllOrders = $seller->role
                && $seller->role->permission_type === 'all'
                && $platformRoleIds !== []
                && in_array((int) $seller->role_id, $platformRoleIds, true);

            if (! $seeAllOrders) {
                $queryBuilder->where('orders.seller_id', $seller->id);
            }
        }

        /**
         * Seller panel toolbar (GET params merged into datagrid AJAX — see v-datagrid).
         */
        $scope = request()->input('seller_order_scope', 'all');
        if ($scope === 'pending') {
            $queryBuilder->whereIn('orders.status', [
                Order::STATUS_PENDING,
                Order::STATUS_PENDING_PAYMENT,
            ]);
        } elseif ($scope === 'processing') {
            $queryBuilder->where('orders.status', Order::STATUS_PROCESSING);
        } elseif ($scope === 'completed') {
            $queryBuilder->whereIn('orders.status', [
                Order::STATUS_COMPLETED,
                Order::STATUS_CLOSED,
            ]);
        } elseif ($scope === 'rejected') {
            $queryBuilder->where(function ($statusQuery) {
                $statusQuery
                    ->where('orders.seller_approval_status', 'rejected')
                    ->orWhereIn('orders.status', [
                        Order::STATUS_CANCELED,
                        Order::STATUS_FRAUD,
                    ]);
            });
        }

        $incrementId = request()->input('seller_increment_id');
        if ($incrementId !== null && $incrementId !== '') {
            $queryBuilder->where('orders.increment_id', 'like', '%'.addcslashes((string) $incrementId, '%_\\').'%');
        }

        $paymentMethod = request()->input('seller_payment_method');
        if ($paymentMethod !== null && $paymentMethod !== '') {
            $queryBuilder->whereExists(function ($sub) use ($paymentMethod) {
                $sub->select(DB::raw(1))
                    ->from('order_payment as opf')
                    ->whereColumn('opf.order_id', 'orders.id')
                    ->where('opf.method', $paymentMethod);
            });
        }

        $dateFrom = request()->input('seller_date_from');
        $dateTo = request()->input('seller_date_to');
        if ($dateFrom) {
            $queryBuilder->whereDate('orders.created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $queryBuilder->whereDate('orders.created_at', '<=', $dateTo);
        }

        $logisticsOrStatus = request()->input('seller_logistics_status');
        if ($logisticsOrStatus !== null && $logisticsOrStatus !== '' && $logisticsOrStatus !== 'all') {
            $queryBuilder->where('orders.status', $logisticsOrStatus);
        }

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->setSortColumn('orders.created_at');

        $this->setSortOrder('desc');

        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('admin::app.sales.orders.index.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'order_status_raw',
            'label' => 'Order Status Raw',
            'type' => 'string',
            'visibility' => false,
            'exportable' => false,
            'sortable' => false,
            'closure' => function ($row) {
                return $row->status;
            },
        ]);

        $this->addColumn([
            'index' => 'seller_can_make_order',
            'label' => 'Seller Can Make Order',
            'type' => 'string',
            'visibility' => false,
            'exportable' => false,
            'sortable' => false,
            'closure' => function ($row) {
                if (! empty($row->seller_make_order_at)) {
                    return '0';
                }

                if (isset($row->seller_approval_status) && $row->seller_approval_status === 'rejected') {
                    return '0';
                }

                $status = (string) ($row->status ?? '');

                return in_array($status, [
                    Order::STATUS_PENDING,
                    Order::STATUS_PENDING_PAYMENT,
                    Order::STATUS_PROCESSING,
                ], true) ? '1' : '0';
            },
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.sales.orders.index.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.processing'),
                    'value' => Order::STATUS_PROCESSING,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.completed'),
                    'value' => Order::STATUS_COMPLETED,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.canceled'),
                    'value' => Order::STATUS_CANCELED,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.closed'),
                    'value' => Order::STATUS_CLOSED,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.pending'),
                    'value' => Order::STATUS_PENDING,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.pending-payment'),
                    'value' => Order::STATUS_PENDING_PAYMENT,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.fraud'),
                    'value' => Order::STATUS_FRAUD,
                ],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                if (isset($row->seller_approval_status) && $row->seller_approval_status === 'rejected') {
                    return '<p class="label-canceled">'.trans('admin::app.seller-panel.orders.status-rejected').'</p>';
                }

                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('admin::app.sales.orders.index.datagrid.processing').'</p>';

                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('admin::app.sales.orders.index.datagrid.completed').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('admin::app.sales.orders.index.datagrid.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('admin::app.sales.orders.index.datagrid.closed').'</p>';

                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('admin::app.sales.orders.index.datagrid.pending').'</p>';

                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.trans('admin::app.sales.orders.index.datagrid.pending-payment').'</p>';

                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.trans('admin::app.sales.orders.index.datagrid.fraud').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.sales.orders.index.datagrid.grand-total'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'method',
            'label' => trans('admin::app.sales.orders.index.datagrid.pay-via'),
            'type' => 'string',
            'closure' => function ($row) {
                return collect(explode('|', $row->method))
                    ->map(fn ($method) => core()->getConfigData('sales.payment_methods.'.$method.'.title'))
                    ->filter()
                    ->unique()
                    ->join(', ');
            },
        ]);

        $this->addColumn([
            'index' => 'channel_id',
            'label' => trans('admin::app.sales.orders.index.datagrid.channel-name'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('admin::app.sales.orders.index.datagrid.customer'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        /**
         * Searchable dropdown sample. In testing phase.
         */
        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('admin::app.sales.orders.index.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'location',
            'label' => trans('admin::app.sales.orders.index.datagrid.location'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'seller_avg_commission',
            'label' => trans('admin::app.seller.shop-order.col-commission'),
            'type' => 'decimal',
            'sortable' => false,
            'closure' => function ($row) {
                return number_format((float) ($row->seller_avg_commission ?? 0), 2).'%';
            },
        ]);

        $this->addColumn([
            'index' => 'items',
            'label' => trans('admin::app.sales.orders.index.datagrid.items'),
            'type' => 'string',
            'exportable' => false,
            'closure' => function ($value) {
                $order = app(OrderRepository::class)->with('items')->find($value->id);

                return view('admin::sales.orders.items', compact('order'))->render();
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.sales.orders.index.datagrid.date'),
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'order_age_hours',
            'label' => trans('admin::app.seller.shop-order.col-age-hours'),
            'type' => 'integer',
            'sortable' => false,
            'filterable' => false,
            'closure' => function ($row) {
                if (empty($row->created_at)) {
                    return '—';
                }

                try {
                    $hours = (int) floor(Carbon::parse($row->created_at)->diffInHours(Carbon::now()));

                    return (string) max(0, $hours);
                } catch (\Throwable $e) {
                    return '—';
                }
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'icon' => 'icon-view',
                'title' => trans('admin::app.sales.orders.index.datagrid.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.sales.orders.view', $row->id);
                },
            ]);
        }
    }

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon' => 'icon-cart',
            'title' => trans('admin::app.seller.shop-order.bulk-make-order'),
            'method' => 'POST',
            'url' => route('admin.seller.shop-order.bulk-make-order'),
        ]);
    }
}
