<?php

namespace Webkul\SuperAdmin\DataGrids\Marketing\Communications;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class EmailTemplateDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketing_templates')
            ->select(
                'id',
                'name',
                'status'
            );

        $this->addFilter('status', 'status');

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
            'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.active'),
                    'value' => 'active',
                ],
                [
                    'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.inactive'),
                    'value' => 'inactive',
                ],
                [
                    'label' => trans('superadmin::app.marketing.communications.templates.index.datagrid.draft'),
                    'value' => 'draft',
                ],
            ],
            'sortable' => true,
            'closure' => function ($value) {
                if ($value->status == 'active') {
                    return trans('superadmin::app.marketing.communications.templates.index.datagrid.active');
                } elseif ($value->status == 'inactive') {
                    return trans('superadmin::app.marketing.communications.templates.index.datagrid.inactive');
                } elseif ($value->status == 'draft') {
                    return trans('superadmin::app.marketing.communications.templates.index.datagrid.draft');
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
        if (bouncer()->hasPermission('marketing.communications.email_templates.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('superadmin::app.marketing.communications.templates.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('superadmin.marketing.communications.email_templates.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.communications.email_templates.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('superadmin::app.marketing.communications.templates.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('superadmin.marketing.communications.email_templates.delete', $row->id);
                },
            ]);
        }
    }
}
