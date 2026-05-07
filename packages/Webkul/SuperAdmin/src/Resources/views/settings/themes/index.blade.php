<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.themes.index.title')
    </x-slot>
   
    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.themes.index.title')
        </p>
        
        <div class="flex items-center gap-x-2.5">
            <div class="flex items-center gap-x-2.5">
                {!! view_render_event('bagisto.admin.settings.themes.create.before') !!}

                <!-- Create Button -->
                <v-create-theme-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('superadmin::app.settings.themes.index.create-btn')
                    </button>  
                </v-create-theme-form>

                {!! view_render_event('bagisto.admin.settings.themes.create.after') !!}
            </div>
        </div>
    </div>
    
    {!! view_render_event('bagisto.admin.settings.themes.list.before') !!}

    <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.settings.themes.index')" />

    {!! view_render_event('bagisto.admin.settings.themes.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-theme-form-template"
        >
            <div>
                <!-- Theme Create Button -->
                @if (bouncer()->hasPermission('settings.themes.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.themeCreateModal.toggle()"
                    >
                        @lang('superadmin::app.settings.themes.index.create-btn')
                    </button>
                @endif

                <!-- Modal Form -->
                <x-superadmin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Customer Create Modal -->
                        <x-superadmin::modal ref="themeCreateModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('superadmin::app.settings.themes.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <!-- Name -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.themes.create.name')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('superadmin::app.settings.themes.create.name')"
                                        :placeholder="trans('superadmin::app.settings.themes.create.name')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="name" />
                                </x-superadmin::form.control-group>

                                <!-- Sort Order -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.themes.create.sort-order')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required|numeric"
                                        :label="trans('superadmin::app.settings.themes.create.sort-order')"
                                        :placeholder="trans('superadmin::app.settings.themes.create.sort-order')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="sort_order" />
                                </x-superadmin::form.control-group>

                                <!-- Type -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.themes.create.type.title')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        value="product_carousel"
                                    >
                                        <option 
                                            v-for="(type, key) in themeTypes"
                                            :value="key"
                                            :text="type"
                                        >
                                        </option>
                                    </x-superadmin::form.control-group.control>

                                    <x-superadmin::form.control-group.error control-name="type" />
                                </x-superadmin::form.control-group>

                                <!-- Channels -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.themes.edit.channels')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="select"
                                        name="channel_id"
                                        rules="required"
                                        :value="1"
                                    >
                                        @foreach (core()->getAllChannels() as $channel)
                                            <option
                                                value="{{ $channel->id }}"
                                                v-pre
                                            >
                                                {{ $channel->name }}
                                            </option>
                                        @endforeach 
                                    </x-superadmin::form.control-group.control>

                                    <x-superadmin::form.control-group.error control-name="type" />
                                </x-superadmin::form.control-group>

                                 <!-- Theme Selector -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.themes.create.themes')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="select"
                                        id="theme_code"
                                        name="theme_code"
                                        :value="config('themes.admin-default')"
                                        :label="trans('superadmin::app.settings.themes.create.themes')"
                                    >
                                        @foreach (config('themes.shop') as $themeCode => $theme)
                                            <option
                                                value="{{ $themeCode }}" {{ old('theme') == $themeCode ? 'selected' : '' }}
                                                v-pre
                                            >
                                                {{ $theme['name'] }}
                                            </option>
                                        @endforeach
                                    </x-superadmin::form.control-group.control>

                                    <x-superadmin::form.control-group.error control-name="theme" />
                                </x-superadmin::form.control-group>
                            </x-slot>

                             <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Save Button -->
                                <x-superadmin::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('superadmin::app.settings.themes.create.save-btn')"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
                            </x-slot>
                        </x-superadmin::modal>
                    </form>
                </x-superadmin::form>
            </div>
        </script>

        <script type="module">
            window.app.component('v-create-theme-form', {
                template: '#v-create-theme-form-template',

                data() {
                    return {
                        themeTypes: {
                            product_carousel: "@lang('superadmin::app.settings.themes.create.type.product-carousel')",
                            category_carousel: "@lang('superadmin::app.settings.themes.create.type.category-carousel')",
                            static_content: "@lang('superadmin::app.settings.themes.create.type.static-content')",
                            image_carousel: "@lang('superadmin::app.settings.themes.create.type.image-carousel')",
                            footer_links: "@lang('superadmin::app.settings.themes.create.type.footer-links')",
                            services_content: "@lang('superadmin::app.settings.themes.create.type.services-content')",
                        },

                        isLoading: false,
                    };
                },

                methods: {
                    create(params, { setErrors }) {
                        this.isLoading = true;

                        this.$axios.post('{{ route('superadmin.settings.themes.store') }}', params)
                            .then((response) => {
                                this.isLoading = false;

                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } 
                            })
                            .catch((error) => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
    
</x-superadmin::layouts>