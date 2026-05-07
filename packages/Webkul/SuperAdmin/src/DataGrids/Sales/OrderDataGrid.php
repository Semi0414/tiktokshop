<?php

namespace Webkul\SuperAdmin\DataGrids\Sales;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Repositories\OrderRepository;

class OrderDataGrid extends DataGrid
{
    /**
     * Default sort must be qualified: `seller` join also exposes `id` (same as {@see SellerOrderDataGrid}).
     */
    protected $sortColumn = 'orders.created_at';

    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')
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
            ->select(
                'orders.id',
                DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'order_payment.method SEPARATOR "|") as method'),
                'orders.increment_id',
                'orders.base_grand_total',
                'orders.created_at',
                'orders.channel_name',
                'orders.channel_id',
                'orders.status',
                'orders.customer_email',
                'orders.cart_id as items',
                'seller.name as seller_name',
                DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name) as full_name'),
                DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.city, ", ", '.DB::getTablePrefix().'order_address_billing.state,", ", '.DB::getTablePrefix().'order_address_billing.country) as location')
            )
            ->groupBy('orders.id');

        $this->addFilter('full_name', DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name)'));
        $this->addFilter('created_at', 'orders.created_at');
        $this->addFilter('channel_name', 'orders.channel_name');
        $this->addFilter('seller_name', 'seller.name');

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * Requested sort keys are column indexes (e.g. `id`); qualify them so joins (`seller`) do not break SQL.
     */
    protected function processRequestedSorting($requestedSort)
    {
        $this->dispatchEvent('process_request.sorting.before', $this);

        if (! $this->sortColumn) {
            $this->sortColumn = 'orders.created_at';
        }

        $column = $requestedSort['column'] ?? $this->sortColumn;
        $order = strtolower((string) ($requestedSort['order'] ?? $this->sortOrder));
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

        $prefix = DB::getTablePrefix();

        $qualified = match ($column) {
            'id' => 'orders.id',
            'increment_id' => 'orders.increment_id',
            'status' => 'orders.status',
            'base_grand_total' => 'orders.base_grand_total',
            'channel_id' => 'orders.channel_id',
            'customer_email' => 'orders.customer_email',
            'channel_name' => 'orders.channel_name',
            'created_at' => 'orders.created_at',
            'full_name' => DB::raw('CONCAT('.$prefix.'orders.customer_first_name, " ", '.$prefix.'orders.customer_last_name)'),
            'location' => DB::raw('CONCAT('.$prefix.'order_address_billing.city, ", ", '.$prefix.'order_address_billing.state,", ", '.$prefix.'order_address_billing.country)'),
            'row_actions' => 'orders.id',
            'seller_name' => 'seller.name',
            'method' => DB::raw('GROUP_CONCAT('.$prefix.'order_payment.method SEPARATOR "|")'),
            'items' => 'orders.cart_id',
            default => str_starts_with((string) $column, 'orders.')
                ? $column
                : (Schema::hasColumn((new Order)->getTable(), (string) $column)
                    ? 'orders.'.$column
                    : 'orders.created_at'),
        };

        $this->queryBuilder->orderBy($qualified, $order);

        $this->dispatchEvent('process_request.sorting.after', $this);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.processing'),
                    'value' => Order::STATUS_PROCESSING,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.completed'),
                    'value' => Order::STATUS_COMPLETED,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.canceled'),
                    'value' => Order::STATUS_CANCELED,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.closed'),
                    'value' => Order::STATUS_CLOSED,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.pending'),
                    'value' => Order::STATUS_PENDING,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.pending-payment'),
                    'value' => Order::STATUS_PENDING_PAYMENT,
                ],
                [
                    'label' => trans('superadmin::app.sales.orders.index.datagrid.fraud'),
                    'value' => Order::STATUS_FRAUD,
                ],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('superadmin::app.sales.orders.index.datagrid.processing').'</p>';

                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('superadmin::app.sales.orders.index.datagrid.completed').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('superadmin::app.sales.orders.index.datagrid.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('superadmin::app.sales.orders.index.datagrid.closed').'</p>';

                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('superadmin::app.sales.orders.index.datagrid.pending').'</p>';

                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.trans('superadmin::app.sales.orders.index.datagrid.pending-payment').'</p>';

                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.trans('superadmin::app.sales.orders.index.datagrid.fraud').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.grand-total'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'method',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.pay-via'),
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
            'label' => trans('superadmin::app.sales.orders.index.datagrid.channel-name'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
        ]);

        $this->addColumn([
            'index' => 'channel_name',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.store-name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'closure' => fn ($row) => $row->channel_name ? e($row->channel_name) : '—',
        ]);

        $this->addColumn([
            'index' => 'seller_name',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.seller-name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'closure' => fn ($row) => $row->seller_name ? e($row->seller_name) : '—',
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.customer'),
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
            'label' => trans('superadmin::app.sales.orders.index.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'location',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.location'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'items',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.items'),
            'type' => 'string',
            'exportable' => false,
            'closure' => function ($row) {
                $order = app(OrderRepository::class)->with('items')->find($row->id);

                if (! $order) {
                    return '<span class="text-gray-400">&mdash;</span>';
                }

                try {
                    return view('superadmin::sales.orders.items', compact('order'))->render();
                } catch (\Throwable $e) {
                    report($e);

                    return '<span class="text-gray-400">&mdash;</span>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.date'),
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
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
                'title' => trans('superadmin::app.sales.orders.index.datagrid.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('superadmin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
