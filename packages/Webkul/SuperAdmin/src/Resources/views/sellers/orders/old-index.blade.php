@php
    $sellerOrdersGridSrc = route('superadmin.sellers.orders.index');
    $sellerOrdersBulkUrl = route('superadmin.sellers.orders.bulk-status');
    $canSellerOrderBulk = bouncer()->hasPermission('sellers.orders');
@endphp

<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.sellers.orders.index.title')
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200">
            {{ session('warning') }}
        </div>
    @endif

    <div
        class="seller-orders-page mb-4 space-y-1.5"
        @if ($canSellerOrderBulk)
            id="seller-orders-page-root"
            data-grid-src="{{ $sellerOrdersGridSrc }}"
            data-bulk-url="{{ $sellerOrdersBulkUrl }}"
            data-currency-symbol="{{ core()->getBaseCurrency()->symbol }}"
            data-placeholder="@lang('superadmin::app.sellers.orders.index.bulk-status-select-placeholder')"
        @endif
    >
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.sellers.orders.index.title')
            </p>

            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2">
                @if ($canSellerOrderBulk)
                    <button
                        type="button"
                        id="seller-order-bulk-modal-open"
                        class="secondary-button cursor-not-allowed px-4 py-2 text-sm opacity-60"
                        disabled
                        aria-disabled="true"
                    >
                        <span id="seller-order-bulk-open-label">@lang('superadmin::app.sellers.orders.index.bulk-status-open-modal-btn')</span>
                    </button>
                @endif

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('superadmin.sellers.orders.dashboard') }}"
                        class="secondary-button px-4 py-2 text-sm"
                    >
                        Seller Pro Dashboard
                    </a>

                    <label for="seller-order-export-format" class="sr-only">
                        @lang('superadmin::app.export.download')
                    </label>

                    <select
                        id="seller-order-export-format"
                        class="rounded-md border border-gray-300 bg-white px-2 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                    >
                        <option value="xls">@lang('superadmin::app.export.xls')</option>
                        <option value="xlsx">@lang('superadmin::app.export.xlsx')</option>
                        <option value="csv">@lang('superadmin::app.export.csv')</option>
                    </select>

                    <button
                        type="button"
                        id="seller-order-export-btn"
                        class="secondary-button px-4 py-2 text-sm"
                    >
                        @lang('superadmin::app.export.export')
                    </button>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400">
            @lang('superadmin::app.sellers.orders.index.hint')
        </p>

        <p class="text-sm text-gray-600 dark:text-gray-400">
            @lang('superadmin::app.sellers.orders.index.bulk-select-hint')
        </p>

        @if ($canSellerOrderBulk)
            <div
                id="seller-order-inline-bulk"
                class="hidden items-center gap-2 rounded-md border border-blue-200 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-950"
            >
                <p
                    id="seller-order-inline-count"
                    class="text-sm font-semibold text-blue-700 dark:text-blue-200"
                ></p>

                <select
                    id="seller-order-inline-status"
                    class="rounded-md border border-gray-300 bg-white px-2 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                >
                    <option value="">@lang('superadmin::app.sellers.orders.index.bulk-status-select-placeholder')</option>
                    <option value="completed">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-completed')</option>
                    <option value="rejected">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-rejected')</option>
                    <option value="pending">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-pending')</option>
                </select>

                <p
                    id="seller-order-inline-error"
                    class="hidden rounded-md border border-red-200 bg-red-50 px-2 py-1 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200"
                ></p>
            </div>

            {{-- Popup: totals + status (filled when opened or when checkboxes change while open) --}}
            <div
                id="seller-order-bulk-modal"
                class="hidden fixed inset-0 z-[10050]"
                aria-modal="true"
                role="dialog"
                aria-labelledby="seller-order-bulk-modal-title"
            >
                <button
                    type="button"
                    id="seller-order-bulk-modal-backdrop"
                    class="absolute inset-0 h-full w-full cursor-default bg-gray-900/50 dark:bg-black/60"
                    aria-label="{{ __('superadmin::app.components.modal.confirm.disagree-btn') }}"
                ></button>

                <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-4 sm:p-6">
                    <div
                        class="pointer-events-auto relative max-h-[min(90vh,640px)] w-full max-w-lg overflow-y-auto rounded-lg bg-white shadow-2xl dark:border dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                            <div>
                                <p
                                    id="seller-order-bulk-modal-title"
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    @lang('superadmin::app.sellers.orders.index.bulk-status-modal-title')
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('superadmin::app.sellers.orders.index.bulk-status-modal-hint')
                                </p>
                            </div>
                            <button
                                type="button"
                                id="seller-order-bulk-modal-close"
                                class="icon-cancel-1 shrink-0 cursor-pointer rounded-md p-1 text-2xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
                            ></button>
                        </div>

                        <div class="space-y-4 border-b border-gray-200 px-4 py-4 dark:border-gray-800">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-950">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        @lang('superadmin::app.sellers.orders.index.bulk-status-selection-heading')
                                    </p>
                                    <p id="seller-order-modal-count" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"></p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-950">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        @lang('superadmin::app.sellers.orders.index.bulk-status-total-amount')
                                    </p>
                                    <p id="seller-order-modal-amount" class="mt-1 font-mono text-base font-semibold text-gray-900 dark:text-white"></p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 sm:col-span-2 dark:border-gray-700 dark:bg-gray-950">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        @lang('superadmin::app.sellers.orders.index.bulk-status-total-commission')
                                    </p>
                                    <p id="seller-order-modal-commission" class="mt-1 font-mono text-base font-semibold text-gray-900 dark:text-white"></p>
                                </div>
                            </div>

                            <div>
                                <label for="seller-order-modal-status" class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                                    @lang('superadmin::app.sellers.orders.index.datagrid.bulk-update-status')
                                </label>
                                <select
                                    id="seller-order-modal-status"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">@lang('superadmin::app.sellers.orders.index.bulk-status-select-placeholder')</option>
                                    <option value="completed">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-completed')</option>
                                    <option value="rejected">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-rejected')</option>
                                    <option value="pending">@lang('superadmin::app.sellers.orders.index.datagrid.bulk-option-pending')</option>
                                </select>
                            </div>

                            <p
                                id="seller-order-modal-error"
                                class="hidden rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200"
                                role="alert"
                            ></p>
                        </div>

                        <div class="flex flex-wrap justify-end gap-2 px-4 py-3">
                            <button type="button" id="seller-order-modal-cancel" class="transparent-button px-4 py-2 text-sm">
                                @lang('superadmin::app.components.modal.confirm.disagree-btn')
                            </button>
                            <button type="button" id="seller-order-modal-apply" class="primary-button px-4 py-2 text-sm">
                                @lang('superadmin::app.sellers.orders.index.bulk-status-apply')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-superadmin::datagrid.ssr
        :datagrid-payload="$datagridPayload"
        :src="$sellerOrdersGridSrc"
        :isMultiRow="true"
    />
