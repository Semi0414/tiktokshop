<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.users.index.title')
    </x-slot>

    <v-users>
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.users.index.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Create Button -->
                @if (bouncer()->hasPermission('settings.users.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('superadmin::app.settings.users.index.create.title')
                    </button>
                @endif
            </div>
        </div>

        <x-superadmin::shimmer.datagrid />
    </v-users>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-users-template"
        >
            <div class="flex items-center justify-between">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('superadmin::app.settings.users.index.title')
                </p>

                <div class="flex items-center gap-x-2.5">
                    @if (bouncer()->hasPermission('settings.users.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="resetForm();$refs.userUpdateOrCreateModal.open()"
                        >
                            @lang('superadmin::app.settings.users.index.create.title')
                        </button>
                    @endif
                </div>
            </div>

            <x-superadmin::datagrid.ssr
                :datagrid-payload="$datagridPayload"
                :src="route('superadmin.settings.users.index')"
                ref="datagrid"
            />

            <!-- Modal Form -->
            <x-superadmin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="userCreateForm"
                >
                    <!-- User Create Modal -->
                    <x-superadmin::modal ref="userUpdateOrCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="isUpdating"
                            >
                                @lang('superadmin::app.settings.users.index.edit.title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('superadmin::app.settings.users.index.create.title')
                            </p>

                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Name -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.users.index.create.name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="data.user.id"
                                />

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    id="name"
                                    name="name"
                                    rules="required"
                                    v-model="data.user.name"
                                    :label="trans('superadmin::app.settings.users.index.create.name')"
                                    :placeholder="trans('superadmin::app.settings.users.index.create.name')"
                                />

                                <x-superadmin::form.control-group.error control-name="name" />
                            </x-superadmin::form.control-group>

                            <!-- Email -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.users.index.create.email')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    v-model="data.user.email"
                                    :label="trans('superadmin::app.settings.users.index.create.email')"
                                    placeholder="email@example.com"
                                />

                                <x-superadmin::form.control-group.error control-name="email" />
                            </x-superadmin::form.control-group>

                            <div class="flex gap-4">
                                <!-- Password -->
                                <x-superadmin::form.control-group class="mb-2.5 flex-1">
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.settings.users.index.create.password')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="password"
                                        id="password"
                                        name="password"
                                        rules="min:6"
                                        v-model="data.user.password"
                                        :label="trans('superadmin::app.settings.users.index.create.password')"
                                        :placeholder="trans('superadmin::app.settings.users.index.create.password')"
                                        ref="password"
                                    />

                                    <x-superadmin::form.control-group.error control-name="password" />
                                </x-superadmin::form.control-group>

                                <!-- Confirm Password -->
                                <x-superadmin::form.control-group class="flex-1">
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.settings.users.index.create.confirm-password')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        rules="confirmed:@password"
                                        v-model="data.user.password_confirmation"
                                        :label="trans('superadmin::app.settings.users.index.create.password')"
                                        :placeholder="trans('superadmin::app.settings.users.index.create.confirm-password')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="password_confirmation" />
                                </x-superadmin::form.control-group>
                            </div>

                            <div class="flex gap-4">
                                <!-- Role -->
                                <x-superadmin::form.control-group class="w-full flex-1">
                                    <x-superadmin::form.control-group.label class="required">
                                        @lang('superadmin::app.settings.users.index.create.role')
                                    </x-superadmin::form.control-group.label>

                                    <v-field
                                        name="role_id"
                                        rules="required"
                                        label="@lang('superadmin::app.settings.users.index.create.role')"
                                        v-model="data.user.role_id"
                                    >
                                        <select
                                            name="role_id"
                                            class="flex min-h-[39px] w-full rounded-md border bg-white px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                            :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                            v-model="data.user.role_id"
                                        >
                                            <option value="" disabled>@lang('superadmin::app.settings.taxes.categories.index.create.select')</option>

                                            <option
                                                v-for="role in roles"
                                                :value="role.id"
                                                :text="role.name"
                                            >
                                            </option>
                                        </select>
                                    </v-field>

                                    <x-superadmin::form.control-group.error control-name="role_id" />
                                </x-superadmin::form.control-group>

                                <template v-if="currentUserId != data.user.id">
                                    <x-superadmin::form.control-group class="!mb-0 w-full flex-1">
                                        <x-superadmin::form.control-group.label>
                                            @lang('superadmin::app.settings.users.index.create.status')
                                        </x-superadmin::form.control-group.label>

                                        <div class="mt-2.5 w-full gap-2.5">
                                            <x-superadmin::form.control-group.control
                                                type="switch"
                                                name="status"
                                                :value="1"
                                                v-model="data.user.status"
                                                :label="trans('superadmin::app.settings.users.index.create.status')"
                                                ::checked="data.user.status"
                                            />

                                            <x-superadmin::form.control-group.error control-name="status" />
                                        </div>
                                    </x-superadmin::form.control-group>
                                </template>

                                <template v-else>
                                    <input
                                        type="hidden"
                                        name="status"
                                        v-model="data.user.status"
                                    />
                                </template>
                            </div>

                            <x-superadmin::form.control-group>
                                <div class="hidden">
                                    <x-superadmin::media.images
                                        name="image"
                                        ::uploaded-images='data.images'
                                    />
                                </div>

                                <v-media-images
                                    name="image"
                                    :uploaded-images='data.images'
                                >
                                </v-media-images>

                                <p class="required my-3 text-sm text-gray-400">
                                    @lang('superadmin::app.settings.users.index.create.upload-image-info')
                                </p>
                            </x-superadmin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
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
            window.app.component('v-users', {
                template: '#v-users-template',

                data() {
                    return {
                        isUpdating: false,

                        roles: @json($roles),

                        data: {
                            user: {},
                            images: [],
                        },

                        isLoading: false,

                        currentUserId: "{{ auth()->guard('superadmin')->user()->id }}",
                    }
                },

                methods: {
                    updateOrCreate(params, { setErrors }) {
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.userCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('superadmin.settings.users.update') }}" : "{{ route('superadmin.settings.users.store') }}", formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                }
                            })
                            .then((response) => {
                                this.isLoading = false;

                                this.$refs.userUpdateOrCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.resetForm();
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.isUpdating = true;

                        this.$axios.get(url)
                            .then((response) => {
                                this.data = {
                                    ...response.data,
                                        images: response.data.user.image_url
                                        ? [{ id: 'image', url: response.data.user.image_url }]
                                        : [],
                                        user: {
                                            ...response.data.user,
                                            password:'',
                                            password_confirmation:'',
                                        },
                                };

                                this.$refs.modalForm.setValues(response.data.user);

                                this.$refs.userUpdateOrCreateModal.toggle();
                            })
                            .catch(error => this.$emitter.emit('add-flash', {
                                type: 'error', message: error.response.data.message
                            }));
                    },

                    resetForm() {
                        this.isUpdating = false;

                        this.data = {
                            user: {},
                            images: [],
                        };
                    },
                },
            });
        </script>
    @endPushOnce
</x-superadmin::layouts>
