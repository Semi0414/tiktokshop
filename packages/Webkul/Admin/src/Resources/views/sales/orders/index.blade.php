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
                    <div class="flex gap-2">
                        <input type="date" name="seller_date_from" value="{{ request('seller_date_from') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="seller_date_to" value="{{ request('seller_date_to') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.sales.orders.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>

        <div class="mb-3 flex justify-end">
            <button
                type="button"
                class="seller-btn-primary text-xs"
                onclick="window.sellerBulkPurchaseFromHeader && window.sellerBulkPurchaseFromHeader()"
            >
                @lang('admin::app.seller-panel.orders.bulk-purchase')
            </button>
        </div>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.orders.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <x-admin::datagrid.export src="{{ route('admin.sales.orders.index') }}" />

            {!! view_render_event('bagisto.admin.sales.orders.create.before') !!}

            @if (bouncer()->hasPermission('sales.orders.create'))
                <button
                    class="primary-button"
                    @click="$refs.selectCustomerComponent.openDrawer()"
                >
                    @lang('admin::app.sales.orders.index.create-btn')
                </button>
            @endif

            {!! view_render_event('bagisto.admin.sales.orders.create.after') !!}
        </div>
    </div>

    <v-customer-search ref="selectCustomerComponent"></v-customer-search>

    <div id="seller-shop-order-grid">
    <x-admin::datagrid :src="route('admin.sales.orders.index')" :isMultiRow="true">
        <template #header="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>

            <template v-else>
                <!-- Grid Header Columns -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 items-center border-b px-2 sm:px-4 py-2.5 dark:border-gray-800">
                    <div
                        class="flex select-none items-center gap-2.5"
                        v-for="(columnGroup, index) in [['increment_id', 'created_at', 'status'], ['base_grand_total', 'method', 'channel_id'], ['full_name', 'customer_email', 'location'], ['items']]"
                    >
                        <label
                            v-if="! index && available.massActions.length"
                            class="flex w-max cursor-pointer select-none items-center gap-1"
                            for="mass_action_select_all_records"
                        >
                            <input
                                type="checkbox"
                                name="mass_action_select_all_records"
                                id="mass_action_select_all_records"
                                class="peer hidden"
                                :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                @change="selectAll"
                            >
                            <span
                                class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                :class="[
                                    applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600' : (
                                        applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-blue-600' : ''
                                    ),
                                ]"
                            >
                            </span>
                        </label>
                        <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup" :key="column">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)) : {}
                                        "
                                    >
                                        <template v-if="column === 'increment_id'">
                                            <span class="contents">@lang('admin::app.seller-panel.orders.seq-no')</span>
                                        </template>
                                        <template v-else>
                                            @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                        </template>
                                    </span>
                                </template>
                            </span>

                            <i
                                class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            >
                            </i>
                        </p>
                    </div>
                </div>
            </template>
        </template>

        <template #body="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>

            <template v-else>
                <div
                    v-if="available.massActions.length && applied.massActions.indices.length"
                    class="seller-filter-card mb-3 flex flex-row flex-wrap items-stretch justify-between gap-4 border-b border-gray-100 px-2 py-3 sm:gap-6 sm:px-4 dark:border-gray-800"
                >
                    <div class="min-w-0 flex-1 basis-[min(100%,12rem)]">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.shop-order.bulk-principal')</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                            @{{ $admin.formatPrice(
                                available.records
                                    .filter(r => applied.massActions.indices.map(String).includes(String(r.id)))
                                    .reduce((s, r) => s + parseFloat(r.base_grand_total || 0), 0)
                            ) }}
                        </p>
                    </div>
                    <div class="min-w-0 flex-1 basis-[min(100%,12rem)]">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.shop-order.bulk-profit')</p>
                        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                            @{{ $admin.formatPrice(
                                available.records
                                    .filter(r => applied.massActions.indices.map(String).includes(String(r.id)))
                                    .reduce((s, r) => s + parseFloat(r.seller_commission_expected || 0), 0)
                            ) }}
                        </p>
                    </div>
                    <div class="min-w-0 flex-1 basis-[min(100%,12rem)]">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.shop-order.bulk-total')</p>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                            @{{ $admin.formatPrice(
                                available.records
                                    .filter(r => applied.massActions.indices.map(String).includes(String(r.id)))
                                    .reduce((s, r) => s + parseFloat(r.base_grand_total || 0) + parseFloat(r.seller_commission_expected || 0), 0)
                            ) }}
                        </p>
                    </div>
                </div>

                <!-- Order Rows -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-4 border-b px-2 sm:px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                    v-for="(record, rowIndex) in available.records"
                >
                    <!-- Order Id, Created, Status Section -->
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-start gap-2">
                            <template v-if="available.massActions.length">
                                <input
                                    type="checkbox"
                                    :name="`mass_action_select_record_${record.id}`"
                                    :id="`mass_action_select_record_${record.id}`"
                                    :value="record.id"
                                    class="peer sr-only"
                                    v-model="applied.massActions.indices"
                                >
                                <label
                                    :for="`mass_action_select_record_${record.id}`"
                                    class="icon-uncheckbox peer-checked:icon-checked mt-0.5 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"
                                >
                                </label>
                            </template>
                            <div class="min-w-0 flex-1">
                        <p class="text-sm sm:text-base font-semibold text-gray-800 dark:text-white">
                            @{{ ((available.meta.current_page || 1) - 1) * (available.meta.per_page || applied.pagination.perPage || 10) + rowIndex + 1 }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                            @{{ record.created_at }}
                        </p>
                        
                        <p v-html="record.status"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount, Pay Via, Channel -->
                    <div class="flex flex-col gap-1.5">
                        <p class="text-sm sm:text-base font-semibold text-gray-800 dark:text-white">
                            @{{ $admin.formatPrice(record.base_grand_total) }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.orders.index.datagrid.pay-by', ['method' => ''])@{{ record.method }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                            @{{ record.channel_name }}
                        </p>

                        <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400">
                            @lang('admin::app.seller.shop-order.col-commission'): <span>@{{ record.seller_avg_commission }}</span>
                        </p>
                    </div>

                    <!-- Customer, Email, Location Section -->
                    <div class="flex flex-col gap-1.5">
                        <p class="text-sm sm:text-base text-gray-800 dark:text-white">
                            @{{ record.full_name }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                            @{{ record.customer_email }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                            @{{ record.location }}
                        </p>
                    </div>

                    <!-- Items + Make Order -->
                    <div class="flex flex-col items-stretch gap-2 md:items-end">
                        <div class="flex items-start justify-between gap-x-2">
                            <div
                                class="flex flex-col gap-1.5 text-xs sm:text-sm"
                                v-html="record.items"
                            >
                            </div>

                            <a :href=`{{ route('admin.sales.orders.view', '') }}/${record.id}`>
                                <span class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-xl sm:text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                            </a>
                        </div>

                        <button
                            v-if="!record.seller_make_order_at"
                            type="button"
                            class="seller-btn-primary self-end text-xs"
                            @click="sellerSubmitMakeOrder(record.id)"
                        >
                            @lang('admin::app.seller.shop-order.make-order')
                        </button>
                        <p
                            v-else-if="record.order_status_raw === 'completed'"
                            class="self-end text-xs font-medium text-emerald-700 dark:text-emerald-400"
                        >
                            @lang('admin::app.sales.orders.index.datagrid.completed')
                        </p>
                        <p
                            v-else-if="record.order_status_raw === 'closed'"
                            class="self-end text-xs font-medium text-gray-600 dark:text-gray-400"
                        >
                            @lang('admin::app.sales.orders.index.datagrid.closed')
                        </p>
                        <p
                            v-else-if="record.order_status_raw === 'canceled'"
                            class="self-end text-xs font-medium text-red-700 dark:text-red-400"
                        >
                            @lang('admin::app.sales.orders.index.datagrid.canceled')
                        </p>
                        <p v-else class="self-end text-xs text-gray-500 dark:text-gray-400">
                            @lang('admin::app.seller.shop-order.order-in-progress')
                        </p>
                    </div>
                </div>
            </template>
        </template>
    </x-admin::datagrid>
    </div>

    @include('admin::customers.customers.index.create')

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customer-search-template"
        >
            <div class="">
                <!-- Search Drawer -->
                <x-admin::drawer
                    ref="searchCustomerDrawer"
                    @close="searchTerm = ''; searchedCustomers = [];"
                >
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-3">
                            <p class="py-2 text-xl font-medium dark:text-white">
                                @lang('admin::app.sales.orders.index.search-customer.title')
                            </p>

                            <div class="relative w-full">
                                <input
                                    type="text"
                                    class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"
                                    placeholder="@lang('admin::app.sales.orders.index.search-customer.search-by')"
                                    v-model.lazy="searchTerm"
                                    v-debounce="500"
                                />

                                <template v-if="isSearching">
                                    <img
                                        class="absolute top-2.5 h-5 w-5 animate-spin ltr:right-3 rtl:left-3"
                                        src="{{ bagisto_asset('images/spinner.svg') }}"
                                    />
                                </template>

                                <template v-else>
                                    <span class="icon-search pointer-events-none absolute top-1.5 flex items-center text-2xl ltr:right-3 rtl:left-3"></span>
                                </template>
                            </div>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div
                            class="grid max-h-[400px] overflow-y-auto"
                            v-if="searchedCustomers.length"
                        >
                            <div
                                class="grid cursor-pointer place-content-start gap-1.5 border-b border-slate-300 p-4 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950"
                                v-for="customer in searchedCustomers"
                                @click="createCart(customer)"
                            >
                                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @{{ customer.first_name + ' ' + customer.last_name }}
                                </p>

                                <p class="text-gray-500">
                                    @{{ customer.email }}
                                </p>
                            </div>
                        </div>

                        <!-- For Empty Variations -->
                        <div
                            class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                            v-else
                        >
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/empty-placeholders/customers.svg') }}"
                                class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                            />

                            <!-- Add Variants Information -->
                            <div class="flex flex-col items-center gap-1.5">
                                <p class="text-base font-semibold text-gray-400">
                                    @lang('admin::app.sales.orders.index.search-customer.empty-title')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.sales.orders.index.search-customer.empty-info')
                                </p>

                                <button
                                    class="secondary-button"
                                    @click="$refs.searchCustomerDrawer.close(); $refs.createCustomerComponent.openModal()"
                                >
                                    @lang('admin::app.sales.orders.index.search-customer.create-btn')
                                </button>
                            </div>
                        </div>
                    </x-slot>
                </x-admin::drawer>

                <v-create-customer-form
                    ref="createCustomerComponent"
                    @customer-created="createCart"
                ></v-create-customer-form>
            </div>
        </script>

        <script type="module">
            app.component('v-customer-search', {
                template: '#v-customer-search-template',

                data() {
                    return {
                        searchTerm: '',

                        searchedCustomers: [],

                        isSearching: false,
                    }
                },

                watch: {
                    searchTerm: function(newVal, oldVal) {
                        this.search();
                    }
                },

                methods: {
                    openDrawer() {
                        this.$refs.searchCustomerDrawer.open();
                    },

                    search() {
                        if (this.searchTerm.length <= 1) {
                            this.searchedCustomers = [];

                            return;
                        }

                        this.isSearching = true;

                        let self = this;

                        this.$axios.get("{{ route('admin.customers.customers.search') }}", {
                                params: {
                                    query: this.searchTerm,
                                }
                            })
                            .then(function(response) {
                                self.isSearching = false;

                                self.searchedCustomers = response.data.data;
                            })
                            .catch(function (error) {
                            });
                    },

                    createCart(customer) {
                        this.$axios.post("{{ route('admin.sales.cart.store') }}", {customer_id: customer.id})
                            .then(function(response) {
                                window.location.href = response.data.redirect_url;
                            })
                            .catch(function (error) {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },
                }
            });
        </script>
    @endPushOnce
    </x-admin::seller.panel>

    @pushOnce('scripts')
        <script>
            /**
             * Vue templates cannot use `window.*` (window is undefined on the render proxy).
             * Register on app.config.globalProperties so @click="sellerSubmitMakeOrder(id)" works.
             */
            (function attachSellerSubmitMakeOrder() {
                if (typeof window.app === 'undefined' || !window.app.config) {
                    setTimeout(attachSellerSubmitMakeOrder, 10);
                    return;
                }
                function sellerFlash(type, message) {
                    if (typeof window.emitter !== 'undefined' && window.emitter.emit) {
                        window.emitter.emit('add-flash', { type: type, message: message });
                    } else {
                        alert(message);
                    }
                }

                if (!window.app.config.globalProperties.sellerSubmitMakeOrder) {
                    window.app.config.globalProperties.sellerSubmitMakeOrder = async function (orderId) {
                        const template = @json(route('admin.seller.shop-order.make-order', ['order' => 0]));
                        const url = template.replace(/\/\d+\/make-order/, '/' + String(orderId) + '/make-order');
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token || '',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                        });
                        const data = await res.json().catch(function () { return {}; });
                        const msg = data.message || (res.ok ? 'OK' : 'Request failed');
                        sellerFlash(res.ok ? 'success' : 'error', msg);
                        if (res.ok) {
                            window.location.reload();
                        }
                    };
                }

                if (!window.sellerBulkPurchaseFromHeader) {
                    window.sellerBulkPurchaseFromHeader = async function () {
                    const grid = document.getElementById('seller-shop-order-grid');
                    if (grid) {
                        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    const boxes = grid
                        ? grid.querySelectorAll('input[type="checkbox"][name^="mass_action_select_record"]')
                        : [];
                    const indices = [];
                    boxes.forEach(function (cb) {
                        if (cb.checked && cb.value) {
                            const n = parseInt(cb.value, 10);
                            if (!Number.isNaN(n)) {
                                indices.push(n);
                            }
                        }
                    });
                    if (!indices.length) {
                        sellerFlash('warning', @json(__('admin::app.seller-panel.orders.bulk-purchase-no-selection')));
                        return;
                    }
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const res = await fetch(@json(route('admin.seller.shop-order.bulk-make-order')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token || '',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ indices: indices }),
                    });
                    const data = await res.json().catch(function () { return {}; });
                    const msg = data.message || (res.ok ? 'OK' : 'Request failed');
                    sellerFlash(res.ok ? 'success' : 'error', msg);
                    if (res.ok) {
                        window.location.reload();
                    }
                };
                }
            })();
        </script>
    @endPushOnce

</x-admin::layouts>