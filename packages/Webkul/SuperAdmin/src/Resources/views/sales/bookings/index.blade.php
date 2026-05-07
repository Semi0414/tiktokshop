<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.sales.booking.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-[16px] max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.sales.booking.index.title')
        </p>

        <x-superadmin::datagrid.export src="{{ route('superadmin.sales.bookings.index') }}" />
    </div>

    @include('superadmin::sales.bookings.calendar')

    <div class="mt-6">
        <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.sales.bookings.index')" />
    </div>
</x-superadmin::layouts>