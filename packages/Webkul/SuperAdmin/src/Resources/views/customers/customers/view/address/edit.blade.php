@if (bouncer()->hasPermission('customers.addresses.edit'))
    <a
        class="cursor-pointer text-blue-600 transition-all hover:underline"
        href="{{ route('superadmin.customers.customers.addresses.edit', $address->id) }}"
    >
        @lang('superadmin::app.customers.customers.view.address.edit.edit-btn')
    </a>
@endif
