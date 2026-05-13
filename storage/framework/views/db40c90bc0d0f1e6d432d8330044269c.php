<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'active' => null,
    'breadcrumb' => null,
    'showWorkspaceTabs' => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'active' => null,
    'breadcrumb' => null,
    'showWorkspaceTabs' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
?>

<div class="seller-panel-scope max-w-full min-w-0 overflow-x-hidden rounded-xl bg-[#F4F6F9] p-3 sm:p-4 dark:bg-gray-950">
    
    <?php if($showWorkspaceTabs): ?>
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
                tabs: <?php echo json_encode($workspaceTabsPayload, 15, 512) ?>,
                activeKey: <?php echo json_encode($activeKey, 15, 512) ?>,
                dashboardHref: <?php echo json_encode($workspaceDashboardHref, 15, 512) ?>,
            });
        </script>
    <?php endif; ?>

    <?php if(! empty($breadcrumb) && is_array($breadcrumb)): ?>
        <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($i > 0): ?>
                    <span class="mx-1.5 text-gray-400">/</span>
                <?php endif; ?>
                <span class="<?php echo e($loop->last ? 'font-semibold text-gray-800 dark:text-white' : ''); ?>"><?php echo e($crumb); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    <?php endif; ?>

    <div class="seller-panel-inner">
        <?php echo e($slot); ?>

    </div>
</div>

<?php if (! $__env->hasRenderedOnce('763b8ca8-bc3b-4e6f-8ca6-7b360a2a879a')): $__env->markAsRenderedOnce('763b8ca8-bc3b-4e6f-8ca6-7b360a2a879a');
$__env->startPush('styles'); ?>
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
    </style>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/components/seller/panel.blade.php ENDPATH**/ ?>