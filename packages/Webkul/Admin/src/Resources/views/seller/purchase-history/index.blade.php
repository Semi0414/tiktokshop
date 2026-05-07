<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.purchase-history.title')
    </x-slot>

    <x-admin::seller.panel active="shop_order" :breadcrumb="[__('admin::app.seller-panel.purchase-history.title')]">
        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            @lang('admin::app.seller-panel.purchase-history.subtitle')
        </p>

        <form method="get" action="{{ route('admin.seller.purchase-history.index') }}" class="seller-filter-card mb-4">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.purchase-history.order-no')</label>
                    <input
                        type="text"
                        name="seller_ph_increment_id"
                        value="{{ request('seller_ph_increment_id') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.order-time')</label>
                    <div class="flex gap-2">
                        <input type="date" name="seller_ph_date_from" value="{{ request('seller_ph_date_from') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="seller_ph_date_to" value="{{ request('seller_ph_date_to') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.purchase-history.status')</label>
                    <select name="seller_ph_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected(! request('seller_ph_status') || request('seller_ph_status') === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PENDING }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_PENDING)>@lang('admin::app.sales.orders.index.datagrid.pending')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT)>@lang('admin::app.sales.orders.index.datagrid.pending-payment')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PROCESSING }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_PROCESSING)>@lang('admin::app.sales.orders.index.datagrid.processing')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_COMPLETED }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_COMPLETED)>@lang('admin::app.sales.orders.index.datagrid.completed')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_CLOSED }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_CLOSED)>@lang('admin::app.sales.orders.index.datagrid.closed')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_CANCELED }}" @selected(request('seller_ph_status') === \Webkul\Sales\Models\Order::STATUS_CANCELED)>@lang('admin::app.sales.orders.index.datagrid.canceled')</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                    <a href="{{ route('admin.seller.purchase-history.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
                </div>
            </div>
        </form>

        <div class="seller-filter-card overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="seller-data-table !border-0">
                    <thead>
                        <tr>
                            <th>@lang('admin::app.seller-panel.purchase-history.order-no')</th>
                            <th>@lang('admin::app.seller-panel.table.date')</th>
                            <th>@lang('admin::app.seller-panel.purchase-history.total')</th>
                            <th>@lang('admin::app.seller-panel.purchase-history.status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="font-mono text-sm">{{ $order->increment_id }}</td>
                                <td class="whitespace-nowrap">{{ $order->created_at?->format('Y-m-d H:i:s') ?? $order->created_at }}</td>
                                <td>{{ core()->formatPrice($order->grand_total) }}</td>
                                <td><span class="seller-pill seller-pill--blue">{{ $order->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">@lang('admin::app.seller-panel.empty')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($orders->hasPages())
                <div class="border-t border-gray-100 px-4 py-3">{{ $orders->links() }}</div>
            @endif
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>
