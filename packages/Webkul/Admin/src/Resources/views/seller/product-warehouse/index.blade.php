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
                        onclick="
                            (function () {
                                var checked = Array.from(document.querySelectorAll('.warehouse-row-checkbox:checked'));
                                var ids = checked.map(function (cb) { return parseInt(cb.value, 10); }).filter(function (id) { return !Number.isNaN(id); });

                                if (!ids.length) {
                                    alert('Select at least one product.');
                                    return;
                                }

                                var filtered = [];
                                var skipped = 0;

                                checked.forEach(function (cb) {
                                    if (String(cb.dataset.alreadyAdded) === '1') {
                                        skipped++;
                                    } else {
                                        var id = parseInt(cb.value, 10);
                                        if (!Number.isNaN(id)) filtered.push(id);
                                    }
                                });

                                if (!filtered.length) {
                                    alert('This product is already added to your catalog.');
                                    return;
                                }

                                if (skipped > 0) {
                                    alert('Some selected products are already added to your catalog. Remaining products will be added.');
                                }

                                var modal = document.getElementById('warehouse-add-modal');
                                var modalIndices = document.getElementById('warehouse-add-modal-indices');
                                var modalCommission = document.getElementById('warehouse-add-modal-commission');
                                var commissionInput = document.getElementById('warehouse-commission-input');

                                if (!modal || !modalIndices || !modalCommission) return;

                                modalIndices.innerHTML = filtered.map(function (id) {
                                    return '<input type=\'hidden\' name=\'indices[]\' value=\'' + id + '\'>';
                                }).join('');

                                if (commissionInput && commissionInput.value) {
                                    modalCommission.value = commissionInput.value;
                                }

                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                                modal.style.display = 'flex';
                                modal.setAttribute('aria-hidden', 'false');
                            })();
                        "
                    >
                        @lang('admin::app.seller-panel.product-warehouse.add-to-store')
                    </button>
                </div>
            </form>

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
                                        onclick="
                                            (function (btn) {
                                                if (String(btn.dataset.alreadyAdded) === '1') {
                                                    alert('This product is already added to your catalog.');
                                                    return;
                                                }

                                                var pid = parseInt(btn.dataset.productId || '0', 10);
                                                if (Number.isNaN(pid) || pid <= 0) return;

                                                var modal = document.getElementById('warehouse-add-modal');
                                                var modalIndices = document.getElementById('warehouse-add-modal-indices');
                                                var modalCommission = document.getElementById('warehouse-add-modal-commission');
                                                var commissionInput = document.getElementById('warehouse-commission-input');

                                                if (!modal || !modalIndices || !modalCommission) return;

                                                modalIndices.innerHTML = '<input type=\'hidden\' name=\'indices[]\' value=\'' + pid + '\'>';

                                                if (commissionInput && commissionInput.value) {
                                                    modalCommission.value = commissionInput.value;
                                                }

                                                modal.classList.remove('hidden');
                                                modal.classList.add('flex');
                                                modal.style.display = 'flex';
                                                modal.setAttribute('aria-hidden', 'false');
                                            })(this);
                                        "
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

            <div class="mt-3 flex items-center justify-between gap-3 text-xs text-gray-600 dark:text-gray-300">
                <span>
                    Showing {{ $warehouseMeta['from'] ?? 0 }} to {{ $warehouseMeta['to'] ?? 0 }} of {{ $warehouseMeta['total'] ?? 0 }}
                </span>
                <div>
                    {{ $warehouseDebugPaginator->links() }}
                </div>
            </div>
        </div>

        <div id="warehouse-add-modal" class="fixed inset-0 z-[10050] hidden items-center justify-center bg-black/50 p-4" aria-hidden="true" onclick="if (event.target === this && window.closeWarehouseAddModal) { window.closeWarehouseAddModal(); }">
            <div class="w-[50%] max-w-none rounded-xl border border-gray-200 bg-white p-5 shadow-2xl max-md:w-full dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-title')
                    </p>
                    <button type="button" id="warehouse-add-modal-close" onclick="window.closeWarehouseAddModal && window.closeWarehouseAddModal()" class="text-2xl leading-none text-gray-500 hover:text-gray-800">&times;</button>
                </div>

                <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">
                    @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-hint')
                </p>

                <form id="warehouse-add-modal-form" method="post" action="{{ route('admin.seller.product-warehouse.attach') }}" class="grid gap-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.seller-panel.product-warehouse.bulk-add-seller-profit-label')
                        </label>
                        <input
                            id="warehouse-add-modal-commission"
                            type="number"
                            step="0.01"
                            min="{{ number_format((float) $commissionRule['min'], 2, '.', '') }}"
                            max="{{ number_format((float) $commissionRule['max'], 2, '.', '') }}"
                            name="commission_percent"
                            class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            value="{{ number_format((float) $currentCommissionPercent, 2, '.', '') }}"
                            @if ($commissionRule['readonly']) readonly @endif
                            required
                        />
                    </div>

                    <div id="warehouse-add-modal-indices"></div>

                    <button type="submit" class="seller-btn-primary">
                        @lang('admin::app.seller-panel.product-warehouse.bulk-add-confirm')
                    </button>
                </form>
            </div>
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>

