<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.settings.taxes.categories.index.title')
    </x-slot>

    <v-tax-categories>
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.taxes.categories.index.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <div class="flex items-center gap-x-2.5">
                    <!-- Create Tax Category Button -->
                    @if (bouncer()->hasPermission('settings.taxes.tax_categories.create'))
                        <button
                            type="button"
                            class="primary-button"
                        >
                            @lang('superadmin::app.settings.taxes.categories.index.create.title')
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- DataGrid Shimmer -->
        <x-superadmin::shimmer.datagrid />
    </v-tax-categories>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-tax-categories-template"
        >
            <div class="flex items-center justify-between">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('superadmin::app.settings.taxes.categories.index.title')
                </p>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        <!-- Create Tax Category Button -->
                        @if (bouncer()->hasPermission('settings.taxes.tax_categories.create'))
                            <button
                                type="button"
                                class="primary-button"
                                @click="selectedTaxRates={}; selectedTaxCategories=0; $refs.taxCategory.toggle()"
                            >
                                @lang('superadmin::app.settings.taxes.categories.index.create.title')
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.settings.taxes.categories.index')"
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
                            <!-- ID -->
                            <p>@{{ record.id }}</p>

                            <!-- Name -->
                            <p>@{{ record.name }}</p>

                            <!-- Code -->
                            <p>@{{ record.code }}</p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('settings.taxes.tax_categories.edit'))
                                    <a @click="selectedTaxCategories=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('settings.taxes.tax_categories.delete'))
                                    <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                        <span
                                            :class="record.actions.find(action => action.index === 'delete')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </template>
                </template>
            </x-superadmin::datagrid.ssr>

            <!-- Model Form -->
            <x-superadmin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="taxCategoryCreateForm"
                >
                    <x-superadmin::modal ref="taxCategory">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                <span v-if="selectedTaxCategories">
                                    @lang('superadmin::app.settings.taxes.categories.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('superadmin::app.settings.taxes.categories.index.create.title')
                                </span>
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Code -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.taxes.categories.index.create.code')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="selectedTaxRates.id"
                                />

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    id="code"
                                    name="code"
                                    rules="required"
                                    v-model="selectedTaxRates.code"
                                    :label="trans('superadmin::app.settings.taxes.categories.index.create.code')"
                                    :placeholder="trans('superadmin::app.settings.taxes.categories.index.create.code')"
                                />

                                <x-superadmin::form.control-group.error control-name="code" />
                            </x-superadmin::form.control-group>

                            <!-- Name -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.taxes.categories.index.create.name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    id="name"
                                    name="name"
                                    rules="required"
                                    v-model="selectedTaxRates.name"
                                    :label="trans('superadmin::app.settings.taxes.categories.index.create.name')"
                                    :placeholder="trans('superadmin::app.settings.taxes.categories.index.create.name')"
                                />

                                <x-superadmin::form.control-group.error control-name="name" />
                            </x-superadmin::form.control-group>

                            <!-- Description -->
                            <x-superadmin::form.control-group class="!mb-0">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.taxes.categories.index.create.description')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="textarea"
                                    id="description"
                                    name="description"
                                    rules="required"
                                    v-model="selectedTaxRates.description"
                                    :label="trans('superadmin::app.settings.taxes.categories.index.create.description')"
                                    :placeholder="trans('superadmin::app.settings.taxes.categories.index.create.description')"
                                />

                                <x-superadmin::form.control-group.error control-name="description" />
                            </x-superadmin::form.control-group>

                            <!-- Select Tax Rates -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.taxes.categories.index.create.tax-rates')
                                </x-superadmin::form.control-group.label>

                                <v-field
                                    name="taxrates[]"
                                    rules="required"
                                    label="@lang('superadmin::app.settings.taxes.categories.index.create.tax-rates')"
                                    v-model="selectedTaxRates.tax_rates"
                                    multiple
                                >
                                    <select
                                        name="taxrates[]"
                                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                        :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        multiple
                                        v-model="selectedTaxRates.tax_rates"
                                    >
                                        <option
                                            v-for="taxRate in taxRates"
                                            :value="taxRate.id"
                                            :text="taxRate.identifier"
                                        >
                                        </option>
                                    </select>
                                </v-field>

                                <x-superadmin::form.control-group.error
                                    control-name="taxrates[]"
                                >
                                </x-superadmin::form.control-group.error>
                            </x-superadmin::form.control-group>

                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-superadmin::button
                                button-type="submit"
                                class="primary-button"
                                :title="trans('superadmin::app.settings.taxes.categories.index.create.save-btn')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
                        </x-slot>
                    </x-superadmin::modal>
                </form>
            </x-superadmin::form>
        </script>

        <script type="module">
            window.app.component('v-tax-categories', {
                template: '#v-tax-categories-template',

                data() {
                    return {
                        taxRates: @json($taxRates),

                        selectedTaxRates: {},

                        selectedTaxCategories: 0,

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

                        let formData = new FormData(this.$refs.taxCategoryCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('superadmin.settings.taxes.categories.update') }}" : "{{ route('superadmin.settings.taxes.categories.store') }}", formData,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then((response) => {
                                this.isLoading = false;

                                this.$refs.taxCategory.toggle();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.selectedTaxRates = {};
                            })
                            .catch((error) => {
                                this.isLoading = false;
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then(response => {
                                this.selectedTaxRates = response.data.data;

                                this.$refs.taxCategory.toggle();
                            })
                            .catch(error => this.$emitter.emit('add-flash', {
                                type: 'error', message: error.response.data.message
                            }));
                    },
                },
            });
        </script>
    @endPushOnce
</x-superadmin::layouts>
