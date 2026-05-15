@props([
    'active' => null,
    'breadcrumb' => null,
    'showWorkspaceTabs' => true,
])

@php
    use Webkul\Admin\Support\SellerPanel;
    $activeKey = $active ?? SellerPanel::activeTabKey();
    $workspaceTabsPayload = collect(SellerPanel::tabs())->map(function ($tab) {
        $routeName = $tab['route'] ?? '';

        return [
            'key' => $tab['key'] ?? '',
            'label' => __($tab['label'] ?? ''),
            'href' => ($routeName && Route::has($routeName)) ? route($routeName) : '#',
        ];
    })->values()->all();
    $workspaceDashboardHref = Route::has('admin.dashboard.index') ? route('admin.dashboard.index') : url('/admin');
@endphp

<div class="seller-panel-scope max-w-full min-w-0 overflow-x-hidden rounded-xl bg-transparent p-3 sm:p-4">
    {{-- Recently opened workspace tabs (localStorage); × removes from list --}}
    @if ($showWorkspaceTabs)
        <div
            id="seller-workspace-tabs-root"
            class="mb-4 flex flex-wrap items-center gap-2 border-b border-gray-200/80 pb-3 dark:border-gray-800"
        ></div>

        <script>
            (function initSellerWorkspaceRecentTabs(cfg) {
                const root = document.getElementById('seller-workspace-tabs-root');

                if (!root || !cfg || !Array.isArray(cfg.tabs)) {
                    return;
                }

                const STORAGE_KEY = 'admin_seller_workspace_recent_v1';
                const MAX_TABS = 20;

                function tabByKey(key) {
                    return cfg.tabs.find(function (t) {
                        return t.key === key;
                    });
                }

                function loadKeys() {
                    try {
                        const raw = localStorage.getItem(STORAGE_KEY);
                        const parsed = raw ? JSON.parse(raw) : [];

                        return Array.isArray(parsed)
                            ? parsed.filter(function (k) {
                                  return typeof k === 'string';
                              })
                            : [];
                    } catch (e) {
                        return [];
                    }
                }

                function saveKeys(keys) {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(keys));
                }

                function recordVisit(key) {
                    if (!key) {
                        return;
                    }

                    let keys = loadKeys().filter(function (k) {
                        return k !== key;
                    });

                    keys.push(key);

                    if (keys.length > MAX_TABS) {
                        keys = keys.slice(keys.length - MAX_TABS);
                    }

                    saveKeys(keys);
                }

                function render() {
                    root.innerHTML = '';
                    const keys = loadKeys().filter(function (k) {
                        return tabByKey(k);
                    });

                    keys.forEach(function (key) {
                        const t = tabByKey(key);

                        if (!t) {
                            return;
                        }

                        const isActive = key === cfg.activeKey;
                        const row = document.createElement('div');
                        row.className =
                            'seller-workspace-tab-row inline-flex max-w-full min-w-0 items-stretch overflow-hidden rounded-md border text-sm font-medium transition ' +
                            (isActive
                                ? 'border-emerald-600 bg-emerald-500 text-white shadow-sm'
                                : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200');

                        const a = document.createElement('a');
                        a.href = t.href;
                        a.textContent = t.label;
                        a.className =
                            'min-w-0 flex-1 truncate px-3 py-1.5 ' +
                            (isActive
                                ? 'text-white hover:text-white'
                                : 'hover:text-blue-700 dark:hover:text-blue-300');

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.setAttribute('aria-label', 'Close tab');
                        btn.textContent = '×';
                        btn.className =
                            'flex shrink-0 items-center justify-center px-2 py-1.5 text-base leading-none ' +
                            (isActive
                                ? 'text-white/90 hover:bg-emerald-600/90 hover:text-white'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white');

                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            let ks = loadKeys().filter(function (k) {
                                return k !== key;
                            });

                            saveKeys(ks);

                            if (key === cfg.activeKey) {
                                if (ks.length) {
                                    const next = tabByKey(ks[ks.length - 1]);

                                    if (next && next.href && next.href !== '#') {
                                        window.location.assign(next.href);
                                    } else {
                                        window.location.assign(cfg.dashboardHref);
                                    }
                                } else {
                                    window.location.assign(cfg.dashboardHref);
                                }
                            } else {
                                render();
                            }
                        });

                        row.appendChild(a);
                        row.appendChild(btn);
                        root.appendChild(row);
                    });
                }

                recordVisit(cfg.activeKey);
                render();
            })({
                tabs: @json($workspaceTabsPayload),
                activeKey: @json($activeKey),
                dashboardHref: @json($workspaceDashboardHref),
            });
        </script>
    @endif

    @if (! empty($breadcrumb) && is_array($breadcrumb))
        <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            @foreach ($breadcrumb as $i => $crumb)
                @if ($i > 0)
                    <span class="mx-1.5 text-gray-400">/</span>
                @endif
                <span class="{{ $loop->last ? 'font-semibold text-gray-800 dark:text-white' : '' }}">{{ $crumb }}</span>
            @endforeach
        </nav>
    @endif

    <div class="seller-panel-inner">
        {{ $slot }}
    </div>
