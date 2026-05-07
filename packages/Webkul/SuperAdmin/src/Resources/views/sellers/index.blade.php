<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.sellers.index.title')
    </x-slot>

    @php
        $sortUrl = function (string $column) use ($sort, $order) {
            if ($sort === $column) {
                $nextOrder = $order === 'asc' ? 'desc' : 'asc';
            } else {
                $nextOrder = 'asc';
            }

            return route('superadmin.sellers.index', array_merge(
                request()->except(['sort', 'order', 'page']),
                ['sort' => $column, 'order' => $nextOrder]
            ));
        };
    @endphp

    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.sellers.index.title')
            </p>

            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2">
                <form
                    method="get"
                    action="{{ route('superadmin.sellers.index') }}"
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

                @if (bouncer()->hasPermission('sellers.all.create'))
                    @include('superadmin::sellers.index.create')

                    <v-create-seller-form
                        ref="createSellerComponent"
                        @seller-created="window.location.reload()"
                    ></v-create-seller-form>

                    <button
                        class="primary-button"
                        type="button"
                        @click="$refs.createSellerComponent.openModal()"
                    >
                        @lang('superadmin::app.sellers.index.create.create-btn')
                    </button>
                @endif
            </div>
        </div>

        @php
            $sellerTableColspan = \Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code') ? 10 : 9;
        @endphp

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-950">
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="{{ $sortUrl('name') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.customers.customers.index.datagrid.name')
                                @if ($sort === 'name')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="{{ $sortUrl('email') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.customers.customers.index.datagrid.email')
                                @if ($sort === 'email')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code'))
                            <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                @lang('superadmin::app.sellers.index.datagrid.referral-code')
                            </th>
                        @endif
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            @lang('superadmin::app.customers.customers.index.datagrid.group')
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="{{ $sortUrl('status') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.customers.customers.index.datagrid.status')
                                @if ($sort === 'status')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.sellers.index.datagrid.balance')
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.customers.customers.index.datagrid.order-count')
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            @lang('superadmin::app.customers.customers.index.datagrid.revenue')
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 dark:hover:text-white">
                                @lang('superadmin::app.customers.customers.index.datagrid.id')
                                @if ($sort === 'id')
                                    <span class="text-xs">({{ $order === 'asc' ? '↑' : '↓' }})</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            @lang('superadmin::app.components.datagrid.table.actions')
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($sellers as $seller)
                        @php
                            $rev = $revenueBySellerId[$seller->id] ?? 0;
                            $ordersCount = isset($seller->seller_orders_count) ? (int) $seller->seller_orders_count : null;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $seller->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $seller->email }}
                            </td>
                            @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code'))
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 font-mono">
                                    {{ $seller->referral_code ?: '—' }}
                                </td>
                            @endif
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ optional($seller->role)->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ((int) $seller->status === 1)
                                    <span class="label-active">
                                        @lang('superadmin::app.customers.customers.index.datagrid.active')
                                    </span>
                                @else
                                    <span class="label-canceled">
                                        @lang('superadmin::app.customers.customers.index.datagrid.inactive')
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-900 dark:text-white whitespace-nowrap">
                                {{ core()->formatPrice((float) ($seller->wallet_balance ?? 0)) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-600 dark:text-gray-300">
                                {{ $ordersCount !== null ? $ordersCount : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-end font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ core()->formatPrice($rev) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-600 dark:text-gray-300">
                                {{ $seller->id }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="inline-flex items-center gap-1">
                                    <a
                                        href="{{ route('superadmin.sellers.login_as_seller', $seller->id) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="icon-login cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800"
                                        title="@lang('superadmin::app.sellers.index.datagrid.login-as-seller')"
                                    ></a>
                                    <a
                                        href="{{ route('superadmin.sellers.view', $seller->id) }}"
                                        class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800"
                                        title="@lang('superadmin::app.customers.customers.index.datagrid.view')"
                                    ></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ $sellerTableColspan }}"
                                class="px-4 py-12 text-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                @lang('superadmin::app.components.datagrid.table.no-records-available')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sellers->hasPages())
            <div class="mt-2">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>
</x-superadmin::layouts>
