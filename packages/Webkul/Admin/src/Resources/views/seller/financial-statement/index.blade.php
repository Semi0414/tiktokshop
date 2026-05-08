<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.components.layouts.sidebar.financial-statement')
    </x-slot>

    <x-admin::seller.panel active="financial_statement" :breadcrumb="[__('admin::app.components.layouts.sidebar.financial-statement')]">
        {{-- Period filters --}}
        <div class="seller-filter-card mb-4">
            <div class="flex flex-wrap items-center gap-2">
                @foreach (['yesterday' => __('admin::app.seller-panel.periods.yesterday'), 'today' => __('admin::app.seller-panel.periods.today'), 'week' => __('admin::app.seller-panel.periods.this-week'), 'month' => __('admin::app.seller-panel.periods.this-month'), 'all' => __('admin::app.seller-panel.periods.all')] as $key => $label)
                    <a
                        href="{{ route('admin.seller.financial-statement.index', ['period' => $key]) }}"
                        class="rounded-md border px-3 py-1.5 text-sm font-medium transition {{ $period === $key ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-300' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="mb-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="seller-summary-card border-blue-100 bg-gradient-to-b from-blue-50 to-white">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($pendingAmount) }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.seller-panel.financial.pending-amount')</p>
            </div>
            <div class="seller-summary-card border-sky-100 bg-gradient-to-b from-sky-50 to-white">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($totalSales) }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.seller-panel.financial.total-sales')</p>
            </div>
            <div class="seller-summary-card border-teal-100 bg-gradient-to-b from-teal-50 to-white">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($totalProfit) }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.seller-panel.financial.total-profit')</p>
            </div>
            <div class="seller-summary-card border-gray-200 bg-gray-50">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.seller-panel.financial.total-orders')</p>
            </div>
        </div>

        {{-- Daily table --}}
        <div class="seller-filter-card overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="seller-data-table !border-0 table-fixed">
                    <colgroup>
                        <col class="w-1/5">
                        <col class="w-1/5">
                        <col class="w-1/5">
                        <col class="w-1/5">
                        <col class="w-1/5">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-left">@lang('admin::app.seller-panel.table.date')</th>
                            <th class="text-center">@lang('admin::app.seller-panel.table.total-orders')</th>
                            <th class="text-right">@lang('admin::app.seller-panel.financial.total-sales')</th>
                            <th class="text-right">@lang('admin::app.seller-panel.financial.pending-amount')</th>
                            <th class="text-right">@lang('admin::app.seller-panel.table.profit')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyRows as $row)
                            <tr>
                                <td class="whitespace-nowrap text-left">{{ $row->date }}</td>
                                <td class="text-center">{{ $row->orders_count }}</td>
                                <td class="text-right">{{ core()->formatPrice($row->sales) }}</td>
                                <td class="text-right">{{ core()->formatPrice($row->pending_amount) }}</td>
                                <td class="text-right">{{ core()->formatPrice($row->profit) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    @lang('admin::app.seller-panel.empty')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-center gap-2 border-t border-gray-100 px-4 py-3 text-sm text-gray-600">
                <span>@lang('admin::app.seller-panel.pagination-total', ['n' => $dailyRows->count()])</span>
            </div>
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>
