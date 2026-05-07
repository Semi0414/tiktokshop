<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\OrderAddress;
use Webkul\User\Models\Admin;

class OrderRefundDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('refunds')
            ->leftJoin('orders', 'refunds.order_id', '=', 'orders.id')
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->select(
                'refunds.id',
                'orders.increment_id',
                'refunds.state',
                'refunds.base_grand_total',
                'refunds.created_at'
            )
            ->addSelect(DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.first_name, " ", '.DB::getTablePrefix().'order_address_billing.last_name) as billed_to'));

        $this->addFilter('billed_to', DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.first_name, " ", '.DB::getTablePrefix().'order_address_billing.last_name)'));
        $this->addFilter('id', 'refunds.id');
        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('state', 'refunds.state');
        $this->addFilter('base_grand_total', 'refunds.base_grand_total');
        $this->addFilter('created_at', 'refunds.created_at');

        $seller = auth()->guard('admin')->user();

        if ($seller instanceof Admin) {
            $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);
            $seeAllOrders = $seller->role
                && $seller->role->permission_type === 'all'
                && $platformRoleIds !== []
                && in_array((int) $seller->role_id, $platformRoleIds, true);

            if (! $seeAllOrders) {
                $queryBuilder->where('orders.seller_id', $seller->id)
                    ->where('orders.seller_approval_status', 'approved');
            }
        }

        /**
         * Seller panel toolbar (GET params merged into datagrid AJAX — see v-datagrid).
         */
        $incrementId = request()->input('seller_refund_increment_id');
        if ($incrementId !== null && $incrementId !== '') {
            $queryBuilder->where('orders.increment_id', 'like', '%'.addcslashes((string) $incrementId, '%_\\').'%');
        }

        $dateFrom = request()->input('seller_refund_date_from');
        $dateTo = request()->input('seller_refund_date_to');
        if ($dateFrom) {
            $queryBuilder->whereDate('refunds.created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $queryBuilder->whereDate('refunds.created_at', '<=', $dateTo);
        }

        $state = request()->input('seller_refund_state');
        if ($state !== null && $state !== '' && $state !== 'all') {
            $queryBuilder->where('refunds.state', $state);
        }

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.sales.refunds.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('admin::app.sales.refunds.index.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.sales.refunds.index.datagrid.refunded-amount'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return core()->formatBasePrice($row->base_grand_total);
            },
        ]);

        $this->addColumn([
            'index' => 'billed_to',
            'label' => trans('admin::app.sales.refunds.index.datagrid.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.sales.refunds.index.datagrid.refund-date'),
            'type' => 'date',
            'searchable' => true,
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
        if (bouncer()->hasPermission('sales.refunds.view')) {
            $this->addAction([
                'icon' => 'icon-view',
                'title' => trans('admin::app.sales.refunds.index.datagrid.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.sales.refunds.view', $row->id);
                },
            ]);
        }
    }
}
