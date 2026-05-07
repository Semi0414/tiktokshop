<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.sales.orders.index.title')
    </x-slot>

    @php
        // Avoid `use (...)` inside closures here — Blade's compiler can mis-parse `use`.
        $sortUrl = function (string $column) {
            $sort = request()->input('sort', 'created_at');
            $order = strtolower((string) request()->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';

            if ($sort === $column) {
                $nextOrder = $order === 'asc' ? 'desc' : 'asc';
            } else {
                $nextOrder = 'asc';
            }

            return route('superadmin.sales.orders.customers.index', array_merge(
                request()->except(['sort', 'order', 'page']),
                ['sort' => $column, 'order' => $nextOrder]
            ));
        };

        $ordersTableColspan = 12;
    @endphp

    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.sales.orders.index.title')
            </p>

            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2">
                <form
                    method="get"
                    action="{{ route('superadmin.sales.orders.customers.index') }}"
                    class="flex items-center gap-2"
                    role="search"
                >
                    <input type="hidden" name="sort" value="{{ $sort }}" />
                    <input type="hidden" name="order" value="{{ $order }}" />

                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="{{ __('superadmin::app.components.datagrid.toolbar.search.title') }}"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white min-w-[200px]"
                    />

                    <button type="submit" class="secondary-button text-sm py-2 px-3">
                        @lang('superadmin::app.components.datagrid.toolbar.search.title')
                    </button>
                </form>

                <x-superadmin::datagrid.export src="{{ route('superadmin.sales.orders.customers.index') }}" />

                {!! view_render_event('bagisto.admin.sales.orders.create.before') !!}

                @if (bouncer()->hasPermission('sales.orders.create'))
                    <button
                        class="primary-button"
                        type="button"
                        @click="$refs.selectCustomerComponent.openDrawer()"
                    >
                        @lang('superadmin::app.sales.orders.index.create-btn')
                    </button>
                @endif

                {!! view_render_event('bagisto.admin.sales.orders.create.after') !!}
            </div>
        </div>

        <v-customer-search ref="selectCustomerComponent"></v-customer-search>

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-950">
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('increment_id') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.order-id')
                                @if ($sort === 'increment_id')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('created_at') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.date')
                                @if ($sort === 'created_at')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('status') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.status')
                                @if ($sort === 'status')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('base_grand_total') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.grand-total')
                                @if ($sort === 'base_grand_total')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.sales.orders.index.datagrid.pay-via')
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('channel_name') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.store-name')
                                @if ($sort === 'channel_name')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('seller_name') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.seller-name')
                                @if ($sort === 'seller_name')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('full_name') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.customer')
                                @if ($sort === 'full_name')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <a href="{{ $sortUrl('customer_email') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.sales.orders.index.datagrid.email')
                                @if ($sort === 'customer_email')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.sales.orders.index.datagrid.location')
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.sales.orders.index.datagrid.items')
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.components.datagrid.table.actions')
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($orders as $row)
                        @php
                            $orderModel = $ordersWithItems[$row->id] ?? null;
                            $payLabels = collect(explode('|', (string) ($row->method ?? '')))
                                ->map(fn ($m) => core()->getConfigData('sales.payment_methods.'.trim($m).'.title'))
                                ->filter()
                                ->unique()
                                ->join(', ');
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950 align-top">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ __('superadmin::app.sales.orders.index.datagrid.id', ['id' => $row->increment_id]) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ core()->formatDate($row->created_at) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @switch($row->status)
                                    @case(\Webkul\Sales\Models\Order::STATUS_PROCESSING)
                                        <p class="label-processing">{{ __('superadmin::app.sales.orders.index.datagrid.processing') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_COMPLETED)
                                        <p class="label-active">{{ __('superadmin::app.sales.orders.index.datagrid.completed') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_CANCELED)
                                        <p class="label-canceled">{{ __('superadmin::app.sales.orders.index.datagrid.canceled') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_CLOSED)
                                        <p class="label-closed">{{ __('superadmin::app.sales.orders.index.datagrid.closed') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_PENDING)
                                        <p class="label-pending">{{ __('superadmin::app.sales.orders.index.datagrid.pending') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT)
                                        <p class="label-pending">{{ __('superadmin::app.sales.orders.index.datagrid.pending-payment') }}</p>
                                        @break
                                    @case(\Webkul\Sales\Models\Order::STATUS_FRAUD)
                                        <p class="label-canceled">{{ __('superadmin::app.sales.orders.index.datagrid.fraud') }}</p>
                                        @break
                                    @default
                                        <span class="text-gray-600 dark:text-gray-300">{{ $row->status }}</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-sm text-end font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ core()->formatPrice((float) $row->base_grand_total) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ __('superadmin::app.sales.orders.index.datagrid.pay-by', ['method' => $payLabels !== '' ? $payLabels : '—']) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $row->channel_name ? $row->channel_name : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $row->seller_name ? $row->seller_name : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">
                                {{ trim((string) $row->full_name) !== '' ? $row->full_name : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $row->customer_email ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 max-w-[220px]">
                                {{ $row->location ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($orderModel)
                                    @include('superadmin::sales.orders.items', ['order' => $orderModel])
                                @else
                                    <span class="text-gray-400">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                @if (bouncer()->hasPermission('sales.orders.view'))
                                    <a
                                        href="{{ route('superadmin.sales.orders.view', $row->id) }}"
                                        class="icon-sort-right rtl:icon-sort-left inline-block cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800"
                                        title="@lang('superadmin::app.sales.orders.index.datagrid.view')"
                                    ></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ $ordersTableColspan }}"
                                class="px-4 py-12 text-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                @lang('superadmin::app.components.datagrid.table.no-records-available')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="mt-2">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    @include('superadmin::customers.customers.index.create')

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customer-search-template"
        >
            <div class="">
                <!-- Search Drawer -->
                <x-superadmin::drawer
                    ref="searchCustomerDrawer"
                    @close="searchTerm = ''; searchedCustomers = [];"
                >
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-3">
                            <p class="py-2 text-xl font-medium dark:text-white">
                                @lang('superadmin::app.sales.orders.index.search-customer.title')
                            </p>

                            <div class="relative w-full">
                                <input
                                    type="text"
                                    id="superadmin-order-customer-search"
                                    name="order_customer_search"
                                    class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"
                                    placeholder="@lang('superadmin::app.sales.orders.index.search-customer.search-by')"
                                    autocomplete="off"
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
                                    @lang('superadmin::app.sales.orders.index.search-customer.empty-title')
                                </p>

                                <p class="text-gray-400">
                                    @lang('superadmin::app.sales.orders.index.search-customer.empty-info')
                                </p>

                                <button
                                    class="secondary-button"
                                    @click="$refs.searchCustomerDrawer.close(); $refs.createCustomerComponent.openModal()"
                                >
                                    @lang('superadmin::app.sales.orders.index.search-customer.create-btn')
                                </button>
                            </div>
                        </div>
                    </x-slot>
                </x-superadmin::drawer>

                <v-create-customer-form
                    ref="createCustomerComponent"
                    @customer-created="createCart"
                ></v-create-customer-form>
            </div>
        </script>

        <script type="module">
            window.app.component('v-customer-search', {
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

                        this.$axios.get("{{ route('superadmin.customers.customers.search') }}", {
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
                        this.$axios.post("{{ route('superadmin.sales.cart.store') }}", {customer_id: customer.id})
                            .then(function(response) {
                                window.location.href = response.data.redirect_url;
                            })
                            .catch((error) => {
                                const message = error.response?.data?.message ?? @json(__('Something went wrong.'));

                                this.$emitter.emit('add-flash', { type: 'error', message });
                            });
                    },
                }
            });
        </script>

        <script type="module">
            setTimeout(() => {
                window.app?.$emitter?.emit('change-datagrid', {
                    src: @json(route('superadmin.sales.orders.customers.index')),
                    available: { records: [{ _export: true }] },
                    applied: {
                        pagination: { page: 1, perPage: 10 },
                        sort: { column: '', order: '' },
                        filters: { columns: [] },
                    },
                });
            }, 0);
        </script>
    @endPushOnce
</x-superadmin::layouts>
