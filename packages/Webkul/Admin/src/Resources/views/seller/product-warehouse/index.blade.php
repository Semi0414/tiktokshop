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

                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Seller Level</p>
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        {{ \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null) }}
                    </p>
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
                        id="warehouse-commission-input"
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
            @php
                $warehouseRows = $warehouseDebugPayload['data'] ?? [];
                $warehouseMeta = $warehouseDebugPayload['meta'] ?? [];
            @endphp

            <form
                id="warehouse-bulk-add-form"
                method="post"
                action="{{ route('admin.seller.product-warehouse.attach') }}"
                class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/50"
            >
                @csrf
                <div class="text-xs text-gray-600 dark:text-gray-300">
                    Select products and add to seller store with current commission.
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="warehouse-bulk-open-modal-btn"
                        class="seller-btn-primary text-xs"
                    >
                        @lang('admin::app.seller-panel.product-warehouse.add-to-store')
                    </button>
                </div>
            </form>

            <x-admin::seller.responsive-table class="overflow-hidden">
                <x-slot:table>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                <input id="warehouse-select-all" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Image</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Product ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">SKU</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Type</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Price</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Quantity</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Attribute Family</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($warehouseRows as $row)
                            <tr>
                                <td class="px-3 py-3">
                                    <input
                                        type="checkbox"
                                        class="warehouse-row-checkbox h-4 w-4 rounded border-gray-300"
                                        name="indices[]"
                                        value="{{ $row->product_id }}"
                                        data-already-added="{{ in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0' }}"
                                        form="warehouse-bulk-add-form"
                                    >
                                </td>
                                <td class="px-3 py-3">
                                    @if (!empty($row->base_image ?? null))
                                        <img
                                            src="{{ Storage::url($row->base_image) }}"
                                            alt=""
                                            class="h-10 w-10 rounded border border-gray-200 object-cover dark:border-gray-700"
                                        />
                                    @else
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded border border-dashed border-gray-200 text-xs text-gray-400 dark:border-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->product_id ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->sku ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->name ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->type ?? '—' }}</td>
                                <td class="px-3 py-3 text-right text-sm font-semibold text-blue-700 dark:text-blue-400">
                                    {{ $row->price !== null ? core()->formatPrice((float) $row->price) : '—' }}
                                </td>
                                <td class="px-3 py-3 text-right text-sm text-gray-700 dark:text-gray-200">{{ $row->quantity ?? 0 }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->attribute_family ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm">
                                    <button
                                        type="button"
                                        class="seller-btn-primary text-xs warehouse-single-open-modal-btn"
                                        data-product-id="{{ $row->product_id }}"
                                        data-already-added="{{ in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0' }}"
                                    >
                                        @lang('admin::app.seller-panel.product-warehouse.add-to-store')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.components.datagrid.table.no-records-available')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                        </table>
                    </div>
                </x-slot:table>

                <x-slot:cards>
                    @forelse ($warehouseRows as $row)
                        <article class="seller-mobile-card">
                            <div class="seller-mobile-card__header">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        class="warehouse-row-checkbox h-4 w-4 rounded border-gray-300"
                                        name="indices[]"
                                        value="{{ $row->product_id }}"
                                        data-already-added="{{ in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0' }}"
                                        form="warehouse-bulk-add-form"
                                    />
                                    <span class="seller-mobile-card__title">{{ $row->name ?? '—' }}</span>
                                </label>
                            </div>
                            <div class="seller-mobile-card__rows">
                                @if (!empty($row->base_image ?? null))
                                    <div class="mb-1">
                                        <img src="{{ Storage::url($row->base_image) }}" alt="" class="h-14 w-14 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                    </div>
                                @endif
                                <x-admin::seller.mobile-card-field label="Product ID">{{ $row->product_id ?? '—' }}</x-admin::seller.mobile-card-field>
                                <x-admin::seller.mobile-card-field label="SKU">{{ $row->sku ?? '—' }}</x-admin::seller.mobile-card-field>
                                <x-admin::seller.mobile-card-field label="Type">{{ $row->type ?? '—' }}</x-admin::seller.mobile-card-field>
                                <x-admin::seller.mobile-card-field label="Price">{{ $row->price !== null ? core()->formatPrice((float) $row->price) : '—' }}</x-admin::seller.mobile-card-field>
                                <x-admin::seller.mobile-card-field label="Quantity">{{ $row->quantity ?? 0 }}</x-admin::seller.mobile-card-field>
                                <x-admin::seller.mobile-card-field label="Attribute Family">{{ $row->attribute_family ?? '—' }}</x-admin::seller.mobile-card-field>
                            </div>
                            <div class="seller-mobile-card__actions">
                                <button
                                    type="button"
                                    class="seller-btn-primary w-full text-xs warehouse-single-open-modal-btn"
                                    data-product-id="{{ $row->product_id }}"
                                    data-already-added="{{ in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0' }}"
                                >
                                    @lang('admin::app.seller-panel.product-warehouse.add-to-store')
                                </button>
                            </div>
                        </article>
                    @empty
                        <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                            @lang('admin::app.components.datagrid.table.no-records-available')
                        </p>
                    @endforelse
                </x-slot:cards>

                <x-slot:footer>
                    <div class="flex items-center justify-between gap-3 border-t border-gray-100 px-3 py-3 text-xs text-gray-600 dark:text-gray-300">
                        <span>Showing {{ $warehouseMeta['from'] ?? 0 }} to {{ $warehouseMeta['to'] ?? 0 }} of {{ $warehouseMeta['total'] ?? 0 }}</span>
                        <div>{{ $warehouseDebugPaginator->links() }}</div>
                    </div>
                </x-slot:footer>
            </x-admin::seller.responsive-table>
        </div>

        @include('admin::seller.product-warehouse.partials.add-modal')

    </x-admin::seller.panel>
</x-admin::layouts>

@pushOnce('scripts')
    <script>
        (function initWarehousePage() {
            function notify(type, message) {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('add-flash', { type: type, message: message });
                } else {
                    alert(message);
                }
            }

            function boot() {
                var grid = document.getElementById('seller-warehouse-grid');

                if (grid && grid.dataset.singleAddDelegationBound !== '1') {
                    grid.dataset.singleAddDelegationBound = '1';
                    grid.addEventListener('click', function (event) {
                        var btn = event.target.closest('.warehouse-single-open-modal-btn');

                        if (!btn) {
                            return;
                        }

                        event.preventDefault();

                        if (String(btn.dataset.alreadyAdded) === '1') {
                            alert('This product is already added to your catalog.');

                            return;
                        }

                        var id = parseInt(btn.dataset.productId || '0', 10);

                        if (typeof window.openWarehouseAddModalFromSingle === 'function') {
                            window.openWarehouseAddModalFromSingle(id);
                        } else if (typeof window.openWarehouseAddModalWithIds === 'function') {
                            window.openWarehouseAddModalWithIds(Number.isNaN(id) ? [] : [id]);
                        }
                    });
                }

                var commissionInput = document.getElementById('warehouse-commission-input');
                var modalCommission = document.getElementById('warehouse-add-modal-commission');

                function syncCommissionTargets() {
                    if (commissionInput && modalCommission) {
                        modalCommission.value = commissionInput.value || modalCommission.value;
                    }
                }

                if (commissionInput) {
                    commissionInput.addEventListener('input', syncCommissionTargets);
                    commissionInput.addEventListener('change', syncCommissionTargets);
                    syncCommissionTargets();
                }

                var selectAll = document.getElementById('warehouse-select-all');
                var rowCheckboxes = document.querySelectorAll('.warehouse-row-checkbox');

                if (selectAll && rowCheckboxes.length) {
                    selectAll.addEventListener('change', function () {
                        rowCheckboxes.forEach(function (cb) {
                            cb.checked = selectAll.checked;
                        });
                    });
                }

                var bulkOpenBtn = document.getElementById('warehouse-bulk-open-modal-btn');

                if (bulkOpenBtn) {
                    bulkOpenBtn.addEventListener('click', function () {
                        var ids = Array.from(rowCheckboxes)
                            .filter(function (cb) { return cb.checked; })
                            .map(function (cb) { return parseInt(cb.value, 10); })
                            .filter(function (id) { return !Number.isNaN(id); });

                        if (typeof window.openWarehouseAddModalWithIds === 'function') {
                            window.openWarehouseAddModalWithIds(ids);
                        }
                    });
                }

                var form = document.getElementById('warehouse-commission-form');
                var submitBtn = document.getElementById('warehouse-commission-save-btn');

                if (!form || !submitBtn) {
                    return;
                }

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    submitBtn.disabled = true;

                    try {
                        var response = await fetch(form.dataset.saveUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new FormData(form),
                            credentials: 'same-origin',
                        });

                        var payload = await response.json().catch(function () { return {}; });

                        if (!response.ok) {
                            var message = payload.message
                                || (payload.errors && payload.errors.commission_percent ? payload.errors.commission_percent[0] : 'Error');

                            notify('error', message);

                            return;
                        }

                        notify('success', payload.message || 'Saved');
                    } catch (error) {
                        notify('error', 'Error');
                    } finally {
                        submitBtn.disabled = false;
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
            } else {
                boot();
            }
        })();
    </script>
@endPushOnce
