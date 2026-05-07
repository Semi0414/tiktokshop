<?php

namespace Webkul\Admin\DataGrids\Settings;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class InventorySourcesDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('inventory_sources')
            ->select(
                'id',
                'code',
                'name',
                'priority',
                'status'
            );

        /**
         * Seller panel toolbar (GET params merged into datagrid AJAX — see v-datagrid).
         */
        $sellerWarehouseName = request()->input('seller_warehouse_name');
        if ($sellerWarehouseName !== null && $sellerWarehouseName !== '') {
            $queryBuilder->where('inventory_sources.name', 'like', '%'.addcslashes((string) $sellerWarehouseName, '%_\\').'%');
        }

        $sellerWarehouseCode = request()->input('seller_warehouse_code');
        if ($sellerWarehouseCode !== null && $sellerWarehouseCode !== '') {
            $queryBuilder->where('inventory_sources.code', 'like', '%'.addcslashes((string) $sellerWarehouseCode, '%_\\').'%');
        }

        $sellerWarehouseId = request()->input('seller_warehouse_id');
        if ($sellerWarehouseId !== null && $sellerWarehouseId !== '' && is_numeric($sellerWarehouseId)) {
            $queryBuilder->where('inventory_sources.id', (int) $sellerWarehouseId);
        }

        $sellerWarehouseStatus = request()->input('seller_warehouse_status');
        if ($sellerWarehouseStatus !== null && $sellerWarehouseStatus !== '') {
            $queryBuilder->where('inventory_sources.status', (int) $sellerWarehouseStatus);
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
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.inventory-sources.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.settings.inventory-sources.index.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.settings.inventory-sources.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'priority',
            'label' => trans('admin::app.settings.inventory-sources.index.datagrid.priority'),
            'type' => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.settings.inventory-sources.index.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.settings.inventory-sources.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.settings.inventory-sources.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
            'closure' => function ($value) {
                if ($value->status) {
                    return trans('admin::app.settings.inventory-sources.index.datagrid.active');
                }

                return trans('admin::app.settings.inventory-sources.index.datagrid.inactive');
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
        if (bouncer()->hasPermission('settings.inventory_sources.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.inventory-sources.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.settings.inventory_sources.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.inventory_sources.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.inventory-sources.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.settings.inventory_sources.delete', $row->id);
                },
            ]);
        }
    }
}
