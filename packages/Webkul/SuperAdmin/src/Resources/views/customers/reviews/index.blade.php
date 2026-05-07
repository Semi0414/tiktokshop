<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.customers.reviews.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.reviews.index.title')
        </p>
    </div>

    {!! view_render_event('bagisto.admin.customers.reviews.edit.before') !!}

    <x-superadmin::datagrid.ssr
        :datagrid-payload="$datagridPayload"
        :src="route('superadmin.customers.customers.review.index')"
        :isMultiRow="true"
    />

    {!! view_render_event('bagisto.admin.customers.groups.edit.after') !!}
</x-superadmin::layouts>
