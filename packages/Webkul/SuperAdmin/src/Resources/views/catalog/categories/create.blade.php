<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.categories.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.categories.create.before') !!}

    <!-- Category Create Form -->
    <x-superadmin::form
        :action="route('superadmin.catalog.categories.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.catalog.categories.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.catalog.categories.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.catalog.categories.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.catalog.categories.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.catalog.categories.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">

            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.general.before') !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.catalog.categories.create.general')
                    </p>

                    <!-- Locales -->
                    <x-superadmin::form.control-group.control
                        type="hidden"
                        name="locale"
                        value="all"
                    />

                    <!-- Name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.catalog.categories.create.name')
                        </x-superadmin::form.control-group.label>

                        <v-field
                            type="text"
                            name="name"
                            rules="required"
                            value="{{ old('name') }}"
                            v-slot="{ field, errors }"
                            label="{{ trans('superadmin::app.catalog.categories.create.name') }}"
                        >
                            <input
                                type="text"
                                id="name"
                                :class="[errors.length ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                name="name"
                                v-bind="field"
                                placeholder="{{ trans('superadmin::app.catalog.categories.create.name') }}"
                                v-slugify-target:slug="setValues"
                            />
                        </v-field>

                        <x-superadmin::form.control-group.error control-name="name" />
                    </x-superadmin::form.control-group>

                    <div>
                        <!-- Parent category -->
                        <label class="mb-2.5 block text-xs font-medium leading-6 text-gray-800 dark:text-white">
                            @lang('superadmin::app.catalog.categories.create.parent-category')
                        </label>

                        <!-- Radio select button -->
                        <div class="flex flex-col gap-3">
                            <x-superadmin::tree.view
                                input-type="radio"
                                id-field="id"
                                name-field="parent_id"
                                value-field="id"
                                :items="json_encode($categories)"
                                :fallback-locale="config('app.fallback_locale')"
                            />
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.description_images.before') !!}

                <!-- Description and images -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.catalog.categories.create.description-and-images')
                    </p>

                    <!-- Description -->
                    <v-description v-slot="{ isDescriptionRequired }">
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label ::class="{ 'required' : isDescriptionRequired}">
                                @lang('superadmin::app.catalog.categories.create.description')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="textarea"
                                id="description"
                                class="description"
                                name="description"
                                ::rules="{ 'required' : isDescriptionRequired}"
                                :value="old('description')"
                                :label="trans('superadmin::app.catalog.categories.create.description')"
                                :tinymce="true"
                                :prompt="core()->getConfigData('general.magic_ai.content_generation.category_description_prompt')"
                            />

                            <x-superadmin::form.control-group.error control-name="description" />
                        </x-superadmin::form.control-group>
                    </v-description>

                    <div class="flex pt-5">
                        <!-- Add Logo -->
                        <div class="flex w-2/5 flex-col gap-2">
                            <p class="font-medium text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.categories.create.logo')
                            </p>

                            <p class="text-xs text-gray-500">
                                @lang('superadmin::app.catalog.categories.create.logo-size')
                            </p>

                            <x-superadmin::media.images name="logo_path" />
                        </div>

                        <!-- Add Banner -->
                        <div class="flex w-3/5 flex-col gap-2">
                            <p class="font-medium text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.categories.create.banner')
                            </p>

                            <p class="text-xs text-gray-500">
                                @lang('superadmin::app.catalog.categories.create.banner-size')
                            </p>

                            <x-superadmin::media.images
                                name="banner_path"
                                width="220px"
                            />
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.description_images.after') !!}

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.seo.before') !!}

                <!-- SEO Details -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.catalog.categories.create.seo-details')
                    </p>

                    <!-- SEO Title & Description Blade Component -->
                    <x-superadmin::seo
                        meta-title-field="meta_title"
                        url-key-field="slug"
                        meta-description-field="meta_description"
                    />

                    <div class="mt-8">
                        <!-- Meta Title -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.catalog.categories.create.meta-title')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="meta_title"
                                name="meta_title"
                                :value="old('meta_title')"
                                :label="trans('superadmin::app.catalog.categories.create.meta-title')"
                                :placeholder="trans('superadmin::app.catalog.categories.create.meta-title')"
                            />
                        </x-superadmin::form.control-group>

                        <!-- Slug -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.catalog.categories.create.slug')
                            </x-superadmin::form.control-group.label>

                            <v-field
                                type="text"
                                name="slug"
                                rules="required"
                                value="{{ old('slug') }}"
                                label="{{ trans('superadmin::app.catalog.categories.create.slug') }}"
                                v-slot="{ field, errors }"
                            >
                                <input
                                    type="text"
                                    id="slug"
                                    :class="[errors.length ? 'border border-red-600 hover:border-red-600' : '']"
                                    class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    name="slug"
                                    v-bind="field"
                                    placeholder="{{ trans('superadmin::app.catalog.categories.create.slug') }}"
                                    v-slugify-target:slug
                                />
                            </v-field>

                            <x-superadmin::form.control-group.error control-name="slug" />
                        </x-superadmin::form.control-group>

                        <!-- Meta Keywords -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.catalog.categories.create.meta-keywords')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="meta_keywords"
                                :value="old('meta_keywords')"
                                :label="trans('superadmin::app.catalog.categories.create.meta-keywords')"
                                :placeholder="trans('superadmin::app.catalog.categories.create.meta-keywords')"
                            />
                        </x-superadmin::form.control-group>

                        <!-- Meta Description -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.catalog.categories.create.meta-description')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="textarea"
                                id="meta_description"
                                name="meta_description"
                                :value="old('meta_description')"
                                :label="trans('superadmin::app.catalog.categories.create.meta-description')"
                                :placeholder="trans('superadmin::app.catalog.categories.create.meta-description')"
                            />
                        </x-superadmin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.seo.after') !!}
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- Settings -->

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.accordion.settings.before') !!}

                <x-superadmin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.catalog.categories.create.settings')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Position -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.categories.create.position')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="position"
                                rules="required|integer"
                                :value="old('position')"
                                :label="trans('superadmin::app.catalog.categories.create.position')"
                                :placeholder="trans('superadmin::app.catalog.categories.create.enter-position')"
                            />

                            <x-superadmin::form.control-group.error control-name="position" />
                        </x-superadmin::form.control-group>

                        <!-- Display Mode  -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required font-medium text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.categories.create.display-mode')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                id="display_mode"
                                class="cursor-pointer"
                                name="display_mode"
                                rules="required"
                                value="products_and_description"
                                :label="trans('superadmin::app.catalog.categories.create.display-mode')"
                            >
                                <!-- Options -->
                                <option value="products_and_description">
                                    @lang('superadmin::app.catalog.categories.create.products-and-description')
                                </option>

                                <option value="products_only">
                                    @lang('superadmin::app.catalog.categories.create.products-only')
                                </option>

                                <option value="description_only">
                                    @lang('superadmin::app.catalog.categories.create.description-only')
                                </option>
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="display_mode" />
                        </x-superadmin::form.control-group>

                        <!-- Visible in menu -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="font-medium text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.categories.create.visible-in-menu')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="switch"
                                class="cursor-pointer"
                                name="status"
                                value="1"
                                :label="trans('superadmin::app.catalog.categories.create.visible-in-menu')"
                            />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.accordion.settings.after') !!}

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.accordion.filterable_attributes.before') !!}

                <!-- Filterable Attributes -->
                <x-superadmin::accordion>
                    <x-slot:header>
                        <p class="required p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.catalog.categories.create.filterable-attributes')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        @foreach ($attributes as $attribute)
                            <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5 last:!mb-0">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    :id="$attribute->name ?? $attribute->admin_name"
                                    name="attributes[]"
                                    rules="required"
                                    :value="$attribute->id"
                                    :label="trans('superadmin::app.catalog.categories.create.filterable-attributes')"
                                    :for="$attribute->name ?? $attribute->admin_name"
                                    :checked="in_array($attribute->id, old('attributes', []))"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="{{ $attribute->name ?? $attribute->admin_name }}"
                                    v-pre
                                >
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </label>
                            </x-superadmin::form.control-group>
                        @endforeach

                        <x-superadmin::form.control-group.error control-name="attributes[]" />
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.create.card.accordion.filterable_attributes.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.create.create_form_controls.after') !!}

    </x-superadmin::form>

    {!! view_render_event('bagisto.admin.catalog.categories.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-description-template"
        >
            <div>
                <slot :is-description-required="isDescriptionRequired"></slot>
            </div>
        </script>

        <script type="module">
            window.app.component('v-description', {
                template: '#v-description-template',

                data() {
                    return {
                        isDescriptionRequired: true,

                        displayMode: "{{ old('display_mode') ?? 'products_and_description' }}",
                    };
                },

                mounted() {
                    this.isDescriptionRequired = this.displayMode !== 'products_only';

                    this.$nextTick(() => {
                        document.querySelector('#display_mode').addEventListener('change', (e) => {
                            this.isDescriptionRequired = e.target.value !== 'products_only';
                        });
                    });
                },
            });
        </script>
    @endPushOnce

</x-superadmin::layouts>
