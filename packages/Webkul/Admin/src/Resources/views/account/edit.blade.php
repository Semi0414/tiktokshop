@php use Illuminate\Support\Facades\Storage; @endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.account.edit.title')
    </x-slot>

    <main class="w-full min-w-0 max-w-full pb-8">
        <form
            method="POST"
            action="{{ route('admin.account.update') }}"
            enctype="multipart/form-data"
            class="grid w-full min-w-0 gap-6"
        >
            @csrf
            @method('PUT')

            <header class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-4 dark:border-gray-800">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white sm:text-2xl">
                        @lang('admin::app.account.edit.title')
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-2 sm:gap-x-2.5">
                    <a
                        href="{{ route('admin.dashboard.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('admin::app.account.edit.back-btn')
                    </a>

                    <button type="submit" class="primary-button">
                        @lang('admin::app.account.edit.save-btn')
                    </button>
                </div>
            </header>

            <div class="flex min-w-0 flex-col gap-6 xl:flex-row xl:items-start">
                <div class="min-w-0 flex-1 space-y-6">
                    @if (! empty($sellerApplication))
                        <section
                            class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                            aria-labelledby="account-store-registration-heading"
                        >
                            <h2 id="account-store-registration-heading" class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.account.edit.store-registration-title')
                            </h2>

                            <dl class="grid gap-3 text-sm text-gray-700 dark:text-gray-300">
                                @if (! empty($sellerApplication->shop_logo))
                                    <div class="flex flex-wrap items-start gap-3">
                                        <dt class="min-w-[8rem] font-medium text-gray-500">@lang('admin::app.account.edit.shop-logo')</dt>
                                        <dd>
                                            <a href="{{ Storage::url($sellerApplication->shop_logo) }}" target="_blank" rel="noopener noreferrer" class="inline-block text-blue-600 hover:underline">
                                                <img src="{{ Storage::url($sellerApplication->shop_logo) }}" alt="" width="64" height="64" class="h-16 w-16 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.shop-name')</dt>
                                    <dd>{{ $sellerApplication->shop_name }}</dd>
                                </div>
                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.shop-address')</dt>
                                    <dd>{{ $sellerApplication->shop_address }}</dd>
                                </div>
                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.country')</dt>
                                    <dd>{{ $applicationCountryName ?? $sellerApplication->country }}</dd>
                                </div>
                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.legal-name')</dt>
                                    <dd>{{ $sellerApplication->legal_name }}</dd>
                                </div>
                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.id-passport-number')</dt>
                                    <dd>{{ $sellerApplication->id_passport_number }}</dd>
                                </div>
                                <div class="grid gap-1 sm:grid-cols-[minmax(8rem,auto)_1fr] sm:gap-3">
                                    <dt class="font-medium text-gray-500">@lang('admin::app.account.edit.invite-code-used')</dt>
                                    <dd><span class="font-mono">{{ $sellerApplication->invite_code }}</span></dd>
                                </div>
                            </dl>

                            <div class="mt-4 flex flex-wrap gap-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                                @if (! empty($sellerApplication->document_front))
                                    <figure class="text-center">
                                        <figcaption class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-front')</figcaption>
                                        <a href="{{ Storage::url($sellerApplication->document_front) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_front) }}" alt="" width="112" height="80" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </figure>
                                @endif
                                @if (! empty($sellerApplication->document_back))
                                    <figure class="text-center">
                                        <figcaption class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-back')</figcaption>
                                        <a href="{{ Storage::url($sellerApplication->document_back) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_back) }}" alt="" width="112" height="80" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </figure>
                                @endif
                                @if (! empty($sellerApplication->document_selfie))
                                    <figure class="text-center">
                                        <figcaption class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-selfie')</figcaption>
                                        <a href="{{ Storage::url($sellerApplication->document_selfie) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_selfie) }}" alt="" width="112" height="80" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </figure>
                                @endif
                            </div>
                        </section>
                    @endif

                    <section
                        class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                        aria-labelledby="account-general-heading"
                    >
                        <h2 id="account-general-heading" class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.account.edit.general')
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="account-profile-image" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.account.edit.profile-image')
                                </label>
                                @if ($user->image)
                                    <div class="mb-2">
                                        <img src="{{ $user->image_url }}" alt="" width="110" height="110" class="h-28 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                    </div>
                                @endif
                                <input
                                    id="account-profile-image"
                                    type="file"
                                    name="image"
                                    accept="image/bmp,image/jpeg,image/jpg,image/png,image/webp"
                                    class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium dark:text-gray-300 dark:file:bg-gray-800"
                                />
                                <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.account.edit.upload-image-info')
                                </p>
                                @error('image')
                                    <p class="mt-1 text-xs italic text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account-name" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    <span class="text-red-600" aria-hidden="true">*</span> @lang('admin::app.account.edit.name')
                                </label>
                                <input
                                    id="account-name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    autocomplete="name"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 @error('name') !border-red-600 @enderror"
                                />
                                @error('name')
                                    <p class="mt-1 text-xs italic text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <p id="account-email-label" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.account.edit.email')
                                </p>
                                <input
                                    id="account-email"
                                    type="email"
                                    value="{{ $user->email }}"
                                    readonly
                                    tabindex="-1"
                                    autocomplete="email"
                                    aria-labelledby="account-email-label"
                                    aria-readonly="true"
                                    class="w-full cursor-not-allowed rounded-md border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-300"
                                />
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="w-full min-w-0 shrink-0 xl:w-[360px]">
                    <details open class="box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <summary class="cursor-pointer list-none border-b border-gray-200 px-3 py-3 text-base font-semibold text-gray-800 dark:border-gray-800 dark:text-white sm:px-4">
                            @lang('admin::app.account.edit.change-password')
                        </summary>

                        <div class="space-y-4 p-4">
                            <div>
                                <label for="account-current-password" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    <span class="text-red-600" aria-hidden="true">*</span> @lang('admin::app.account.edit.current-password')
                                </label>
                                <input
                                    id="account-current-password"
                                    type="password"
                                    name="current_password"
                                    required
                                    minlength="6"
                                    autocomplete="current-password"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 @error('current_password') !border-red-600 @enderror"
                                />
                                @error('current_password')
                                    <p class="mt-1 text-xs italic text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account-new-password" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.account.edit.password')
                                </label>
                                <input
                                    id="account-new-password"
                                    type="password"
                                    name="password"
                                    minlength="6"
                                    autocomplete="new-password"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 @error('password') !border-red-600 @enderror"
                                />
                                @error('password')
                                    <p class="mt-1 text-xs italic text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account-password-confirmation" class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.account.edit.confirm-password')
                                </label>
                                <input
                                    id="account-password-confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    minlength="6"
                                    autocomplete="new-password"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 @error('password_confirmation') !border-red-600 @enderror"
                                />
                                @error('password_confirmation')
                                    <p class="mt-1 text-xs italic text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </details>
                </aside>
            </div>
        </form>
    </main>
</x-admin::layouts>

<style>
    aside details > summary::-webkit-details-marker {
        display: none;
    }
    aside details > summary::marker {
        display: none;
        content: '';
    }
</style>
