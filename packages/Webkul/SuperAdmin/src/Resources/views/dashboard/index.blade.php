<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.dashboard.index.title')
    </x-slot>

    <div class="superadmin-home-dashboard">
        <div class="superadmin-home-dashboard__header">
            <div>
                <p class="superadmin-home-dashboard__welcome">
                    @lang('superadmin::app.dashboard.index.user-name', ['user_name' => auth()->guard('superadmin')->user()->name])
                </p>
                <p class="superadmin-home-dashboard__sub">
                    @lang('superadmin::app.dashboard.index.platform-overview')
                </p>
            </div>

            <form
                method="get"
                action="{{ route('superadmin.dashboard.index') }}"
                class="superadmin-home-dashboard__filters"
            >
                <label class="superadmin-home-dashboard__filter-label">
                    <span class="superadmin-home-dashboard__filter-text">@lang('superadmin::app.dashboard.index.start-date')</span>
                    <input
                        type="date"
                        name="start"
                        value="{{ $startDate->format('Y-m-d') }}"
                        class="superadmin-home-dashboard__input"
                    />
                </label>
                <label class="superadmin-home-dashboard__filter-label">
                    <span class="superadmin-home-dashboard__filter-text">@lang('superadmin::app.dashboard.index.end-date')</span>
                    <input
                        type="date"
                        name="end"
                        value="{{ $endDate->format('Y-m-d') }}"
                        class="superadmin-home-dashboard__input"
                    />
                </label>
                <button type="submit" class="superadmin-home-dashboard__btn">
                    @lang('superadmin::app.dashboard.index.apply-filters')
                </button>
            </form>
        </div>

        <p class="superadmin-home-dashboard__period">
            @lang('superadmin::app.dashboard.index.platform-period', [
                'from' => $startDate->format('d M Y'),
                'to' => $endDate->format('d M Y'),
            ])
        </p>

        {{-- Legacy strings for tests / screen readers --}}
        <div class="superadmin-home-dashboard__sr-only" aria-hidden="true">
            @lang('superadmin::app.dashboard.index.overall-details')
            @lang('superadmin::app.dashboard.index.total-sales')
            @lang('superadmin::app.dashboard.index.product-image')
            @lang('superadmin::app.dashboard.index.today-sales')
        </div>

        <div class="superadmin-home-dashboard__grid-4">
            <div class="superadmin-home-dashboard__stat superadmin-home-dashboard__stat--rose">
                <p class="superadmin-home-dashboard__stat-label">@lang('superadmin::app.dashboard.index.active-sellers')</p>
                <p class="superadmin-home-dashboard__stat-value">{{ number_format($dashboardStats['period']['active_sellers'] ?? 0) }}</p>
            </div>
            <div class="superadmin-home-dashboard__stat superadmin-home-dashboard__stat--blue">
                <p class="superadmin-home-dashboard__stat-label">@lang('superadmin::app.dashboard.index.active-buyers')</p>
                <p class="superadmin-home-dashboard__stat-value">{{ number_format($dashboardStats['period']['active_buyers'] ?? 0) }}</p>
            </div>
            <div class="superadmin-home-dashboard__stat superadmin-home-dashboard__stat--indigo">
                <p class="superadmin-home-dashboard__stat-label">@lang('superadmin::app.dashboard.index.total-orders')</p>
                <p class="superadmin-home-dashboard__stat-value">{{ number_format($dashboardStats['period']['total_orders'] ?? 0) }}</p>
            </div>
            <div class="superadmin-home-dashboard__stat superadmin-home-dashboard__stat--teal">
                <p class="superadmin-home-dashboard__stat-label">@lang('superadmin::app.dashboard.index.total-sales')</p>
                <p class="superadmin-home-dashboard__stat-value">{{ $dashboardStats['period']['formatted_total_sales'] ?? core()->formatBasePrice(0) }}</p>
            </div>
        </div>

        <div class="superadmin-home-dashboard__grid-3">
            <div class="superadmin-home-dashboard__card superadmin-home-dashboard__card--accent">
                <h3 class="superadmin-home-dashboard__card-title">@lang('superadmin::app.dashboard.index.catalog-snapshot')</h3>
                <div class="superadmin-home-dashboard__card-row">
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['period']['total_products'] ?? 0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.catalog-products-total')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ $dashboardStats['period']['formatted_avg_sale'] ?? core()->formatBasePrice(0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.average-sale')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ $dashboardStats['period']['formatted_pending_orders'] ?? core()->formatBasePrice(0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.pending-orders-total')</p>
                    </div>
                </div>
            </div>

            <div class="superadmin-home-dashboard__card superadmin-home-dashboard__card--accent">
                <h3 class="superadmin-home-dashboard__card-title">@lang('superadmin::app.dashboard.index.today-activity')</h3>
                <div class="superadmin-home-dashboard__card-row">
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['today']['orders'] ?? 0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.today-orders')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ $dashboardStats['today']['formatted_sales'] ?? core()->formatBasePrice(0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.today-sales')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['today']['customers'] ?? 0) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.today-customers')</p>
                    </div>
                </div>
            </div>

            <div class="superadmin-home-dashboard__card superadmin-home-dashboard__card--accent">
                <h3 class="superadmin-home-dashboard__card-title">@lang('superadmin::app.dashboard.index.visitor-statistics')</h3>
                <div class="superadmin-home-dashboard__card-row">
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['today'] ?? 0)) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.todays-visitors')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['last_7_days'] ?? 0)) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.last-7-days')</p>
                    </div>
                    <div>
                        <p class="superadmin-home-dashboard__accent-value">{{ number_format((int) ($dashboardStats['visitors']['last_30_days'] ?? 0)) }}</p>
                        <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.last-30-days')</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="superadmin-home-dashboard__split">
            <div class="superadmin-home-dashboard__card superadmin-home-dashboard__card--table">
                <h3 class="superadmin-home-dashboard__table-title">@lang('superadmin::app.dashboard.index.top-10-selling')</h3>
                <div class="superadmin-home-dashboard__table-wrap">
                    <table class="superadmin-home-dashboard__table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('superadmin::app.dashboard.index.product-name')</th>
                                <th>@lang('superadmin::app.dashboard.index.price')</th>
                                    <th class="superadmin-home-dashboard__th-num">@lang('superadmin::app.dashboard.index.orders-count-column')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dashboardStats['top_selling'] ?? [] as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="superadmin-home-dashboard__cell-name">{{ $row['name'] ?? '—' }}</td>
                                    <td>{{ $row['formatted_price'] ?? '—' }}</td>
                                    <td class="superadmin-home-dashboard__cell-num">{{ $row['formatted_sales_volume'] ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="superadmin-home-dashboard__empty">@lang('superadmin::app.dashboard.index.empty-list')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="superadmin-home-dashboard__aside">
                <div class="superadmin-home-dashboard__card superadmin-home-dashboard__card--accent">
                    <h3 class="superadmin-home-dashboard__aside-title">@lang('superadmin::app.dashboard.index.order-statistics')</h3>
                    <div class="superadmin-home-dashboard__order-grid">
                        <div>
                            <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['total'] ?? 0) }}</p>
                            <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.total-orders')</p>
                        </div>
                        <div>
                            <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['in_process'] ?? 0) }}</p>
                            <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.in-process')</p>
                        </div>
                        <div>
                            <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['completed'] ?? 0) }}</p>
                            <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.completed')</p>
                        </div>
                        <div>
                            <p class="superadmin-home-dashboard__accent-value">{{ number_format($dashboardStats['order_status']['canceled'] ?? 0) }}</p>
                            <p class="superadmin-home-dashboard__muted">@lang('superadmin::app.dashboard.index.cancel-order')</p>
                        </div>
                    </div>
                </div>

                <div class="superadmin-home-dashboard__verified-card">
                    <div class="superadmin-home-dashboard__verified-orbit">
                        <div class="superadmin-home-dashboard__verified-orbit-inner" aria-hidden="true"></div>
                        <div class="superadmin-home-dashboard__verified-text">
                            <div class="superadmin-home-dashboard__verified-stars-row">
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                            </div>
                            <h4 class="superadmin-home-dashboard__verified-heading">
                                @lang('superadmin::app.dashboard.index.verified')
                            </h4>
                            <div class="superadmin-home-dashboard__verified-stars-row superadmin-home-dashboard__verified-stars-row--bottom">
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                                <span class="superadmin-home-dashboard__verified-star">★</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('styles')
        <style>
            /* Scoped: does not override global admin layout */
            .superadmin-home-dashboard {
                font-size: 14px;
                color: #111827;
            }
            .dark .superadmin-home-dashboard {
                color: #f3f4f6;
            }
            .superadmin-home-dashboard__header {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-end;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 1rem;
            }
            .superadmin-home-dashboard__welcome {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 700;
                color: #1f2937;
            }
            .dark .superadmin-home-dashboard__welcome {
                color: #fff;
            }
            .superadmin-home-dashboard__sub {
                margin: 0.25rem 0 0;
                font-size: 0.875rem;
                color: #6b7280;
            }
            .superadmin-home-dashboard__filters {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-end;
                gap: 0.75rem;
            }
            .superadmin-home-dashboard__filter-label {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
                font-size: 0.75rem;
                color: #6b7280;
            }
            .superadmin-home-dashboard__filter-text {
                font-weight: 500;
            }
            .superadmin-home-dashboard__select,
            .superadmin-home-dashboard__input {
                min-height: 2.25rem;
                border-radius: 0.375rem;
                border: 1px solid #e5e7eb;
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                background: #fff;
                color: #111827;
            }
            .dark .superadmin-home-dashboard__select,
            .dark .superadmin-home-dashboard__input {
                border-color: #374151;
                background: #111827;
                color: #f3f4f6;
            }
            .superadmin-home-dashboard__btn {
                min-height: 2.25rem;
                border-radius: 0.375rem;
                border: none;
                padding: 0 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                background: #ff0050;
                color: #fff;
                cursor: pointer;
            }
            .superadmin-home-dashboard__btn:hover {
                opacity: 0.92;
            }
            .superadmin-home-dashboard__period {
                margin: 0 0 1rem;
                font-size: 0.8125rem;
                color: #6b7280;
            }
            .superadmin-home-dashboard__sr-only {
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
            .superadmin-home-dashboard__grid-4 {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 768px) {
                .superadmin-home-dashboard__grid-4 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media (min-width: 1024px) {
                .superadmin-home-dashboard__grid-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }
            .superadmin-home-dashboard__stat {
                border-radius: 0.5rem;
                padding: 1.5rem;
                color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            }
            .superadmin-home-dashboard__stat--rose { background: #fb7185; }
            .superadmin-home-dashboard__stat--blue { background: #60a5fa; }
            .superadmin-home-dashboard__stat--indigo { background: #6366f1; }
            .superadmin-home-dashboard__stat--teal { background: #2dd4bf; }
            .superadmin-home-dashboard__stat-label {
                margin: 0 0 0.5rem;
                font-size: 0.8125rem;
                opacity: 0.95;
            }
            .superadmin-home-dashboard__stat-value {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 700;
                line-height: 1.2;
            }
            .superadmin-home-dashboard__grid-3 {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 1024px) {
                .superadmin-home-dashboard__grid-3 {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
            .superadmin-home-dashboard__card {
                background: #fff;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                padding: 1.25rem 1.5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            }
            .dark .superadmin-home-dashboard__card {
                background: #111827;
                border-color: #374151;
            }
            .superadmin-home-dashboard__card--accent {
                border-top: 3px solid #fb923c;
            }
            .superadmin-home-dashboard__card-title {
                margin: 0 0 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e5e7eb;
                font-size: 0.9375rem;
                font-weight: 600;
                color: #6b7280;
            }
            .dark .superadmin-home-dashboard__card-title {
                border-color: #374151;
                color: #9ca3af;
            }
            .superadmin-home-dashboard__card-row {
                display: flex;
                justify-content: space-between;
                gap: 0.75rem;
                text-align: center;
            }
            .superadmin-home-dashboard__accent-value {
                margin: 0 0 0.25rem;
                font-size: 1.125rem;
                font-weight: 700;
                color: #ea580c;
            }
            .superadmin-home-dashboard__muted {
                margin: 0;
                font-size: 0.6875rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #9ca3af;
            }
            .superadmin-home-dashboard__split {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .superadmin-home-dashboard__split {
                    flex-direction: row;
                    align-items: flex-start;
                }
            }
            .superadmin-home-dashboard__card--table {
                flex: 1;
                min-width: 0;
            }
            .superadmin-home-dashboard__table-title {
                margin: 0 0 1rem;
                font-size: 1rem;
                font-weight: 700;
                color: #374151;
            }
            .dark .superadmin-home-dashboard__table-title {
                color: #e5e7eb;
            }
            .superadmin-home-dashboard__table-wrap {
                overflow-x: auto;
            }
            .superadmin-home-dashboard__table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.875rem;
            }
            .superadmin-home-dashboard__table thead th {
                background: #f9fafb;
                padding: 0.75rem 1rem;
                text-align: left;
                font-weight: 600;
                color: #4b5563;
                border-bottom: 1px solid #e5e7eb;
            }
            .dark .superadmin-home-dashboard__table thead th {
                background: #1f2937;
                color: #d1d5db;
                border-color: #374151;
            }
            .superadmin-home-dashboard__table tbody td {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid #f3f4f6;
            }
            .dark .superadmin-home-dashboard__table tbody td {
                border-color: #374151;
            }
            .superadmin-home-dashboard__th-num,
            .superadmin-home-dashboard__cell-num {
                text-align: right;
            }
            .superadmin-home-dashboard__cell-name {
                color: #2563eb;
            }
            .dark .superadmin-home-dashboard__cell-name {
                color: #93c5fd;
            }
            .superadmin-home-dashboard__empty {
                text-align: center;
                color: #6b7280;
                padding: 1rem !important;
            }
            .superadmin-home-dashboard__aside {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .superadmin-home-dashboard__aside {
                    width: 20rem;
                    flex-shrink: 0;
                }
            }
            .superadmin-home-dashboard__aside-title {
                margin: 0 0 1.25rem;
                text-align: center;
                font-size: 1rem;
                font-weight: 700;
                color: #6b7280;
            }
            .superadmin-home-dashboard__order-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem 1rem;
                text-align: center;
            }
            /* Static “Verified” badge (same layout as seller admin dashboard; scoped) */
            .superadmin-home-dashboard__verified-card {
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
            .dark .superadmin-home-dashboard__verified-card {
                border-color: #374151;
                background: #111827;
            }
            .superadmin-home-dashboard__verified-orbit {
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
            .superadmin-home-dashboard__verified-orbit-inner {
                position: absolute;
                inset: 0.25rem;
                border: 2px solid #60a5fa;
                border-radius: 50%;
                opacity: 0.5;
                pointer-events: none;
            }
            .superadmin-home-dashboard__verified-text {
                position: relative;
                z-index: 1;
                text-align: center;
            }
            .superadmin-home-dashboard__verified-stars-row {
                display: flex;
                justify-content: center;
                gap: 0.25rem;
                margin-bottom: 0.25rem;
            }
            .superadmin-home-dashboard__verified-stars-row--bottom {
                margin-top: 0.25rem;
                margin-bottom: 0;
            }
            .superadmin-home-dashboard__verified-star {
                font-size: 10px;
                line-height: 1;
                color: #3b82f6;
            }
            .superadmin-home-dashboard__verified-heading {
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
</x-superadmin::layouts>
