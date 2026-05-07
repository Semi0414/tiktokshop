<x-superadmin::layouts>
    <!--Page title -->
    <x-slot:title>
        @lang('superadmin::app.cms.create.title')
    </x-slot>

    <!--Create Page Form -->
    <x-superadmin::form
        :action="route('superadmin.cms.store')"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.cms.pages.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.cms.create.title')
            </p>


            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.cms.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.account.edit.back-btn')
                </a>

                <!--Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.cms.create.save-btn')
                </button>
            </div>
        </div>

        <!-- body content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.cms.pages.create.card.description.before') !!}

                <!--Content -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.cms.create.description')
                    </p>

                    <!-- Html Content -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.cms.create.content')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="textarea"
                            id="content"
                            name="html_content"
                            rules="required"
                            :value="old('html_content')"
                            :label="trans('superadmin::app.cms.create.content')"
                            :placeholder="trans('superadmin::app.cms.create.content')"
                            :tinymce="true"
                            :prompt="core()->getConfigData('general.magic_ai.content_generation.cms_page_content_prompt')"
                        />

                        <x-superadmin::form.control-group.error control-name="html_content" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.create.card.description.after') !!}

                {!! view_render_event('bagisto.admin.cms.pages.create.card.seo.before') !!}

                <!-- SEO Input Fields -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.cms.create.seo')
                    </p>

                    <!-- SEO Title & Description Blade Component -->
                    <x-superadmin::seo
                        slug="page"
                        meta-title-field="meta_title"
                        url-key-field="url_key"
                        meta-description-field="meta_description"
                    />

                    <!-- Meta Title -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.cms.create.meta-title')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="meta_title"
                            name="meta_title"
                            :value="old('meta_title')"
                            :label="trans('superadmin::app.cms.create.meta-title')"
                            :placeholder="trans('superadmin::app.cms.create.meta-title')"
                        />

                        <x-superadmin::form.control-group.error control-name="meta_title" />
                    </x-superadmin::form.control-group>

                    <!-- URL Key -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.cms.create.url-key')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="url_key"
                            name="url_key"
                            rules="required"
                            :value="old('url_key')"
                            :label="trans('superadmin::app.cms.create.url-key')"
                            :placeholder="trans('superadmin::app.cms.create.url-key')"
                        />

                        <x-superadmin::form.control-group.error control-name="url_key" />
                    </x-superadmin::form.control-group>

                    <!-- Meta Keywords -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.cms.create.meta-keywords')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="textarea"
                            id="meta_keywords"
                            name="meta_keywords"
                            :value="old('meta_keywords')"
                            :label="trans('superadmin::app.cms.create.meta-keywords')"
                            :placeholder="trans('superadmin::app.cms.create.meta-keywords')"
                        />

                        <x-superadmin::form.control-group.error control-name="meta_keywords" />
                    </x-superadmin::form.control-group>

                    <!-- Meta Description -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.cms.create.meta-description')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="textarea"
                            id="meta_description"
                            name="meta_description"
                            :value="old('meta_description')"
                            :label="trans('superadmin::app.cms.create.meta-description')"
                            :placeholder="trans('superadmin::app.cms.create.meta-description')"
                        />

                        <x-superadmin::form.control-group.error control-name="meta_description" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.create.card.seo.after') !!}
            </div>

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                <!-- General -->

                {!! view_render_event('bagisto.admin.cms.pages.create.card.accordion.general.before') !!}

                <x-superadmin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.cms.create.general')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Page Title -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.cms.create.page-title')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="page_title"
                                name="page_title"
                                rules="required"
                                :value="old('page_title')"
                                :label="trans('superadmin::app.cms.create.page-title')"
                                :placeholder="trans('superadmin::app.cms.create.page-title')"
                            />

                            <x-superadmin::form.control-group.error control-name="page_title" />
                        </x-superadmin::form.control-group>

                        <!-- Select Channels -->
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.cms.create.channels')
                        </x-superadmin::form.control-group.label>

                        @foreach(core()->getAllChannels() as $channel)
                            <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5 last:!mb-0">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    :id="'channels_' . $channel->id"
                                    name="channels[]"
                                    rules="required"
                                    :value="$channel->id"
                                    :for="'channels_' . $channel->id"
                                    :label="trans('superadmin::app.cms.create.channels')"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="channels_{{ $channel->id }}"
                                    v-pre
                                >
                                    {{ core()->getChannelName($channel) }}
                                </label>
                            </x-superadmin::form.control-group>
                        @endforeach

                        <x-superadmin::form.control-group.error control-name="channels[]" />
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.cms.pages.create.card.accordion.general.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.cms.pages.create.create_form_controls.after') !!}

    </x-superadmin::form>
</x-superadmin::layouts>
