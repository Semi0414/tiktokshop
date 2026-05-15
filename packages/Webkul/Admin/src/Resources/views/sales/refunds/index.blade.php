<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.refund-request')
    </x-slot>

    <x-admin::seller.panel active="refund_request" :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.refund-request')]">
        <form method="get" action="{{ route('admin.sales.refunds.index') }}" class="seller-filter-card mb-4">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.order-id')</label>
                    <input
                        type="text"
                        name="seller_refund_increment_id"
                        value="{{ request('seller_refund_increment_id') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.refund-date')</label>
                    <div class="flex gap-2">
                        <input type="date" name="seller_refund_date_from" value="{{ request('seller_refund_date_from') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="seller_refund_date_to" value="{{ request('seller_refund_date_to') }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.sales.refunds.index.datagrid.status')</label>
                    <select name="seller_refund_state" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected(! request('seller_refund_state') || request('seller_refund_state') === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        @foreach ($refundStateOptions ?? [] as $st)
                            <option value="{{ $st }}" @selected(request('seller_refund_state') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                    <a href="{{ route('admin.sales.refunds.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
                </div>
            </div>
        </form>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.refunds.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.refunds.index')" />
        </div>
    </div>

    <div id="seller-refund-grid">
        @php
            $refundRows = $refundDebugPayload['data'] ?? [];
            $refundMeta = $refundDebugPayload['meta'] ?? [];
        @endphp

        <x-admin::seller.responsive-table class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <x-slot:table>
                <div class="overflow-x-auto p-3">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">ID</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Order ID</th>
                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Refunded Amount</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Billed To</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Refund Date</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($refundRows as $row)
                        <tr>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->id ?? '—' }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->increment_id ?? '—' }}</td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-blue-700 dark:text-blue-400">
                                {{ isset($row->base_grand_total) ? core()->formatBasePrice((float) $row->base_grand_total) : '—' }}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $row->billed_to ?? '—' }}</td>
                            <td class="px-3 py-3 text-sm">
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                    {{ ucfirst((string) ($row->state ?? '—')) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ !empty($row->created_at) ? \Illuminate\Support\Carbon::parse($row->created_at)->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-3 py-3 text-sm">
                                <a href="{{ route('admin.sales.refunds.view', $row->id) }}" class="seller-btn-primary text-xs">
                                    @lang('admin::app.sales.refunds.index.datagrid.view')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                @lang('admin::app.components.datagrid.table.no-records-available')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                    </table>
                </div>
            </x-slot:table>

            <x-slot:cards>
                @forelse ($refundRows as $row)
                    <article class="seller-mobile-card">
                        <div class="seller-mobile-card__header">
                            <p class="seller-mobile-card__title">#{{ $row->increment_id ?? '—' }}</p>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                {{ ucfirst((string) ($row->state ?? '—')) }}
                            </span>
                        </div>
                        <div class="seller-mobile-card__rows">
                            <x-admin::seller.mobile-card-field label="ID">
                                {{ $row->id ?? '—' }}
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.sales.refunds.index.datagrid.refund-date')">
                                {{ !empty($row->created_at) ? \Illuminate\Support\Carbon::parse($row->created_at)->format('Y-m-d H:i') : '—' }}
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Refunded Amount">
                                <span class="font-semibold text-blue-700 dark:text-blue-400">
                                    {{ isset($row->base_grand_total) ? core()->formatBasePrice((float) $row->base_grand_total) : '—' }}
                                </span>
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Billed To">
                                {{ $row->billed_to ?? '—' }}
                            </x-admin::seller.mobile-card-field>
                        </div>
                        <div class="seller-mobile-card__actions">
                            <a href="{{ route('admin.sales.refunds.view', $row->id) }}" class="seller-btn-primary text-xs">
                                @lang('admin::app.sales.refunds.index.datagrid.view')
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                        @lang('admin::app.components.datagrid.table.no-records-available')
                    </p>
                @endforelse
            </x-slot:cards>

            <x-slot:footer>
                <div class="flex items-center justify-between gap-3 border-t border-gray-100 px-3 py-3 text-xs text-gray-600 dark:border-gray-800 dark:text-gray-300">
                    <span>
                        Showing {{ $refundMeta['from'] ?? 0 }} to {{ $refundMeta['to'] ?? 0 }} of {{ $refundMeta['total'] ?? 0 }}
                    </span>
                    <div>
                        {{ ($refundPaginator ?? null)?->links() }}
                    </div>
                </div>
            </x-slot:footer>
        </x-admin::seller.responsive-table>
    </div>
    </x-admin::seller.panel>
</x-admin::layouts>
