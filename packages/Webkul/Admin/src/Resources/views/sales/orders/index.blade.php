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
                @lang('admin::app.sales.orders.index.datagrid.pending')
            </a>
            <a href="{{ $chipUrl('processing') }}" class="{{ $chipClass }} {{ $scope === 'processing' ? $chipActive : $chipInactive }}">
                @lang('admin::app.sales.orders.index.datagrid.processing')
            </a>
            <a href="{{ $chipUrl('completed') }}" class="{{ $chipClass }} {{ $scope === 'completed' ? $chipActive : $chipInactive }}">
                @lang('admin::app.sales.orders.index.datagrid.completed')
            </a>
            <a href="{{ $chipUrl('rejected') }}" class="{{ $chipClass }} {{ $scope === 'rejected' ? $chipActive : $chipInactive }}">
                Rejected
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

            <div id="seller-orders-table-wrap" class="-mx-px overflow-x-auto overscroll-x-contain sm:mx-0">
                <table class="min-w-[720px] w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                <input id="seller-orders-select-all" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                            </th>
                            <th data-sort-column="increment_id" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Order ID</th>
                            <th data-sort-column="created_at" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            <th data-sort-column="created_at" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.seller.shop-order.col-age-hours')</th>
                            <th data-sort-column="status" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th data-sort-column="base_grand_total" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Grand Total</th>
                            <th data-sort-column="method" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Pay Via</th>
                            <th data-sort-column="full_name" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                            <th data-sort-column="customer_email" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Email</th>
                            <th data-sort-column="location" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Location</th>
                            <th data-sort-column="seller_commission_expected" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Commission %</th>
                            <th data-sort-column="seller_commission_expected" class="seller-sortable px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Commission Amount</th>
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
            <div id="seller-orders-cards" class="grid gap-3 p-3"></div>

            <div id="seller-orders-table-meta" class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400"></div>
            <div
                id="seller-orders-pagination"
                class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-200 px-3 py-2 text-xs text-gray-600 dark:border-gray-800 dark:text-gray-300"
            ></div>
        </div>
    </x-admin::seller.panel>

    {{-- Dedicated password modal for shop orders only (does not depend on global script stack / shared gate) --}}
    <style>
        #seller-orders-table-wrap {
            display: block;
        }

        #seller-orders-cards {
            display: none;
        }

        @media (max-width: 767px) {
            #seller-orders-table-wrap {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                max-height: 0 !important;
                overflow: hidden !important;
                pointer-events: none !important;
                position: absolute !important;
                width: 0 !important;
                opacity: 0 !important;
            }

            #seller-orders-cards {
                display: grid;
                position: relative;
                z-index: 1;
            }
        }

        #seller-shop-order-pw-root {
            position: fixed;
            inset: 0;
            z-index: 99990;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: rgba(2, 6, 23, 0.66);
            -webkit-backdrop-filter: blur(1.5px);
            backdrop-filter: blur(1.5px);
        }

        #seller-shop-order-pw-root:not(.hidden) {
            display: flex;
        }

        #seller-shop-order-pw-root .ssopw-card {
            width: min(560px, 100%);
            border-radius: 16px;
            border: 1px solid #d9dee8;
            background: #ffffff;
            box-shadow: 0 28px 55px -22px rgba(15, 23, 42, 0.5);
            padding: 24px;
            box-sizing: border-box;
            font-family: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            color: #0f172a;
        }

        #seller-shop-order-pw-root .ssopw-title {
            margin: 0;
            font-size: 20px;
            line-height: 1.35;
            font-weight: 700;
            color: #0f172a;
        }

        #seller-shop-order-pw-root .ssopw-desc {
            margin: 8px 0 0 0;
            font-size: 14px;
            line-height: 1.55;
            color: #475569;
        }

        #seller-shop-order-pw-root .ssopw-label {
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 12px;
            line-height: 1.45;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #334155;
        }

        #seller-shop-order-pw-root .ssopw-input {
            width: 100%;
            min-height: 44px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: #ffffff;
            color: #0f172a;
            box-sizing: border-box;
            padding: 10px 12px;
            font-size: 14px;
            line-height: 1.4;
            outline: none;
            transition: border-color 120ms ease, box-shadow 120ms ease;
        }

        #seller-shop-order-pw-root .ssopw-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        #seller-shop-order-pw-root .ssopw-error {
            margin-top: 8px;
            font-size: 13px;
            line-height: 1.45;
            color: #dc2626;
        }

        #seller-shop-order-pw-root .ssopw-actions {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        #seller-shop-order-pw-root .ssopw-btn {
            appearance: none;
            border: 1px solid transparent;
            border-radius: 10px;
            min-height: 40px;
            padding: 9px 16px;
            font-size: 13px;
            line-height: 1.2;
            font-weight: 600;
            cursor: pointer;
            transition: transform 80ms ease, filter 120ms ease, background-color 120ms ease, border-color 120ms ease;
        }

        #seller-shop-order-pw-root .ssopw-btn:hover {
            filter: brightness(0.98);
        }

        #seller-shop-order-pw-root .ssopw-btn:active {
            transform: translateY(1px);
        }

        #seller-shop-order-pw-root .ssopw-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        #seller-shop-order-pw-root .ssopw-btn--cancel {
            border-color: #cbd5e1;
            color: #334155;
            background: #ffffff;
        }

        #seller-shop-order-pw-root .ssopw-btn--ok {
            border-color: #4f46e5;
            background: #4f46e5;
            color: #ffffff;
        }

        @media (max-width: 640px) {
            #seller-shop-order-pw-root .ssopw-card {
                padding: 18px;
                border-radius: 14px;
            }

            #seller-shop-order-pw-root .ssopw-actions {
                flex-direction: column-reverse;
            }

            #seller-shop-order-pw-root .ssopw-btn {
                width: 100%;
            }
        }
    </style>

    <div
        id="seller-shop-order-pw-root"
        class="hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="seller-shop-order-pw-title"
    >
        <div class="ssopw-card">
            <h2 id="seller-shop-order-pw-title" class="ssopw-title">
                @lang('admin::app.account.verify-password.title')
            </h2>
            <p class="ssopw-desc">
                @lang('admin::app.account.verify-password.description')
            </p>
            <label for="seller-shop-order-pw-input" class="ssopw-label">
                @lang('admin::app.account.verify-password.placeholder')
            </label>
            <input
                type="password"
                id="seller-shop-order-pw-input"
                autocomplete="current-password"
                class="ssopw-input"
            />
            <p id="seller-shop-order-pw-err" class="ssopw-error hidden"></p>
            <div class="ssopw-actions">
                <button
                    type="button"
                    id="seller-shop-order-pw-btn-cancel"
                    class="ssopw-btn ssopw-btn--cancel"
                >
                    @lang('admin::app.account.verify-password.cancel')
                </button>
                <button
                    type="button"
                    id="seller-shop-order-pw-btn-ok"
                    class="ssopw-btn ssopw-btn--ok"
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
                        try {
                            sessionStorage.setItem('seller_order_success_flash', msg);
                        } catch (e) {}
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
                        try {
                            sessionStorage.setItem('seller_order_success_flash', msg);
                        } catch (e) {}
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
                const cardsBody = document.getElementById('seller-orders-cards');
                const tableMeta = document.getElementById('seller-orders-table-meta');
                const tablePagination = document.getElementById('seller-orders-pagination');
                const selectAllBox = document.getElementById('seller-orders-select-all');
                const sortableHeaders = Array.from(document.querySelectorAll('.seller-sortable[data-sort-column]'));
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
                    || ! cardsBody
                    || ! tableMeta
                    || ! tablePagination
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

                if (! cardsBody.dataset.actionsBound) {
                    cardsBody.dataset.actionsBound = '1';
                    cardsBody.addEventListener('click', function (event) {
                        const makeBtn = event.target.closest('[data-seller-make-order]');

                        if (makeBtn && ! makeBtn.disabled) {
                            event.preventDefault();
                            const orderId = parseInt(makeBtn.getAttribute('data-seller-make-order') || '0', 10);

                            if (! Number.isNaN(orderId) && orderId > 0 && typeof window.sellerSubmitMakeOrder === 'function') {
                                window.sellerSubmitMakeOrder(orderId);
                            }
                        }
                    });
                }

                function toNumber(value) {
                    const n = parseFloat(value ?? 0);
                    return Number.isFinite(n) ? n : 0;
                }

                function formatMoney(value) {
                    return toNumber(value).toFixed(2);
                }

                function formatOrderAge(hoursRaw) {
                    const hours = parseInt(String(hoursRaw ?? 0), 10);

                    if (! Number.isFinite(hours) || hours < 0) {
                        return '—';
                    }

                    if (hours <= 72) {
                        return `${hours} hours`;
                    }

                    if (hours < 24 * 30) {
                        return `${Math.floor(hours / 24)} days`;
                    }

                    if (hours < 24 * 365) {
                        return `${Math.floor(hours / (24 * 30))} months`;
                    }

                    return `${Math.floor(hours / (24 * 365))} years`;
                }

                function showSellerSuccessToastIfAny() {
                    try {
                        const msg = sessionStorage.getItem('seller_order_success_flash');

                        if (! msg) {
                            return;
                        }

                        if (window.emitter && window.emitter.emit) {
                            window.emitter.emit('add-flash', { type: 'success', message: msg });
                        }

                        sessionStorage.removeItem('seller_order_success_flash');
                    } catch (e) {}
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

                function renderSellerOrdersPagination(meta) {
                    const last = Math.max(1, parseInt(String(meta?.last_page ?? 1), 10) || 1);
                    const cur = Math.min(last, Math.max(1, parseInt(String(meta?.current_page ?? 1), 10) || 1));
                    const perPage = Math.max(1, parseInt(String(meta?.per_page ?? 10), 10) || 10);
                    const opts = Array.isArray(meta?.per_page_options) && meta.per_page_options.length
                        ? meta.per_page_options
                        : [10, 20, 30, 40, 50];

                    const idle = 'border-gray-200 bg-white text-gray-700 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200';
                    const active = 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-950/40 dark:text-indigo-200';

                    const pageButtons = [];
                    for (let p = 1; p <= last; p++) {
                        const cls = p === cur ? active : idle;
                        pageButtons.push(
                            `<button type="button" data-seller-page="${p}" class="seller-page-btn rounded-md border px-2.5 py-1 font-medium ${cls}"${p === cur ? ' aria-current="page"' : ''}>${p}</button>`
                        );
                    }

                    const perOpts = opts
                        .map((n) => `<option value="${n}"${Number(n) === perPage ? ' selected' : ''}>${n}</option>`)
                        .join('');

                    tablePagination.innerHTML = `
                        <div class="flex min-w-0 flex-1 flex-wrap items-center gap-1">
                            <button type="button" data-seller-page="${cur - 1}" class="seller-page-btn rounded-md border px-2.5 py-1 font-medium ${idle}"${cur <= 1 ? ' disabled' : ''}>Prev</button>
                            ${pageButtons.join('')}
                            <button type="button" data-seller-page="${cur + 1}" class="seller-page-btn rounded-md border px-2.5 py-1 font-medium ${idle}"${cur >= last ? ' disabled' : ''}>Next</button>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-400">Per page</span>
                            <select data-seller-per-page class="rounded-md border border-gray-200 bg-white px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">${perOpts}</select>
                        </div>
                    `;

                    tablePagination.querySelectorAll('button[data-seller-page]').forEach((btn) => {
                        btn.addEventListener('click', function () {
                            if (this.disabled) {
                                return;
                            }
                            const p = parseInt(this.getAttribute('data-seller-page') || '0', 10);
                            if (! Number.isFinite(p) || p < 1 || p > last) {
                                return;
                            }
                            const params = new URLSearchParams(window.location.search);
                            params.set('pagination[page]', String(p));
                            window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                            void loadTableData();
                        });
                    });

                    const sel = tablePagination.querySelector('select[data-seller-per-page]');
                    if (sel) {
                        sel.addEventListener('change', function () {
                            const v = parseInt(this.value, 10) || 10;
                            const params = new URLSearchParams(window.location.search);
                            params.set('pagination[per_page]', String(v));
                            params.set('pagination[page]', '1');
                            window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                            void loadTableData();
                        });
                    }
                }

                function updateSortableHeaders(params) {
                    const activeColumn = params.get('sort[column]') || '';
                    const activeOrder = (params.get('sort[order]') || 'desc').toLowerCase();

                    sortableHeaders.forEach((th) => {
                        const col = th.getAttribute('data-sort-column') || '';
                        th.classList.add('cursor-pointer', 'select-none');

                        const icon = col === activeColumn
                            ? (activeOrder === 'asc' ? ' ↑' : ' ↓')
                            : ' ↕';

                        const label = (th.textContent || '').replace(/\s[↑↓↕]$/, '').trim();
                        th.textContent = `${label}${icon}`;
                    });
                }

                async function loadTableData() {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="14" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Loading...</td>
                        </tr>
                    `;
                    tablePagination.innerHTML = '';

                    try {
                        const params = new URLSearchParams(window.location.search);

                        if (! params.has('pagination[page]')) {
                            params.set('pagination[page]', '1');
                        }

                        if (! params.has('pagination[per_page]')) {
                            params.set('pagination[per_page]', '10');
                        }

                        if (! params.has('sort[column]')) {
                            params.set('sort[column]', 'orders.created_at');
                        }

                        if (! params.has('sort[order]')) {
                            params.set('sort[order]', 'desc');
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
                        updateSortableHeaders(params);

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
                            tablePagination.innerHTML = '';
                            cardsBody.innerHTML = '<div class="rounded-lg border border-gray-200 bg-white p-3 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">No records available.</div>';
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
                            const hasServerFlag = Object.prototype.hasOwnProperty.call(record, 'seller_can_make_order');
                            const canMakeOrder = hasServerFlag
                                ? String(record.seller_can_make_order).trim() === '1'
                                : (! record.seller_make_order_at && ['pending', 'pending_payment', 'processing'].includes(statusRaw));
                            const isSelectable = hasServerFlag
                                ? String(record.seller_can_make_order).trim() === '1'
                                : (! ['completed', 'closed', 'canceled', 'fraud'].includes(statusRaw));

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
                                    <td class="px-3 py-3 text-sm font-medium tabular-nums text-indigo-700 dark:text-indigo-300">${escapeHtml(formatOrderAge(record.order_age_hours))}</td>
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

                        cardsBody.innerHTML = records.map((record) => {
                            const actionUrl = record?.actions?.[0]?.url || '#';
                            const orderId = Number(record.id);
                            const statusRaw = String(record.order_status_raw || '').toLowerCase();
                            const hasServerFlag = Object.prototype.hasOwnProperty.call(record, 'seller_can_make_order');
                            const canMakeOrder = hasServerFlag
                                ? String(record.seller_can_make_order).trim() === '1'
                                : (! record.seller_make_order_at && ['pending', 'pending_payment', 'processing'].includes(statusRaw));
                            const isSelectable = hasServerFlag
                                ? String(record.seller_can_make_order).trim() === '1'
                                : (! ['completed', 'closed', 'canceled', 'fraud'].includes(statusRaw));

                            return `
                                <article class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                    <div class="mb-2 flex items-center justify-between gap-2">
                                        <input
                                            type="checkbox"
                                            class="seller-order-card-checkbox h-4 w-4 rounded border-gray-300"
                                            data-order-id="${escapeHtml(record.id)}"
                                            ${isSelectable ? '' : 'disabled'}
                                            ${selectedOrderIds.has(record.id) ? 'checked' : ''}
                                        >
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">#${escapeHtml(record.increment_id)}</p>
                                        <div class="text-xs">${record.status || ''}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-xs text-gray-600 dark:text-gray-300">
                                        <p><span class="font-medium">Date:</span> ${escapeHtml(record.created_at)}</p>
                                        <p><span class="font-medium">Age:</span> ${escapeHtml(formatOrderAge(record.order_age_hours))}</p>
                                        <p><span class="font-medium">Total:</span> ${formatMoney(record.base_grand_total)}</p>
                                        <p><span class="font-medium">Commission:</span> ${formatMoney(record.seller_commission_expected)}</p>
                                        <p><span class="font-medium">Customer:</span> ${escapeHtml(record.full_name)}</p>
                                        <p><span class="font-medium">Pay Via:</span> ${escapeHtml(record.method)}</p>
                                    </div>
                                    <div class="seller-order-card-actions mt-3 flex flex-wrap gap-2">
                                        <a href="${escapeHtml(actionUrl)}" class="seller-btn-secondary inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium no-underline">${escapeHtml(actionLabels.view)}</a>
                                        ${canMakeOrder
                                            ? `<button type="button" class="seller-btn-primary text-xs" data-seller-make-order="${orderId}">${escapeHtml(actionLabels.make)}</button>`
                                            : `<button type="button" class="seller-btn-primary cursor-not-allowed text-xs opacity-50" disabled title="${escapeHtml(actionLabels.makeDisabled)}">${escapeHtml(actionLabels.make)}</button>`
                                        }
                                    </div>
                                </article>
                            `;
                        }).join('');

                        const from = meta.from ?? 0;
                        const to = meta.to ?? 0;
                        const total = meta.total ?? records.length;
                        tableMeta.textContent = `Showing ${from} to ${to} of ${total} orders`;
                        renderSellerOrdersPagination(meta);
                    } catch (error) {
                        tableBody.innerHTML = `
                            <tr>
                                    <td colspan="14" class="px-3 py-4 text-sm text-red-600 dark:text-red-400">
                                    Failed to load orders: ${escapeHtml(error?.message || 'Unknown error')}
                                </td>
                            </tr>
                        `;
                        tableMeta.textContent = '';
                        tablePagination.innerHTML = '';
                        cardsBody.innerHTML = '';
                        summaryOrdersEl.textContent = '0';
                        summaryPriceEl.textContent = '0.00';
                        summaryCommissionEl.textContent = '0.00';
                        summaryPayableEl.textContent = '0.00';
                    }

                    const rowCheckboxes = tableBody.querySelectorAll('.seller-order-row-checkbox');
                    const cardCheckboxes = cardsBody.querySelectorAll('.seller-order-card-checkbox');
                    const allCheckboxes = Array.from(rowCheckboxes).concat(Array.from(cardCheckboxes));
                    const selectableIds = new Set(
                        allCheckboxes
                            .filter((cb) => !cb.disabled)
                            .map((cb) => parseInt(cb.dataset.orderId || '0', 10))
                            .filter((id) => !Number.isNaN(id) && id > 0)
                    );

                    Array.from(selectedOrderIds).forEach((id) => {
                        if (!selectableIds.has(id)) {
                            selectedOrderIds.delete(id);
                        }
                    });

                    allCheckboxes.forEach((checkbox) => {
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

                                allCheckboxes.forEach((peer) => {
                                    if (peer === this || peer.disabled) {
                                        return;
                                    }

                                    const peerId = parseInt(peer.dataset.orderId || '0', 10);
                                    if (peerId === orderId) {
                                        peer.checked = this.checked;
                                    }
                                });
                            }

                            const allChecked = allCheckboxes.length > 0 && allCheckboxes.filter((cb) => !cb.disabled).every((cb) => cb.checked);
                            selectAllBox.checked = allChecked;
                            updateBulkToolbar();
                        });
                    });

                    const allChecked = allCheckboxes.length > 0 && allCheckboxes.filter((cb) => !cb.disabled).every((cb) => cb.checked);
                    selectAllBox.checked = allChecked;
                    updateBulkToolbar();
                }

                selectAllBox.addEventListener('change', function () {
                    const checked = this.checked;
                    const rowCheckboxes = tableBody.querySelectorAll('.seller-order-row-checkbox');
                    const cardCheckboxes = cardsBody.querySelectorAll('.seller-order-card-checkbox');
                    const allCheckboxes = Array.from(rowCheckboxes).concat(Array.from(cardCheckboxes));

                    allCheckboxes.forEach((checkbox) => {
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

                sortableHeaders.forEach((th) => {
                    th.addEventListener('click', function () {
                        const column = this.getAttribute('data-sort-column');
                        if (! column) {
                            return;
                        }

                        const params = new URLSearchParams(window.location.search);
                        const prevColumn = params.get('sort[column]') || '';
                        const prevOrder = (params.get('sort[order]') || 'desc').toLowerCase();
                        const nextOrder = prevColumn === column && prevOrder === 'asc' ? 'desc' : 'asc';

                        params.set('sort[column]', column);
                        params.set('sort[order]', nextOrder);
                        params.set('pagination[page]', '1');
                        window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                        void loadTableData();
                    });
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

                showSellerSuccessToastIfAny();
                loadTableData();
            })();
        </script>
    @endPushOnce

</x-admin::layouts>