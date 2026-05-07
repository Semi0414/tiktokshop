<?php

namespace Webkul\SuperAdmin\DataGrids\Marketing;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;

class EmailManagementDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $table = 'superadmin_email_logs';

        if (! Schema::hasTable($table)) {
            return DB::table($table)->whereRaw('1 = 0');
        }

        $existing = array_flip(Schema::getColumnListing($table));
        /* mail_type is always "custom" for compose HTML; recipient (seller/customer/custom) is in recipient_type — omit Kind to avoid confusion */
        $preferred = [
            'id',
            'to_email',
            'recipient_type',
            'recipient_id',
            'subject',
            'status',
            'error_message',
            'body_preview',
            'created_at',
        ];

        $select = array_values(array_filter($preferred, function ($col) use ($existing) {
            return isset($existing[$col]);
        }));

        if ($select === []) {
            $select = ['*'];
        }

        return DB::table($table)
            ->select($select)
            ->orderByDesc('id');
    }

    public function prepareColumns(): void
    {
        $table = 'superadmin_email_logs';
        $exists = Schema::hasTable($table)
            ? array_flip(Schema::getColumnListing($table))
            : [];

        $addIf = function (string $index, array $def) use ($exists) {
            if (isset($exists[$index])) {
                $this->addColumn(array_merge($def, ['index' => $index]));
            }
        };

        $addIf('id', [
            'label' => trans('superadmin::app.email-management.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $addIf('to_email', [
            'label' => trans('superadmin::app.email-management.datagrid.to'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $addIf('recipient_type', [
            'label' => trans('superadmin::app.email-management.datagrid.recipient-type'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $addIf('subject', [
            'label' => trans('superadmin::app.email-management.datagrid.subject'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $addIf('status', [
            'label' => trans('superadmin::app.email-management.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                $raw = (string) ($row->status ?? '');

                return match ($raw) {
                    'queued' => trans('superadmin::app.email-management.datagrid.status-queued'),
                    'sent', 'success' => trans('superadmin::app.email-management.datagrid.status-sent'),
                    'failed' => trans('superadmin::app.email-management.datagrid.status-failed'),
                    default => $raw !== ''
                        ? $raw
                        : trans('superadmin::app.email-management.datagrid.status-other'),
                };
            },
        ]);

        $addIf('created_at', [
            'label' => trans('superadmin::app.email-management.datagrid.sent-at'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
        ]);
    }

    public function prepareActions(): void
    {
        if (! bouncer()->hasPermission('marketing.email_management')) {
            return;
        }

        $this->addAction([
            'index' => 'resend',
            'icon' => 'icon-mail',
            'title' => trans('superadmin::app.email-management.datagrid.action-resend'),
            'method' => 'GET',
            'url' => function ($row) {
                return route('superadmin.email-management.index', ['compose_from' => $row->id]);
            },
        ]);

        $this->addAction([
            'index' => 'delete',
            'icon' => 'icon-delete',
            'title' => trans('superadmin::app.email-management.datagrid.action-delete'),
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('superadmin.email-management.logs.destroy', $row->id);
            },
        ]);
    }
}
