<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.attributes.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Title -->
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.catalog.attributes.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('catalog.attributes.create'))
                <a href="{{ route('superadmin.catalog.attributes.create') }}">
                    <div class="primary-button">
                        @lang('superadmin::app.catalog.attributes.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.attributes.list.before') !!}

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.catalog.attributes.index')" />

    {!! view_render_event('bagisto.admin.catalog.attributes.list.after') !!}

</x-superadmin::layouts>