</x-superadmin::layouts>

@if ($canSellerOrderBulk)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pageRoot = document.getElementById('seller-orders-page-root');

                if (! pageRoot) {
                    return;
                }

                const gridSrc = pageRoot.dataset.gridSrc || '';

                const bulkUrl = pageRoot.dataset.bulkUrl || '';

                const currencySymbol = pageRoot.dataset.currencySymbol || '';

                const placeholderMsg = pageRoot.dataset.placeholder || '';

                const countTemplate = @json(__('superadmin::app.sellers.orders.index.bulk-status-count', ['count' => '__n__']));

                const msgNoRecords = @json(__('superadmin::app.components.datagrid.index.no-records-selected'));

                const openBtn = document.getElementById('seller-order-bulk-modal-open');

                const openLabel = document.getElementById('seller-order-bulk-open-label');

                const openLabelBase = openLabel ? openLabel.textContent : '';

                const modal = document.getElementById('seller-order-bulk-modal');

                const modalBackdrop = document.getElementById('seller-order-bulk-modal-backdrop');

                const modalClose = document.getElementById('seller-order-bulk-modal-close');

                const modalCancel = document.getElementById('seller-order-modal-cancel');

                const modalApply = document.getElementById('seller-order-modal-apply');

                const countEl = document.getElementById('seller-order-modal-count');

                const amtEl = document.getElementById('seller-order-modal-amount');

                const commEl = document.getElementById('seller-order-modal-commission');

                const errEl = document.getElementById('seller-order-modal-error');

                const selectEl = document.getElementById('seller-order-modal-status');
                const inlineWrap = document.getElementById('seller-order-inline-bulk');
                const inlineCount = document.getElementById('seller-order-inline-count');
                const inlineStatus = document.getElementById('seller-order-inline-status');
                const inlineError = document.getElementById('seller-order-inline-error');

                let lastIndices = [];

                function selectedIndicesFromDom(scopeEl) {
                    const scope = scopeEl || getScope() || document;
                    const header = scope.querySelector('input[data-dg-ssr-select-all]');
                    const rowCheckboxes = Array.from(scope.querySelectorAll('input[data-dg-ssr-mass]'));

                    if (header && header.checked && rowCheckboxes.length) {
                        return rowCheckboxes
                            .map(function (input) { return coerceMassId(input.value); })
                            .filter(function (id) { return id !== undefined && id !== null && id !== ''; });
                    }

                    return rowCheckboxes
                        .filter(function (input) { return input.checked; })
                        .map(function (input) { return coerceMassId(input.value); })
                        .filter(function (id) { return id !== undefined && id !== null && id !== ''; });
                }

                function getScope() {
                    const content = pageRoot.closest('.w-full.overflow-x-hidden')
                        || document.querySelector('#superadmin-app-layout .w-full.overflow-x-hidden');

                    return content
                        || pageRoot.closest('#superadmin-app-layout')
                        || document.getElementById('superadmin-app-layout')
                        || document.getElementById('app')
                        || pageRoot.parentElement;
                }

                function readBootstrapPayload() {
                    const scopeEl = getScope();

                    let jsonEl = null;

                    const candidates = document.querySelectorAll('script[type="application/json"][id^="sdgb_"]');

                    for (let i = 0; i < candidates.length; i++) {
                        const s = candidates[i];

                        const pos = pageRoot.compareDocumentPosition(s);

                        if (pos & Node.DOCUMENT_POSITION_FOLLOWING) {
                            jsonEl = s;

                            break;
                        }
                    }

                    if (! jsonEl && scopeEl) {
                        jsonEl = scopeEl.querySelector('script[type="application/json"][id^="sdgb_"]');
                    }

                    if (! jsonEl && document.getElementById('app')) {
                        jsonEl = document.getElementById('app').querySelector('script[type="application/json"][id^="sdgb_"]');
                    }

                    if (! jsonEl || ! jsonEl.textContent) {
                        return { records: [], meta: {} };
                    }

                    try {
                        const payload = JSON.parse(jsonEl.textContent.trim());

                        return {
                            records: Array.isArray(payload.records) ? payload.records : [],

                            meta: payload.meta && typeof payload.meta === 'object' ? payload.meta : {},
                        };
                    } catch (e) {
                        return { records: [], meta: {} };
                    }
                }

                function sameListingSrc(a, b) {
                    if (! a || ! b) {
                        return false;
                    }

                    if (a === b) {
                        return true;
                    }

                    try {
                        const ua = new URL(String(a), window.location.origin);

                        const ub = new URL(String(b), window.location.origin);

                        const pa = ua.pathname.replace(/\/$/, '');

                        const pb = ub.pathname.replace(/\/$/, '');

                        return pa === pb && ua.search === ub.search;
                    } catch (err) {
                        return false;
                    }
                }

                function coerceMassId(value) {
                    if (value === undefined || value === null || value === '') {
                        return value;
                    }

                    const numeric = Number(value);

                    if (Number.isFinite(numeric) && String(numeric) === String(value).trim()) {
                        return numeric;
                    }

                    return value;
                }

                function xsrfToken() {
                    const m = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);

                    return m ? decodeURIComponent(m[1]) : '';
                }

                function parseNum(v) {
                    if (typeof v === 'number' && ! Number.isNaN(v)) {
                        return v;
                    }

                    const n = parseFloat(String(v ?? '').replace(/[^\d.-]/g, ''));

                    return Number.isNaN(n) ? 0 : n;
                }

                function fmtMoney(n) {
                    const rounded = Math.round(n * 100) / 100;

                    return currencySymbol ? currencySymbol + rounded.toFixed(2) : rounded.toFixed(2);
                }

                function flashModalError(message) {
                    errEl.textContent = message;

                    errEl.classList.remove('hidden');
                }

                function clearModalError() {
                    errEl.textContent = '';

                    errEl.classList.add('hidden');
                }

                function clearInlineError() {
                    if (! inlineError) {
                        return;
                    }

                    inlineError.textContent = '';
                    inlineError.classList.add('hidden');
                }

                function showInlineError(message) {
                    if (! inlineError) {
                        return;
                    }

                    inlineError.textContent = message;
                    inlineError.classList.remove('hidden');
                }

                function fetchErrorMessage(data, response) {
                    if (data && typeof data.message === 'string' && data.message) {
                        return data.message;
                    }

                    if (data && data.errors && typeof data.errors === 'object') {
                        for (const msgs of Object.values(data.errors)) {
                            if (Array.isArray(msgs) && msgs.length) {
                                return msgs[0];
                            }

                            if (typeof msgs === 'string') {
                                return msgs;
                            }
                        }
                    }

                    return (response && response.statusText) ? response.statusText : 'Something went wrong.';
                }

                /** Disabled + not-allowed when nothing selected; enabled when sync sees ≥1 row (proper UX). */
                function setOpenButtonState(count) {
                    if (! openBtn) {
                        if (inlineWrap) {
                            if (count > 0) {
                                inlineWrap.classList.remove('hidden');
                                inlineWrap.classList.add('flex');
                            } else {
                                inlineWrap.classList.add('hidden');
                                inlineWrap.classList.remove('flex');
                            }
                        }

                        if (inlineCount) {
                            inlineCount.textContent = countTemplate.replace('__n__', String(count));
                        }

                        return;
                    }

                    if (count > 0) {
                        openBtn.disabled = false;

                        openBtn.removeAttribute('aria-disabled');

                        openBtn.classList.remove('opacity-60', 'cursor-not-allowed');

                        openBtn.classList.add('cursor-pointer');

                        if (openLabel) {
                            openLabel.textContent = openLabelBase + ' (' + count + ')';
                        }

                        if (inlineWrap) {
                            inlineWrap.classList.remove('hidden');
                            inlineWrap.classList.add('flex');
                        }
                    } else {
                        openBtn.disabled = true;

                        openBtn.setAttribute('aria-disabled', 'true');

                        openBtn.classList.add('opacity-60', 'cursor-not-allowed');

                        openBtn.classList.remove('cursor-pointer');

                        if (openLabel) {
                            openLabel.textContent = openLabelBase;
                        }

                        if (inlineWrap) {
                            inlineWrap.classList.add('hidden');
                            inlineWrap.classList.remove('flex');
                        }
                    }

                    if (inlineCount) {
                        inlineCount.textContent = countTemplate.replace('__n__', String(count));
                    }
                }

                function fillModalSummary(indices, records, primaryCol) {
                    lastIndices = indices.slice();

                    const idSet = new Set(indices.map(function (v) { return String(v); }));

                    let totalAmt = 0;

                    let totalComm = 0;

                    for (let i = 0; i < records.length; i++) {
                        const row = records[i];

                        const rid = row ? row[primaryCol] : undefined;

                        if (rid === undefined || rid === null || ! idSet.has(String(rid))) {
                            continue;
                        }

                        totalAmt += parseNum(row.base_grand_total);

                        totalComm += parseNum(row.seller_commission_preview);
                    }

                    countEl.textContent = countTemplate.replace('__n__', String(indices.length));

                    amtEl.textContent = fmtMoney(totalAmt);

                    commEl.textContent = fmtMoney(totalComm);

                    setOpenButtonState(indices.length);
                }

                function syncFromDom() {
                    const scopeEl = getScope() || document.getElementById('app');

                    if (! scopeEl) {
                        setOpenButtonState(0);

                        return;
                    }

                    const payload = readBootstrapPayload();

                    const records = payload.records;

                    const meta = payload.meta;

                    const primaryCol = meta.primary_column || 'id';

                    let indices = selectedIndicesFromDom(scopeEl);

                    // Fallback to payload-derived ids if header checked but DOM rows are still repainting.
                    if (! indices.length) {
                        const header = scopeEl.querySelector('input[data-dg-ssr-select-all]');

                        if (header && header.checked && records.length) {
                            indices = records.map(function (r) { return r[primaryCol]; });
                        }
                    }

                    fillModalSummary(indices, records, primaryCol);

                    if (modal && ! modal.classList.contains('hidden')) {
                        clearModalError();
                    }
                }

                let massSyncTimer = null;

                function scheduleMassSync() {
                    window.clearTimeout(massSyncTimer);

                    massSyncTimer = window.setTimeout(function () {
                        syncFromDom();

                        window.requestAnimationFrame(syncFromDom);

                        window.setTimeout(syncFromDom, 80);

                        window.setTimeout(syncFromDom, 200);
                    }, 0);
                }

                function openModal() {
                    if (openBtn && openBtn.disabled) {
                        return;
                    }

                    syncFromDom();

                    window.requestAnimationFrame(function () {
                        syncFromDom();

                        window.setTimeout(function () {
                            syncFromDom();

                            if (! lastIndices.length) {
                                if (window.emitter && typeof window.emitter.emit === 'function') {
                                    window.emitter.emit('add-flash', { type: 'warning', message: msgNoRecords });
                                } else {
                                    window.alert(msgNoRecords);
                                }

                                return;
                            }

                            clearModalError();

                            selectEl.value = '';

                            modal.classList.remove('hidden');

                            document.body.style.overflow = 'hidden';

                            syncFromDom();
                        }, 0);
                    });
                }

                function isSellerOrdersListingPayload(payload) {
                    if (! payload || typeof payload.src !== 'string') {
                        return false;
                    }

                    if (sameListingSrc(payload.src, gridSrc)) {
                        return true;
                    }

                    try {
                        const path = new URL(payload.src, window.location.origin).pathname.replace(/\/$/, '');

                        return path.includes('sellers/orders');
                    } catch (err) {
                        return false;
                    }
                }

                function onMassCheckboxInteraction(ev) {
                    const t = ev.target;

                    if (! t || typeof t.closest !== 'function') {
                        return;
                    }

                    const scopeEl = getScope() || document.getElementById('app');

                    const slot = t.closest('.datagrid-ssr-slot');

                    const insideScope = scopeEl && scopeEl.contains(t);

                    if (! insideScope && ! slot) {
                        return;
                    }

                    if (ev.type === 'change' || ev.type === 'input') {
                        if (typeof t.matches === 'function' && t.matches('input[data-dg-ssr-mass], input[data-dg-ssr-select-all]')) {
                            scheduleMassSync();
                        }

                        return;
                    }

                    if (ev.type !== 'click') {
                        return;
                    }

                    const lab = t.closest('label');

                    if (lab && lab.querySelector('input[data-dg-ssr-mass], input[data-dg-ssr-select-all]')) {
                        scheduleMassSync();

                        return;
                    }

                    if (t.closest('.icon-uncheckbox')) {
                        scheduleMassSync();
                    }
                }

                function onSsrMassSelectionCustomEvent(ev) {
                    const d = ev.detail || {};

                    if (! isSellerOrdersListingPayload({ src: d.src })) {
                        return;
                    }

                    const primaryCol = (d.meta && d.meta.primary_column) ? d.meta.primary_column : 'id';

                    fillModalSummary(Array.isArray(d.indices) ? d.indices : [], Array.isArray(d.records) ? d.records : [], primaryCol);

                    if (modal && ! modal.classList.contains('hidden')) {
                        clearModalError();
                    }
                }

                function closeModal() {
                    modal.classList.add('hidden');

                    document.body.style.overflow = '';
                }

                document.addEventListener('change', onMassCheckboxInteraction, true);

                document.addEventListener('input', onMassCheckboxInteraction, true);

                document.addEventListener('click', onMassCheckboxInteraction, true);

                document.addEventListener('superadmin-ssr-mass-selection', onSsrMassSelectionCustomEvent);

                let sellerOrdersPollId = window.setInterval(function () {
                    if (! document.getElementById('seller-orders-page-root')) {
                        window.clearInterval(sellerOrdersPollId);

                        sellerOrdersPollId = null;

                        return;
                    }

                    syncFromDom();
                }, 350);

                openBtn?.addEventListener('click', openModal);

                modalBackdrop?.addEventListener('click', closeModal);

                modalClose?.addEventListener('click', closeModal);

                modalCancel?.addEventListener('click', closeModal);

                function emitFlash(type, message) {
                    if (window.emitter && typeof window.emitter.emit === 'function') {
                        window.emitter.emit('add-flash', { type: type, message: message });
                    }
                }

                modalApply?.addEventListener('click', async function () {
                    clearModalError();

                    syncFromDom();

                    const value = String(selectEl.value || '').trim();

                    if (! value) {
                        flashModalError(placeholderMsg || 'Choose a status.');

                        return;
                    }

                    const indices = selectedIndicesFromDom(getScope() || document);

                    if (! indices.length) {
                        flashModalError(msgNoRecords);

                        return;
                    }

                    try {
                        const response = await fetch(bulkUrl, {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                Accept: 'application/json',

                                'X-Requested-With': 'XMLHttpRequest',

                                'X-XSRF-TOKEN': xsrfToken(),
                            },

                            credentials: 'same-origin',

                            body: JSON.stringify({ indices: indices, value: value }),
                        });

                        const data = await response.json().catch(function () { return {}; });

                        if (! response.ok) {
                            const msg = fetchErrorMessage(data, response);

                            emitFlash('error', msg);

                            flashModalError(msg);

                            return;
                        }

                        if (data && data.message) {
                            emitFlash('success', data.message);
                        }

                        closeModal();

                        window.setTimeout(function () { window.location.reload(); }, 250);
                    } catch (e) {
                        const msg = (e && e.message) ? e.message : 'Something went wrong.';

                        emitFlash('error', msg);

                        flashModalError(msg);
                    }
                });

                inlineStatus?.addEventListener('change', async function () {
                    clearInlineError();
                    clearModalError();
                    syncFromDom();

                    const value = String((inlineStatus && inlineStatus.value) || '').trim();

                    if (! value) {
                        return;
                    }

                    const indices = selectedIndicesFromDom(getScope() || document);

                    if (! indices.length) {
                        showInlineError(msgNoRecords);
                        return;
                    }

                    try {
                        const response = await fetch(bulkUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-XSRF-TOKEN': xsrfToken(),
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ indices: indices, value: value }),
                        });

                        const data = await response.json().catch(function () { return {}; });

                        if (! response.ok) {
                            const msg = fetchErrorMessage(data, response);
                            emitFlash('error', msg);
                            showInlineError(msg);
                            return;
                        }

                        if (data && data.message) {
                            emitFlash('success', data.message);
                        }

                        inlineStatus.value = '';

                        window.setTimeout(function () { window.location.reload(); }, 200);
                    } catch (e) {
                        const msg = (e && e.message) ? e.message : 'Something went wrong.';
                        emitFlash('error', msg);
                        showInlineError(msg);
                    }
                });

                function syncFromDatagridEmitter(payload) {
                    if (! isSellerOrdersListingPayload(payload)) {
                        return;
                    }

                    const primaryCol = (payload.available && payload.available.meta && payload.available.meta.primary_column)
                        ? payload.available.meta.primary_column
                        : 'id';

                    const indices = (payload.applied && payload.applied.massActions && payload.applied.massActions.indices)
                        ? payload.applied.massActions.indices
                        : [];

                    const records = (payload.available && payload.available.records) ? payload.available.records : [];

                    fillModalSummary(indices, records, primaryCol);
                }

                let sellerOrdersChangeDgAttached = false;

                function attachChangeDatagridOnce() {
                    if (sellerOrdersChangeDgAttached) {
                        return;
                    }

                    const em = window.emitter;

                    if (em && typeof em.on === 'function') {
                        em.on('change-datagrid', syncFromDatagridEmitter);

                        sellerOrdersChangeDgAttached = true;
                    }
                }

                attachChangeDatagridOnce();

                window.setTimeout(attachChangeDatagridOnce, 0);

                window.setTimeout(attachChangeDatagridOnce, 300);

                const layoutObserver = new MutationObserver(syncFromDom);

                const observeRoot = document.getElementById('superadmin-app-layout') || document.getElementById('app') || document.body;

                layoutObserver.observe(observeRoot, { childList: true, subtree: true });

                window.setTimeout(syncFromDom, 50);

                window.setTimeout(syncFromDom, 300);

                window.setTimeout(syncFromDom, 1200);

                window.setTimeout(function () { layoutObserver.disconnect(); }, 10000);

                const exportBtn = document.getElementById('seller-order-export-btn');
                const exportFormat = document.getElementById('seller-order-export-format');

                exportBtn?.addEventListener('click', function () {
                    const url = new URL(window.location.href);
                    url.searchParams.set('export', '1');
                    url.searchParams.set('format', (exportFormat && exportFormat.value) ? exportFormat.value : 'xls');
                    window.location.assign(url.toString());
                });
            });
        </script>
    @endpush
@endif
