<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.refund-request')
    </x-slot>

    <x-admin::seller.panel active="refund_request" :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.refund-request')]">
        <form method="get" action="{{ route('admin.sales.refunds.index') }}" class="seller-filter-card mb-4">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.order-id')</label>
                    <input
                        type="text"
                        name="seller_refund_increment_id"
                        value="{{ request('seller_refund_increment_id') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.refund-date')</label>
                    <div class="flex gap-2">
                        <input type="date" name="seller_refund_date_from" value="{{ request('seller_refund_date_from') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="seller_refund_date_to" value="{{ request('seller_refund_date_to') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.status')</label>
                    <select name="seller_refund_state" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected(! request('seller_refund_state') || request('seller_refund_state') === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        @foreach ($refundStateOptions ?? [] as $st)
                            <option value="{{ $st }}" @selected(request('seller_refund_state') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                    <a href="{{ route('admin.sales.refunds.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
                </div>
            </div>
        </form>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.refunds.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.refunds.index')" />
        </div>
    </div>

    <div id="seller-refund-grid">
    <x-admin::datagrid :src="route('admin.sales.refunds.index')" />
    </div>
    </x-admin::seller.panel>
</x-admin::layouts>
