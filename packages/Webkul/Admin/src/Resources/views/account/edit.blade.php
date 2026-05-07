@php use Illuminate\Support\Facades\Storage; @endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.account.edit.title')
    </x-slot>

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.account.update')"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.account.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                 <!-- Back Button -->
                <a
                    href="{{ route('admin.dashboard.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>

                <!-- Save Button -->
                <div class="flex items-center gap-x-2.5">
                    <button 
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.account.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
             <!-- Left sub Component -->
             <div class="flex flex-1 flex-col gap-2">
                @if (! empty($sellerApplication))
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.account.edit.store-registration-title')
                        </p>

                        <div class="grid gap-3 text-sm text-gray-700 dark:text-gray-300">
                            @if (! empty($sellerApplication->shop_logo))
                                <div class="flex flex-wrap items-start gap-3">
                                    <span class="min-w-[8rem] font-medium text-gray-500">@lang('admin::app.account.edit.shop-logo')</span>
                                    <a href="{{ Storage::url($sellerApplication->shop_logo) }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                                        <img src="{{ Storage::url($sellerApplication->shop_logo) }}" alt="" class="h-16 w-16 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                    </a>
                                </div>
                            @endif

                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.shop-name')</span> {{ $sellerApplication->shop_name }}</p>
                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.shop-address')</span> {{ $sellerApplication->shop_address }}</p>
                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.country')</span> {{ $applicationCountryName ?? $sellerApplication->country }}</p>
                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.legal-name')</span> {{ $sellerApplication->legal_name }}</p>
                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.id-passport-number')</span> {{ $sellerApplication->id_passport_number }}</p>
                            <p><span class="font-medium text-gray-500">@lang('admin::app.account.edit.invite-code-used')</span> <span class="font-mono">{{ $sellerApplication->invite_code }}</span></p>

                            <div class="flex flex-wrap gap-4 pt-2">
                                @if (! empty($sellerApplication->document_front))
                                    <div class="text-center">
                                        <p class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-front')</p>
                                        <a rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_front) }}" alt="" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </div>
                                @endif
                                @if (! empty($sellerApplication->document_back))
                                    <div class="text-center">
                                        <p class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-back')</p>
                                        <a rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_back) }}" alt="" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </div>
                                @endif
                                @if (! empty($sellerApplication->document_selfie))
                                    <div class="text-center">
                                        <p class="mb-1 text-xs text-gray-500">@lang('admin::app.account.edit.document-selfie')</p>
                                        <a rel="noopener noreferrer">
                                            <img src="{{ Storage::url($sellerApplication->document_selfie) }}" alt="" class="h-20 w-28 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                 <!-- General -->
                 <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.account.edit.general')
                    </p>

                    <!-- Image -->
                    <x-admin::form.control-group>
                        <x-admin::media.images
                            name="image"
                            :uploaded-images="$user->image ? [['id' => 'image', 'url' => $user->image_url]] : []"
                        />
                    </x-admin::form.control-group>

                    <p class="mb-4 text-xs text-gray-600 dark:text-gray-300">
                        @lang('admin::app.account.edit.upload-image-info')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name') ?: $user->name"
                            :label="trans('admin::app.account.edit.name')"
                            :placeholder="trans('admin::app.account.edit.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Email -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.account.edit.email')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="email"
                            name="email"
                            id="email"
                            rules="required"
                            :value="old('email') ?: $user->email"
                            :label="trans('admin::app.account.edit.email')"
                        />

                        <x-admin::form.control-group.error control-name="email" />
                    </x-admin::form.control-group>
                </div>
             </div>

             <!-- Right sub-component -->
             <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.account.edit.change-password')
                        </p>
                    </x-slot>

                     <!-- Change Account Password -->
                    <x-slot:content>
                        <!-- Current Password -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.account.edit.current-password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="current_password"
                                rules="required|min:6"
                                :label="trans('admin::app.account.edit.current-password')"
                                :placeholder="trans('admin::app.account.edit.current-password')"
                            />

                            <x-admin::form.control-group.error control-name="current_password" />
                        </x-admin::form.control-group>

                        <!-- Password -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="password"
                                rules="min:6"
                                :placeholder="trans('admin::app.account.edit.password')"
                                ref="password"
                            />

                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>

                        <!-- Confirm Password -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.account.edit.confirm-password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                name="password_confirmation"
                                rules="confirmed:@password"
                                :label="trans('admin::app.account.edit.confirm-password')"
                                :placeholder="trans('admin::app.account.edit.confirm-password')"
                            />

                            <x-admin::form.control-group.error control-name="password_confirmation" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
             </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
