<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.inventory-sources.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.before', ['inventorySource' => $inventorySource]) !!}

    <x-superadmin::form 
        :action="route('superadmin.settings.inventory_sources.update', $inventorySource->id)"
        enctype="multipart/form-data"
        method="PUT"
    >

        {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.edit_form_controls.before', ['inventorySource' => $inventorySource]) !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.inventory-sources.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.settings.inventory_sources.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.settings.inventory-sources.edit.back-btn')
                </a>
                    
                <!-- Save Inventory -->
                <div class="flex items-center gap-x-2.5">
                    <button 
                        type="submit"
                        class="primary-button"
                    >
                        @lang('superadmin::app.settings.inventory-sources.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.general.before', ['inventorySource' => $inventorySource]) !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.settings.inventory-sources.edit.general')
                    </p>

                    <!-- Code -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.edit.code')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="code"
                            name="code"
                            rules="required"
                            :value="old('code') ?? $inventorySource->code"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.code')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.code')"
                        />

                        <x-superadmin::form.control-group.error control-name="code" />
                    </x-superadmin::form.control-group>

                    <!-- Name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.edit.name')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            :value="old('name') ?? $inventorySource->name"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.name')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.name')"
                        />

                        <x-superadmin::form.control-group.error control-name="name" />
                    </x-superadmin::form.control-group>

                    <!-- Description -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.inventory-sources.edit.description')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="textarea"
                            class="text-gray-600 dark:text-gray-300"
                            id="description"
                            name="description"
                            :value="old('description') ?? $inventorySource->description"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.description')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.description')"
                        />

                        <x-superadmin::form.control-group.error control-name="description" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.general.after', ['inventorySource' => $inventorySource]) !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.contact_info.before', ['inventorySource' => $inventorySource]) !!}

                <!-- Contact Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.settings.inventory-sources.edit.contact-info')
                    </p>

                    <!-- Contact Name -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.edit.contact-name')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            name="contact_name"
                            id="contact_name"
                            rules="required"
                            :value="old('contact_name') ?? $inventorySource->contact_name"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.contact-name')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.contact-name')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_name" />
                    </x-superadmin::form.control-group>

                    <!-- Contact Email -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.settings.inventory-sources.edit.contact-email')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            rules="required|email"
                            :value="old('contact_email') ?? $inventorySource->contact_email"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.contact-email')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.contact-email')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_email" />
                    </x-superadmin::form.control-group>

                    <!-- Contact Number -->
                    <x-superadmin::form.control-group>
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.inventory-sources.edit.contact-number')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            rules="required"
                            :value="old('contact_number') ?? $inventorySource->contact_number"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.contact-number')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.contact-number')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_number" />
                    </x-superadmin::form.control-group>

                    <!-- Contact Fax -->
                    <x-superadmin::form.control-group class="!mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.settings.inventory-sources.edit.contact-fax')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            id="contact_fax"
                            name="contact_fax"
                            :value="old('contact_fax') ?? $inventorySource->contact_fax"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.contact-fax')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.contact-fax')"
                        />

                        <x-superadmin::form.control-group.error control-name="contact_fax" />
                    </x-superadmin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.contact_info.after', ['inventorySource' => $inventorySource]) !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.source_address.before', ['inventorySource' => $inventorySource]) !!}

                <!-- Create Inventory -->
                <v-source-address></v-source-address>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.source_address.after', ['inventorySource' => $inventorySource]) !!}

            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.accordion.settings.before', ['inventorySource' => $inventorySource]) !!}

                <!-- Settings -->
                <x-superadmin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.settings.inventory-sources.edit.settings')
                            </p>
                        </div>
                    </x-slot>
                
                    <x-slot:content>
                        <!-- Latitude -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.edit.latitude')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="latitude"
                                name="latitude"
                                :value="old('latitude') ?? $inventorySource->latitude"
                                :label="trans('superadmin::app.settings.inventory-sources.edit.latitude')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.edit.latitude')"
                            />

                            <x-superadmin::form.control-group.error control-name="latitude" />
                        </x-superadmin::form.control-group>

                        <!-- Longitude -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.edit.longitude')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="longitude"
                                name="longitude"
                                :value="old('longitude') ?? $inventorySource->longitude"
                                :label="trans('superadmin::app.settings.inventory-sources.edit.longitude')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.edit.longitude')"
                            />

                            <x-superadmin::form.control-group.error control-name="longitude" />
                        </x-superadmin::form.control-group>

                        <!-- Priority -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.edit.priority')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                id="priority"
                                name="priority"
                                :value="old('priority') ?? $inventorySource->priority"
                                :label="trans('superadmin::app.settings.inventory-sources.edit.priority')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.edit.priority')"
                            />

                            <x-superadmin::form.control-group.error control-name="priority" />
                            
                        </x-superadmin::form.control-group>

                        <!-- Status -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.settings.inventory-sources.edit.status')
                            </x-superadmin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $inventorySource->status; @endphp

                            <x-superadmin::form.control-group.control
                                type="hidden"
                                name="status"
                                value="0"
                            />

                            <x-superadmin::form.control-group.control
                                type="switch"
                                name="status"
                                value="1"
                                :label="trans('superadmin::app.settings.inventory-sources.edit.status')"
                                :placeholder="trans('superadmin::app.settings.inventory-sources.edit.status')"
                                :checked="(bool) $selectedValue"
                            />

                            <x-superadmin::form.control-group.error control-name="status" />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.accordion.settings.after', ['inventorySource' => $inventorySource]) !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.edit_form_controls.after', ['inventorySource' => $inventorySource]) !!}

    </x-superadmin::form>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.after', ['inventorySource' => $inventorySource]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-source-address-template"
        >
            <!-- Source Address -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('superadmin::app.settings.inventory-sources.edit.source-address')
                </p>

                <!-- Country -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.edit.country')
                    </x-superadmin::form.control-group.label>
    
                    <x-superadmin::form.control-group.control
                        type="select"
                        id="country"
                        name="country"
                        rules="required"
                        v-model="country"
                        :label="trans('superadmin::app.settings.inventory-sources.edit.country')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.edit.country')"
                    >
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
                        @lang('superadmin::app.settings.inventory-sources.edit.state')
                    </x-superadmin::form.control-group.label>
    
                    <template v-if="haveStates()">
                        <x-superadmin::form.control-group.control
                            type="select"
                            id="state"
                            name="state"
                            rules="required"
                            v-model="state"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.state')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.state')"
                        >
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
                            :value="old('state') ?? $inventorySource->code"
                            v-model="state"
                            :label="trans('superadmin::app.settings.inventory-sources.edit.state')"
                            :placeholder="trans('superadmin::app.settings.inventory-sources.edit.state')"
                        />
                    </template>

                    <x-superadmin::form.control-group.error control-name="state" />
                </x-superadmin::form.control-group>

                <!-- City -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.edit.city')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        id="city"
                        name="city"
                        rules="required"
                        :value="old('city') ?? $inventorySource->city"
                        :label="trans('superadmin::app.settings.inventory-sources.edit.city')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.edit.city')"
                    />

                    <x-superadmin::form.control-group.error control-name="city" />
                </x-superadmin::form.control-group>

                <!-- Street -->
                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.edit.street')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        name="street"
                        id="street"
                        rules="required"
                        :value="old('street') ?? $inventorySource->street"
                        :label="trans('superadmin::app.settings.inventory-sources.edit.street')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.edit.street')"
                    />

                    <x-superadmin::form.control-group.error control-name="street" />
                </x-superadmin::form.control-group>

                <!-- Post Code -->
                <x-superadmin::form.control-group class="!mb-0">
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.settings.inventory-sources.edit.postcode')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="text"
                        id="postcode"
                        name="postcode"
                        rules="required|postcode"
                        :value="old('postcode') ?? $inventorySource->postcode"
                        :label="trans('superadmin::app.settings.inventory-sources.edit.postcode')"
                        :placeholder="trans('superadmin::app.settings.inventory-sources.edit.postcode')"
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
                        country: "{{ old('country') ?? $inventorySource->country }}",

                        state: "{{ old('state') ?? $inventorySource->state }}",

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                methods: {
                    haveStates() {
                        if (this.countryStates[this.country] && this.countryStates[this.country].length) {
                            return true;
                        }

                        return false;
                    },
                }
            })
        </script>
    @endpushOnce
</x-superadmin::layouts>
