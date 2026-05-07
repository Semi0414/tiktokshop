@if (bouncer()->hasPermission('customers.addresses.create'))
    <a
        href="{{ route('superadmin.customers.customers.addresses.create', $customer->id) }}"
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
    >
        @lang('superadmin::app.customers.customers.view.address.create.create-btn')
    </a>
@endif