</div>

@pushOnce('styles')
    <style>
        .seller-panel-scope {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }
        .seller-panel-scope .seller-filter-card {
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        .dark .seller-panel-scope .seller-filter-card {
            border-color: #374151;
            background: #111827;
        }
        .seller-panel-scope .seller-summary-card {
            border-radius: 0.5rem;
            padding: 1.25rem;
            text-align: center;
            border: 1px solid #e5e7eb;
            background: #fff;
        }
        .seller-panel-scope .seller-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            background: #1890ff;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
        }
        .seller-panel-scope .seller-btn-primary:hover {
            background: #096dd9;
        }
        .seller-panel-scope .seller-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            background: #fff;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }
        .seller-panel-scope .seller-data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #fff;
        }
        .seller-panel-scope .seller-data-table thead th {
            background: #f9fafb;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0;
            color: #4b5563;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .seller-panel-scope .seller-data-table tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
        }
        .seller-panel-scope .seller-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.125rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .seller-panel-scope .seller-pill--blue {
            background: #e0f2fe;
            color: #0369a1;
        }
        .seller-panel-scope .seller-pill--green {
            background: #dcfce7;
            color: #15803d;
        }
        .seller-panel-scope .seller-pill--red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .seller-panel-scope .seller-rt-table-wrap {
            display: block;
        }

        .seller-panel-scope .seller-rt-cards {
            display: none;
        }

        @media (max-width: 767px) {
            .seller-panel-scope .seller-rt-table-wrap,
            .seller-panel-scope #seller-orders-table-wrap {
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

            .seller-panel-scope .seller-rt-cards,
            .seller-panel-scope #seller-orders-cards {
                display: grid !important;
                gap: 0.75rem;
                padding: 0.75rem;
                background: transparent;
                position: relative;
                z-index: 1;
            }

            .seller-panel-scope .seller-data-table {
                background: transparent !important;
                border: none !important;
            }

            .seller-panel-scope .seller-mobile-card {
                -webkit-backdrop-filter: none;
                backdrop-filter: none;
            }

            .seller-panel-scope .seller-mobile-card__actions,
            .seller-panel-scope .seller-order-card-actions {
                position: relative;
                z-index: 2;
                isolation: isolate;
                transform: translateZ(0);
                touch-action: manipulation;
            }

            .seller-panel-scope .seller-mobile-card__actions .seller-btn-primary,
            .seller-panel-scope .seller-mobile-card__actions .seller-btn-secondary,
            .seller-panel-scope .seller-mobile-card__actions form,
            .seller-panel-scope .seller-order-card-actions .seller-btn-primary,
            .seller-panel-scope .seller-order-card-actions .seller-btn-secondary,
            .seller-panel-scope .seller-order-card-actions a {
                touch-action: manipulation;
                cursor: pointer;
                pointer-events: auto !important;
            }

            .seller-panel-scope .seller-responsive-table {
                pointer-events: auto;
            }
        }

        .seller-panel-scope .seller-mobile-card {
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 0.875rem 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        @media (min-width: 768px) {
            .dark .seller-panel-scope .seller-mobile-card {
                border-color: #374151;
                background: #111827;
            }
        }

        .seller-panel-scope .seller-mobile-card--empty {
            border-style: dashed;
            padding: 2rem 1rem;
        }

        .seller-panel-scope .seller-mobile-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.625rem;
            padding-bottom: 0.625rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .dark .seller-panel-scope .seller-mobile-card__header {
            border-bottom-color: #1f2937;
        }

        .seller-panel-scope .seller-mobile-card__title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            line-height: 1.35;
        }

        .dark .seller-panel-scope .seller-mobile-card__title {
            color: #f9fafb;
        }

        .seller-panel-scope .seller-mobile-card__rows {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .seller-panel-scope .seller-mobile-card__row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            font-size: 0.8125rem;
        }

        .seller-panel-scope .seller-mobile-card__label {
            flex-shrink: 0;
            font-size: 0.6875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: #6b7280;
        }

        .dark .seller-panel-scope .seller-mobile-card__label {
            color: #9ca3af;
        }

        .seller-panel-scope .seller-mobile-card__value {
            text-align: right;
            color: #111827;
            word-break: break-word;
        }

        .dark .seller-panel-scope .seller-mobile-card__value {
            color: #e5e7eb;
        }

        .seller-panel-scope .seller-mobile-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
        }

        .dark .seller-panel-scope .seller-mobile-card__actions {
            border-top-color: #1f2937;
        }
    </style>
@endpushOnce
