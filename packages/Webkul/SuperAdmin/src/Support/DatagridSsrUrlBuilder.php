<?php

namespace Webkul\SuperAdmin\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Builds full listing URLs mirroring SuperAdmin Vue {@see buildListingUrl} (global + namespaced pagination).
 */
final class DatagridSsrUrlBuilder
{
    /**
     * @param  array<string, mixed>  $replace  Global mode: pagination (page, per_page), sort (column, order), filters (column => values[]).
     *                                           Namespaced mode: [ $ns => [ 'pagination' => [...] ] ] only (matches Vue nested branch).
     */
    public static function listingUrl(Request $request, string $src, array $replace = [], ?string $paginationNamespace = null): string
    {
        $path = parse_url($src, PHP_URL_PATH) ?: '/';

        $routeQuery = [];

        parse_str((string) parse_url($src, PHP_URL_QUERY), $routeQuery);

        if ($paginationNamespace) {
            return self::namespacedUrl($request, $path, $routeQuery, $replace, $paginationNamespace);
        }

        return self::globalUrl($request, $path, $routeQuery, $replace);
    }

    /**
     * @param  array<string, mixed>|null  $column  Payload column (needs index + sortable).
     */
    public static function sortUrl(Request $request, string $src, ?array $column, ?string $paginationNamespace = null): ?string
    {
        if (! is_array($column) || empty($column['sortable']) || empty($column['index'])) {
            return null;
        }

        $idx = $column['index'];

        if ($paginationNamespace) {
            return null;
        }

        $currentColumn = $request->input('sort.column');
        $currentOrder = strtolower((string) $request->input('sort.order', 'desc'));

        $nextOrder = 'asc';

        if ($currentColumn === $idx) {
            $nextOrder = $currentOrder === 'asc' ? 'desc' : 'asc';
        }

        return self::listingUrl($request, $src, [
            'sort' => ['column' => $idx, 'order' => $nextOrder],
            'pagination' => ['page' => 1],
        ]);
    }

    /**
     * @param  array<string, mixed>  $routeQuery
     */
    protected static function globalUrl(Request $request, string $path, array $routeQuery, array $replace): string
    {
        $params = [];

        foreach ($request->query() as $key => $value) {
            if (in_array($key, ['pagination', 'sort', 'filters'], true)) {
                continue;
            }

            $params[$key] = $value;
        }

        foreach ($routeQuery as $key => $value) {
            if (in_array($key, ['pagination', 'sort', 'filters'], true)) {
                continue;
            }

            if (! array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }

        $pagination = array_replace(
            [
                'page' => (int) data_get($request->input('pagination'), 'page', 1),
                'per_page' => (int) data_get($request->input('pagination'), 'per_page', 10),
            ],
            Arr::get($routeQuery, 'pagination', []) ?: [],
            Arr::get($replace, 'pagination', []) ?: [],
        );

        $sortInput = array_filter(Arr::wrap($request->input('sort')), fn ($v) => $v !== null && $v !== '');
        $sortReplace = Arr::get($replace, 'sort');

        $sort = $sortInput;

        if (is_array($sortReplace)) {
            foreach ($sortReplace as $k => $v) {
                if ($v === null || $v === '') {
                    unset($sort[$k]);

                    continue;
                }

                $sort[$k] = $v;
            }
        }

        $filtersInput = Arr::wrap($request->input('filters'));
        $filtersReplace = Arr::get($replace, 'filters');

        $filters = $filtersInput;

        if (is_array($filtersReplace)) {
            foreach ($filtersReplace as $k => $v) {
                if ($v === null || $v === [] || $v === '') {
                    unset($filters[$k]);

                    continue;
                }

                $filters[$k] = $v;
            }
        }

        $params['pagination'] = $pagination;

        if ($sort !== []) {
            $params['sort'] = $sort;
        }

        if ($filters !== []) {
            $params['filters'] = $filters;
        }

        $qs = self::buildQueryString($params);

        return $qs !== '' ? $path.'?'.$qs : $path;
    }

    /**
     * Vue nested branch strips every "{$ns}[…]" param then sets only pagination page/per_page under ns.
     *
     * @param  array<string, mixed>  $routeQuery
     */
    protected static function namespacedUrl(Request $request, string $path, array $routeQuery, array $replace, string $ns): string
    {
        $pairs = [];

        foreach ($request->query() as $key => $value) {
            if (str_starts_with((string) $key, $ns.'[')) {
                continue;
            }

            if (str_starts_with((string) $key, 'pagination[') || str_starts_with((string) $key, 'sort[') || str_starts_with((string) $key, 'filters[')) {
                continue;
            }

            foreach (Arr::wrap($value) as $vv) {
                $pairs[] = rawurlencode((string) $key).'='.rawurlencode((string) $vv);
            }
        }

        foreach ($routeQuery as $key => $value) {
            if (str_starts_with((string) $key, $ns.'[')) {
                continue;
            }

            if (in_array($key, ['pagination', 'sort', 'filters'], true)) {
                continue;
            }

            if ($request->query->has($key)) {
                continue;
            }

            foreach (Arr::wrap($value) as $vv) {
                $pairs[] = rawurlencode((string) $key).'='.rawurlencode((string) $vv);
            }
        }

        $nestedReplace = data_get($replace, $ns, []);

        $pagination = array_replace(
            [
                'page' => (int) data_get($request->input($ns), 'pagination.page', 1),
                'per_page' => (int) data_get($request->input($ns), 'pagination.per_page', 10),
            ],
            data_get($nestedReplace, 'pagination', []) ?: [],
        );

        $pairs[] = rawurlencode($ns.'[pagination][page]').'='.(int) data_get($pagination, 'page', 1);
        $pairs[] = rawurlencode($ns.'[pagination][per_page]').'='.(int) data_get($pagination, 'per_page', 10);

        $qs = implode('&', array_filter($pairs));

        return $qs !== '' ? $path.'?'.$qs : $path;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected static function buildQueryString(array $params): string
    {
        $parts = [];

        if ($pagination = data_get($params, 'pagination')) {
            $parts[] = 'pagination[page]='.rawurlencode((string) data_get($pagination, 'page', 1));
            $parts[] = 'pagination[per_page]='.rawurlencode((string) data_get($pagination, 'per_page', 10));
        }

        if ($sort = data_get($params, 'sort')) {
            foreach (['column', 'order'] as $sk) {
                if (data_get($sort, $sk) !== null && data_get($sort, $sk) !== '') {
                    $parts[] = 'sort['.$sk.']='.rawurlencode((string) data_get($sort, $sk));
                }
            }
        }

        if ($filters = data_get($params, 'filters')) {
            foreach (Arr::wrap($filters) as $idx => $vals) {
                foreach (Arr::wrap($vals) as $v) {
                    if ($v === null || $v === '') {
                        continue;
                    }

                    $parts[] = 'filters['.$idx.'][]='.rawurlencode((string) $v);
                }
            }
        }

        foreach ($params as $key => $value) {
            if (in_array($key, ['pagination', 'sort', 'filters'], true)) {
                continue;
            }

            foreach (Arr::wrap($value) as $vv) {
                if ($vv === null || $vv === '') {
                    continue;
                }

                $parts[] = rawurlencode((string) $key).'='.rawurlencode((string) $vv);
            }
        }

        return implode('&', $parts);
    }
}
