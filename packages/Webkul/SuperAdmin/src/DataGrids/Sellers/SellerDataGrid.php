<?php

namespace Webkul\SuperAdmin\DataGrids\Sellers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\User\Repositories\RoleRepository;

class SellerDataGrid extends DataGrid
{
    public function __construct(protected RoleRepository $roleRepository) {}

    protected $primaryColumn = 'seller_id';

    protected $sortColumn = 'seller_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();
        $ordersTable = $tablePrefix.'orders';
        $sellerTable = $tablePrefix.'seller';

        $queryBuilder = DB::table('seller')
            ->leftJoin('roles', 'seller.role_id', '=', 'roles.id');

        $select = [
            'seller.id as seller_id',
            'seller.name as full_name',
            'seller.email',
            'seller.status',
        ];

        if (Schema::hasColumn('seller', 'referral_code')) {
            $select[] = 'seller.referral_code';
        } else {
            $select[] = DB::raw('NULL as referral_code');
        }

        if (Schema::hasColumn('seller', 'wallet_balance')) {
            $select[] = 'seller.wallet_balance';
        } else {
            $select[] = DB::raw('0 as wallet_balance');
        }

        if (Schema::hasColumn('seller', 'credit_score')) {
            $select[] = 'seller.credit_score';
        } else {
            $select[] = DB::raw('100 as credit_score');
        }

        $select[] = 'roles.name as group';
        $select[] = DB::raw('0 as is_suspended');
        $select[] = DB::raw('NULL as channel_id');
        $select[] = DB::raw('"" as gender');
        $select[] = DB::raw('"" as phone');

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'seller_id')) {
            $select[] = DB::raw("(SELECT COUNT(*) FROM {$ordersTable} o WHERE o.seller_id = {$sellerTable}.id) as order_count");
        } else {
            $select[] = DB::raw('0 as order_count');
        }

        $select[] = DB::raw('0 as address_count');

        $queryBuilder->select($select);

        $this->addFilter('seller_id', 'seller.id');
        $this->addFilter('email', 'seller.email');
        $this->addFilter('full_name', 'seller.name');
        $this->addFilter('group', 'roles.name');
        $this->addFilter('status', 'seller.status');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'channel_id',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.channel'),
            'type' => 'string',
            'filterable' => false,
            'sortable' => false,
            'closure' => fn () => 'N/A',
        ]);

        $this->addColumn([
            'index' => 'seller_id',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'referral_code',
            'label' => trans('superadmin::app.sellers.index.datagrid.referral-code'),
            'type' => 'string',
            'sortable' => true,
            'closure' => fn ($row) => $row->referral_code ? e($row->referral_code) : '—',
        ]);

        $this->addColumn([
            'index' => 'wallet_balance',
            'label' => trans('superadmin::app.sellers.index.datagrid.balance'),
            'type' => 'string',
            'sortable' => true,
            'closure' => fn ($row) => core()->formatPrice((float) ($row->wallet_balance ?? 0)),
        ]);

        $this->addColumn([
            'index' => 'credit_score',
            'label' => trans('superadmin::app.sellers.index.datagrid.credit-score'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'phone',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.phone'),
            'type' => 'string',
            'filterable' => false,
            'closure' => fn () => 'N/A',
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.status'),
            'type' => 'boolean',
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.customers.customers.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('superadmin::app.customers.customers.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'gender',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.gender'),
            'type' => 'string',
            'sortable' => false,
            'closure' => fn () => 'N/A',
        ]);

        $this->addColumn([
            'index' => 'group',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.group'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => $this->roleRepository->all(['name as label', 'name as value'])->toArray(),
        ]);

        $this->addColumn([
            'index' => 'is_suspended',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.suspended'),
            'type' => 'boolean',
            'sortable' => false,
        ]);

        $this->addColumn([
            'index' => 'revenue',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.revenue'),
            'type' => 'integer',
            'closure' => function ($row) {
                return app(OrderRepository::class)->scopeQuery(function ($q) use ($row) {
                    return $q->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_CLOSED])
                        ->where('seller_id', $row->seller_id);
                })->sum('base_grand_total_invoiced');
            },
        ]);

        $this->addColumn([
            'index' => 'order_count',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.order-count'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'address_count',
            'label' => trans('superadmin::app.customers.customers.index.datagrid.address-count'),
            'type' => 'integer',
            'sortable' => false,
        ]);
    }

    public function prepareActions(): void
    {
        $this->addAction([
            'icon' => 'icon-view',
            'title' => trans('superadmin::app.customers.customers.index.datagrid.view'),
            'method' => 'GET',
            'url' => function ($row) {
                return route('superadmin.sellers.view', $row->seller_id);
            },
        ]);

        $this->addAction([
            'icon' => 'icon-exit',
            'title' => trans('superadmin::app.sellers.index.datagrid.login-as-seller'),
            'method' => 'GET',
            'target' => 'blank',
            'url' => function ($row) {
                return route('superadmin.sellers.login_as_seller', $row->seller_id);
            },
        ]);
    }

    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('sellers.all.delete')) {
            $this->addMassAction([
                'title' => trans('superadmin::app.customers.customers.index.datagrid.delete'),
                'method' => 'POST',
                'url' => route('superadmin.sellers.mass_delete'),
            ]);
        }

        if (bouncer()->hasPermission('sellers.all.edit')) {
            $this->addMassAction([
                'title' => trans('superadmin::app.customers.customers.index.datagrid.update-status'),
                'method' => 'POST',
                'url' => route('superadmin.sellers.mass_update'),
                'options' => [
                    [
                        'label' => trans('superadmin::app.customers.customers.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('superadmin::app.customers.customers.index.datagrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }
}
