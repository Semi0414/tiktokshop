<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.shop-order')
    </x-slot>

    <x-admin::seller.panel active="shop_order" :breadcrumb="[__('admin::app.seller-panel.tabs.shop-order')]">
        @php
            $scope = request('seller_order_scope', 'all');
            $preserve = request()->only(['seller_increment_id', 'seller_payment_method', 'seller_date_from', 'seller_date_to', 'seller_logistics_status']);
            $chipUrl = function ($s) use ($preserve) {
                return route('admin.sales.orders.index', array_merge($preserve, ['seller_order_scope' => $s]));
            };
            $chipClass = 'rounded-md border px-3 py-1.5 text-sm font-medium transition';
            $chipActive = 'seller-pill seller-pill--blue border-blue-500';
            $chipInactive = 'border-gray-200 bg-white text-gray-700 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200';
        @endphp

        {{-- Status chips: seller_order_scope on DataGrid query --}}
        <div class="seller-filter-card mb-4 flex flex-wrap items-center gap-2">
            <a href="{{ $chipUrl('all') }}" class="{{ $chipClass }} {{ $scope === 'all' ? $chipActive : $chipInactive }}">
                @lang('admin::app.seller-panel.orders.all')
            </a>
            <a href="{{ $chipUrl('pending') }}" class="{{ $chipClass }} {{ $scope === 'pending' ? $chipActive : $chipInactive }}">
                @lang('admin::app.seller-panel.orders.pending')
            </a>
            <a href="{{ $chipUrl('purchased') }}" class="{{ $chipClass }} {{ $scope === 'purchased' ? $chipActive : $chipInactive }}">
                @lang('admin::app.seller-panel.orders.purchased')
            </a>
        </div>

        {{-- Filter: GET params merged into datagrid AJAX --}}
        <form method="get" action="{{ route('admin.sales.orders.index') }}" class="seller-filter-card mb-4" id="seller-order-filters">
            <input type="hidden" name="seller_order_scope" value="{{ $scope }}" />

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.order-no')</label>
                    <input
                        type="text"
                        name="seller_increment_id"
                        value="{{ request('seller_increment_id') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.payment-status')</label>
                    <select name="seller_payment_method" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        @foreach ($paymentMethodOptions ?? [['value' => '', 'label' => __('admin::app.seller-panel.filters.all')]] as $opt)
                            <option value="{{ $opt['value'] }}" @selected(request('seller_payment_method', '') === (string) ($opt['value'] ?? ''))>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.logistics-status')</label>
                    <select name="seller_logistics_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected(! request('seller_logistics_status') || request('seller_logistics_status') === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PENDING }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_PENDING)>@lang('admin::app.sales.orders.index.datagrid.pending')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT)>@lang('admin::app.sales.orders.index.datagrid.pending-payment')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_PROCESSING }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_PROCESSING)>@lang('admin::app.sales.orders.index.datagrid.processing')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_COMPLETED }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_COMPLETED)>@lang('admin::app.sales.orders.index.datagrid.completed')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_CLOSED }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_CLOSED)>@lang('admin::app.sales.orders.index.datagrid.closed')</option>
                        <option value="{{ \Webkul\Sales\Models\Order::STATUS_CANCELED }}" @selected(request('seller_logistics_status') === \Webkul\Sales\Models\Order::STATUS_CANCELED)>@lang('admin::app.sales.orders.index.datagrid.canceled')</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.order-time')</label>
                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row">
                        <input type="date" name="seller_date_from" value="{{ request('seller_date_from') }}" class="min-w-0 w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="seller_date_to" value="{{ request('seller_date_to') }}" class="min-w-0 w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.sales.orders.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>
        <!-- <div class="mb-3 flex justify-end max-sm:justify-stretch">
            <button
                type="button"
                class="seller-btn-primary w-full text-xs max-sm:justify-center sm:w-auto"
                onclick="window.sellerBulkPurchaseFromHeader && window.sellerBulkPurchaseFromHeader()"
            >
                @lang('admin::app.seller-panel.orders.bulk-purchase')
            </button>
        </div>  -->

        <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="min-w-0 py-1 text-lg font-bold text-gray-800 dark:text-white sm:py-3 sm:text-xl">
                @lang('admin::app.sales.orders.index.title')
            </p>

            <!-- <div class="flex shrink-0 items-center gap-x-2.5">
                <x-admin::datagrid.export src="{{ route('admin.sales.orders.index') }}" />
            </div> -->
        </div>

        <div id="seller-orders-summary-cards" class="mb-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="seller-filter-card">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                <p id="seller-summary-orders" class="mt-1 text-lg font-bold text-gray-800 dark:text-white">0</p>
            </div>
            <div class="seller-filter-card">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Price</p>
                <p id="seller-summary-price" class="mt-1 text-lg font-bold text-blue-600 dark:text-blue-400">0.00</p>
            </div>
            <div class="seller-filter-card">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Commission</p>
                <p id="seller-summary-commission" class="mt-1 text-lg font-bold text-orange-600 dark:text-orange-400">0.00</p>
            </div>
            <div class="seller-filter-card">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Payable</p>
                <p id="seller-summary-payable" class="mt-1 text-lg font-bold text-emerald-600 dark:text-emerald-400">0.00</p>
            </div>
        </div>

        <div id="seller-shop-order-grid" class="seller-filter-card max-w-full overflow-hidden p-0">
            <div
                id="seller-orders-bulk-toolbar"
                class="hidden flex-col gap-3 border-b border-gray-200 bg-gray-50 px-3 py-2 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="text-xs text-gray-600 dark:text-gray-300">
                    <span id="seller-orders-selected-count">0</span> selected
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="rounded-md bg-blue-100 px-2 py-1 font-semibold text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                        Principal:
                        <span id="seller-selected-principal" class="font-bold text-blue-800 dark:text-blue-200">0.00</span>
                    </span>
                    <span class="rounded-md bg-orange-100 px-2 py-1 font-semibold text-orange-700 dark:bg-orange-950/50 dark:text-orange-300">
                        Commission:
                        <span id="seller-selected-commission" class="font-bold text-orange-800 dark:text-orange-200">0.00</span>
                    </span>
                    <span class="rounded-md bg-emerald-100 px-2 py-1 font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                        Total:
                        <span id="seller-selected-total" class="font-bold text-emerald-800 dark:text-emerald-200">0.00</span>
                    </span>
                </div>

                <div class="flex min-w-0 flex-wrap items-center gap-2">
                    <select
                        id="seller-orders-bulk-action"
                        class="min-w-0 max-w-full flex-1 rounded-md border border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-700 sm:max-w-xs sm:flex-none dark:border-gray-700 dark:bg-gray-950 dark:text-gray-200"
                    >
                        <option value="">Select bulk action</option>
                        <option value="bulk_make_order">Bulk Make Order</option>
                    </select>

                    <button
                        id="seller-orders-bulk-apply"
                        type="button"
                        class="seller-btn-primary text-xs"
                    >
                        Apply
                    </button>
                </div>
            </div>

            <div class="-mx-px overflow-x-auto overscroll-x-contain sm:mx-0">
                <table class="min-w-[720px] w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                <input id="seller-orders-select-all" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Order ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.seller.shop-order.col-age-hours')</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Grand Total</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Pay Via</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Email</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Location</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Commission %</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Commission Amount</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Items</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Action</th>
                        </tr>
                    </thead>

                    <tbody
                        id="seller-orders-table-body"
                        class="divide-y divide-gray-100 dark:divide-gray-800"
                    >
                        <tr>
                            <td colspan="14" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="seller-orders-table-meta" class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400"></div>
        </div>
    </x-admin::seller.panel>

    {{-- Dedicated password modal for shop orders only (does not depend on global script stack / shared gate) --}}
    <div
        id="seller-shop-order-pw-root"
        class="fixed inset-0 z-[99990] hidden items-center justify-center bg-black/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="seller-shop-order-pw-title"
    >
        <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
            <h2 id="seller-shop-order-pw-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                @lang('admin::app.account.verify-password.title')
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @lang('admin::app.account.verify-password.description')
            </p>
            <label for="seller-shop-order-pw-input" class="mt-4 block text-xs font-medium text-gray-700 dark:text-gray-300">
                @lang('admin::app.account.verify-password.placeholder')
            </label>
            <input
                type="password"
                id="seller-shop-order-pw-input"
                autocomplete="current-password"
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-950 dark:text-white"
            />
            <p id="seller-shop-order-pw-err" class="mt-2 hidden text-sm text-red-600 dark:text-red-400"></p>
            <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="seller-shop-order-pw-btn-cancel"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    @lang('admin::app.account.verify-password.cancel')
                </button>
                <button
                    type="button"
                    id="seller-shop-order-pw-btn-ok"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    @lang('admin::app.account.verify-password.submit-password')
                </button>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            (function registerSellerShopOrderPasswordForce() {
                const sellerMakeOrderUrlTemplate = @json(route('admin.seller.shop-order.make-order', ['order' => 0]));
                const sellerBulkMakeOrderUrl = @json(route('admin.seller.shop-order.bulk-make-order'));

                function getCsrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                }

                const state = {
                    kind: 'single',
                    orderId: null,
                    bulkIndices: null,
                    busy: false,
                };

                function el(id) {
                    return document.getElementById(id);
                }

                async function sellerFlash(type, message) {
                    if (typeof window.emitter !== 'undefined' && window.emitter.emit) {
                        window.emitter.emit('add-flash', { type: type, message: message });
                    } else {
                        alert(message);
                    }
                }

                function buildSellerMakeOrderUrl(orderId) {
                    const id = String(parseInt(orderId, 10) || 0);
                    const tpl = sellerMakeOrderUrlTemplate;
                    const m = tpl.match(/^(.*\/shop-order\/)(\d+)(\/make-order.*)$/i);
                    if (m) {
                        return m[1] + id + m[3];
                    }
                    return tpl.replace(/\/([0-9]+)\/make-order(\?|#|$)/, '/' + id + '/make-order$2');
                }

                function showPwError(text) {
                    const err = el('seller-shop-order-pw-err');
                    if (!err) {
                        return;
                    }
                    err.textContent = text || '';
                    if (text) {
                        err.classList.remove('hidden');
                    } else {
                        err.classList.add('hidden');
                    }
                }

                function openPwModal(kind, orderId, bulkIndices) {
                    const root = el('seller-shop-order-pw-root');
                    const input = el('seller-shop-order-pw-input');
                    if (!root || !input) {
                        sellerFlash('error', 'Password dialog failed to load. Hard-refresh the page (Ctrl+F5).');
                        return;
                    }
                    state.kind = kind;
                    state.orderId = orderId;
                    state.bulkIndices = bulkIndices;
                    showPwError('');
                    input.value = '';
                    root.classList.remove('hidden');
                    root.classList.add('flex');
                    setTimeout(function () {
                        input.focus();
                    }, 40);
                }

                function closePwModal() {
                    const root = el('seller-shop-order-pw-root');
                    const input = el('seller-shop-order-pw-input');
                    const okBtn = el('seller-shop-order-pw-btn-ok');
                    if (root) {
                        root.classList.add('hidden');
                        root.classList.remove('flex');
                    }
                    if (input) {
                        input.value = '';
                    }
                    if (okBtn) {
                        okBtn.disabled = false;
                    }
                    showPwError('');
                    state.kind = 'single';
                    state.orderId = null;
                    state.bulkIndices = null;
                    state.busy = false;
                }

                async function postMakeOrderWithPassword(orderId, password) {
                    const url = buildSellerMakeOrderUrl(orderId);
                    const token = getCsrfToken();
                    const body = JSON.stringify({
                        password: password,
                        _token: token,
                    });
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            Accept: 'application/json, text/plain, */*',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: body,
                    });
                    const data = await res.json().catch(function () {
                        return {};
                    });
                    const msg = data.message || (res.ok ? 'OK' : 'HTTP ' + res.status);
                    sellerFlash(res.ok ? 'success' : 'error', msg);
                    if (res.ok) {
                        window.location.reload();
                    }
                }

                async function postBulkWithPassword(indices, password) {
                    const token = getCsrfToken();
                    const body = JSON.stringify({
                        password: password,
                        indices: indices,
                        _token: token,
                    });
                    const res = await fetch(sellerBulkMakeOrderUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            Accept: 'application/json, text/plain, */*',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: body,
                    });
                    const data = await res.json().catch(function () {
                        return {};
                    });
                    const msg = data.message || (res.ok ? 'OK' : 'HTTP ' + res.status);
                    sellerFlash(res.ok ? 'success' : 'error', msg);
                    if (res.ok) {
                        window.location.reload();
                    }
                }

                async function onPwConfirm() {
                    if (state.busy) {
                        return;
                    }
                    const input = el('seller-shop-order-pw-input');
                    const okBtn = el('seller-shop-order-pw-btn-ok');
                    const pwd = (input && input.value ? input.value : '').trim();
                    if (!pwd) {
                        showPwError(@json(__('admin::app.account.verify-password.required')));
                        return;
                    }
                    state.busy = true;
                    if (okBtn) {
                        okBtn.disabled = true;
                    }
                    showPwError('');
                    try {
                        if (state.kind === 'bulk' && Array.isArray(state.bulkIndices) && state.bulkIndices.length) {
                            await postBulkWithPassword(state.bulkIndices, pwd);
                        } else if (state.orderId != null && !Number.isNaN(Number(state.orderId))) {
                            await postMakeOrderWithPassword(state.orderId, pwd);
                        } else {
                            sellerFlash('error', 'Missing order. Close this dialog and click Make Order again.');
                        }
                    } catch (e) {
                        sellerFlash('error', (e && e.message) ? e.message : 'Request failed.');
                    } finally {
                        state.busy = false;
                        if (okBtn) {
                            okBtn.disabled = false;
                        }
                    }
                }

                function wireShopOrderPwModal() {
                    const root = el('seller-shop-order-pw-root');
                    const ok = el('seller-shop-order-pw-btn-ok');
                    const cancel = el('seller-shop-order-pw-btn-cancel');
                    const input = el('seller-shop-order-pw-input');
                    if (!root || !ok || !cancel || !input) {
                        return;
                    }
                    if (ok.dataset.sellerShopOrderPwWired === '1') {
                        return;
                    }
                    ok.dataset.sellerShopOrderPwWired = '1';
                    ok.addEventListener('click', function () {
                        onPwConfirm();
                    });
                    cancel.addEventListener('click', function () {
                        closePwModal();
                    });
                    root.addEventListener('click', function (e) {
                        if (e.target === root) {
                            closePwModal();
                        }
                    });
                    input.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            onPwConfirm();
                        }
                        if (e.key === 'Escape') {
                            closePwModal();
                        }
                    });
                }

                wireShopOrderPwModal();

                window.sellerSubmitMakeOrder = function (orderId) {
                    wireShopOrderPwModal();
                    openPwModal('single', orderId, null);
                };

                window.sellerBulkPurchaseFromHeader = function () {
                    wireShopOrderPwModal();
                    const grid = document.getElementById('seller-shop-order-grid');
                    if (grid) {
                        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    const indices = typeof window.getSellerSelectedOrderIds === 'function'
                        ? window.getSellerSelectedOrderIds()
                        : [];
                    if (!indices.length) {
                        sellerFlash('warning', @json(__('admin::app.seller-panel.orders.bulk-purchase-no-selection')));
                        return;
                    }
                    openPwModal('bulk', null, indices.slice());
                };
            })();

            (function attachSellerOrdersTable() {
                const tableBody = document.getElementById('seller-orders-table-body');
                const tableMeta = document.getElementById('seller-orders-table-meta');
                const selectAllBox = document.getElementById('seller-orders-select-all');
                const bulkToolbar = document.getElementById('seller-orders-bulk-toolbar');
                const selectedCount = document.getElementById('seller-orders-selected-count');
                const selectedPrincipalEl = document.getElementById('seller-selected-principal');
                const selectedCommissionEl = document.getElementById('seller-selected-commission');
                const selectedTotalEl = document.getElementById('seller-selected-total');
                const bulkActionSelect = document.getElementById('seller-orders-bulk-action');
                const bulkApplyButton = document.getElementById('seller-orders-bulk-apply');
                const summaryOrdersEl = document.getElementById('seller-summary-orders');
                const summaryPriceEl = document.getElementById('seller-summary-price');
                const summaryCommissionEl = document.getElementById('seller-summary-commission');
                const summaryPayableEl = document.getElementById('seller-summary-payable');
                const selectedOrderIds = new Set();
                let latestRecords = [];

                if (
                    ! tableBody
                    || ! tableMeta
                    || ! selectAllBox
                    || ! bulkToolbar
                    || ! selectedCount
                    || ! selectedPrincipalEl
                    || ! selectedCommissionEl
                    || ! selectedTotalEl
                    || ! bulkActionSelect
                    || ! bulkApplyButton
                    || ! summaryOrdersEl
                    || ! summaryPriceEl
                    || ! summaryCommissionEl
                    || ! summaryPayableEl
                ) {
                    return;
                }

                function toNumber(value) {
                    const n = parseFloat(value ?? 0);
                    return Number.isFinite(n) ? n : 0;
                }

                function formatMoney(value) {
                    return toNumber(value).toFixed(2);
                }

                function updateBulkToolbar() {
                    const count = selectedOrderIds.size;
                    selectedCount.textContent = String(count);

                    const selectedRecords = latestRecords.filter((record) => selectedOrderIds.has(Number(record.id)));
                    const selectedPrincipal = selectedRecords.reduce((sum, record) => sum + toNumber(record.base_grand_total), 0);
                    const selectedCommission = selectedRecords.reduce((sum, record) => sum + toNumber(record.seller_commission_expected), 0);
                    const selectedTotal = selectedPrincipal + selectedCommission;

                    selectedPrincipalEl.textContent = formatMoney(selectedPrincipal);
                    selectedCommissionEl.textContent = formatMoney(selectedCommission);
                    selectedTotalEl.textContent = formatMoney(selectedTotal);

                    if (count > 0) {
                        bulkToolbar.classList.remove('hidden');
                        bulkToolbar.classList.add('flex');
                    } else {
                        bulkToolbar.classList.add('hidden');
                        bulkToolbar.classList.remove('flex');
                    }
                }

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                }

                async function loadTableData() {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="14" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                        </tr>
                    `;

                    try {
                        const params = new URLSearchParams(window.location.search);

                        if (! params.has('pagination[page]')) {
                            params.set('pagination[page]', '1');
                        }

                        if (! params.has('pagination[per_page]')) {
                            params.set('pagination[per_page]', '10');
                        }

                        const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin',
                        });

                        const payload = await response.json();
                        const records = Array.isArray(payload?.records) ? payload.records : [];
                        const meta = payload?.meta || {};
                        latestRecords = records;

                        if (! records.length) {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="14" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">No records available.</td>
                                </tr>
                            `;
                            tableMeta.textContent = '';
                            summaryOrdersEl.textContent = '0';
                            summaryPriceEl.textContent = '0.00';
                            summaryCommissionEl.textContent = '0.00';
                            summaryPayableEl.textContent = '0.00';
                            return;
                        }

                        const totalPrice = records.reduce((sum, record) => sum + toNumber(record.base_grand_total), 0);
                        const totalCommission = records.reduce((sum, record) => sum + toNumber(record.seller_commission_expected), 0);
                        const totalPayable = totalPrice + totalCommission;

                        summaryOrdersEl.textContent = String(meta.total ?? records.length);
                        summaryPriceEl.textContent = formatMoney(totalPrice);
                        summaryCommissionEl.textContent = formatMoney(totalCommission);
                        summaryPayableEl.textContent = formatMoney(totalPayable);

                        const actionLabels = {
                            view: @json(__('admin::app.acl.view')),
                            make: @json(__('admin::app.seller.shop-order.make-order')),
                            makeDisabled: @json(__('admin::app.seller.shop-order.make-order-disabled-hint')),
                        };

                        tableBody.innerHTML = records.map((record) => {
                            const actionUrl = record?.actions?.[0]?.url || '#';
                            const orderId = Number(record.id);
                            const statusRaw = String(record.order_status_raw || '').toLowerCase();
                            const isSelectable = ! ['completed', 'processing'].includes(statusRaw);
                            const canMakeOrder = !record.seller_make_order_at && ['pending', 'pending_payment', 'processing'].includes(statusRaw);

                            const makeBtn = canMakeOrder
                                ? `<button
                                    type="button"
                                    class="seller-btn-primary text-xs"
                                    onclick="window.sellerSubmitMakeOrder(${orderId})"
                                >${escapeHtml(actionLabels.make)}</button>`
                                : `<button
                                    type="button"
                                    class="seller-btn-primary cursor-not-allowed text-xs opacity-50"
                                    disabled
                                    title="${escapeHtml(actionLabels.makeDisabled)}"
                                >${escapeHtml(actionLabels.make)}</button>`;

                            return `
                                <tr class="align-top">
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        <input
                                            type="checkbox"
                                            class="seller-order-row-checkbox h-4 w-4 rounded border-gray-300"
                                            data-order-id="${escapeHtml(record.id)}"
                                            ${isSelectable ? '' : 'disabled'}
                                            ${selectedOrderIds.has(record.id) ? 'checked' : ''}
                                        >
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.increment_id)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.created_at)}</td>
                                    <td class="px-3 py-3 text-sm font-medium tabular-nums text-indigo-700 dark:text-indigo-300">${escapeHtml(record.order_age_hours ?? '—')}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${record.status || ''}</td>
                                    <td class="px-3 py-3 text-sm font-semibold text-blue-700 dark:text-blue-400">${formatMoney(record.base_grand_total)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.method)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.full_name)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.customer_email)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${escapeHtml(record.location)}</td>
                                    <td class="px-3 py-3 text-sm font-semibold text-emerald-700 dark:text-emerald-400">${escapeHtml(record.seller_avg_commission)}</td>
                                    <td class="px-3 py-3 text-sm font-semibold text-orange-700 dark:text-orange-400">${formatMoney(record.seller_commission_expected)}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">${record.items || ''}</td>
                                    <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="${escapeHtml(actionUrl)}" class="seller-btn-secondary inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium no-underline">${escapeHtml(actionLabels.view)}</a>
                                            ${makeBtn}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('');

                        const from = meta.from ?? 0;
                        const to = meta.to ?? 0;
                        const total = meta.total ?? records.length;
                        tableMeta.textContent = `Showing ${from} to ${to} of ${total} orders`;
                    } catch (error) {
                        tableBody.innerHTML = `
                            <tr>
                                    <td colspan="14" class="px-3 py-4 text-sm text-red-600 dark:text-red-400">
                                    Failed to load orders: ${escapeHtml(error?.message || 'Unknown error')}
                                </td>
                            </tr>
                        `;
                        tableMeta.textContent = '';
                        summaryOrdersEl.textContent = '0';
                        summaryPriceEl.textContent = '0.00';
                        summaryCommissionEl.textContent = '0.00';
                        summaryPayableEl.textContent = '0.00';
                    }

                    const rowCheckboxes = tableBody.querySelectorAll('.seller-order-row-checkbox');
                    const selectableIds = new Set(
                        Array.from(rowCheckboxes)
                            .filter((cb) => !cb.disabled)
                            .map((cb) => parseInt(cb.dataset.orderId || '0', 10))
                            .filter((id) => !Number.isNaN(id) && id > 0)
                    );

                    Array.from(selectedOrderIds).forEach((id) => {
                        if (!selectableIds.has(id)) {
                            selectedOrderIds.delete(id);
                        }
                    });

                    rowCheckboxes.forEach((checkbox) => {
                        checkbox.addEventListener('change', function () {
                            if (this.disabled) {
                                return;
                            }

                            const orderId = parseInt(this.dataset.orderId || '0', 10);

                            if (!Number.isNaN(orderId) && orderId > 0) {
                                if (this.checked) {
                                    selectedOrderIds.add(orderId);
                                } else {
                                    selectedOrderIds.delete(orderId);
                                }
                            }

                            const allChecked = rowCheckboxes.length > 0 && Array.from(rowCheckboxes).every((cb) => cb.checked);
                            selectAllBox.checked = allChecked;
                            updateBulkToolbar();
                        });
                    });

                    const allChecked = rowCheckboxes.length > 0 && Array.from(rowCheckboxes).every((cb) => cb.checked);
                    selectAllBox.checked = allChecked;
                    updateBulkToolbar();
                }

                selectAllBox.addEventListener('change', function () {
                    const checked = this.checked;
                    const rowCheckboxes = tableBody.querySelectorAll('.seller-order-row-checkbox');

                    rowCheckboxes.forEach((checkbox) => {
                        if (checkbox.disabled) {
                            return;
                        }

                        checkbox.checked = checked;

                        const orderId = parseInt(checkbox.dataset.orderId || '0', 10);

                        if (!Number.isNaN(orderId) && orderId > 0) {
                            if (checked) {
                                selectedOrderIds.add(orderId);
                            } else {
                                selectedOrderIds.delete(orderId);
                            }
                        }
                    });

                    updateBulkToolbar();
                });

                window.getSellerSelectedOrderIds = function () {
                    return Array.from(selectedOrderIds);
                };

                bulkApplyButton.addEventListener('click', function () {
                    const action = bulkActionSelect.value;

                    if (! action) {
                        if (window.emitter && window.emitter.emit) {
                            window.emitter.emit('add-flash', { type: 'warning', message: 'Please select a bulk action.' });
                        }

                        return;
                    }

                    if (action === 'bulk_make_order') {
                        if (typeof window.sellerBulkPurchaseFromHeader === 'function') {
                            window.sellerBulkPurchaseFromHeader();
                        }
                    }
                });

                loadTableData();
            })();
        </script>
    @endPushOnce

</x-admin::layouts>