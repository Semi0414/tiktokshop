<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.create.before') !!}

    <!-- Create Sitemap Vue Component -->
    <v-create-sitemaps>
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.marketing.search-seo.url-rewrites.index.title')
            </p>

            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.create'))
                <div class="primary-button">
                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create-btn')
                </div>
            @endif
        </div>

        <!-- Added For Shimmer -->
        <x-superadmin::shimmer.datagrid />
    </v-create-sitemaps>

    {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-sitemaps-template"
        >
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.create'))
                    <div
                        class="primary-button"
                        @click="selectedSitemap=0; $refs.sitemap.toggle()"
                    >
                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.list.before') !!}

            <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.marketing.search_seo.url_rewrites.index')"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-superadmin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                    <input
                                        type="checkbox"
                                        class="peer hidden"
                                        :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        :value="record[available.meta.primary_column]"
                                        :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        v-model="applied.massActions.indices"
                                    >

                                    <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600">
                                    </span>
                                </label>
                            </p>

                            <!-- Id -->
                            <p class="break-words">
                                @{{ record.id }}
                            </p>

                            <!-- For -->
                            <p class="break-words">
                                @{{ record.entity_type }}
                            </p>

                            <!-- Request Path -->
                            <p class="break-words">
                                @{{ record.request_path }}
                            </p>

                            <!-- Target Path -->
                            <p class="break-words">
                                @{{ record.target_path }}
                            </p>

                            <!-- Redirect Type -->
                            <p class="break-words">
                                @{{ record.redirect_type }}
                            </p>

                            <!-- Locale -->
                            <p class="break-words">
                                @{{ record.locale }}
                            </p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.edit'))
                                    <a @click="selectedSitemap=1; editModal(record)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.delete'))
                                    <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                        <span
                                            :class="record.actions.find(action => action.index === 'delete')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </template>
                </template>
            </x-superadmin::datagrid.ssr>

            {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.list.after') !!}

            <!-- Model Form -->
            <x-superadmin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <!-- Create Sitemap form -->
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="sitemapCreateForm"
                >
                    <x-superadmin::modal ref="sitemap">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <!-- Create Modal title -->
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="selectedSitemap"
                            >
                                @lang('superadmin::app.marketing.search-seo.url-rewrites.index.edit.title')
                            </p>

                            <!-- Edit Modal title -->
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- ID -->
                            <x-superadmin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <!-- Entity Type -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.for')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    name="entity_type"
                                    rules="required"
                                    :label="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.for')"
                                >
                                    <option value="product">
                                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.product')
                                    </option>

                                    <option value="category">
                                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.category')
                                    </option>

                                    <option value="cms_page">
                                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.cms-page')
                                    </option>
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="entity_type" />
                            </x-superadmin::form.control-group>

                            <!-- Request Path -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.request-path')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="request_path"
                                    rules="required"
                                    :label="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.request-path')"
                                    :placeholder="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.request-path')"
                                >
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="request_path" />
                            </x-superadmin::form.control-group>

                            <!-- Target Path -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.target-path')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="target_path"
                                    rules="required"
                                    :label="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.target-path')"
                                    :placeholder="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.target-path')"
                                >
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="target_path" />
                            </x-superadmin::form.control-group>

                            <!-- Redirect Type -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.redirect-type')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    name="redirect_type"
                                    rules="required"
                                    :label="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.redirect-type')"
                                >
                                    <option value="302">
                                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.temporary-redirect')
                                    </option>

                                    <option value="301">
                                        @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.permanent-redirect')
                                    </option>
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="redirect_type" />
                            </x-superadmin::form.control-group>

                            <!-- Locales -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.marketing.search-seo.url-rewrites.index.create.locale')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    name="locale"
                                    rules="required"
                                    :label="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.locale')"
                                >
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option 
                                            value="{{ $locale->code }}"
                                            v-pre
                                        >
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="locale" />
                            </x-superadmin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-superadmin::button
                                button-type="submit"
                                class="primary-button"
                                :title="trans('superadmin::app.marketing.search-seo.url-rewrites.index.create.save-btn')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
                        </x-slot>
                    </x-superadmin::modal>
                </form>
            </x-superadmin::form>
        </script>

        <script type="module">
            window.app.component('v-create-sitemaps', {
                template: '#v-create-sitemaps-template',

                data() {
                    return {
                        selectedSitemap: 0,

                        isLoading: false,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.sitemapCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('superadmin.marketing.search_seo.url_rewrites.update') }}" : "{{ route('superadmin.marketing.search_seo.url_rewrites.store') }}", formData )
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.sitemap.toggle();

                                this.$refs.datagrid.get();

                                resetForm();

                                this.isLoading = false;
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(values) {
                        this.$refs.sitemap.toggle();

                        this.$refs.modalForm.setValues(values);
                    },
                },
            })
        </script>
    @endPushOnce
</x-superadmin::layouts>
