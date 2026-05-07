<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.currencies.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

    <v-currencies>
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.currencies.index.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Create Currency Button -->
                @if (bouncer()->hasPermission('settings.currencies.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('superadmin::app.settings.currencies.index.create-btn')
                    </button>
                @endif
            </div>
        </div>

        <!-- DataGrid Shimmer -->
        <x-superadmin::shimmer.datagrid />
    </v-currencies>

    {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-currencies-template"
        >
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('superadmin::app.settings.currencies.index.title')
                </p>

                <div class="flex items-center gap-x-2.5">
                    <!-- Create Currency Button -->
                    @if (bouncer()->hasPermission('settings.currencies.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="isEditable=0; selectedCurrency={}; $refs.currencyUpdateOrCreateModal.toggle();"
                        >
                            @lang('superadmin::app.settings.currencies.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.settings.currencies.index')"
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
                            <!-- Currency ID -->
                            <p>@{{ record.id }}</p>

                            <!-- Currency Name -->
                            <p>@{{ record.name }}</p>

                            <!-- Currency Code -->
                            <p>@{{ record.code }}</p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('settings.currencies.edit'))
                                    <a @click="selectedCurrencies=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('settings.currencies.delete'))
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

            <!-- Modal Form -->
            <x-superadmin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="currencyCreateForm"
                >
                    <x-superadmin::modal ref="currencyUpdateOrCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="isEditable"
                            >
                                @lang('superadmin::app.settings.currencies.index.edit.title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('superadmin::app.settings.currencies.index.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                            <x-superadmin::form.control-group.control
                                type="hidden"
                                name="id"
                                v-model="selectedCurrency.id"
                            />

                            <!-- Code -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.currencies.index.create.code')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="code"
                                    rules="required|min:3|max:3"
                                    ::value="selectedCurrency.code"
                                    :label="trans('superadmin::app.settings.currencies.index.create.code')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.code')"
                                    ::disabled="selectedCurrency.code"
                                />

                                <x-superadmin::form.control-group.error control-name="code" />
                            </x-superadmin::form.control-group>

                            <!-- Name -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.currencies.index.create.name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="name"
                                    rules="required"
                                    :value="old('name')"
                                    v-model="selectedCurrency.name"
                                    :label="trans('superadmin::app.settings.currencies.index.create.name')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.name')"
                                />

                                <x-superadmin::form.control-group.error control-name="name" />
                            </x-superadmin::form.control-group>

                            <!-- Symbol -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.settings.currencies.index.create.symbol')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="symbol"
                                    :value="old('symbol')"
                                    v-model="selectedCurrency.symbol"
                                    :label="trans('superadmin::app.settings.currencies.index.create.symbol')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.symbol')"
                                />

                                <x-superadmin::form.control-group.error control-name="symbol" />
                            </x-superadmin::form.control-group>

                            <!-- Decimal -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.settings.currencies.index.create.decimal')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="decimal"
                                    :value="old('decimal')"
                                    rules="numeric|between:0,9"
                                    v-model="selectedCurrency.decimal"
                                    :label="trans('superadmin::app.settings.currencies.index.create.decimal')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.decimal')"
                                />

                                <x-superadmin::form.control-group.error control-name="decimal" />
                            </x-superadmin::form.control-group>

                            <!-- Group Separator -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.settings.currencies.index.create.group-separator')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="group_separator"
                                    :value="old('group_separator')"
                                    ::rules="{ regex: /^[,\.' ]$/ }"
                                    v-model="selectedCurrency.group_separator"
                                    :label="trans('superadmin::app.settings.currencies.index.create.group-separator')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.group-separator')"
                                />

                                <p class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('superadmin::app.settings.currencies.index.create.group-separator-note', [
                                        'attribute' => trans('superadmin::app.settings.currencies.index.create.group-separator')
                                    ])
                                </p>

                                <x-superadmin::form.control-group.error control-name="group_separator" />
                            </x-superadmin::form.control-group>

                            <!-- Decimal Separator -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.settings.currencies.index.create.decimal-separator')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="decimal_separator"
                                    :value="old('decimal_separator')"
                                    ::rules="{ regex: /^[,\.]+$/ }"
                                    v-model="selectedCurrency.decimal_separator"
                                    :label="trans('superadmin::app.settings.currencies.index.create.decimal-separator')"
                                    :placeholder="trans('superadmin::app.settings.currencies.index.create.decimal-separator')"
                                />

                                <p class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('superadmin::app.settings.currencies.index.create.decimal-separator-note', [
                                        'attribute' => trans('superadmin::app.settings.currencies.index.create.decimal-separator')
                                    ])
                                </p>

                                <x-superadmin::form.control-group.error control-name="decimal_separator" />
                            </x-superadmin::form.control-group>

                            <!-- Currency Position -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.settings.currencies.index.create.currency-position')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    name="currency_position"
                                    v-model="selectedCurrency.currency_position"
                                    :label="trans('superadmin::app.settings.currencies.index.create.currency-position')"
                                >
                                    <option value="">@lang('superadmin::app.settings.taxes.categories.index.create.select')</option>

                                    <option
                                        v-for="(position, key) in positions"
                                        :value="key"
                                        :text="position"
                                        :selected="key == selectedCurrency.currency_position"
                                    >
                                    </option>
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="currency_position" />
                            </x-superadmin::form.control-group>

                            {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-superadmin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('superadmin::app.settings.currencies.index.create.save-btn')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
                        </x-slot>
                    </x-superadmin::modal>
                </form>
            </x-superadmin::form>
        </script>

        <script type="module">
            window.app.component('v-currencies', {
                template: '#v-currencies-template',

                data() {
                    return {
                        isEditable: 0,

                        isLoading: false,

                        selectedCurrency: {},

                        positions: @json($currencyPositions),
                    };
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

                        let formData = new FormData(this.$refs.currencyCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('superadmin.settings.currencies.update') }}" : "{{ route('superadmin.settings.currencies.store') }}", formData)
                            .then((response) => {
                                this.isLoading = false;

                                this.$refs.currencyUpdateOrCreateModal.close();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.isEditable = 1;

                        this.$axios.get(url)
                            .then((response) => {
                                this.selectedCurrency = response.data;

                                this.$refs.currencyUpdateOrCreateModal.toggle();
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },
                }
            })
        </script>
    @endPushOnce
</x-superadmin::layouts>