@pushOnce('scripts')
    <script>
        (function exposeWarehouseModalHelpersOnly() {
            const existingProductIds = new Set(@json($sellerExistingProductIds ?? []));

            function flash(type, message) {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('add-flash', { type: type, message: message });
                } else {
                    alert(message);
                }
            }

            function closeModal() {
                const modal = document.getElementById('warehouse-add-modal');

                if (!modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            function openModalWithIds(productIds) {
                const modal = document.getElementById('warehouse-add-modal');
                const modalIndices = document.getElementById('warehouse-add-modal-indices');
                const modalCommission = document.getElementById('warehouse-add-modal-commission');
                const commissionInput = document.getElementById('warehouse-commission-input');

                if (!modal || !modalIndices || !modalCommission) {
                    return;
                }

                const normalizedIds = (productIds || [])
                    .map((id) => parseInt(id, 10))
                    .filter((id) => !Number.isNaN(id));

                if (!normalizedIds.length) {
                    flash('warning', @json(__('admin::app.components.datagrid.index.no-records-selected')));

                    return;
                }

                const alreadyAdded = normalizedIds.filter((id) => existingProductIds.has(id));
                const toAdd = normalizedIds.filter((id) => !existingProductIds.has(id));

                if (!toAdd.length) {
                    flash('warning', 'This product is already added to your catalog.');
                    closeModal();

                    return;
                }

                if (alreadyAdded.length) {
                    flash('warning', 'Some selected products are already added to your catalog. Showing remaining products only.');
                }

                modalIndices.innerHTML = toAdd
                    .map((id) => `<input type="hidden" name="indices[]" value="${id}">`)
                    .join('');

                modalCommission.value = (commissionInput && commissionInput.value) ? commissionInput.value : modalCommission.value;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
            }

            window.openWarehouseAddModalWithIds = function (ids) {
                openModalWithIds((ids || []).map((id) => parseInt(id, 10)).filter((id) => !Number.isNaN(id)));
            };

            window.openWarehouseAddModalFromSingle = function (id) {
                const n = parseInt(id || '0', 10);
                openModalWithIds(Number.isNaN(n) ? [] : [n]);
            };

            window.closeWarehouseAddModal = closeModal;
        })();
    </script>

    <script>
        (function initWarehousePage() {
            const boot = function () {
            function notify(type, message) {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('add-flash', { type: type, message: message });
                } else {
                    alert(message);
                }
            }

            const form = document.getElementById('warehouse-commission-form');
            const submitBtn = document.getElementById('warehouse-commission-save-btn');
            const commissionInput = document.getElementById('warehouse-commission-input');
            const selectAll = document.getElementById('warehouse-select-all');
            const rowCheckboxes = document.querySelectorAll('.warehouse-row-checkbox');
            const bulkOpenBtn = document.getElementById('warehouse-bulk-open-modal-btn');
            const singleOpenBtns = document.querySelectorAll('.warehouse-single-open-modal-btn');
            const modal = document.getElementById('warehouse-add-modal');
            const modalClose = document.getElementById('warehouse-add-modal-close');
            const modalForm = document.getElementById('warehouse-add-modal-form');
            const modalCommission = document.getElementById('warehouse-add-modal-commission');
            const modalIndices = document.getElementById('warehouse-add-modal-indices');

            function openAddModal(productIds) {
                if (!modal || !modalIndices || !modalCommission) {
                    return;
                }

                if (!productIds.length) {
                    if (window.emitter && typeof window.emitter.emit === 'function') {
                        window.emitter.emit('add-flash', {
                            type: 'warning',
                            message: @json(__('admin::app.components.datagrid.index.no-records-selected')),
                        });
                    }

                    return;
                }

                modalIndices.innerHTML = productIds
                    .map((id) => `<input type="hidden" name="indices[]" value="${id}">`)
                    .join('');

                modalCommission.value = (commissionInput && commissionInput.value) ? commissionInput.value : modalCommission.value;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeAddModal() {
                if (!modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            function syncCommissionTargets() {
                const value = (commissionInput && commissionInput.value) ? commissionInput.value : '';
                if (modalCommission) {
                    modalCommission.value = value;
                }
            }

            if (commissionInput) {
                commissionInput.addEventListener('input', syncCommissionTargets);
                commissionInput.addEventListener('change', syncCommissionTargets);
                syncCommissionTargets();
            }

            if (selectAll && rowCheckboxes.length) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(function (cb) {
                        cb.checked = selectAll.checked;
                    });
                });
            }

            if (bulkOpenBtn) {
                bulkOpenBtn.addEventListener('click', function () {
                    const ids = Array.from(rowCheckboxes)
                        .filter((cb) => cb.checked)
                        .map((cb) => parseInt(cb.value, 10))
                        .filter((id) => !Number.isNaN(id));

                    if (window.openWarehouseAddModalWithIds) {
                        window.openWarehouseAddModalWithIds(ids);
                    } else {
                        openAddModal(ids);
                    }
                });
            }

            singleOpenBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = parseInt(btn.dataset.productId || '0', 10);
                    if (window.openWarehouseAddModalFromSingle) {
                        window.openWarehouseAddModalFromSingle(id);
                    } else {
                        openAddModal(Number.isNaN(id) ? [] : [id]);
                    }
                });
            });

            if (modalClose) {
                modalClose.addEventListener('click', closeAddModal);
            }

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeAddModal();
                    }
                });
            }

            if (modalForm) {
                modalForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const submitButton = modalForm.querySelector('button[type="submit"]');

                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    try {
                        const response = await fetch(modalForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new FormData(modalForm),
                            credentials: 'same-origin',
                        });

                        const payload = await response.json().catch(function () { return {}; });

                        if (!response.ok) {
                            notify('error', payload.message || 'Unable to add product(s).');

                            return;
                        }

                        notify('success', payload.message || 'Product(s) added to your catalog successfully.');

                        closeAddModal();
                    } catch (error) {
                        notify('error', 'Unable to add product(s).');
                    } finally {
                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                    }
                });
            }

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
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
            } else {
                boot();
            }
        })();
    </script>
@endPushOnce
