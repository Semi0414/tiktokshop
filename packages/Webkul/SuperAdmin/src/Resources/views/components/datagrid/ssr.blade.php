@props([
    'datagridPayload' => null,
    'paginationNamespace' => null,
    'isMultiRow' => false,
])

@php
    $bootstrapJsonId = '';

    $dgBootstrapEncoded = null;

    if ($datagridPayload !== null) {
        $bootstrapJsonId = 'sdgb_'.str_replace('.', '', uniqid('', true));

        $dgBootstrapEncoded = json_encode(
            $datagridPayload,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE
        );

        if ($dgBootstrapEncoded === false) {
            report(new \RuntimeException(
                'SuperAdmin datagrid JSON encode failed (grid id '.($datagridPayload['id'] ?? 'unknown').'): '.json_last_error_msg(),
            ));

            $dgBootstrapEncoded = json_encode([
                'id' => '',
                'columns' => [],
                'actions' => [],
                'mass_actions' => [],
                'records' => [],
                'meta' => [
                    'primary_column' => 'id',
                    'from' => null,
                    'to' => null,
                    'total' => 0,
                    'per_page_options' => [10, 20, 30, 40, 50],
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        }
    }

    $listingSrc = trim((string) ($attributes->get('src') ?? ''));

    $appliedBootstrap = \Webkul\SuperAdmin\Support\DatagridSsrApplied::forToolbar(
        request(),
        is_array($datagridPayload['meta'] ?? null) ? $datagridPayload['meta'] : [],
        $paginationNamespace
    );

    /** Unique per grid instance — avoids duplicate DOM ids when multiple SSR grids mount on one page (e.g. customer/seller view). */
    $massCheckboxSuffix = $bootstrapJsonId !== '' ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $bootstrapJsonId) : 'dg';
@endphp

@if ($bootstrapJsonId !== '')
    <script
        type="application/json"
        id="{{ $bootstrapJsonId }}"
    >{!! $dgBootstrapEncoded !!}</script>
@endif

<v-datagrid
    {{ $attributes }}
    :tableless="@json(true)"
    :applied-bootstrap="@js($appliedBootstrap)"
    :bootstrap="@js($datagridPayload ?? null)"
    bootstrap-json-id="{{ $bootstrapJsonId }}"
    :pagination-namespace="@js($paginationNamespace)"
>
    @isset($table)
        {{ $table }}
    @else
        @include('superadmin::components.datagrid.ssr-default-table', [
            'datagridPayload' => $datagridPayload ?? [],
            'listingSrc' => $listingSrc,
            'paginationNamespace' => $paginationNamespace,
            'massCheckboxSuffix' => $massCheckboxSuffix,
        ])
    @endisset
</v-datagrid>

@include('superadmin::components.datagrid.scripts-register')
