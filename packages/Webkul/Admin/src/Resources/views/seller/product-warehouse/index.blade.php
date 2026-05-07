<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.product-warehouse-browse')
    </x-slot>

    <x-admin::seller.panel
        active="product_warehouse"
        :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.product-warehouse-browse')]"
    >
        <div class="mb-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.seller-panel.tabs.product-warehouse-browse')
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Browse eligible catalog products and add them to your store.
                    </p>
                </div>

                <div class="grid gap-2 sm:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Warehouse Products</p>
                        <p class="text-base font-semibold text-gray-800 dark:text-white">{{ $warehouseTotalProducts }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Seller Level</p>
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            {{ \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50/80 p-4 dark:border-blue-900 dark:bg-blue-950/40">
            <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.seller-panel.product-warehouse.commission-title')
            </p>
            <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                @lang('admin::app.seller-panel.product-warehouse.commission-hint')
            </p>

            <form
                id="warehouse-commission-form"
                data-save-url="{{ route('admin.seller.product-warehouse.commission') }}"
                class="flex flex-wrap items-end gap-3"
            >
                @csrf

                <div class="min-w-[140px]">
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">%</label>
                    <input
                        type="number"
                        step="0.01"
                        name="commission_percent"
                        value="{{ number_format((float) $currentCommissionPercent, 2, '.', '') }}"
                        min="{{ number_format((float) $commissionRule['min'], 2, '.', '') }}"
                        max="{{ number_format((float) $commissionRule['max'], 2, '.', '') }}"
                        @if ($commissionRule['readonly']) readonly @endif
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                @if (! $commissionRule['readonly'])
                    <button
                        type="submit"
                        id="warehouse-commission-save-btn"
                        class="seller-btn-primary"
                    >
                        @lang('admin::app.seller-panel.product-warehouse.save-commission')
                    </button>
                @endif
            </form>
        </div>

        <form method="get" action="{{ route('admin.seller.product-warehouse.index') }}" class="seller-filter-card mb-4" id="seller-warehouse-product-filters">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-name')</label>
                    <input
                        type="text"
                        name="seller_warehouse_product_name"
                        value="{{ request('seller_warehouse_product_name') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-id')</label>
                    <input
                        type="text"
                        name="seller_warehouse_product_id"
                        value="{{ request('seller_warehouse_product_id') }}"
                        inputmode="numeric"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.seller.product-warehouse.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>

        <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.seller-panel.tabs.product-warehouse')
            </p>
        </div>

        <div id="seller-warehouse-grid" class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <x-admin::datagrid
                :src="route('admin.seller.product-warehouse.index')"
                :isMultiRow="true"
            >
            </x-admin::datagrid>
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('warehouse-commission-form');
            const submitBtn = document.getElementById('warehouse-commission-save-btn');

            if (! form || ! submitBtn) {
                return;
            }

            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                submitBtn.disabled = true;

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.dataset.saveUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                        credentials: 'same-origin',
                    });

                    const payload = await response.json().catch(function () { return {}; });

                    if (! response.ok) {
                        const message = payload.message
                            || (payload.errors && payload.errors.commission_percent ? payload.errors.commission_percent[0] : 'Error');

                        if (window.emitter && typeof window.emitter.emit === 'function') {
                            window.emitter.emit('add-flash', { type: 'error', message: message });
                        }

                        return;
                    }

                    if (window.emitter && typeof window.emitter.emit === 'function') {
                        window.emitter.emit('add-flash', { type: 'success', message: payload.message || 'Saved' });
                    }
                } catch (error) {
                    if (window.emitter && typeof window.emitter.emit === 'function') {
                        window.emitter.emit('add-flash', { type: 'error', message: 'Error' });
                    }
                } finally {
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
@endPushOnce
