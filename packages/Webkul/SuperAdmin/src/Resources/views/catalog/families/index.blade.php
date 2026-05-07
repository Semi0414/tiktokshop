<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.families.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.catalog.families.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('catalog.families.create'))
                <a href="{{ route('superadmin.catalog.families.create') }}">
                    <div class="primary-button">
                        @lang('superadmin::app.catalog.families.index.add')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.families.list.before') !!}

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.catalog.families.index')" />

    {!! view_render_event('bagisto.admin.catalog.families.list.after') !!}

</x-superadmin::layouts>