<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.store-products')
    </x-slot>

    <x-admin::seller.panel
        active="store_products"
        :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.store-products')]"
    >
        <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                @lang('admin::app.seller-panel.store-products.total-in-store', ['count' => $storeProductsTotalCount ?? 0])
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.seller-panel.product-warehouse.level', ['level' => \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null)])
            </p>
        </div>

        <form
            method="get"
            action="{{ route('admin.seller.store-products.index') }}"
            class="seller-filter-card mb-4"
            id="seller-store-product-filters"
        >
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-name')</label>
                    <input
                        type="text"
                        name="seller_product_name"
                        value="{{ request('seller_product_name') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-id')</label>
                    <input
                        type="text"
                        name="seller_product_id"
                        value="{{ request('seller_product_id') }}"
                        inputmode="numeric"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.category')</label>
                    <select name="seller_product_category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">@lang('admin::app.seller-panel.filters.all')</option>
                        @foreach ($categoryFilterOptions ?? [] as $cat)
                            <option value="{{ $cat->category_id }}" @selected((string) request('seller_product_category', '') === (string) $cat->category_id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-status')</label>
                    <select name="seller_product_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" @selected(request('seller_product_status', '') === '')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="1" @selected(request('seller_product_status') === '1')>@lang('admin::app.catalog.products.index.datagrid.active')</option>
                        <option value="0" @selected(request('seller_product_status') === '0')>@lang('admin::app.catalog.products.index.datagrid.disable')</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.store-products.recommended-filter')</label>
                    <select name="seller_product_recommended" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" @selected(request('seller_product_recommended', '') === '')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="1" @selected(request('seller_product_recommended') === '1')>@lang('admin::app.seller-panel.store-products.recommended-yes')</option>
                        <option value="0" @selected(request('seller_product_recommended') === '0')>@lang('admin::app.seller-panel.store-products.recommended-no')</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.seller.store-products.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>

        <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.seller-panel.tabs.store-products')
            </p>
        </div>

        <div id="seller-store-native-edit-modal" class="fixed inset-0 z-[10050] hidden items-center justify-center bg-black/50 p-4">
            <div class="w-[50%] max-w-none rounded-lg border border-gray-200 bg-white p-5 shadow-xl max-md:w-full dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.seller-panel.store-products.edit-modal-title')
                    </p>
                    <button type="button" id="seller-store-native-modal-close" class="text-2xl leading-none text-gray-500 hover:text-gray-800">&times;</button>
                </div>

                <p id="seller-store-native-product-label" class="mb-3 text-xs text-gray-600 dark:text-gray-400"></p>

                <div class="mb-3">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                        @lang('admin::app.seller-panel.product-warehouse.commission-title') (%)
                    </label>
                    <input id="seller-store-native-commission" type="number" step="0.01" class="w-full rounded-md border border-gray-200 px-2.5 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                    <p id="seller-store-native-range-hint" class="mt-1 text-[11px] text-gray-500"></p>
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" id="seller-store-native-recommended" class="rounded border-gray-300" />
                    <label for="seller-store-native-recommended" class="text-sm text-gray-800 dark:text-gray-200">
                        @lang('admin::app.seller-panel.store-products.recommended')
                    </label>
                </div>

                <div class="mb-4">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="seller-store-native-status" class="w-full rounded-md border border-gray-200 px-2.5 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="seller-store-native-cancel" class="seller-btn-secondary text-xs">@lang('admin::app.acl.cancel')</button>
                    <button type="button" id="seller-store-native-save" class="seller-btn-primary text-xs">@lang('admin::app.account.edit.save-btn')</button>
                </div>
            </div>
        </div>

        <div id="seller-store-product-grid">
            @php
                $storeRows = $storeProductsDebugPayload['data'] ?? [];
                $storeMeta = $storeProductsDebugPayload['meta'] ?? [];
            @endphp

            <div class="mb-3 flex flex-wrap items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/50">
                <button
                    type="button"
                    id="store-products-bulk-edit-btn"
                    class="seller-btn-primary text-xs"
                    data-bulk-update-url="{{ route('admin.seller.store-products.bulk-update') }}"
                    data-commission-readonly="{{ !empty($commissionRule['readonly']) ? '1' : '0' }}"
                    data-commission-min="{{ (float) ($commissionRule['min'] ?? 0) }}"
                    data-commission-max="{{ (float) ($commissionRule['max'] ?? 100) }}"
                    data-commission-default="{{ (float) ($commissionRule['default'] ?? 15) }}"
                    onclick="
                        (function (btn) {
                            // alert('INLINE BULK CLICK');
                            var ids = Array.from(document.querySelectorAll('.store-products-row-checkbox:checked'))
                                .map(function (cb) { return parseInt(cb.value, 10); })
                                .filter(function (id) { return !Number.isNaN(id); });
                            var checkedRows = Array.from(document.querySelectorAll('.store-products-row-checkbox:checked'));

                            if (!ids.length) {
                                alert('Select at least one product.');
                                return;
                            }

                            var modal = document.getElementById('seller-store-native-edit-modal');
                            var label = document.getElementById('seller-store-native-product-label');
                            var commission = document.getElementById('seller-store-native-commission');
                            var recommended = document.getElementById('seller-store-native-recommended');
                            var status = document.getElementById('seller-store-native-status');
                            var hint = document.getElementById('seller-store-native-range-hint');
                            var saveBtn = document.getElementById('seller-store-native-save');
                            var closeBtn = document.getElementById('seller-store-native-modal-close');
                            var cancelBtn = document.getElementById('seller-store-native-cancel');

                            if (!modal || !commission || !recommended || !status || !saveBtn) {
                                alert('Modal elements missing.');
                                return;
                            }

                            var isReadonly = btn.dataset.commissionReadonly === '1';
                            var min = parseFloat(btn.dataset.commissionMin || '0');
                            var max = parseFloat(btn.dataset.commissionMax || '100');
                            var def = parseFloat(btn.dataset.commissionDefault || '15');
                            var bulkUrl = btn.dataset.bulkUpdateUrl || '';

                            label && (label.textContent = 'Bulk edit for ' + ids.length + ' products');

                            var firstRow = checkedRows[0];
                            var prefillCommission = firstRow ? parseFloat(firstRow.dataset.commission || String(def)) : def;
                            var prefillRecommended = firstRow ? (firstRow.dataset.recommended === '1') : false;
                            var prefillStatus = firstRow ? String(firstRow.dataset.status || '1') : '1';

                            commission.value = String(Number.isNaN(prefillCommission) ? def : prefillCommission);
                            commission.readOnly = isReadonly;
                            commission.min = String(min);
                            commission.max = String(max);
                            recommended.checked = prefillRecommended;
                            status.value = prefillStatus;
                            if (hint) {
                                hint.textContent = isReadonly
                                    ? 'Fixed commission for your level.'
                                    : ('Allowed range: ' + String(min) + '% to ' + String(max) + '%');
                            }

                            modal.classList.remove('hidden');
                            modal.classList.add('flex');

                            var closeModal = function () {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            };
                            if (closeBtn) closeBtn.onclick = closeModal;
                            if (cancelBtn) cancelBtn.onclick = closeModal;
                            modal.onclick = function (event) { if (event.target === modal) closeModal(); };

                            saveBtn.onclick = async function () {
                                var pct = parseFloat(commission.value || '0');
                                if (isReadonly) pct = def;

                                if (Number.isNaN(pct) || pct < min || pct > max) {
                                    alert('Invalid commission range.');
                                    return;
                                }

                                var payload = {
                                    indices: ids,
                                    commission_percent: pct,
                                    is_recommended: recommended.checked ? 1 : 0,
                                    status: parseInt(status.value || '1', 10),
                                };

                                try {
                                    var response = await fetch(bulkUrl, {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]') || {}).content || '',
                                        },
                                        credentials: 'same-origin',
                                        body: JSON.stringify(payload),
                                    });

                                    var out = await response.json().catch(function () { return {}; });
                                    if (!response.ok) {
                                        alert(out.message || 'Unable to save bulk edit.');
                                        return;
                                    }

                                    if (window.emitter && typeof window.emitter.emit === 'function') {
                                        window.emitter.emit('add-flash', { type: 'success', message: out.message || 'Bulk updated.' });
                                    } else {
                                        alert(out.message || 'Bulk updated.');
                                    }

                                    closeModal();
                                    window.location.reload();
                                } catch (e) {
                                    alert('Unable to save bulk edit.');
                                }
                            };
                        })(this);
                    "
                >
                    Bulk Edit
                </button>

                <form id="store-products-bulk-remove-form" method="post" action="{{ route('admin.seller.store-products.mass-destroy') }}">
                    @csrf
                    <button type="submit" class="seller-btn-secondary text-xs" onclick="return window.submitStoreProductsBulkRemove && window.submitStoreProductsBulkRemove(event);">
                        Remove Selected
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                <input type="checkbox" id="store-products-select-all" class="h-4 w-4 rounded border-gray-300" />
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Product ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">SKU</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Price</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Commission %</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Recommended</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($storeRows as $row)
                            <tr>
                                <td class="px-3 py-3">
                                    <input
                                        type="checkbox"
                                        class="store-products-row-checkbox h-4 w-4 rounded border-gray-300"
                                        value="{{ (int) ($row->ssp_id ?? 0) }}"
                                        data-commission="{{ number_format((float) ($row->commission_percent ?? 0), 2, '.', '') }}"
                                        data-recommended="{{ (int) ($row->is_recommended ?? 0) }}"
                                        data-status="{{ (int) ($row->status ?? 0) === 1 ? '1' : '0' }}"
                                    />
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->product_id ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->name ?? '—' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->sku ?? '—' }}</td>
                                <td class="px-3 py-3 text-right text-sm font-semibold text-blue-700 dark:text-blue-400">
                                    {{ isset($row->price) ? core()->formatPrice((float) $row->price) : '—' }}
                                </td>
                                <td class="px-3 py-3 text-right text-sm font-semibold text-orange-700 dark:text-orange-300">
                                    {{ isset($row->commission_percent) ? number_format((float) $row->commission_percent, 2) . '%' : '—' }}
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    @if ((int) ($row->is_recommended ?? 0) === 1)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Yes</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">No</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    @if ((int) ($row->status ?? 0) === 1)
                                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900/40 dark:text-green-300">Active</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/40 dark:text-red-300">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="seller-btn-primary text-xs store-products-edit-btn"
                                            data-modal-url="{{ route('admin.seller.store-products.modal-data', ['sellerStoreProduct' => (int) ($row->ssp_id ?? 0)]) }}"
                                            data-product-name="{{ $row->name ?? '' }}"
                                            data-product-sku="{{ $row->sku ?? '' }}"
                                            data-commission="{{ number_format((float) ($row->commission_percent ?? 0), 2, '.', '') }}"
                                            data-recommended="{{ (int) ($row->is_recommended ?? 0) }}"
                                            data-status="{{ (int) ($row->status ?? 0) === 1 ? '1' : '0' }}"
                                            onclick="
                                                (async function (btn) {
                                                    var modal = document.getElementById('seller-store-native-edit-modal');
                                                    var label = document.getElementById('seller-store-native-product-label');
                                                    var commission = document.getElementById('seller-store-native-commission');
                                                    var recommended = document.getElementById('seller-store-native-recommended');
                                                    var status = document.getElementById('seller-store-native-status');
                                                    var hint = document.getElementById('seller-store-native-range-hint');
                                                    var saveBtn = document.getElementById('seller-store-native-save');
                                                    var closeBtn = document.getElementById('seller-store-native-modal-close');
                                                    var cancelBtn = document.getElementById('seller-store-native-cancel');
                                                    var url = btn.dataset.modalUrl || '';

                                                    if (!modal || !commission || !recommended || !status || !saveBtn || !url) {
                                                        alert('Modal elements missing.');
                                                        return;
                                                    }

                                                    label && (label.textContent = (btn.dataset.productName || '') + (btn.dataset.productSku ? (' · ' + btn.dataset.productSku) : ''));
                                                    commission.value = String(btn.dataset.commission || '15');
                                                    recommended.checked = String(btn.dataset.recommended || '0') === '1';
                                                    status.value = String(btn.dataset.status || '1');

                                                    try {
                                                        var response = await fetch(url, {
                                                            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                                                            credentials: 'same-origin',
                                                        });
                                                        var data = await response.json().catch(function () { return {}; });

                                                        if (data.product_name || data.sku) {
                                                            label && (label.textContent = (data.product_name || '') + (data.sku ? (' · ' + data.sku) : ''));
                                                        }

                                                        if (data.commission_percent !== undefined && data.commission_percent !== null) {
                                                            commission.value = String(data.commission_percent);
                                                        }

                                                        recommended.checked = !!data.is_recommended;
                                                        status.value = String(data.status ?? status.value ?? '1');

                                                        if (data.commission_rule) {
                                                            commission.readOnly = !!data.commission_rule.readonly;
                                                            commission.min = String(data.commission_rule.min ?? 0);
                                                            commission.max = String(data.commission_rule.max ?? 100);
                                                            if (hint) {
                                                                hint.textContent = data.commission_rule.readonly
                                                                    ? 'Fixed commission for your level.'
                                                                    : ('Allowed range: ' + String(data.commission_rule.min ?? 0) + '% to ' + String(data.commission_rule.max ?? 100) + '%');
                                                            }
                                                        }

                                                        modal.classList.remove('hidden');
                                                        modal.classList.add('flex');

                                                        var closeModal = function () {
                                                            modal.classList.add('hidden');
                                                            modal.classList.remove('flex');
                                                        };

                                                        if (closeBtn) closeBtn.onclick = closeModal;
                                                        if (cancelBtn) cancelBtn.onclick = closeModal;
                                                        modal.onclick = function (event) { if (event.target === modal) closeModal(); };

                                                        saveBtn.onclick = async function () {
                                                            var payload = {
                                                                commission_percent: parseFloat(commission.value || '0'),
                                                                is_recommended: recommended.checked ? 1 : 0,
                                                                status: parseInt(status.value || '1', 10),
                                                            };

                                                            var saveResponse = await fetch(data.update_url || url, {
                                                                method: 'PUT',
                                                                headers: {
                                                                    'X-Requested-With': 'XMLHttpRequest',
                                                                    Accept: 'application/json',
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]') || {}).content || '',
                                                                },
                                                                credentials: 'same-origin',
                                                                body: JSON.stringify(payload),
                                                            });

                                                            var saveData = await saveResponse.json().catch(function () { return {}; });

                                                            if (!saveResponse.ok) {
                                                                alert(saveData.message || 'Unable to save.');
                                                                return;
                                                            }

                                                            if (window.emitter && typeof window.emitter.emit === 'function') {
                                                                window.emitter.emit('add-flash', { type: 'success', message: saveData.message || 'Saved.' });
                                                            } else {
                                                                alert(saveData.message || 'Saved.');
                                                            }

                                                            closeModal();
                                                            window.location.reload();
                                                        };
                                                    } catch (e) {
                                                        alert('Failed to load modal data.');
                                                    }
                                                })(this);
                                            "
                                        >
                                            Edit
                                        </button>

                                        <form method="post" action="{{ route('admin.seller.store-products.destroy', ['sellerStoreProduct' => (int) ($row->ssp_id ?? 0)]) }}" onsubmit="return confirm('Remove this product from store?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="seller-btn-secondary text-xs">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    @lang('admin::app.components.datagrid.table.no-records-available')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 flex items-center justify-between gap-3 text-xs text-gray-600 dark:text-gray-300">
                    <span>
                        Showing {{ $storeMeta['from'] ?? 0 }} to {{ $storeMeta['to'] ?? 0 }} of {{ $storeMeta['total'] ?? 0 }}
                    </span>
                    <div>
                        {{ ($debugPaginator ?? null)?->links() }}
                    </div>
                </div>
            </div>
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>

