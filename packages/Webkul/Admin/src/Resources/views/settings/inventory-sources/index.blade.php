<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.product-warehouse')
    </x-slot>

    <x-admin::seller.panel active="product_warehouse" :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.product-warehouse')]">
        <form method="get" action="{{ route('admin.settings.inventory_sources.index') }}" class="seller-filter-card mb-4" id="seller-warehouse-filters">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.settings.inventory-sources.index.datagrid.name')</label>
                    <input
                        type="text"
                        name="seller_warehouse_name"
                        value="{{ request('seller_warehouse_name') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.settings.inventory-sources.index.datagrid.code')</label>
                    <input
                        type="text"
                        name="seller_warehouse_code"
                        value="{{ request('seller_warehouse_code') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.settings.inventory-sources.index.datagrid.id')</label>
                    <input
                        type="text"
                        name="seller_warehouse_id"
                        value="{{ request('seller_warehouse_id') }}"
                        inputmode="numeric"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.settings.inventory-sources.index.datagrid.status')</label>
                    <select name="seller_warehouse_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="1" @selected(request('seller_warehouse_status') === '1')>@lang('admin::app.settings.inventory-sources.index.datagrid.active')</option>
                        <option value="0" @selected(request('seller_warehouse_status') === '0')>@lang('admin::app.settings.inventory-sources.index.datagrid.inactive')</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.settings.inventory_sources.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>

    <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.settings.inventory-sources.index.title')
        </p>

        <!-- Create Button -->
        @if (bouncer()->hasPermission('settings.inventory_sources.create'))
            <a href="{{ route('admin.settings.inventory_sources.create') }}">
                <div class="primary-button">
                    @lang('admin::app.settings.inventory-sources.index.create-btn')
                </div>
            </a>
        @endif
    </div>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.before') !!}

    <div id="seller-warehouse-grid">
    <x-admin::datagrid :src="route('admin.settings.inventory_sources.index')" />
    </div>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.after') !!}

    </x-admin::seller.panel>
</x-admin::layouts>
