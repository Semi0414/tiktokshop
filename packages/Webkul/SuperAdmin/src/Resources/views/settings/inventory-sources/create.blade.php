<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.inventory-sources.create.add-title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.create.before') !!}

    <x-superadmin::form 
        :action="route('superadmin.settings.inventory_sources.store')"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.settings.inventory_sources.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.inventory-sources.create.add-title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.settings.inventory_sources.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.marketing.communications.campaigns.create.back-btn')
                </a>
                    
                <!-- Save Inventory -->
                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.settings.inventory-sources.create.save-btn')
                </button>
            </div>
        </div>
    
        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.general.before') !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.settings.inventory-sources.create.general')
                    </p>

                    <!-- Code -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.create.code')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="code"
                            name="code"
                            rules="required"
                            :value="old('code')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.code')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.code')"
                        />

                        <x-superadmin::form.control-group.error control-name="code" />
                    </x-superadmin::form.control-group>

                    <!-- Name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.create.name')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.name')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.name')"
                        />

                        <x-superadmin::form.control-group.error control-name="name" />
                    </x-superadmin::form.control-group>

                    <!-- Description -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.inventory-sources.create.description')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="textarea"
                            class="!mb-0 text-gray-600 dark:text-gray-300"
                            id="description"
                            name="description"
                            :value="old('description')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.description')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.description')"
                        />

                        <x-superadmin::form.control-group.error control-name="description" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.contact_info.before') !!}

                <!-- Contact Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.settings.inventory-sources.create.contact-info')
                    </p>

                    <!-- Contact name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.create.contact-name')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="contact_name"
                            name="contact_name"
                            rules="required"
                            :value="old('contact_name')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.contact-name')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.contact-name')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_name" />
                    </x-superadmin::form.control-group>

                    <!-- Contact Email -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.create.contact-email')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            rules="required|email"
                            :value="old('contact_email')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.contact-email')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.contact-email')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_email" />
                    </x-superadmin::form.control-group>

                    <!-- Contact Number -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.create.contact-number')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            rules="required"
                            :value="old('contact_number')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.contact-number')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.contact-number')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_number" />
                    </x-superadmin::form.control-group>

                    <!-- Contact fax -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.inventory-sources.create.contact-fax')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="contact_fax"
                            name="contact_fax"
                            :value="old('contact_fax')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.contact-fax')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.contact-fax')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_fax" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.contact_info.after') !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.address.before') !!}

                <!-- Source Address -->
                <v-source-address></v-source-address>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.address.after') !!}

            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.accordion.settings.before') !!}

                <!-- Settings -->
                <x-superadmin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.settings.inventory-sources.create.settings')
                            </p>
                        </div>
                    </x-slot>
                
                    <x-slot:content>
                        <!-- Latitude -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.create.latitude')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="latitude"
                                name="latitude"
                                :value="old('latitude')"
                                :label="trans('superadmin::app.settings.inventory-sources.create.latitude')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.create.latitude')"
                            />

                            <x-superadmin::form.control-group.error control-name="latitude" />
                        </x-superadmin::form.control-group>

                        <!-- Longitude -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.create.longitude')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="longitude"
                                name="longitude"
                                :value="old('longitude')"
                                :label="trans('superadmin::app.settings.inventory-sources.create.longitude')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.create.longitude')"
                            />

                            <x-superadmin::form.control-group.error control-name="longitude" />
                        </x-superadmin::form.control-group>

                        <!-- Priority -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.create.priority')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="priority"
                                name="priority"
                                :value="old('priority')"
                                :label="trans('superadmin::app.settings.inventory-sources.create.priority')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.create.priority')"
                            />

                            <x-superadmin::form.control-group.error control-name="priority" />
                        </x-superadmin::form.control-group>

                        <!-- Status -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.create.status')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="hidden"
                                name="status"
                                value="0"
                            />

                            <x-superadmin::form.control-group.control
                                type="switch"
                                name="status"
                                value="1"
                                :label="trans('superadmin::app.settings.inventory-sources.create.status')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.create.status')"
                                :checked="(bool) old('status')"
                            />

                            <x-superadmin::form.control-group.error control-name="status" />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.inventory_sources.create.create_form_controls.after') !!}

    </x-superadmin::form>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-source-address-template"
        >
            <!-- Source Address -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('superadmin::app.settings.inventory-sources.create.address')
                </p>

                <!-- Country -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.create.country')
                    </x-superadmin::form.control-group.label>
    
                    <x-superadmin::form.control-group.control
                        type="select"
                        id="country"
                        name="country"
                        rules="required"
                        v-model="country"
                        :label="trans('superadmin::app.settings.inventory-sources.create.country')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.create.country')"
                    >
                        <option value="">
                            @lang('superadmin::app.settings.inventory-sources.create.select-country')
                        </option>
    
                        @foreach (core()->countries() as $country)
                            <option value="{{ $country->code }}">
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </x-superadmin::form.control-group.control>
    
                    <x-superadmin::form.control-group.error control-name="country" />
                </x-superadmin::form.control-group>
                        
                <!-- State -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.create.state')
                    </x-superadmin::form.control-group.label>
    
                    <template v-if="haveStates()">
                        <x-superadmin::form.control-group.control
                            type="select"
                            id="state"
                            name="state"
                            rules="required"
                            :value="old('state')"
                            :label="trans('superadmin::app.settings.inventory-sources.create.state')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.state')"
                        >
                            <option value="">
                                @lang('superadmin::app.settings.inventory-sources.create.select-state')
                            </option>

                            <option 
                                v-for='(state, index) in countryStates[country]'
                                :value="state.code"
                            >
                                @{{ state.default_name }}
                            </option>
                        </x-superadmin::form.control-group.control>
                    </template>
    
                    <template v-else>
                        <x-superadmin::form.control-group.control
                            type="text"
                            id="state"
                            name="state"
                            rules="required"
                            :value="old('state')"
                            v-model="state"
                            :label="trans('superadmin::app.settings.inventory-sources.create.state')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.create.state')"
                        />
                    </template>

                    <x-superadmin::form.control-group.error control-name="state" />
                </x-superadmin::form.control-group>

                <!-- City -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.create.city')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        id="city"
                        name="city"
                        rules="required"
                        :value="old('city')"
                        :label="trans('superadmin::app.settings.inventory-sources.create.city')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.create.city')"
                    />

                    <x-superadmin::form.control-group.error control-name="city" />
                </x-superadmin::form.control-group>

                <!-- Street -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.create.street')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        id="street"
                        name="street"
                        rules="required"
                        :value="old('street')"
                        :label="trans('superadmin::app.settings.inventory-sources.create.street')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.create.street')"
                    />

                    <x-superadmin::form.control-group.error control-name="street" />
                </x-superadmin::form.control-group>

                <!-- postcode -->
                <x-superadmin::form.control-group class="!mb-0">
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.create.postcode')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        id="postcode"
                        name="postcode"
                        rules="required|postcode"
                        :value="old('postcode')"
                        :label="trans('superadmin::app.settings.inventory-sources.create.postcode')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.create.postcode')"
                    />

                    <x-superadmin::form.control-group.error control-name="postcode" />
                </x-superadmin::form.control-group>
            </div>
        </script>

        <script type="module">
            window.app.component('v-source-address', {
                template: '#v-source-address-template',

                data() {
                    return {
                        country: "{{ old('country') }}",

                        state: "{{ old('state')  }}",

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                methods: {
                    haveStates() {
                        /*
                        * The double negation operator is used to convert the value to a boolean.
                        * It ensures that the final result is a boolean value,
                        * true if the array has a length greater than 0, and otherwise false.
                        */
                        return !!this.countryStates[this.country]?.length;
                    },
                }
            })
        </script>
    @endpushOnce
</x-superadmin::layouts>
