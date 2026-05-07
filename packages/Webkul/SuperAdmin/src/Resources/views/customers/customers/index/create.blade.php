@pushOnce('styles')
    <style>
        /* Hide native calendar icon only for DOB field */
        #dob::-webkit-calendar-picker-indicator {
            display: none;
            -webkit-appearance: none;
        }

        #dob::-webkit-inner-spin-button,
        #dob::-webkit-clear-button {
            display: none;
            -webkit-appearance: none;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-customer-form-template"
    >
        <x-superadmin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">
                <!-- Customer Create Modal -->
                <x-superadmin::modal ref="customerCreateModal">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('superadmin::app.customers.customers.index.create.title')
                        </p>
                    </x-slot>

                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('bagisto.admin.customers.create.before') !!}

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- First Name -->
                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.customers.customers.index.create.first-name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    rules="required"
                                    :label="trans('superadmin::app.customers.customers.index.create.first-name')"
                                    :placeholder="trans('superadmin::app.customers.customers.index.create.first-name')"
                                />

                                <x-superadmin::form.control-group.error control-name="first_name" />
                            </x-superadmin::form.control-group>

                            <!-- Last Name -->
                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.customers.customers.index.create.last-name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    rules="required"
                                    :label="trans('superadmin::app.customers.customers.index.create.last-name')"
                                    :placeholder="trans('superadmin::app.customers.customers.index.create.last-name')"
                                />

                                <x-superadmin::form.control-group.error control-name="last_name" />
                            </x-superadmin::form.control-group>
                        </div>

                        <!-- Email -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.customers.customers.index.create.email')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="email"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('superadmin::app.customers.customers.index.create.email')"
                                placeholder="email@example.com"
                            />

                            <x-superadmin::form.control-group.error control-name="email" />
                        </x-superadmin::form.control-group>

                        <!-- Contact Number -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.customers.customers.index.create.contact-number')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="phone"
                                name="phone"
                                rules="phone"
                                :label="trans('superadmin::app.customers.customers.index.create.contact-number')"
                                :placeholder="trans('superadmin::app.customers.customers.index.create.contact-number')"
                            />

                            <x-superadmin::form.control-group.error control-name="phone" />
                        </x-superadmin::form.control-group>

                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.customers.customers.index.create.date-of-birth')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="date"
                                id="dob"
                                name="date_of_birth"
                                :label="trans('superadmin::app.customers.customers.index.create.date-of-birth')"
                                :placeholder="trans('superadmin::app.customers.customers.index.create.date-of-birth')"
                            />

                            <x-superadmin::form.control-group.error control-name="date_of_birth" />
                        </x-superadmin::form.control-group>

                        <!-- Gender -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.customers.customers.index.create.gender')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                id="gender"
                                name="gender"
                                rules="required"
                                :label="trans('superadmin::app.customers.customers.index.create.gender')"
                            >
                                <option value="">
                                    @lang('superadmin::app.customers.customers.index.create.select-gender')
                                </option>

                                <option value="Male">
                                    @lang('superadmin::app.customers.customers.index.create.male')
                                </option>

                                <option value="Female">
                                    @lang('superadmin::app.customers.customers.index.create.female')
                                </option>

                                <option value="Other">
                                    @lang('superadmin::app.customers.customers.index.create.other')
                                </option>
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="gender" />
                        </x-superadmin::form.control-group>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Channel -->
                            <x-superadmin::form.control-group class="w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.customers.customers.index.create.channel')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    id="channel"
                                    name="channel_id"
                                    rules="required"
                                    :label="trans('superadmin::app.customers.customers.index.create.channel')"
                                    ::value="channels[0]?.id"
                                >
                                    <option 
                                        v-for="channel in channels" 
                                        :value="channel.id"
                                        selected
                                    > 
                                        @{{ channel.name }} (@{{ channel.code }})
                                    </option>
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="channel_id" />
                            </x-superadmin::form.control-group>

                            <!-- Customer Group -->
                            <x-superadmin::form.control-group class="w-full">
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.customers.customers.index.create.customer-group')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    id="customerGroup"
                                    name="customer_group_id"
                                    :label="trans('superadmin::app.customers.customers.index.create.customer-group')"
                                    ::value="groups[0]?.id"
                                >
                                    <option 
                                        v-for="group in groups" 
                                        :value="group.id"
                                        selected
                                    > 
                                        @{{ group.name }} 
                                    </option>
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="customer_group_id" />
                            </x-superadmin::form.control-group>
                        </div>

                        {!! view_render_event('bagisto.admin.customers.create.after') !!}
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <!-- Save Button -->
                        <x-superadmin::button
                            button-type="submit"
                            class="primary-button justify-center"
                            :title="trans('superadmin::app.customers.customers.index.create.save-btn')"
                            ::loading="isLoading"
                            ::disabled="isLoading"
                        />
                    </x-slot>
                </x-superadmin::modal>
            </form>
        </x-superadmin::form>
    </script>

    <script type="module">
        window.app.component('v-create-customer-form', {
            template: '#v-create-customer-form-template',

            data() {
                return {
                    groups: @json($groups),

                    channels: @json($channels),

                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.customerCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('superadmin.customers.customers.store') }}", params)
                        .then((response) => {
                            this.$refs.customerCreateModal.close();

                            this.$emit('customer-created', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();

                            this.isLoading = false;
                        })
                        .catch(error => {                            
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        })
    </script>
@endPushOnce