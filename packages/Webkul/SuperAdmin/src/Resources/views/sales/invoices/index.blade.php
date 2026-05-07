<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.sales.invoices.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.sales.invoices.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-superadmin::datagrid.export :src="route('superadmin.sales.invoices.index')" />
        </div>
    </div>

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.sales.invoices.index')" />

</x-superadmin::layouts>
