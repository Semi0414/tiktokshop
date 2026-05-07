<?php

namespace Webkul\SuperAdmin\DataGrids\Sellers\View;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;

class OrderDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $p = DB::getTablePrefix();

        $sellerId = (int) (request()->query('seller_id') ?? request()->route('id') ?? 0);

        $queryBuilder = DB::table('orders')
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select(
                'orders.id',
                'orders.increment_id',
                'order_payment.method',
                'orders.base_grand_total',
                'orders.created_at',
                'channel_name',
                'status',
                'order_address_billing.email as customer_email',
                'orders.cart_id as image',
                DB::raw('CONCAT('.$p.'order_address_billing.first_name, " ", '.$p.'order_address_billing.last_name) as full_name'),
                DB::raw('CONCAT('.$p.'order_address_billing.address, ", ", '.$p.'order_address_billing.city,", ", '.$p.'order_address_billing.state, ", ", '.$p.'order_address_billing.country) as location')
            )
            ->where('orders.seller_id', $sellerId > 0 ? $sellerId : -1);

        $this->addFilter('full_name', DB::raw('CONCAT('.$p.'orders.customer_first_name, " ", '.$p.'orders.customer_last_name)'));
        $this->addFilter('created_at', 'orders.created_at');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.order-id'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.status'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.processing'), 'value' => Order::STATUS_PROCESSING],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.completed'), 'value' => Order::STATUS_COMPLETED],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.canceled'), 'value' => Order::STATUS_CANCELED],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.closed'), 'value' => Order::STATUS_CLOSED],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.pending'), 'value' => Order::STATUS_PENDING],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.pending-payment'), 'value' => Order::STATUS_PENDING_PAYMENT],
                ['label' => trans('superadmin::app.customers.customers.view.datagrid.orders.fraud'), 'value' => Order::STATUS_FRAUD],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.processing')).'</p>';
                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.completed')).'</p>';
                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.canceled')).'</p>';
                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.closed')).'</p>';
                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.pending')).'</p>';
                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.pending-payment')).'</p>';
                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.e(trans('superadmin::app.customers.customers.view.datagrid.orders.fraud')).'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.grand-total'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'method',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.pay-via'),
            'type' => 'string',
            'closure' => function ($row) {
                return core()->getConfigData('sales.payment_methods.'.$row->method.'.title');
            },
        ]);

        $this->addColumn([
            'index' => 'channel_name',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.channel-name'),
            'type' => 'string',
            'searchable' => false,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.email'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'location',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.location'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('superadmin::app.customers.customers.view.datagrid.orders.date'),
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'icon' => 'icon-view',
                'title' => trans('superadmin::app.customers.customers.view.datagrid.orders.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('superadmin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
