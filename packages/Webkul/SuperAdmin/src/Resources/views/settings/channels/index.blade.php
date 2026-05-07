<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.settings.channels.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.channels.index.title')
        </p>
        
        <div class="flex items-center gap-x-2.5">
            <!-- Create New Channel Button -->
            @if (bouncer()->hasPermission('settings.channels.create'))
                <a 
                    href="{{ route('superadmin.settings.channels.create') }}"
                    class="primary-button"
                >
                    @lang('superadmin::app.settings.channels.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.settings.channels.list.before') !!}
    
    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.settings.channels.index')" />

    {!! view_render_event('bagisto.settings.channels.list.after') !!}

</x-superadmin::layouts>