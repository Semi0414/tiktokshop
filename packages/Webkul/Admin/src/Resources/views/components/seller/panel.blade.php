@props([
    'active' => null,
    'breadcrumb' => null,
    'showWorkspaceTabs' => true,
])

@php
    use Webkul\Admin\Support\SellerPanel;
    $activeKey = $active ?? SellerPanel::activeTabKey();
@endphp

<div class="seller-panel-scope rounded-xl bg-[#F4F6F9] p-4 dark:bg-gray-950">
    {{-- Workspace tabs (reference: green active pill + close icon) --}}
    @if ($showWorkspaceTabs)
    <div class="mb-4 flex flex-wrap items-center gap-2 border-b border-gray-200/80 pb-3 dark:border-gray-800">
        @foreach (SellerPanel::tabs() as $tab)
            @php
                $isActive = ($tab['key'] ?? '') === $activeKey;
                $href = Route::has($tab['route'] ?? '') ? route($tab['route']) : '#';
            @endphp
            <a
                href="{{ $href }}"
                class="seller-workspace-tab inline-flex items-center gap-1.5 rounded-md border px-3 py-1.5 text-sm font-medium transition
                    {{ $isActive
                        ? 'seller-workspace-tab--active border-emerald-600 bg-emerald-500 text-white shadow-sm'
                        : 'border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
            >
                <span>@lang($tab['label'] ?? '')</span>
                @if ($isActive)
                    <span class="text-white/90" aria-hidden="true">×</span>
                @endif
            </a>
        @endforeach
    </div>
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
    </style>
@endpushOnce
