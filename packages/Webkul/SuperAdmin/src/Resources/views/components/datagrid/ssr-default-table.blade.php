@props([
    'datagridPayload' => [],
    'listingSrc' => '',
    'paginationNamespace' => null,
    'massCheckboxSuffix' => 'dg',
])

@php
    use Webkul\SuperAdmin\Support\DatagridSsrUrlBuilder;

    $request = request();
    $records = $datagridPayload['records'] ?? [];
    $columns = collect($datagridPayload['columns'] ?? [])->where('visibility', true)->values()->all();
    $massActions = $datagridPayload['mass_actions'] ?? [];
    $meta = $datagridPayload['meta'] ?? [];
    $primary = $meta['primary_column'] ?? 'id';
    $hasMass = count($massActions) > 0;
    $hasRowActions = count($datagridPayload['actions'] ?? []) > 0;
    $colSpanEmpty = max(1, count($columns) + ($hasMass ? 1 : 0) + ($hasRowActions ? 1 : 0));
@endphp

<div class="table-responsive box-shadow grid w-full overflow-x-auto rounded bg-white dark:bg-gray-900">
    <div class="w-full">
        <div class="border-b bg-gray-50 px-4 py-2.5 dark:border-gray-800 dark:bg-gray-950">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                        @if ($hasMass)
                            @php
                                $hdrCheckId = 'mass_action_select_all_records_ssr_'.$massCheckboxSuffix;
                            @endphp
                            <th scope="col" class="w-14 px-2 py-2 text-start align-middle">
                                <label for="{{ $hdrCheckId }}">
                                    <input
                                        type="checkbox"
                                        id="{{ $hdrCheckId }}"
                                        data-dg-ssr-select-all
                                        class="peer hidden"
                                        aria-label="@lang('superadmin::app.components.datagrid.toolbar.mass-actions.select-action')"
                                    />

                                    <span class="icon-uncheckbox cursor-pointer rounded-md text-2xl peer-checked:text-blue-600 peer-checked:icon-checked"></span>
                                </label>
                            </th>
                        @endif

                        @foreach ($columns as $column)
                            @php
                                $sortHref = DatagridSsrUrlBuilder::sortUrl($request, $listingSrc, $column, $paginationNamespace);
                            @endphp
                            <th scope="col" class="px-3 py-2 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 align-middle">
                                @if ($sortHref && ($column['sortable'] ?? false))
                                    <a href="{{ $sortHref }}" class="inline-flex items-center gap-1 hover:text-gray-900 dark:hover:text-white">
                                        {{ $column['label'] ?? '' }}

                                        @if (($request->input('sort.column') ?? '') === ($column['index'] ?? ''))
                                            <span class="icon-{{ ($request->input('sort.order') ?? 'desc') === 'asc' ? 'down-stat' : 'up-stat' }} text-base"></span>
                                        @endif
                                    </a>
                                @else
                                    {{ $column['label'] ?? '' }}
                                @endif
                            </th>
                        @endforeach

                        @if ($hasRowActions)
                            <th scope="col" class="px-3 py-2 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 align-middle">
                                @lang('superadmin::app.components.datagrid.table.actions')
                            </th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($records as $record)
                        @php
                            $rowId = data_get($record, $primary);
                            $ridLabel = 'mass_action_select_record_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $rowId);
                        @endphp
                        <tr class="transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                            @if ($hasMass)
                                <td class="px-2 py-3 align-middle">
                                    <label for="{{ $ridLabel }}">
                                        <input
                                            type="checkbox"
                                            id="{{ $ridLabel }}"
                                            data-dg-ssr-mass
                                            value="{{ $rowId }}"
                                            class="peer hidden"
                                            aria-label="@lang('superadmin::app.components.datagrid.toolbar.mass-actions.select-action')"
                                        />

                                        <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"></span>
                                    </label>
                                </td>
                            @endif

                            @foreach ($columns as $column)
                                @php
                                    $idx = $column['index'] ?? '';
                                    $cell = data_get($record, $idx);
                                @endphp
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200 align-middle break-words">
                                    @if ($cell !== null && $cell !== '' && is_string($cell))
                                        {!! $cell !!}
                                    @elseif ($cell !== null && $cell !== '')
                                        {{ $cell }}
                                    @endif
                                </td>
                            @endforeach

                            @if ($hasRowActions)
                                <td class="px-3 py-3 text-end align-middle">
                                    @foreach (data_get($record, 'actions', []) ?: [] as $action)
                                        @php
                                            $method = strtoupper((string) ($action['method'] ?? 'GET'));
                                            $actUrl = (string) ($action['url'] ?? '');
                                            $confirmMessage = __('superadmin::app.components.modal.confirm.message');
                                        @endphp
                                        @if ($method === 'GET')
                                            <a
                                                href="{{ $actUrl !== '' ? $actUrl : '#' }}"
                                                title="{{ $action['title'] ?? '' }}"
                                                class="{{ ($action['icon'] ?? '') }} cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 inline-block"
                                            ></a>
                                        @elseif ($actUrl !== '')
                                            <form
                                                method="POST"
                                                action="{{ $actUrl }}"
                                                class="inline-flex"
                                                onsubmit="return confirm(@json($confirmMessage));"
                                            >
                                                @csrf
                                                @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
                                                    @method($method)
                                                @endif

                                                <button
                                                    type="submit"
                                                    title="{{ $action['title'] ?? '' }}"
                                                    class="{{ ($action['icon'] ?? '') }} inline-block cursor-pointer rounded-md border-0 bg-transparent p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                                >
                                                    @if (empty($action['icon']))
                                                        <span class="text-sm">{{ $action['title'] ?? '' }}</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ $colSpanEmpty }}"
                                class="px-4 py-12 text-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                @lang('superadmin::app.components.datagrid.table.no-records-available')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
