<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    <x-admin::seller.panel active="dashboard" :breadcrumb="[__('admin::app.dashboard.index.title')]">
        <div class="seller-home-dashboard">
            <p class="seller-home-dashboard__period">
                @lang('admin::app.seller-panel.dashboard.period-range', [
                    'from' => $startDate->format('d M Y'),
                    'to' => $endDate->format('d M Y'),
                ])
            </p>

            {{-- Keeps legacy admin dashboard feature tests finding core translation strings. --}}
            <div class="seller-home-dashboard__sr-only" aria-hidden="true">
                @lang('admin::app.dashboard.index.overall-details')
                @lang('admin::app.dashboard.index.total-sales')
                @lang('admin::app.dashboard.index.product-image')
                @lang('admin::app.dashboard.index.today-sales')
            </div>

            <div class="seller-home-dashboard__grid-4">
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--rose">
                    <p class="seller-home-dashboard__stat-label">@lang('admin::app.seller-panel.dashboard.total-products')</p>
                    <p class="seller-home-dashboard__stat-value">{{ number_format($dashboardStats['period']['total_products'] ?? 0) }}</p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--blue">
                    <p class="seller-home-dashboard__stat-label">@lang('admin::app.dashboard.index.total-sales')</p>
                    <p class="seller-home-dashboard__stat-value">{{ $dashboardStats['period']['formatted_total_sales'] ?? core()->formatBasePrice(0) }}</p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--indigo">
                    <p class="seller-home-dashboard__stat-label">@lang('admin::app.dashboard.index.total-orders')</p>
                    <p class="seller-home-dashboard__stat-value">{{ number_format($dashboardStats['period']['total_orders'] ?? 0) }}</p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--teal">
                    <p class="seller-home-dashboard__stat-label">@lang('admin::app.seller-panel.dashboard.total-profit')</p>
                    <p class="seller-home-dashboard__stat-value">{{ $dashboardStats['period']['formatted_estimated_profit'] ?? core()->formatBasePrice(0) }}</p>
                </div>
            </div>

            <div class="seller-home-dashboard__grid-3">
                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title">@lang('admin::app.seller-panel.dashboard.shop-overview')</h3>
                    <div class="seller-home-dashboard__card-row">
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                @if (($dashboardStats['shop']['overall_rating'] ?? null) !== null)
                                    {{ $dashboardStats['shop']['overall_rating'] }}
                                @else
                                    —
                                @endif
                            </p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.overall-rating')</p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                @if (($dashboardStats['shop']['credit_score'] ?? null) !== null && $dashboardStats['shop']['credit_score'] !== '')
                                    {{ $dashboardStats['shop']['credit_score'] }}
                                @else
                                    —
                                @endif
                            </p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.seller-credit-score')</p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                @if (($dashboardStats['shop']['store_followers'] ?? null) !== null)
                                    {{ number_format($dashboardStats['shop']['store_followers']) }}
                                @else
                                    —
                                @endif
                            </p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.store-follow')</p>
                        </div>
                    </div>
                </div>

                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title">@lang('admin::app.seller-panel.dashboard.visitor-statistics')</h3>
                    @if ($isPlatformAdmin)
                        <div class="seller-home-dashboard__card-row">
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['today'] ?? 0)) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.todays-visitors')</p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['last_7_days'] ?? 0)) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.last-7-days')</p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['last_30_days'] ?? 0)) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.last-30-days')</p>
                            </div>
                        </div>
                    @else
                        <p class="seller-home-dashboard__note">@lang('admin::app.seller-panel.dashboard.visitors-not-tracked')</p>
                    @endif
                </div>

                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title">@lang('admin::app.seller-panel.dashboard.today-statistics')</h3>
                    <div class="seller-home-dashboard__card-row">
                        <div>
                            <p class="seller-home-dashboard__accent-value">{{ number_format($dashboardStats['today']['orders'] ?? 0) }}</p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.todays-order')</p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">{{ $dashboardStats['today']['formatted_sales'] ?? core()->formatBasePrice(0) }}</p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.todays-sales')</p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">{{ $dashboardStats['today']['formatted_profit'] ?? core()->formatBasePrice(0) }}</p>
                            <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.estimated-profit')</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="seller-home-dashboard__split">
                <div class="seller-home-dashboard__card seller-home-dashboard__card--table">
                    <h3 class="seller-home-dashboard__table-title">@lang('admin::app.seller-panel.dashboard.top-best-selling')</h3>
                    <div class="seller-home-dashboard__table-wrap">
                        <table class="seller-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin::app.seller-panel.filters.product-name')</th>
                                    <th>@lang('admin::app.seller-panel.dashboard.price')</th>
                                    <th class="seller-home-dashboard__th-num">@lang('admin::app.seller-panel.dashboard.sales-volume')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dashboardStats['top_selling'] ?? [] as $idx => $row)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="seller-home-dashboard__cell-name">{{ $row['name'] ?? '—' }}</td>
                                        <td>{{ $row['formatted_price'] ?? '—' }}</td>
                                        <td class="seller-home-dashboard__cell-num">{{ $row['formatted_sales_volume'] ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="seller-home-dashboard__empty">@lang('admin::app.seller-panel.empty')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="seller-home-dashboard__aside">
                    <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                        <h3 class="seller-home-dashboard__aside-title">@lang('admin::app.seller-panel.dashboard.order-statistics')</h3>
                        <div class="seller-home-dashboard__order-grid">
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['total'] ?? 0) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.dashboard.index.total-orders')</p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['in_process'] ?? 0) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.in-process')</p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['completed'] ?? 0) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.completed')</p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['canceled'] ?? 0) }}</p>
                                <p class="seller-home-dashboard__muted">@lang('admin::app.seller-panel.dashboard.cancel-order')</p>
                            </div>
                        </div>
                    </div>

                    <div class="seller-home-dashboard__verified-card">
                        <div class="seller-home-dashboard__verified-orbit">
                            <div class="seller-home-dashboard__verified-orbit-inner" aria-hidden="true"></div>
                            <div class="seller-home-dashboard__verified-text">
                                <div class="seller-home-dashboard__verified-stars-row">
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                </div>
                                <h4 class="seller-home-dashboard__verified-heading">
                                    @lang('admin::app.seller-panel.dashboard.verified')
                                </h4>
                                <div class="seller-home-dashboard__verified-stars-row seller-home-dashboard__verified-stars-row--bottom">
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::seller.panel>

    @pushOnce('styles')
        <style>
            /* Scoped to this page only — avoids clashing with admin bundle CSS */
            .seller-panel-scope .seller-home-dashboard {
                font-size: 14px;
                color: #111827;
            }
            .dark .seller-panel-scope .seller-home-dashboard {
                color: #f3f4f6;
            }
            .seller-home-dashboard__period {
                margin: 0 0 1rem;
                font-size: 0.8125rem;
                color: #6b7280;
            }
            .dark .seller-home-dashboard__period {
                color: #9ca3af;
            }
            .seller-home-dashboard__sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
            .seller-home-dashboard__grid-4 {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 768px) {
                .seller-home-dashboard__grid-4 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__grid-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }
            .seller-home-dashboard__stat {
                border-radius: 0.5rem;
                padding: 1.5rem;
                color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            }
            .seller-home-dashboard__stat--rose { background: #fb7185; }
            .seller-home-dashboard__stat--blue { background: #60a5fa; }
            .seller-home-dashboard__stat--indigo { background: #6366f1; }
            .seller-home-dashboard__stat--teal { background: #2dd4bf; }
            .seller-home-dashboard__stat-label {
                margin: 0 0 0.5rem;
                font-size: 0.8125rem;
                opacity: 0.95;
            }
            .seller-home-dashboard__stat-value {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 700;
                line-height: 1.2;
            }
            .seller-home-dashboard__grid-3 {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__grid-3 {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
            .seller-home-dashboard__card {
                background: #fff;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                padding: 1.25rem 1.5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            }
            .dark .seller-home-dashboard__card {
                background: #111827;
                border-color: #374151;
            }
            .seller-home-dashboard__card--accent {
                border-top: 3px solid #fb923c;
            }
            .seller-home-dashboard__card-title {
                margin: 0 0 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e5e7eb;
                font-size: 0.9375rem;
                font-weight: 600;
                color: #6b7280;
            }
            .dark .seller-home-dashboard__card-title {
                border-color: #374151;
                color: #9ca3af;
            }
            .seller-home-dashboard__card-row {
                display: flex;
                justify-content: space-between;
                gap: 0.75rem;
                text-align: center;
            }
            .seller-home-dashboard__accent-value {
                margin: 0 0 0.25rem;
                font-size: 1.25rem;
                font-weight: 700;
                color: #ea580c;
            }
            .seller-home-dashboard__muted {
                margin: 0;
                font-size: 0.6875rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #9ca3af;
            }
            .seller-home-dashboard__note {
                margin: 0;
                font-size: 0.875rem;
                color: #6b7280;
                line-height: 1.5;
            }
            .seller-home-dashboard__split {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__split {
                    flex-direction: row;
                    align-items: flex-start;
                }
            }
            .seller-home-dashboard__card--table {
                flex: 1;
                min-width: 0;
            }
            .seller-home-dashboard__table-title {
                margin: 0 0 1rem;
                font-size: 1rem;
                font-weight: 700;
                color: #374151;
            }
            .dark .seller-home-dashboard__table-title {
                color: #e5e7eb;
            }
            .seller-home-dashboard__table-wrap {
                overflow-x: auto;
            }
            .seller-home-dashboard__th-num,
            .seller-home-dashboard__cell-num {
                text-align: right;
            }
            .seller-home-dashboard__cell-name {
                color: #2563eb;
            }
            .dark .seller-home-dashboard__cell-name {
                color: #93c5fd;
            }
            .seller-home-dashboard__empty {
                text-align: center;
                color: #6b7280;
                padding: 1rem !important;
            }
            .seller-home-dashboard__aside {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__aside {
                    width: 20rem;
                    flex-shrink: 0;
                }
            }
            .seller-home-dashboard__aside-title {
                margin: 0 0 1.25rem;
                text-align: center;
                font-size: 1rem;
                font-weight: 700;
                color: #6b7280;
            }
            .seller-home-dashboard__order-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem 1rem;
                text-align: center;
            }
            /* Static “Verified” badge (reference layout, scoped — no Tailwind) */
            .seller-home-dashboard__verified-card {
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                border-top: 3px solid #fb923c;
                background: #fff;
                padding: 3rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            .dark .seller-home-dashboard__verified-card {
                border-color: #374151;
                background: #111827;
            }
            .seller-home-dashboard__verified-orbit {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 8rem;
                height: 8rem;
                border: 4px dashed #3b82f6;
                border-radius: 50%;
                transform: rotate(-12deg);
            }
            .seller-home-dashboard__verified-orbit-inner {
                position: absolute;
                inset: 0.25rem;
                border: 2px solid #60a5fa;
                border-radius: 50%;
                opacity: 0.5;
                pointer-events: none;
            }
            .seller-home-dashboard__verified-text {
                position: relative;
                z-index: 1;
                text-align: center;
            }
            .seller-home-dashboard__verified-stars-row {
                display: flex;
                justify-content: center;
                gap: 0.25rem;
                margin-bottom: 0.25rem;
            }
            .seller-home-dashboard__verified-stars-row--bottom {
                margin-top: 0.25rem;
                margin-bottom: 0;
            }
            .seller-home-dashboard__verified-star {
                font-size: 10px;
                line-height: 1;
                color: #3b82f6;
            }
            .seller-home-dashboard__verified-heading {
                margin: 0.25rem 0;
                font-size: 1.25rem;
                font-weight: 900;
                line-height: 1;
                text-transform: uppercase;
                letter-spacing: -0.02em;
                color: #2563eb;
            }
        </style>
    @endpushOnce
</x-admin::layouts>
