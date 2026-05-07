<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.marketing.communications.templates.index.title')
    </x-slot>

    <div class="flex justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.marketing.communications.templates.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('marketing.communications.email_templates.create'))
                <a href="{{ route('superadmin.marketing.communications.email_templates.create') }}">
                    <div class="primary-button">
                        @lang('superadmin::app.marketing.communications.templates.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.before') !!}

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.marketing.communications.email_templates.index')" />

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.after') !!}

</x-superadmin::layouts>
