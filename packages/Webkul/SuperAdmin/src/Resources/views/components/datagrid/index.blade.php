@props([
    'isMultiRow' => false,
    'datagridPayload' => null,
    'paginationNamespace' => null,
])

@php
    $bootstrapJsonId = '';

    /** JSON for <script type="application/json">: hex-escape markup (same idea as Blade @json) + substitute invalid UTF-8 so Vue can JSON.parse payloads with HTML-heavy cells. */
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
@endphp

@if ($bootstrapJsonId !== '')
    <script
        type="application/json"
        id="{{ $bootstrapJsonId }}"
    >{!! $dgBootstrapEncoded !!}</script>
@endif

<v-datagrid
    {{ $attributes }}
    bootstrap-json-id="{{ $bootstrapJsonId }}"
    :pagination-namespace="@js($paginationNamespace)"
>
    {{ $slot }}
</v-datagrid>

@include('superadmin::components.datagrid.scripts-register')
