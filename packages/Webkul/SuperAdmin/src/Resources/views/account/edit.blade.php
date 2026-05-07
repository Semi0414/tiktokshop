<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.account.edit.title')
    </x-slot>

    <!-- Input Form -->
    <x-superadmin::form
        :action="route('superadmin.account.update')"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.account.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                 <!-- Back Button -->
                <a
                    href="{{ route('superadmin.dashboard.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.account.edit.back-btn')
                </a>

                <!-- Save Button -->
                <div class="flex items-center gap-x-2.5">
                    <button 
                        type="submit"
                        class="primary-button"
                    >
                        @lang('superadmin::app.account.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
             <!-- Left sub Component -->
             <div class="flex flex-1 flex-col gap-2">
                 <!-- General -->
                 <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.account.edit.general')
                    </p>

                    <!-- Image -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::media.images
                            name="image"
                            :uploaded-images="$user->image ? [['id' => 'image', 'url' => $user->image_url]] : []"
                        />
                    </x-superadmin::form.control-group>

                    <p class="mb-4 text-xs text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.account.edit.upload-image-info')
                    </p>

                    <!-- Name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.account.edit.name')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name') ?: $user->name"
                            :label="trans('superadmin::app.account.edit.name')"
                            :placeholder="trans('superadmin::app.account.edit.name')"
                        />

                        <x-superadmin::form.control-group.error control-name="name" />
                    </x-superadmin::form.control-group>

                    <!-- Email -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.account.edit.email')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="email"
                            name="email"
                            id="email"
                            rules="required"
                            :value="old('email') ?: $user->email"
                            :label="trans('superadmin::app.account.edit.email')"
                        />

                        <x-superadmin::form.control-group.error control-name="email" />
                    </x-superadmin::form.control-group>
                </div>
             </div>

             <!-- Right sub-component -->
             <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
                <x-superadmin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.account.edit.change-password')
                        </p>
                    </x-slot>

                     <!-- Change Account Password -->
                    <x-slot:content>
                        <!-- Current Password -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.account.edit.current-password')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="password"
                                name="current_password"
                                rules="required|min:6"
                                :label="trans('superadmin::app.account.edit.current-password')"
                                :placeholder="trans('superadmin::app.account.edit.current-password')"
                            />

                            <x-superadmin::form.control-group.error control-name="current_password" />
                        </x-superadmin::form.control-group>

                        <!-- Password -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.account.edit.password')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="password"
                                name="password"
                                rules="min:6"
                                :placeholder="trans('superadmin::app.account.edit.password')"
                                ref="password"
                            />

                            <x-superadmin::form.control-group.error control-name="password" />
                        </x-superadmin::form.control-group>

                        <!-- Confirm Password -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.account.edit.confirm-password')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="password"
                                name="password_confirmation"
                                rules="confirmed:@password"
                                :label="trans('superadmin::app.account.edit.confirm-password')"
                                :placeholder="trans('superadmin::app.account.edit.confirm-password')"
                            />

                            <x-superadmin::form.control-group.error control-name="password_confirmation" />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>
             </div>
        </div>
    </x-superadmin::form>
</x-superadmin::layouts>
