<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.inventory-sources.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.inventory-sources.index.title')
        </p>

        <!-- Create Button -->
        @if (bouncer()->hasPermission('settings.inventory_sources.create'))
            <a href="{{ route('superadmin.settings.inventory_sources.create') }}">
                <div class="primary-button">
                    @lang('superadmin::app.settings.inventory-sources.index.create-btn')
                </div>
            </a>
        @endif
    </div>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.before') !!}

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.settings.inventory_sources.index')" />

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.after') !!}

</x-superadmin::layouts>
