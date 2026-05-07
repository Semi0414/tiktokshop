@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-seller-form-template"
    >
        <x-superadmin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">
                <x-superadmin::modal ref="sellerCreateModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('superadmin::app.sellers.index.create.title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <x-superadmin::form.control-group class="mb-2.5">
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.users.index.create.name')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="seller_name"
                                name="name"
                                rules="required"
                                :label="trans('superadmin::app.settings.users.index.create.name')"
                                :placeholder="trans('superadmin::app.settings.users.index.create.name')"
                            />

                            <x-superadmin::form.control-group.error control-name="name" />
                        </x-superadmin::form.control-group>

                        <x-superadmin::form.control-group class="mb-2.5">
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.users.index.create.email')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="email"
                                id="seller_email"
                                name="email"
                                rules="required|email"
                                :label="trans('superadmin::app.settings.users.index.create.email')"
                                placeholder="email@example.com"
                            />

                            <x-superadmin::form.control-group.error control-name="email" />
                        </x-superadmin::form.control-group>

                        <div class="mb-2.5 flex gap-4 max-sm:flex-wrap">
                            <x-superadmin::form.control-group class="w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.users.index.create.password')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="password"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('superadmin::app.settings.users.index.create.password')"
                                    ref="password"
                                />

                                <x-superadmin::form.control-group.error control-name="password" />
                            </x-superadmin::form.control-group>

                            <x-superadmin::form.control-group class="w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.users.index.create.confirm-password')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    rules="required|confirmed:@password"
                                    :label="trans('superadmin::app.settings.users.index.create.confirm-password')"
                                />

                                <x-superadmin::form.control-group.error control-name="password_confirmation" />
                            </x-superadmin::form.control-group>
                        </div>

                        <x-superadmin::form.control-group class="mb-2.5">
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.settings.users.index.create.role')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                id="seller_role_id"
                                name="role_id"
                                rules="required"
                                :label="trans('superadmin::app.settings.users.index.create.role')"
                                ::value="roles[0]?.id"
                            >
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    @{{ role.name }}
                                </option>
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="role_id" />
                        </x-superadmin::form.control-group>

                        <x-superadmin::form.control-group class="mb-2.5">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.users.index.create.status')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="switch"
                                name="status"
                                :label="trans('superadmin::app.settings.users.index.create.status')"
                                ::value="1"
                            />

                            <x-superadmin::form.control-group.error control-name="status" />
                        </x-superadmin::form.control-group>
                    </x-slot>

                    <x-slot:footer>
                        <x-superadmin::button
                            button-type="submit"
                            class="primary-button justify-center"
                            :title="trans('superadmin::app.settings.users.index.create.save-btn')"
                            ::loading="isLoading"
                            ::disabled="isLoading"
                        />
                    </x-slot>
                </x-superadmin::modal>
            </form>
        </x-superadmin::form>
    </script>

    <script type="module">
        window.app.component('v-create-seller-form', {
            template: '#v-create-seller-form-template',

            data() {
                return {
                    roles: @json($roles ?? []),

                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.sellerCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    const payload = { ...params };

                    if (payload.status === undefined || payload.status === null) {
                        payload.status = 1;
                    }

                    this.$axios.post("{{ route('superadmin.sellers.store') }}", payload)
                        .then((response) => {
                            this.$refs.sellerCreateModal.close();

                            this.$emit('seller-created', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response?.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>
@endPushOnce
