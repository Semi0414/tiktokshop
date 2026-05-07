<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.customers.customers.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-superadmin::datagrid.export src="{{ route('superadmin.customers.customers.index') }}" />

            <div class="flex items-center gap-x-2.5">
                <!-- Included customer create blade file -->
                @if (bouncer()->hasPermission('customers.customers.create'))
                    {!! view_render_event('bagisto.admin.customers.customers.create.before') !!}

                    @include('superadmin::customers.customers.index.create')

                    <v-create-customer-form
                        ref="createCustomerComponent"
                        @customer-created="$refs.customerDatagrid.get()"
                    ></v-create-customer-form>

                    {!! view_render_event('bagisto.admin.customers.customers.create.after') !!}

                    <button
                        class="primary-button"
                        @click="$refs.createCustomerComponent.openModal()"
                    >
                        @lang('superadmin::app.customers.customers.index.create.create-btn')
                    </button>
                @endif
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.list.before') !!}

    <x-superadmin::datagrid.ssr
        :datagrid-payload="$datagridPayload"
        :src="route('superadmin.customers.customers.index')"
        ref="customerDatagrid"
        :isMultiRow="true"
    />

    {!! view_render_event('bagisto.admin.customers.customers.list.after') !!}
</x-superadmin::layouts>
