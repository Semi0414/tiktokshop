<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.customers.gdpr.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.gdpr.index.title')
        </p>

        <x-superadmin::datagrid.export src="{{ route('superadmin.customers.gdpr.index') }}" />
    </div>

    {!! view_render_event('bagisto.admin.customers.gdpr.list.before') !!}

    <v-create-gdpr></v-create-gdpr>

    {!! view_render_event('bagisto.admin.customers.gdpr.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-gdpr-template"
        >
            <div>
                <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" src="{{ route('superadmin.customers.gdpr.index') }}"
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

                                <!-- Customer Name -->
                                <p>@{{ record.customer_name }}</p>

                                <!-- Status -->
                                <p v-html="record.status"></p>

                                <!-- Type -->
                                <p>@{{ record.type }}</p>

                                <!-- Message -->
                                <p>@{{ record.message }}</p>

                                <!-- Created At -->
                                <p>@{{ record.created_at }}</p>

                                <!-- Actions -->
                                <div class="flex justify-end">
                                    @if (bouncer()->hasPermission('customers.gdpr_requests.edit'))
                                        <a @click="editModal(record.actions.find(action => action.index === 'edit')?.url, record.id)">
                                            <span
                                                :class="record.actions.find(action => action.index === 'edit')?.icon"
                                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.title === '@lang('superadmin::app.customers.gdpr.index.datagrid.edit')')?.title"
                                            >
                                            </span>
                                        </a>
                                    @endif

                                    @if (bouncer()->hasPermission('customers.gdpr_requests.delete'))
                                        <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                            <span
                                                :class="record.actions.find(action => action.index === 'delete')?.icon"
                                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.title === '@lang('superadmin::app.customers.gdpr.index.datagrid.delete')')?.title"
                                            >
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </template>
                    </template>
                </x-superadmin::datagrid.ssr>

                {!! view_render_event('bagisto.admin.customers.groups.list.after') !!}

                <!-- Modal Form -->
                <x-superadmin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form
                        @submit="handleSubmit($event, update)"
                        ref="gdprForm"
                    >
                        <!-- Create Group Modal -->
                        <x-superadmin::modal ref="gdprUpdateModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('superadmin::app.customers.gdpr.index.modal.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <!-- Status -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.customers.gdpr.index.modal.status')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                    />

                                    <x-superadmin::form.control-group.control
                                        type="select"
                                        id="status"
                                        name="status"
                                        rules="required"
                                        :label="trans('superadmin::app.customers.gdpr.index.modal.status')"
                                        :placeholder="trans('superadmin::app.customers.gdpr.index.modal.status')"
                                    >
                                        <option value="pending" selected>
                                            @lang('superadmin::app.customers.gdpr.index.modal.pending')
                                        </option>

                                        <option value="processing">
                                            @lang('superadmin::app.customers.gdpr.index.modal.processing')
                                        </option>
                                        
                                        <option value="declined">
                                            @lang('superadmin::app.customers.gdpr.index.modal.declined')
                                        </option>

                                        <option value="completed">
                                            @lang('superadmin::app.customers.gdpr.index.modal.completed')
                                        </option>

                                        <option value="revoked">
                                            @lang('superadmin::app.customers.gdpr.index.modal.revoked')
                                        </option>
                                    </x-superadmin::form.control-group.control>

                                    <x-superadmin::form.control-group.error control-name="status" />
                                </x-superadmin::form.control-group>

                                <!-- Type -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.customers.gdpr.index.modal.type')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                    />

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        id="type"
                                        name="type"
                                        rules="required"
                                        :label="trans('superadmin::app.customers.gdpr.index.modal.type')"
                                        :placeholder="trans('superadmin::app.customers.gdpr.index.modal.type')"
                                        disabled
                                    />

                                    <x-superadmin::form.control-group.error control-name="type" />
                                </x-superadmin::form.control-group>

                                <!-- Message -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.customers.gdpr.index.modal.message')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="textarea"
                                        id="message"
                                        name="message"
                                        rules="required"
                                        :label="trans('superadmin::app.customers.gdpr.index.modal.message')"
                                        :placeholder="trans('superadmin::app.customers.gdpr.index.modal.message')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="message" />
                                </x-superadmin::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <div class="flex items-center gap-x-2.5">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('superadmin::app.customers.gdpr.index.modal.save-btn')
                                    </button>
                                </div>
                            </x-slot>
                        </x-superadmin::modal>
                    </form>
                </x-superadmin::form>
            </div>
        </script>

        <script type="module">
            window.app.component('v-create-gdpr', {
                template: '#v-create-gdpr-template',

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
                    update(params) {
                        const formData = new FormData(this.$refs.gdprForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(`{{ route('superadmin.customers.gdpr.update', '') }}/${params.id}`, formData)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch((error) => {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            })
                            .finally(() => {
                                this.$refs.gdprUpdateModal.close();

                                this.$refs.datagrid.get();
                            });
                    },

                    editModal(url, id) {
                        this.$axios.get(url, { params: { id } })
                            .then((response) => {
                                this.$refs.gdprUpdateModal.toggle();

                                this.$refs.modalForm.setValues(response.data.data);
                            })
                    },
                }
            })
        </script>
    @endPushOnce

</x-superadmin::layouts>
