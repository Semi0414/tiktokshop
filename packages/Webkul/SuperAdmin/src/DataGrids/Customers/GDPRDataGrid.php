<?php

namespace Webkul\SuperAdmin\DataGrids\Customers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GDPRDataGrid extends DataGrid
{
    /**
     * GDPR status "approved".
     */
    const STATUS_COMPLETED = 'completed';

    /**
     * GDPR status "pending", indicating awaiting approval.
     */
    const STATUS_PENDING = 'pending';

    /**
     * GDPR status "declined", indicating rejection or denial.
     */
    const STATUS_DECLINED = 'declined';

    /**
     * GDPR status "processing".
     */
    const STATUS_PROCESSING = 'processing';

    /**
     * Request status "revoked".
     */
    const STATUS_REVOKED = 'revoked';

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('gdpr_data_request as gdpr')
            ->leftJoin('customers', 'gdpr.customer_id', '=', 'customers.id')
            ->addSelect(
                'gdpr.id',
                DB::raw("CONCAT({$tablePrefix}customers.first_name, ' ', {$tablePrefix}customers.last_name) as customer_name"),
                'gdpr.customer_id',
                'gdpr.status',
                'gdpr.type',
                'gdpr.message',
                'gdpr.created_at'
            );

        $this->addFilter('id', 'gdpr.id');
        $this->addFilter('type', 'gdpr.type');
        $this->addFilter('created_at', 'gdpr.created_at');
        $this->addFilter('status', 'gdpr.status');
        $this->addFilter('customer_name', DB::raw("CONCAT({$tablePrefix}customers.first_name, ' ', {$tablePrefix}customers.last_name)"));

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
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.id'),
            'type' => 'integer',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => false,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.pending'),
                    'value' => self::STATUS_PENDING,
                ],
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.processing'),
                    'value' => self::STATUS_PROCESSING,
                ],
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.completed'),
                    'value' => self::STATUS_COMPLETED,
                ],
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.declined'),
                    'value' => self::STATUS_DECLINED,
                ],
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.revoked'),
                    'value' => self::STATUS_REVOKED,
                ],
            ],
            'closure' => function ($row) {
                switch ($row->status) {
                    case self::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('superadmin::app.customers.gdpr.index.datagrid.completed').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('superadmin::app.customers.gdpr.index.datagrid.pending').'</p>';

                    case self::STATUS_DECLINED:
                        return '<p class="label-canceled">'.trans('superadmin::app.customers.gdpr.index.datagrid.declined').'</p>';

                    case self::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('superadmin::app.customers.gdpr.index.datagrid.processing').'</p>';

                    case self::STATUS_REVOKED:
                        return '<p class="label-closed">'.trans('superadmin::app.customers.gdpr.index.datagrid.revoked').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.type'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.delete'),
                    'value' => 'delete',
                ],
                [
                    'label' => trans('superadmin::app.customers.gdpr.index.datagrid.edit'),
                    'value' => 'update',
                ],
            ],
            'closure' => function ($row) {
                switch ($row->type) {
                    case 'delete':
                        return trans('superadmin::app.customers.gdpr.index.datagrid.delete');

                    case 'update':
                        return trans('superadmin::app.customers.gdpr.index.datagrid.edit');
                }
            },
        ]);

        $this->addColumn([
            'index' => 'message',
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.message'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('superadmin::app.customers.gdpr.index.datagrid.created-at'),
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
        ]);
    }

    /**
     * Add actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('customers.gdpr_requests.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('superadmin::app.customers.gdpr.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('superadmin.customers.gdpr.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('customers.gdpr_requests.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('superadmin::app.customers.gdpr.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('superadmin.customers.gdpr.delete', $row->id);
                },
            ]);
        }
    }
}
