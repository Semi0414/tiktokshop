@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-2">
            <x-superadmin::form.control-group class="hidden">
                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-superadmin::form.control-group>

            <!-- Company Name -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label>
                    @lang('superadmin::app.sales.orders.create.cart.address.company-name')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.company_name'"
                    ::value="address.company_name"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.company-name')"
                />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.company_name.after') !!}

            <!-- VatId Name -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label>
                    @lang('superadmin::app.sales.orders.create.cart.address.vat-id')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.vat_id'"
                    ::value="address.vat_id"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.vat-id')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.vat-id')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.vat_id'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.vat_id.after') !!}

            <!-- First Name -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.first-name')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.first_name'"
                    ::value="address.first_name"
                    rules="required"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.first-name')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.first-name')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.first_name'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.first_name.after') !!}

            <!-- Last Name -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.last-name')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.last_name'"
                    ::value="address.last_name"
                    rules="required"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.last-name')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.last-name')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.last_name'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.last_name.after') !!}

            <!-- Email -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.email')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="email"
                    ::name="controlName + '.email'"
                    ::value="address.email"
                    rules="required|email"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.email')"
                    placeholder="email@example.com"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.email'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.email.after') !!}

            <!-- Street Address -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.street-address')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.address.[0]'"
                    ::value="address.address[0]"
                    rules="required|address"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.street-address')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.street-address')"
                />

                <x-superadmin::form.control-group.error
                    class="mb-2"
                    ::name="controlName + '.address.[0]'"
                />

                @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                        <x-superadmin::form.control-group.control
                            type="text"
                            ::name="controlName + '.address.[{{ $i }}]'"
                            class="mt-2"
                            rules="address"
                            :label="trans('superadmin::app.sales.orders.create.cart.address.street-address')"
                            :placeholder="trans('superadmin::app.sales.orders.create.cart.address.street-address')"
                        />

                        <x-superadmin::form.control-group.error
                            class="mb-2"
                            ::name="controlName + '.address.[{{ $i }}]'"
                        />
                    @endfor
                @endif
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.address.after') !!}

            <!-- Country -->
            <x-superadmin::form.control-group class="!mb-4">
                <x-superadmin::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.country')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="select"
                    ::name="controlName + '.country'"
                    ::value="address.country"
                    v-model="selectedCountry"
                    rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.country')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.country')"
                >
                    <option value="">
                        @lang('superadmin::app.sales.orders.create.cart.address.select-country')
                    </option>

                    <option
                        v-for="country in countries"
                        :value="country.code"
                    >
                        @{{ country.name }}
                    </option>
                </x-superadmin::form.control-group.control>

                <x-superadmin::form.control-group.error ::name="controlName + '.country'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.country.after') !!}

            <!-- State -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.state')
                </x-superadmin::form.control-group.label>

                <template v-if="states">
                    <template v-if="haveStates">
                        <x-superadmin::form.control-group.control
                            type="select"
                            ::name="controlName + '.state'"
                            ::value="address.state"
                            rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                            :label="trans('superadmin::app.sales.orders.create.cart.address.state')"
                            :placeholder="trans('superadmin::app.sales.orders.create.cart.address.state')"
                        >
                            <option value="">
                                @lang('superadmin::app.sales.orders.create.cart.address.select-state')
                            </option>

                            <option
                                v-for='state in states[selectedCountry]'
                                :value="state.code"
                            >
                                @{{ state.default_name }}
                            </option>
                        </x-superadmin::form.control-group.control>
                    </template>

                    <template v-else>
                        <x-superadmin::form.control-group.control
                            type="text"
                            ::name="controlName + '.state'"
                            ::value="address.state"
                            rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                            :label="trans('superadmin::app.sales.orders.create.cart.address.state')"
                            :placeholder="trans('superadmin::app.sales.orders.create.cart.address.state')"
                        />
                    </template>
                </template>

                <x-superadmin::form.control-group.error ::name="controlName + '.state'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.state.after') !!}

            <!-- City -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.city')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.city'"
                    ::value="address.city"
                    rules="required"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.city')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.city')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.city'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.city.after') !!}

            <!-- Postcode -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.postcode')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.postcode'"
                    ::value="address.postcode"
                    rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.postcode')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.postcode')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.postcode'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.postcode.after') !!}

            <!-- Phone Number -->
            <x-superadmin::form.control-group>
                <x-superadmin::form.control-group.label class="required !mt-0">
                    @lang('superadmin::app.sales.orders.create.cart.address.telephone')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    ::name="controlName + '.phone'"
                    ::value="address.phone"
                    rules="required|numeric"
                    :label="trans('superadmin::app.sales.orders.create.cart.address.telephone')"
                    :placeholder="trans('superadmin::app.sales.orders.create.cart.address.telephone')"
                />

                <x-superadmin::form.control-group.error ::name="controlName + '.phone'" />
            </x-superadmin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.phone.after') !!}
        </div>
    </script>

    <script type="module">
        window.app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                return {
                    selectedCountry: this.address.country,

                    countries: [],

                    states: null,
                }
            },

            created() {
                this.getCountries();

                this.getStates();
            },

            computed: {
                haveStates() {
                    return !! this.states[this.selectedCountry]?.length;
                },
            },

            methods: {
                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                getStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(() => {});
                },
            }
        });
    </script>
@endPushOnce