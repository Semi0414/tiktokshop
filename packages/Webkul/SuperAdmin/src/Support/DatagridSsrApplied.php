<?php

namespace Webkul\SuperAdmin\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Builds the {@see applied} toolbar state from the current request (sort, pagination, filters)
 * so SSR + tableless Vue toolbar stay in sync with the URL query string.
 */
final class DatagridSsrApplied
{
    /**
     * @param  array<string, mixed>  $meta  From datagrid payload meta (fallback pagination).
     * @return array<string, mixed>
     */
    public static function forToolbar(Request $request, array $meta = [], ?string $paginationNamespace = null): array
    {
        if ($paginationNamespace) {
            return self::forNamespacedToolbar($request, $meta, $paginationNamespace);
        }

        $paginationInput = $request->input('pagination', []);

        return [
            'pagination' => [
                'page' => (int) data_get($paginationInput, 'page', data_get($meta, 'current_page', 1)),
                'perPage' => (int) data_get($paginationInput, 'per_page', data_get($meta, 'per_page', 10)),
            ],
            'sort' => [
                'column' => $request->input('sort.column'),
                'order' => $request->input('sort.order'),
            ],
            'filters' => [
                'columns' => self::filterColumnsFromRequest($request),
            ],
            'savedFilterId' => null,
            'massActions' => [
                'meta' => [
                    'mode' => 'none',
                    'action' => null,
                ],
                'indices' => [],
                'value' => null,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    protected static function forNamespacedToolbar(Request $request, array $meta, string $ns): array
    {
        $block = $request->input($ns, []);
        $paginationInput = data_get($block, 'pagination', []);

        return [
            'pagination' => [
                'page' => (int) data_get($paginationInput, 'page', data_get($meta, 'current_page', 1)),
                'perPage' => (int) data_get($paginationInput, 'per_page', data_get($meta, 'per_page', 10)),
            ],
            'sort' => [
                'column' => data_get($block, 'sort.column'),
                'order' => data_get($block, 'sort.order'),
            ],
            'filters' => [
                'columns' => self::filterColumnsFromNamespacedRequest($request, $ns),
            ],
            'savedFilterId' => null,
            'massActions' => [
                'meta' => [
                    'mode' => 'none',
                    'action' => null,
                ],
                'indices' => [],
                'value' => null,
            ],
        ];
    }

    /**
     * @return array<int, array{index: string, value: array<int, mixed>}>
     */
    protected static function filterColumnsFromRequest(Request $request): array
    {
        $columns = [];

        $allValues = array_values(array_filter(Arr::wrap($request->input('filters.all', []))));

        if ($request->filled('search')) {
            $allValues[] = $request->input('search');
        }

        $columns[] = [
            'index' => 'all',
            'value' => array_values(array_filter(array_unique($allValues), fn ($v) => $v !== null && $v !== '')),
        ];

        $filtersRoot = $request->input('filters', []);

        if (! is_array($filtersRoot)) {
            $filtersRoot = [];
        }

        foreach ($filtersRoot as $idx => $vals) {
            if (! is_string($idx) || $idx === 'all') {
                continue;
            }

            $values = array_values(array_filter(Arr::wrap($vals), fn ($v) => $v !== null && $v !== ''));

            if ($values !== []) {
                $columns[] = ['index' => $idx, 'value' => $values];
            }
        }

        return $columns;
    }

    /**
     * @return array<int, array{index: string, value: array<int, mixed>}>
     */
    protected static function filterColumnsFromNamespacedRequest(Request $request, string $ns): array
    {
        $filters = data_get($request->input($ns), 'filters', []);
        $columns = [];

        $allValues = array_values(array_filter(Arr::wrap(data_get($filters, 'all', []))));

        if (data_get($request->input($ns), 'search')) {
            $allValues[] = data_get($request->input($ns), 'search');
        }

        $columns[] = [
            'index' => 'all',
            'value' => array_values(array_filter(array_unique($allValues), fn ($v) => $v !== null && $v !== '')),
        ];

        foreach (Arr::wrap($filters) as $idx => $vals) {
            if (! is_string($idx) || $idx === 'all') {
                continue;
            }

            $values = array_values(array_filter(Arr::wrap($vals), fn ($v) => $v !== null && $v !== ''));

            if ($values !== []) {
                $columns[] = ['index' => $idx, 'value' => $values];
            }
        }

        return $columns;
    }
}
