<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.settings.data-transfer.imports.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.before') !!}

    <x-superadmin::form
        :action="route('superadmin.settings.data_transfer.imports.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.data-transfer.imports.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.settings.data_transfer.imports.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.settings.data-transfer.imports.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.settings.data-transfer.imports.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Body Content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.before') !!}

                <!-- Setup Import Panel -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.settings.data-transfer.imports.create.general')
                    </p>

                    <!-- Type -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.data-transfer.imports.create.type')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="select"
                            name="type"
                            id="import-type"
                            :value="old('type') ?? 'products'"
                            ref="importType"
                            rules="required"
                            :label="trans('superadmin::app.settings.data-transfer.imports.create.type')"
                        >
                            @foreach (config('importers') as $code => $importer)
                                <option value="{{ $code }}">@lang($importer['title'])</option>
                            @endforeach
                        </x-superadmin::form.control-group.control>

                        <!-- Source Sample Download Links -->
                        <div class="flex items-center mt-2.5">
                            <span>
                                @lang('superadmin::app.settings.data-transfer.imports.create.download-sample')
                            </span>

                            <x-superadmin::dropdown>
                                <x-slot:toggle>
                                    <span class="cursor-pointer text-2xl icon-arrow-down"></span>
                                </x-slot>

                                <x-slot:content>
                                    <div class="grid gap-2.5 max-md:my-0">
                                        @foreach ($supportedFormats as $format)
                                            <a
                                                :href="'{{ route('superadmin.settings.data_transfer.imports.download_sample', ['type' => ':type:', 'format' => ':format:']) }}'.replace(':type:', $refs['importType']?.value).replace(':format:', '{{ $format }}')"
                                                target="_blank"
                                                id="source-sample-link"
                                                class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                                            >
                                                {{ strtoupper($format) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </x-slot>
                            </x-superadmin::dropdown>
                        </div>

                        <x-superadmin::form.control-group.error control-name="type" />
                    </x-superadmin::form.control-group>

                    <!-- Images Directory Path -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.data-transfer.imports.create.file')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="file"
                            name="file"
                            rules="required"
                            :label="trans('superadmin::app.settings.data-transfer.imports.create.file')"
                        />

                        <x-superadmin::form.control-group.error control-name="file" />
                    </x-superadmin::form.control-group>

                    <!-- Images Directory Path -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.data-transfer.imports.create.images-directory')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            name="images_directory_path"
                            :value="old('images_directory_path')"
                            :placeholder="trans('superadmin::app.settings.data-transfer.imports.create.images-directory')"
                        />

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('superadmin::app.settings.data-transfer.imports.create.file-info')
                        </p>

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('superadmin::app.settings.data-transfer.imports.create.file-info-example')
                        </p>
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.after') !!}
            </div>

            <!-- Right Container -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.before') !!}

                <!-- Settings Panel -->
                <x-superadmin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.settings.data-transfer.imports.create.settings')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <!-- Action -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.data-transfer.imports.create.action')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                name="action"
                                id="action"
                                :value="old('action') ?? 'append'"
                                rules="required"
                                :label="trans('superadmin::app.settings.data-transfer.imports.create.action')"
                            >
                                <option value="append">@lang('superadmin::app.settings.data-transfer.imports.create.create-update')</option>
                                <option value="delete">@lang('superadmin::app.settings.data-transfer.imports.create.delete')</option>
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="action" />
                        </x-superadmin::form.control-group>

                        <!-- Validation Strategy -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.data-transfer.imports.create.validation-strategy')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                name="validation_strategy"
                                id="validation_strategy"
                                :value="old('validation_strategy') ?? 'stop-on-errors'"
                                rules="required"
                                :label="trans('superadmin::app.settings.data-transfer.imports.create.validation-strategy')"
                            >
                                <option value="stop-on-errors">@lang('superadmin::app.settings.data-transfer.imports.create.stop-on-errors')</option>
                                <option value="skip-errors">@lang('superadmin::app.settings.data-transfer.imports.create.skip-errors')</option>
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="validation_strategy" />
                        </x-superadmin::form.control-group>

                        <!-- Allowed Errors -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.data-transfer.imports.create.allowed-errors')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="allowed_errors"
                                :value="old('allowed_errors') ?? 10"
                                rules="required"
                                :label="trans('superadmin::app.settings.data-transfer.imports.create.allowed-errors')"
                                :placeholder="trans('superadmin::app.settings.data-transfer.imports.create.allowed-errors')"
                            />

                            <x-superadmin::form.control-group.error control-name="allowed_errors" />
                        </x-superadmin::form.control-group>

                        <!-- CSV Field Separator -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.data-transfer.imports.create.field-separator')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="field_separator"
                                :value="old('field_separator') ?? ','"
                                rules="required"
                                :label="trans('superadmin::app.settings.data-transfer.imports.create.field-separator')"
                                :placeholder="trans('superadmin::app.settings.data-transfer.imports.create.field-separator')"
                            />

                            <x-superadmin::form.control-group.error control-name="field_separator" />
                        </x-superadmin::form.control-group>

                        <!-- Process In Queue -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.data-transfer.imports.create.process-in-queue')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="switch"
                                name="process_in_queue"
                                id="maintenance-mode-status"
                                :checked="false"
                            />

                            <x-superadmin::form.control-group.error control-name="process_in_queue" />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.after') !!}
    </x-superadmin::form>
</x-superadmin::layouts>
