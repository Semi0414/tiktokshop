<x-superadmin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.users.forget-password.create.page-title')
    </x-slot>

    <div class="flex h-[100vh] items-center justify-center">
        <div class="flex flex-col items-center gap-5">
            <!-- Logo -->
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    class="h-10 w-[110px]"
                    src="{{ Storage::url($logo) }}"
                    alt="{{ config('app.name') }}"
                    width="131" height="29" style="background-color: paleturquoise;padding: 10px 30px;border-radius: 50px;"
                />
            @else
                <img
                    class="w-max" 
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131" height="29" style="background-color: paleturquoise;padding: 10px 30px;border-radius: 50px;"
                />
            @endif

            <div class="box-shadow flex min-w-[300px] flex-col rounded-md bg-white dark:bg-gray-900">
                <!-- Forget Password Form -->
                <x-superadmin::form :action="route('superadmin.forget_password.store')">
                    <div class="p-4">
                        <p class="text-xl font-bold text-gray-800 dark:text-white">
                            @lang('superadmin::app.users.forget-password.create.title')
                        </p>
                    </div>

                    <div class="border-y p-4 dark:border-gray-800">
                        <!-- Registered Email -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.users.forget-password.create.email')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="email"
                                class="w-[254px] max-w-full" 
                                id="email"
                                name="email" 
                                rules="required|email" 
                                :value="old('email')"
                                :label="trans('superadmin::app.users.forget-password.create.email')"
                                :placeholder="trans('superadmin::app.users.forget-password.create.email')"
                            />

                            <x-superadmin::form.control-group.error control-name="email" />
                        </x-superadmin::form.control-group>
                    </div>

                    <div class="flex items-center justify-between p-4">
                        <!-- Back to Sign In link -->
                        <a 
                            class="cursor-pointer text-xs font-semibold leading-6 text-blue-600"
                            href="{{ route('superadmin.session.create') }}"
                        >
                            @lang('superadmin::app.users.forget-password.create.sign-in-link')
                        </a>

                        <!-- Form Submit Button -->
                        <button 
                            class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50">
                            @lang('superadmin::app.users.forget-password.create.submit-btn')
                        </button>
                    </div>
                </x-superadmin::form>
            </div>

            <!-- Powered By -->
            <div class="text-sm font-normal">
                @lang('superadmin::app.users.forget-password.create.powered-by-description', [
                    'bagisto' => '<a class="text-blue-600 hover:underline" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                    'webkul' => '<a class="text-blue-600 hover:underline" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                ])
            </div>
        </div>
    </div>
</x-superadmin::layouts.anonymous>