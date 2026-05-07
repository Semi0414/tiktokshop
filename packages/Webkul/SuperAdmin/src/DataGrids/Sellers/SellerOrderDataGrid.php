<?php

namespace Webkul\SuperAdmin\DataGrids\Sellers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Observers\SellerOrderCommissionObserver;
use Webkul\Sales\Repositories\OrderRepository;

class SellerOrderDataGrid extends DataGrid
{
    /**
     * Default sort must be qualified: `seller` join also has `id` (and other overlapping names).
     */
    protected $sortColumn = 'orders.created_at';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder(): Builder
    {
        $prefix = DB::getTablePrefix();

        $sellerShopNameSelect = Schema::hasTable('seller_applications')
            ? DB::raw('(SELECT sa.shop_name FROM '.$prefix.'seller_applications sa WHERE sa.seller_id = '.$prefix.'orders.seller_id ORDER BY sa.id DESC LIMIT 1) as seller_shop_name')
            : DB::raw('NULL as seller_shop_name');

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
            ->whereNotNull('orders.seller_id')
            ->select(
                'orders.id',
                DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'order_payment.method SEPARATOR "|") as method'),
                'orders.increment_id',
                'orders.base_grand_total',
                'orders.created_at',
                'orders.channel_name',
                'orders.channel_id',
                'orders.status',
                'orders.seller_approval_status',
                'seller.name as seller_name',
                'seller.email as seller_email',
                $sellerShopNameSelect,
                'orders.customer_email',
                'orders.cart_id as items',
                DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name) as full_name'),
                DB::raw('CONCAT('.DB::getTablePrefix().'order_address_billing.city, ", ", '.DB::getTablePrefix().'order_address_billing.state,", ", '.DB::getTablePrefix().'order_address_billing.country) as location')
            )
            ->groupBy('orders.id');

        $this->addFilter('full_name', DB::raw('CONCAT('.DB::getTablePrefix().'orders.customer_first_name, " ", '.DB::getTablePrefix().'orders.customer_last_name)'));
        $this->addFilter('created_at', 'orders.created_at');
        $this->addFilter('seller_approval_status', 'orders.seller_approval_status');
        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('status', 'orders.status');
        $this->addFilter('customer_email', 'orders.customer_email');
        $this->addFilter('channel_id', 'orders.channel_id');
        $this->addFilter('base_grand_total', 'orders.base_grand_total');
        $this->addFilter('channel_name', 'orders.channel_name');
        $this->addFilter('seller_name', 'seller.name');
        $this->addFilter('seller_email', 'seller.email');

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * Requested sort keys are column indexes (e.g. `id`, `status`); qualify them for joins.
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

        // Join / aggregate selects: avoid `ORDER BY orders.<alias>` which does not exist.
        $qualified = match ($column) {
            'id' => 'orders.id',
            'increment_id' => 'orders.increment_id',
            'status' => 'orders.status',
            'base_grand_total' => 'orders.base_grand_total',
            'channel_id' => 'orders.channel_id',
            'customer_email' => 'orders.customer_email',
            'seller_approval_status' => 'orders.seller_approval_status',
            'channel_name' => 'orders.channel_name',
            'created_at' => 'orders.created_at',
            'full_name' => DB::raw('CONCAT('.$prefix.'orders.customer_first_name, " ", '.$prefix.'orders.customer_last_name)'),
            'location' => DB::raw('CONCAT('.$prefix.'order_address_billing.city, ", ", '.$prefix.'order_address_billing.state,", ", '.$prefix.'order_address_billing.country)'),
            'seller_name' => 'seller.name',
            'seller_email' => 'seller.email',
            'seller_shop_name' => Schema::hasTable('seller_applications')
                ? DB::raw('(SELECT sa.shop_name FROM '.$prefix.'seller_applications sa WHERE sa.seller_id = '.$prefix.'orders.seller_id ORDER BY sa.id DESC LIMIT 1)')
                : DB::raw('NULL'),
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
     * Remove row actions that are not applicable (reject when already decided).
     */
    protected function formatRecords($records): mixed
    {
        $records = parent::formatRecords($records);

        $orderRepository = app(OrderRepository::class);

        foreach ($records as $record) {
            $order = $orderRepository->find((int) ($record->id ?? 0));

            $record->seller_commission_preview = $order
                ? round(SellerOrderCommissionObserver::calculateCommissionTotal($order), 2)
                : 0.0;

            if (empty($record->actions) || ! is_array($record->actions)) {
                continue;
            }

            $record->actions = array_values(array_filter(
                $record->actions,
                static function ($action) {
                    $url = $action['url'] ?? null;

                    if ($url === null || $url === '') {
                        return false;
                    }

                    return ! (is_string($url) && str_starts_with($url, 'javascript'));
                }
            ));
        }

        return $records;
    }

    public function prepareColumns(): void
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
            'index' => 'seller_store_display',
            'label' => trans('superadmin::app.sellers.orders.index.datagrid.seller-store'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'closure' => function ($row) {
                $shop = trim((string) ($row->seller_shop_name ?? ''));
                $email = trim((string) ($row->seller_email ?? ''));
                $name = trim((string) ($row->seller_name ?? ''));
                $displayShop = $shop !== '' ? $shop : ($name !== '' ? $name : '—');
                $lines = ['<span class="font-medium text-gray-800 dark:text-white">'.e($displayShop).'</span>'];
                if ($email !== '') {
                    $lines[] = '<span class="text-xs text-gray-500 dark:text-gray-400">'.e($email).'</span>';
                }

                return implode('<br>', $lines);
            },
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
                $sellerApproval = $row->seller_approval_status ?? null;

                if ($sellerApproval === 'rejected') {
                    return '<p class="label-canceled">'.e(trans('superadmin::app.sellers.orders.index.datagrid.rejected')).'</p>';
                }

                // Same lifecycle as Bagisto `orders.status` (seller panel / order detail). Seller approval is separate.
                $statusHtml = '';

                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        $statusHtml = '<p class="label-processing">'.trans('superadmin::app.sales.orders.index.datagrid.processing').'</p>';

                        break;

                    case Order::STATUS_COMPLETED:
                        $statusHtml = '<p class="label-active">'.trans('superadmin::app.sales.orders.index.datagrid.completed').'</p>';

                        break;

                    case Order::STATUS_CANCELED:
                        $statusHtml = '<p class="label-canceled">'.trans('superadmin::app.sales.orders.index.datagrid.canceled').'</p>';

                        break;

                    case Order::STATUS_CLOSED:
                        $statusHtml = '<p class="label-closed">'.trans('superadmin::app.sales.orders.index.datagrid.closed').'</p>';

                        break;

                    case Order::STATUS_PENDING:
                        $statusHtml = '<p class="label-pending">'.trans('superadmin::app.sales.orders.index.datagrid.pending').'</p>';

                        break;

                    case Order::STATUS_PENDING_PAYMENT:
                        $statusHtml = '<p class="label-pending">'.trans('superadmin::app.sales.orders.index.datagrid.pending-payment').'</p>';

                        break;

                    case Order::STATUS_FRAUD:
                        $statusHtml = '<p class="label-canceled">'.trans('superadmin::app.sales.orders.index.datagrid.fraud').'</p>';

                        break;

                    default:
                        $statusHtml = '<p class="label-pending">'.e(ucfirst((string) ($row->status ?? ''))).'</p>';

                        break;
                }

                return $statusHtml;
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
            'label' => trans('superadmin::app.sellers.orders.index.datagrid.seller-name-column'),
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

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('superadmin::app.sales.orders.index.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'buyer_display',
            'label' => trans('superadmin::app.sellers.orders.index.datagrid.buyer'),
            'type' => 'string',
            'sortable' => false,
            'closure' => function ($row) {
                $name = trim((string) ($row->full_name ?? ''));
                $email = (string) ($row->customer_email ?? '');
                $lines = array_filter([$name !== '' ? e($name) : null, $email !== '' ? '<span class="text-xs text-gray-500 dark:text-gray-400">'.e($email).'</span>' : null]);

                return $lines ? implode('<br>', $lines) : '—';
            },
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
            'closure' => function ($value) {
                $order = app(OrderRepository::class)->with('items')->find($value->id);

                return view('superadmin::sales.orders.items', compact('order'))->render();
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

    public function prepareActions(): void
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

        if (bouncer()->hasPermission('sellers.orders')) {
            $this->addAction([
                'icon' => 'icon-tick text-green-600 dark:text-green-400',
                'title' => trans('superadmin::app.sellers.orders.index.datagrid.approve'),
                'method' => 'POST',
                'url' => function ($row) {
                    return route('superadmin.sellers.orders.approve', $row->id);
                },
            ]);

            $this->addAction([
                'icon' => 'icon-cross text-red-600 dark:text-red-400',
                'title' => trans('superadmin::app.sellers.orders.index.datagrid.reject'),
                'method' => 'POST',
                'url' => function ($row) {
                    return route('superadmin.sellers.orders.reject', $row->id);
                },
            ]);
        }
    }

    public function prepareMassActions(): void
    {
        if (! bouncer()->hasPermission('sellers.orders')) {
            return;
        }

        $this->addMassAction([
            'icon' => 'icon-setting',
            'title' => trans('superadmin::app.sellers.orders.index.datagrid.bulk-update-status'),
            'method' => 'POST',
            'url' => route('superadmin.sellers.orders.bulk-status'),
            'options' => [
                [
                    'label' => trans('superadmin::app.sellers.orders.index.datagrid.bulk-option-completed'),
                    'value' => 'completed',
                ],
                [
                    'label' => trans('superadmin::app.sellers.orders.index.datagrid.bulk-option-rejected'),
                    'value' => 'rejected',
                ],
                [
                    'label' => trans('superadmin::app.sellers.orders.index.datagrid.bulk-option-pending'),
                    'value' => 'pending',
                ],
            ],
        ]);
    }
}
