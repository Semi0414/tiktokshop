<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.sales.index.title')
    </x-slot>

    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="pt-1.5 text-xl font-bold leading-6 text-gray-800 dark:text-white">
            @lang('admin::app.reporting.sales.index.title')
        </p>
    </div>

    <form method="get" action="{{ route('admin.reporting.sales.index') }}" class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="grid gap-3 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.reporting.sales.index.start-date')</label>
                <input type="date" name="start" value="{{ request('start', $startDate->format('Y-m-d')) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.reporting.sales.index.end-date')</label>
                <input type="date" name="end" value="{{ request('end', $endDate->format('Y-m-d')) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="primary-button">Apply</button>
                <a href="{{ route('admin.reporting.sales.index') }}" class="secondary-button">Reset</a>
            </div>
        </div>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Sales</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['total_sales'] ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Average Sales</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['average_sales'] ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['total_orders'] ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Refunds</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['refunds'] ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tax Collected</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['tax_collected'] ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Shipping Collected</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $staticSummary['shipping_collected'] ?? '—' }}</p>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">Total Sales Over Time</p>
            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                @foreach (($totalSalesTable['records'] ?? []) as $row)
                    <div class="flex items-center justify-between rounded border border-gray-100 px-3 py-2 dark:border-gray-800">
                        <span>{{ $row['label'] ?? '—' }}</span>
                        <span>{{ $row['formatted_total'] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">Average Sales Over Time</p>
            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                @foreach (($averageSalesTable['records'] ?? []) as $row)
                    <div class="flex items-center justify-between rounded border border-gray-100 px-3 py-2 dark:border-gray-800">
                        <span>{{ $row['label'] ?? '—' }}</span>
                        <span>{{ $row['formatted_total'] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">Total Orders Over Time</p>
            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                @foreach (($totalOrdersTable['records'] ?? []) as $row)
                    <div class="flex items-center justify-between rounded border border-gray-100 px-3 py-2 dark:border-gray-800">
                        <span>{{ $row['label'] ?? '—' }}</span>
                        <span>{{ $row['count'] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">Refunds Over Time</p>
            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                @foreach (($refundsTable['records'] ?? []) as $row)
                    <div class="flex items-center justify-between rounded border border-gray-100 px-3 py-2 dark:border-gray-800">
                        <span>{{ $row['label'] ?? '—' }}</span>
                        <span>{{ $row['formatted_total'] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin::layouts>
