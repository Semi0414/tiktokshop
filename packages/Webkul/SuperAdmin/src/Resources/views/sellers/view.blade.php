<x-superadmin::layouts>
    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @php
        $sellerNameParts = preg_split('/\s+/', trim((string) $seller->name), 2, PREG_SPLIT_NO_EMPTY);
        $sellerFirstName = $sellerNameParts[0] ?? '';
        $sellerLastName = $sellerNameParts[1] ?? '';
    @endphp

    <x-slot:title>
        @lang('superadmin::app.sellers.view.page-title')
    </x-slot>

    <div>
        <div class="grid gap-3">
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                        {{ trim($sellerFirstName.' '.$sellerLastName) }}
                    </h1>

                    @if ($seller->status)
                        <span class="label-active mx-1.5 text-sm">
                            @lang('superadmin::app.customers.customers.view.active')
                        </span>
                    @else
                        <span class="label-canceled mx-1.5 text-sm">
                            @lang('superadmin::app.customers.customers.view.inactive')
                        </span>
                    @endif
                </div>

                <a
                    href="{{ route('superadmin.sellers.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.customers.customers.view.back-btn')
                </a>
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

            <div class="mt-7 flex flex-wrap items-center gap-x-1 gap-y-2">
                <a
                    class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                    href="{{ route('superadmin.sellers.login_as_seller', $seller->id) }}"
                    target="_blank"
                >
                    <span class="icon-exit text-2xl"></span>

                    @lang('superadmin::app.sellers.index.datagrid.login-as-seller')
                </a>

                <a
                    class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                    href="{{ route('superadmin.sellers.visit_store', $seller->id) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="icon-store text-2xl"></span>

                    @lang('superadmin::app.sellers.view.visit-store')
                </a>

                @if (bouncer()->hasPermission('marketing.email_management'))
                    <button
                        type="button"
                        class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                        onclick="document.getElementById('welcome-email-modal-{{ $seller->id }}').showModal()"
                    >
                        <span class="icon-mail text-2xl"></span>
                        @lang('superadmin::app.sellers.view.welcome-email-btn')
                    </button>

                    <dialog
                        id="welcome-email-modal-{{ $seller->id }}"
                        class="w-full max-w-3xl rounded-lg p-0 backdrop:bg-black/50" style="width: 50%;"
                    >
                        <div class="rounded-lg bg-white p-5 dark:bg-gray-900">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    @lang('superadmin::app.sellers.view.welcome-email-modal-title')
                                </h3>

                                <button
                                    type="button"
                                    class="text-2xl leading-none text-gray-500 hover:text-gray-800 dark:hover:text-white"
                                    onclick="document.getElementById('welcome-email-modal-{{ $seller->id }}').close()"
                                >
                                    &times;
                                </button>
                            </div>

                            <form
                                method="post"
                                action="{{ route('superadmin.sellers.welcome_email', $seller->id) }}"
                                class="grid gap-3"
                            >
                                @csrf

                                <input
                                    type="text"
                                    name="subject"
                                    value="{{ $welcomeSubjectDefault ?? '' }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                    placeholder="@lang('superadmin::app.sellers.view.welcome-email-subject-label')"
                                    required
                                />

                                <textarea
                                    name="message"
                                    rows="8"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                    placeholder="@lang('superadmin::app.sellers.view.welcome-email-message-label')"
                                    required
                                >{{ $welcomeMessageDefault ?? '' }}</textarea>

                                <div class="mt-1 flex justify-end gap-2">
                                    <button
                                        type="button"
                                        class="secondary-button"
                                        onclick="document.getElementById('welcome-email-modal-{{ $seller->id }}').close()"
                                    >
                                        @lang('superadmin::app.sellers.view.welcome-email-cancel')
                                    </button>

                                    <button
                                        type="submit"
                                        class="primary-button justify-center"
                                    >
                                        @lang('superadmin::app.sellers.view.welcome-email-send')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endif

                @if (bouncer()->hasPermission('sellers.all.delete'))
                    <form
                        method="post"
                        action="{{ route('superadmin.sellers.destroy', $seller->id) }}"
                        onsubmit="return confirm('@lang('superadmin::app.sellers.view.delete-account-confirmation')')"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
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
                    @include('superadmin::sellers.view.orders')
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.before') !!}
                    @include('superadmin::sellers.view.invoices')
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.before') !!}
                    @include('superadmin::sellers.view.reviews')
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.before') !!}
                    @include('superadmin::sellers.view.notes')
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.after') !!}

                    @if (! empty($sellerApplication))
                        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                            <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
                                Seller Documents
                            </p>

                            <div class="mb-3 grid gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium text-gray-500">Shop name:</span> {{ $sellerApplication->shop_name ?? '-' }}</p>
                                <p><span class="font-medium text-gray-500">Shop address:</span> {{ $sellerApplication->shop_address ?? '-' }}</p>
                                <p><span class="font-medium text-gray-500">Country:</span> {{ $applicationCountryName ?? $sellerApplication->country ?? '-' }}</p>
                                <p><span class="font-medium text-gray-500">Legal name:</span> {{ $sellerApplication->legal_name ?? '-' }}</p>
                                <p><span class="font-medium text-gray-500">ID/passport:</span> {{ $sellerApplication->id_passport_number ?? '-' }}</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-4">
                                @if (! empty($sellerApplication->shop_logo))
                                    <div>
                                        <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-300">Shop Logo</p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->shop_logo) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->shop_logo) }}" alt="Shop logo" class="h-24 w-full rounded border border-gray-200 object-cover dark:border-gray-700">
                                        </a>
                                    </div>
                                @endif

                                @if (! empty($sellerApplication->document_front))
                                    <div>
                                        <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-300">Document Front</p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_front) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_front) }}" alt="Document front" class="h-24 w-full rounded border border-gray-200 object-cover dark:border-gray-700">
                                        </a>
                                    </div>
                                @endif

                                @if (! empty($sellerApplication->document_back))
                                    <div>
                                        <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-300">Document Back</p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_back) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_back) }}" alt="Document back" class="h-24 w-full rounded border border-gray-200 object-cover dark:border-gray-700">
                                        </a>
                                    </div>
                                @endif

                                @if (! empty($sellerApplication->document_selfie))
                                    <div>
                                        <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-300">Document Selfie</p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_selfie) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($sellerApplication->document_selfie) }}" alt="Document selfie" class="h-24 w-full rounded border border-gray-200 object-cover dark:border-gray-700">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <div class="mb-3 flex w-full items-center">
                            <p class="w-full text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.sellers.view.seller-accordion')
                            </p>

                            @if (bouncer()->hasPermission('sellers.all.edit'))
                                <a
                                    href="{{ route('superadmin.sellers.edit', $seller->id) }}"
                                    class="text-sm font-medium text-blue-600 hover:underline"
                                >
                                    @lang('superadmin::app.customers.customers.view.edit.edit-btn')
                                </a>
                            @endif
                        </div>

                        <div class="grid gap-y-2.5">
                            <p class="break-all font-semibold text-gray-800 dark:text-white">
                                {{ trim($sellerFirstName.' '.$sellerLastName) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.email', ['email' => $seller->email ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.phone', ['phone' => $sellerForVue['phone'] ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.gender', ['gender' => $sellerForVue['gender'] ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.date-of-birth', ['dob' => $sellerForVue['date_of_birth'] ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.customers.customers.view.group', ['group_code' => optional($seller->role)->name ?? 'N/A'])
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                Wallet Balance: {{ $seller->wallet_balance ?? 0 }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                Credit Score: {{ ($seller->credit_score ?? 100).'%' }}
                            </p>

                            @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code'))
                                <p class="text-gray-600 dark:text-gray-300">
                                    Referral Code: {{ $seller->referral_code ?: 'N/A' }}
                                </p>
                            @endif

                            @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'seller_level'))
                                <p class="text-gray-600 dark:text-gray-300">
                                    Seller Level: {{ \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level) }}
                                </p>
                            @endif

                            <!-- <p class="text-gray-600 dark:text-gray-300">
                                Max Visible Products: {{ $seller->max_visible_products ?? 0 }}
                            </p> -->
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.before') !!}

                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.customers.customers.view.address.count', ['count' => 0])
                        </p>

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
                    </div>

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.after') !!}
                </div>
            </div>
        </div>
    </div>

</x-superadmin::layouts>