@pushOnce('scripts')
    @php
        $storeProductsFrontendConfig = [
            'commission_rule' => $commissionRule ?? ['readonly' => true, 'min' => 15, 'max' => 15, 'default' => 15],
            'bulk_update_url' => route('admin.seller.store-products.bulk-update'),
            'messages' => [
                'select_one' => 'Select at least one product.',
                'remove_confirm' => 'Remove selected products from store?',
                'edit_modal_error' => trans('admin::app.seller-panel.store-products.edit-modal-error'),
                'invalid_range' => trans('admin::app.seller-panel.product-warehouse.bulk-add-invalid-range'),
            ],
        ];
    @endphp
    <script>
        (function initStoreProductsLikeWarehouse() {
            const config = @json($storeProductsFrontendConfig);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const state = { mode: 'single', ids: [], updateUrl: '' };

            function flash(type, message) {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('add-flash', { type: type, message: message });
                } else {
                    alert(message);
                }
            }

            function getEls() {
                return {
                    modal: document.getElementById('seller-store-native-edit-modal'),
                    close: document.getElementById('seller-store-native-modal-close'),
                    cancel: document.getElementById('seller-store-native-cancel'),
                    save: document.getElementById('seller-store-native-save'),
                    label: document.getElementById('seller-store-native-product-label'),
                    commission: document.getElementById('seller-store-native-commission'),
                    recommended: document.getElementById('seller-store-native-recommended'),
                    status: document.getElementById('seller-store-native-status'),
                    hint: document.getElementById('seller-store-native-range-hint'),
                };
            }

            function openModal() {
                const { modal } = getEls();
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                const { modal } = getEls();
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function selectedIds() {
                return Array.from(document.querySelectorAll('.store-products-row-checkbox:checked'))
                    .map((cb) => parseInt(cb.value, 10))
                    .filter((id) => !Number.isNaN(id));
            }

            function applyRule(rule) {
                const { commission, hint } = getEls();
                if (!commission || !hint) return;
                commission.readOnly = !!rule.readonly;
                commission.min = String(rule.min ?? 0);
                commission.max = String(rule.max ?? 100);
                if (!commission.value) {
                    commission.value = String(rule.default ?? 15);
                }
                hint.textContent = rule.readonly
                    ? 'Fixed commission for your level.'
                    : ('Allowed range: ' + String(rule.min ?? 0) + '% to ' + String(rule.max ?? 100) + '%');
            }

            window.openSellerStoreProductModal = async function (payload) {
                const p = payload || {};
                const els = getEls();

                state.mode = p.mode === 'bulk' ? 'bulk' : 'single';
                state.ids = Array.isArray(p.ids) ? p.ids : [];
                state.updateUrl = '';

                if (state.mode === 'bulk') {
                    if (!state.ids.length) {
                        flash('warning', config.messages?.select_one || 'Select at least one product.');
                        return;
                    }

                    if (els.label) els.label.textContent = 'Bulk edit for ' + state.ids.length + ' products';
                    if (els.commission) els.commission.value = String(config.commission_rule?.default ?? 15);
                    if (els.recommended) els.recommended.checked = false;
                    if (els.status) els.status.value = '1';
                    applyRule(config.commission_rule || {});
                    openModal();
                    return;
                }

                if (!p.url) {
                    flash('error', 'Edit URL missing.');
                    return;
                }

                if (p.prefill) {
                    if (els.label) {
                        els.label.textContent = (p.prefill.name || '') + (p.prefill.sku ? (' · ' + p.prefill.sku) : '');
                    }

                    if (els.commission && p.prefill.commission !== undefined) {
                        els.commission.value = String(p.prefill.commission || (config.commission_rule?.default ?? 15));
                    }

                    if (els.recommended) {
                        els.recommended.checked = !!p.prefill.recommended;
                    }

                    if (els.status) {
                        els.status.value = String(p.prefill.status || '1');
                    }
                }

                try {
                    const response = await fetch(p.url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                        credentials: 'same-origin',
                    });
                    const d = await response.json();

                    if (els.label) els.label.textContent = (d.product_name || '') + (d.sku ? (' · ' + d.sku) : '');
                    if (els.commission) els.commission.value = String(d.commission_percent ?? d.commission_rule?.default ?? 15);
                    if (els.recommended) els.recommended.checked = !!d.is_recommended;
                    if (els.status) els.status.value = String(d.status ?? 1);
                    applyRule(d.commission_rule || config.commission_rule || {});
                    state.updateUrl = d.update_url || p.url;
                    openModal();
                } catch (e) {
                    flash('error', config.messages?.edit_modal_error || 'Failed to load edit data.');
                }
            };

            async function submitModal() {
                const els = getEls();
                const rule = config.commission_rule || {};
                let pct = parseFloat(els.commission?.value || '0');

                if (rule.readonly) {
                    pct = parseFloat(String(rule.default ?? 15));
                }

                if (Number.isNaN(pct) || pct < Number(rule.min ?? 0) || pct > Number(rule.max ?? 100)) {
                    flash('warning', config.messages?.invalid_range || 'Invalid commission range.');
                    return;
                }

                const payload = {
                    commission_percent: pct,
                    is_recommended: els.recommended?.checked ? 1 : 0,
                    status: parseInt(els.status?.value || '1', 10),
                };

                const url = state.mode === 'bulk' ? config.bulk_update_url : state.updateUrl;
                const method = state.mode === 'bulk' ? 'POST' : 'PUT';

                if (state.mode === 'bulk') {
                    payload.indices = state.ids;
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload),
                    });
                    const out = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        flash('error', out.message || 'Unable to save.');
                        return;
                    }

                    flash('success', out.message || 'Saved.');
                    closeModal();
                    window.location.reload();
                } catch (e) {
                    flash('error', 'Unable to save.');
                }
            }

            function boot() {
                const selectAll = document.getElementById('store-products-select-all');
                const bulkEditBtn = document.getElementById('store-products-bulk-edit-btn');
                const bulkRemoveForm = document.getElementById('store-products-bulk-remove-form');
                const editButtons = Array.from(document.querySelectorAll('.store-products-edit-btn'));
                const { close, cancel, modal, save } = getEls();

                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        Array.from(document.querySelectorAll('.store-products-row-checkbox')).forEach((cb) => {
                            cb.checked = selectAll.checked;
                        });
                    });
                }

                if (bulkEditBtn) {
                    bulkEditBtn.setAttribute('onclick', "window.openSellerStoreProductModal && window.openSellerStoreProductModal({ mode: 'bulk', ids: Array.from(document.querySelectorAll('.store-products-row-checkbox:checked')).map(cb => parseInt(cb.value, 10)).filter(id => !Number.isNaN(id)) })");
                    bulkEditBtn.addEventListener('click', function () {
                        window.openSellerStoreProductModal({
                            mode: 'bulk',
                            ids: selectedIds(),
                        });
                    });
                }

                editButtons.forEach((btn) => {
                    btn.addEventListener('click', function () {
                        window.openSellerStoreProductModal({
                            mode: 'single',
                            url: btn.dataset.modalUrl || '',
                        });
                    });
                });

                if (close) close.addEventListener('click', closeModal);
                if (cancel) cancel.addEventListener('click', closeModal);
                if (modal) {
                    modal.addEventListener('click', function (event) {
                        if (event.target === modal) closeModal();
                    });
                }
                if (save) save.addEventListener('click', submitModal);

                window.submitStoreProductsBulkRemove = function (event) {
                    const ids = selectedIds();

                    if (!ids.length) {
                        event.preventDefault();
                        alert(config.messages?.select_one || 'Select at least one product.');
                        return false;
                    }

                    if (!confirm(config.messages?.remove_confirm || 'Remove selected products from store?')) {
                        event.preventDefault();
                        return false;
                    }

                    if (bulkRemoveForm) {
                        Array.from(bulkRemoveForm.querySelectorAll('input[name="indices[]"]')).forEach((el) => el.remove());
                        ids.forEach((id) => {
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'indices[]';
                            hidden.value = String(id);
                            bulkRemoveForm.appendChild(hidden);
                        });
                    }

                    return true;
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
            } else {
                boot();
            }
        })();
    </script>
@endPushOnce
