<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.customers.customers.view.title')
    </x-slot>

    <div class="grid">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    {{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) }}
                </h1>

                @if ($customer->status)
                    <span class="label-active mx-1.5 text-sm">
                        @lang('superadmin::app.customers.customers.view.active')
                    </span>
                @else
                    <span class="label-canceled mx-1.5 text-sm">
                        @lang('superadmin::app.customers.customers.view.inactive')
                    </span>
                @endif

                @if ($customer->is_suspended)
                    <span class="label-canceled text-sm">
                        @lang('superadmin::app.customers.customers.view.suspended')
                    </span>
                @endif
            </div>

            <a
                href="{{ route('superadmin.customers.customers.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('superadmin::app.customers.customers.view.back-btn')
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

    <div class="mt-7 flex flex-wrap items-center gap-x-1 gap-y-2">
        @if (bouncer()->hasPermission('sales.orders.create'))
            <form
                method="post"
                action="{{ route('superadmin.customers.customers.cart.store', $customer->id) }}"
                onsubmit="return confirm('@lang('superadmin::app.customers.customers.view.order-create-confirmation')')"
            >
                @csrf

                <button class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800">
                    <span class="icon-cart text-2xl"></span>
                    @lang('superadmin::app.customers.customers.view.create-order')
                </button>
            </form>
        @endif

        <a
            class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
            href="{{ route('superadmin.customers.customers.login_as_customer', $customer->id) }}"
            target="_blank"
        >
            <span class="icon-exit text-2xl"></span>
            @lang('superadmin::app.customers.customers.view.login-as-customer')
        </a>

        @if (bouncer()->hasPermission('customers.customers.delete'))
            <form
                method="post"
                action="{{ route('superadmin.customers.customers.delete', $customer->id) }}"
                onsubmit="return confirm('@lang('superadmin::app.customers.customers.view.account-delete-confirmation')')"
            >
                @csrf
                <button class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800">
                    <span class="icon-cancel text-2xl"></span>
                    @lang('superadmin::app.customers.customers.view.delete-account')
                </button>
            </form>
        @endif
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.view.filters.after') !!}

    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.before') !!}
            @include('superadmin::customers.customers.view.orders')
            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.before') !!}
            @include('superadmin::customers.customers.view.invoices')
            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.before') !!}
            @include('superadmin::customers.customers.view.reviews')
            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.before') !!}
            @include('superadmin::customers.customers.view.notes')
            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.after') !!}
        </div>

        <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-3 flex w-full items-center justify-between">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.customers.customers.view.customer')
                    </p>

                    @include('superadmin::customers.customers.view.edit')
                </div>

                <div class="grid gap-y-2.5">
                    <p class="break-all font-semibold text-gray-800 dark:text-white">
                        {{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) }}
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.email', ['email' => $customer->email ?? 'N/A'])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.phone', ['phone' => $customer->phone ?? 'N/A'])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.gender', ['gender' => $customer->gender ?? 'N/A'])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.date-of-birth', ['dob' => $customer->date_of_birth ?? 'N/A'])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.group', ['group_code' => $customer->group?->name ?? 'N/A'])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        Wallet Balance: {{ $customer->wallet_balance ?? 0 }}
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        Credit Score: {{ ($customer->credit_score ?? 100) . '%' }}
                    </p>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.before') !!}

            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-3 flex w-full items-center justify-between">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.customers.customers.view.address.count', ['count' => $customer->addresses->count()])
                    </p>

                    @include('superadmin::customers.customers.view.address.create')
                </div>

                @if ($customer->addresses->count())
                    @foreach ($customer->addresses as $index => $address)
                        <div class="grid gap-y-2.5">
                            @if ($address->default_address)
                                <p class="label-pending">
                                    @lang('superadmin::app.customers.customers.view.default-address')
                                </p>
                            @endif

                            <p class="break-all font-semibold text-gray-800 dark:text-white">
                                {{ trim(($address->first_name ?? '') . ' ' . ($address->last_name ?? '')) }}
                                @if ($address->company_name)
                                    ({{ $address->company_name }})
                                @endif
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ collect(explode("\n", (string) $address->address))->filter()->implode(', ') }},
                                {{ $address->city }},
                                {{ $address->state }},
                                {{ $address->country }},
                                {{ $address->postcode }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.phone', ['phone' => $address->phone ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.email', ['email' => $address->email ?? 'N/A'])
                            </p>

                            <div class="flex items-center gap-2.5">
                                @include('superadmin::customers.customers.view.address.edit')

                                @if (bouncer()->hasPermission('customers.addresses.delete'))
                                    <form
                                        method="post"
                                        action="{{ route('superadmin.customers.customers.addresses.delete', $address->id) }}"
                                        onsubmit="return confirm('@lang('superadmin::app.customers.customers.view.address-delete-confirmation')')"
                                    >
                                        @csrf
                                        <button class="cursor-pointer text-red-600 transition-all hover:underline">
                                            @lang('superadmin::app.customers.customers.view.delete')
                                        </button>
                                    </form>
                                @endif

                                @if (! $address->default_address)
                                    <form
                                        method="post"
                                        action="{{ route('superadmin.customers.customers.addresses.set_default', $customer->id) }}"
                                    >
                                        @csrf
                                        <input type="hidden" name="set_as_default" value="{{ $address->id }}">
                                        <button class="flex cursor-pointer justify-center text-sm text-blue-600 transition-all hover:underline">
                                            @lang('superadmin::app.customers.customers.view.set-as-default')
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if ($index !== ($customer->addresses->count() - 1))
                            <span class="mb-4 mt-4 block w-full border-b dark:border-gray-800"></span>
                        @endif
                    @endforeach
                @else
                    <div class="flex items-center gap-5 py-2.5">
                        <img
                            src="{{ bagisto_asset('images/settings/address.svg') }}"
                            class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                        />

                        <div class="flex flex-col gap-1.5">
                            <p class="text-base font-semibold text-gray-400">
                                @lang('superadmin::app.customers.customers.view.empty-title')
                            </p>

                            <p class="text-gray-400">
                                @lang('superadmin::app.customers.customers.view.empty-description')
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.after') !!}
        </div>
    </div>
</x-superadmin::layouts>