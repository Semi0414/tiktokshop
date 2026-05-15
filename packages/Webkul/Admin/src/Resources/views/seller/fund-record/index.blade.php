<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.components.layouts.sidebar.fund-record')
    </x-slot>

    <x-admin::seller.panel active="fund_record" :breadcrumb="[__('admin::app.components.layouts.sidebar.fund-record')]">
        @php $m = $metrics ?? []; @endphp

        <div class="mb-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            <div class="seller-filter-card flex flex-col gap-1 border-l-4 border-amber-400">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-total-pending-income')</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($m['total_pending_income'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-400">@lang('admin::app.seller.fund-record.pending-income-hint')</p>
                <ul class="mt-1 space-y-0.5 border-t border-gray-100 pt-2 text-[10px] text-gray-500 dark:text-gray-400">
                    <li>@lang('admin::app.seller.fund-record.breakdown-pending-order'): {{ core()->formatPrice($m['pending_order_amount'] ?? 0) }}</li>
                    <li>@lang('admin::app.seller.fund-record.breakdown-in-progress'): {{ core()->formatPrice($m['in_progress_product_amount'] ?? 0) }}</li>
                    <li>@lang('admin::app.seller.fund-record.breakdown-pending-commission'): {{ core()->formatPrice($m['pending_commission_amount'] ?? 0) }}</li>
                </ul>
            </div>

            <div class="seller-filter-card flex flex-col gap-1 border-l-4 border-emerald-500">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-overall-total-income')</p>
                <p class="text-xl font-bold text-emerald-700 dark:text-emerald-400">{{ core()->formatPrice($m['overall_total_income'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-400">@lang('admin::app.seller.fund-record.credit-label')</p>
            </div>

            <div class="seller-filter-card flex flex-col gap-1 border-l-4 border-green-600">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-total-profit-earned')</p>
                <p class="text-xl font-bold text-green-800 dark:text-green-300">{{ core()->formatPrice($m['total_profit_earned'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-400">@lang('admin::app.seller.fund-record.filter-commission')</p>
            </div>

            <div class="seller-filter-card flex flex-col gap-1">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-total-orders')</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format((int) ($m['total_orders'] ?? 0)) }}</p>
            </div>

            <div class="seller-filter-card flex flex-col gap-1">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-total-pending-orders')</p>
                <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ number_format((int) ($m['total_pending_orders'] ?? 0)) }}</p>
            </div>

            <div class="seller-filter-card flex flex-col gap-1">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.seller.fund-record.card-total-in-progress-orders')</p>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format((int) ($m['total_in_progress_orders'] ?? 0)) }}</p>
            </div>
        </div>

        <form method="get" action="{{ route('admin.seller.fund-record.index') }}" class="seller-filter-card mb-4">
            <div class="grid gap-3 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.order-type')</label>
                    <select name="order_type" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected($orderType === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="deposit" @selected($orderType === 'deposit')>@lang('admin::app.seller.fund-record.filter-deposit')</option>
                        <option value="withdraw" @selected($orderType === 'withdraw')>@lang('admin::app.seller.fund-record.filter-withdraw')</option>
                        <option value="purchase" @selected($orderType === 'purchase')>@lang('admin::app.seller.fund-record.filter-purchase')</option>
                        <option value="commission" @selected($orderType === 'commission')>@lang('admin::app.seller.fund-record.filter-commission')</option>
                        <option value="credit" @selected($orderType === 'credit')>@lang('admin::app.seller.fund-record.credit-label')</option>
                        <option value="debit" @selected($orderType === 'debit')>@lang('admin::app.seller.fund-record.debit-label')</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.order-time')</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-md border border-gray-200 px-2 py-2 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                    <a href="{{ route('admin.seller.fund-record.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
                </div>
            </div>
        </form>

        <x-admin::seller.responsive-table class="seller-filter-card overflow-hidden p-0">
            <x-slot:table>
                <div class="overflow-x-auto">
                    <table class="seller-data-table !border-0">
                    <thead>
                        <tr>
                            <th>@lang('admin::app.seller.fund-record.col-order-type')</th>
                            <th>@lang('admin::app.seller.fund-record.col-serial')</th>
                            <th>@lang('admin::app.seller.fund-record.col-amount-change')</th>
                            <th>@lang('admin::app.seller.fund-record.col-before')</th>
                            <th>@lang('admin::app.seller.fund-record.col-after')</th>
                            <th>@lang('admin::app.seller.fund-record.col-time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $row)
                            @php
                                $walletMeta = \Webkul\Admin\Support\SellerWalletTransactionDisplay::meta($row, true);
                            @endphp
                            <tr>
                                <td>{{ $walletMeta['type_label'] }}</td>
                                <td class="max-w-[200px] truncate font-mono text-xs">{{ 'ff'.str_pad((string) $row->id, 24, '0', STR_PAD_LEFT) }}</td>
                                <td class="{{ $walletMeta['amount_class'] }}">
                                    {{ $walletMeta['amount_sign'] }}{{ core()->formatPrice($row->amount) }}
                                </td>
                                <td>{{ core()->formatPrice($row->balance_before ?? 0) }}</td>
                                <td>{{ core()->formatPrice($row->balance_after ?? 0) }}</td>
                                <td class="whitespace-nowrap">{{ $row->created_at?->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500">@lang('admin::app.seller.fund-record.empty')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </x-slot:table>

            <x-slot:cards>
                @forelse ($transactions as $row)
                    @php
                        $walletMeta = \Webkul\Admin\Support\SellerWalletTransactionDisplay::meta($row, true);
                    @endphp
                    <article class="seller-mobile-card">
                        <div class="seller-mobile-card__header">
                            <p class="seller-mobile-card__title">{{ $walletMeta['type_label'] }}</p>
                            <span class="text-xs text-gray-500">{{ $row->created_at?->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="seller-mobile-card__rows">
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.fund-record.col-serial')">
                                <span class="font-mono text-xs">{{ 'ff'.str_pad((string) $row->id, 24, '0', STR_PAD_LEFT) }}</span>
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.fund-record.col-amount-change')">
                                <span class="{{ $walletMeta['amount_class'] }}">{{ $walletMeta['amount_sign'] }}{{ core()->formatPrice($row->amount) }}</span>
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.fund-record.col-before')">
                                {{ core()->formatPrice($row->balance_before ?? 0) }}
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.fund-record.col-after')">
                                {{ core()->formatPrice($row->balance_after ?? 0) }}
                            </x-admin::seller.mobile-card-field>
                        </div>
                    </article>
                @empty
                    <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500">@lang('admin::app.seller.fund-record.empty')</p>
                @endforelse
            </x-slot:cards>

            @if ($transactions->hasPages())
                <x-slot:footer>
                    <div class="border-t border-gray-100 px-4 py-3 text-sm text-gray-600">
                        {{ $transactions->links() }}
                    </div>
                </x-slot:footer>
            @endif
        </x-admin::seller.responsive-table>
    </x-admin::seller.panel>
</x-admin::layouts>
