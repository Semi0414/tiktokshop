<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.marketing.communications.campaigns.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.before') !!}

    <!-- Input Form -->
    <x-superadmin::form :action="route('superadmin.marketing.communications.campaigns.store')">

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.marketing.communications.campaigns.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.marketing.communications.campaigns.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.marketing.communications.campaigns.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.marketing.communications.campaigns.create.save-btn')
                </button>
            </div>
        </div>
        <!-- Information -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.general.before') !!}

                <!-- General Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('superadmin::app.marketing.communications.campaigns.create.general')
                    </p>

                    <div class="mb-2.5">
                        <!-- Name -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.name')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.name')"
                                :placeholder="trans('superadmin::app.marketing.communications.campaigns.create.name')"
                            />

                            <x-superadmin::form.control-group.error control-name="name" />
                        </x-superadmin::form.control-group>

                        <!-- Subject -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.subject')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="subject"
                                rules="required"
                                :value="old('subject')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.subject')"
                                :placeholder="trans('superadmin::app.marketing.communications.campaigns.create.subject')"
                            />

                            <x-superadmin::form.control-group.error control-name="subject" />
                        </x-superadmin::form.control-group>

                         <!-- Event -->
                         <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.event')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="marketing_event_id"
                                rules="required"
                                :value="old('marketing_event_id')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.event')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('superadmin::app.marketing.communications.campaigns.create.select-event')
                                </option>

                                @foreach (app('Webkul\Marketing\Repositories\EventRepository')->all() as $event)
                                    <option
                                        value="{{ $event->id }}"
                                        {{ old('marketing_event_id') == $event->id ? 'selected' : '' }}
                                        v-pre
                                    >
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="marketing_event_id" />
                        </x-superadmin::form.control-group>

                        <!-- Email Template -->
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.email-template')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                name="marketing_template_id"
                                rules="required"
                                class="cursor-pointer"
                                :value="old('marketing_template_id')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.email-template')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('superadmin::app.marketing.communications.campaigns.create.select-template')
                                </option>

                                @foreach ($templates as $template)
                                    <option
                                        value="{{ $template->id }}"
                                        {{ old('marketing_template_id') == $template->id ? 'selected' : '' }}
                                        v-pre
                                    >
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="marketing_template_id" />
                        </x-superadmin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.general.after') !!}
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.accordion.setting.before') !!}

                <!-- Setting -->
                <x-superadmin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.marketing.communications.campaigns.create.setting')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Channel -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.channel')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="channel_id"
                                rules="required"
                                :value="old('channel_id')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.channel')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('superadmin::app.marketing.communications.campaigns.create.select-channel')
                                </option>

                                @foreach (app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option
                                        value="{{ $channel->id }}"
                                        {{ old('channel_id') == $channel->id ? 'selected' : '' }}
                                        v-pre
                                    >
                                        {{ core()->getChannelName($channel) }}
                                    </option>
                                @endforeach
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="channel_id" />
                        </x-superadmin::form.control-group>

                        <!-- Customer Group -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.marketing.communications.campaigns.create.customer-group')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="customer_group_id"
                                rules="required"
                                :value="old('customer_group_id')"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.customer-group')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('superadmin::app.marketing.communications.campaigns.create.select-group')
                                </option>

                                @foreach (app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                    <option
                                        value="{{ $customerGroup->id }}"
                                        {{ old('customer_group_id') == $customerGroup->id ? 'selected' : '' }}
                                        v-pre
                                    >
                                        {{ $customerGroup->name }}
                                    </option>
                                @endforeach
                            </x-superadmin::form.control-group.control>

                            <x-superadmin::form.control-group.error control-name="customer_group_id" />
                        </x-superadmin::form.control-group>

                         <!-- Status -->
                         <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.marketing.communications.campaigns.create.status')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="switch"
                                class="cursor-pointer"
                                name="status"
                                value="1"
                                :label="trans('superadmin::app.marketing.communications.campaigns.create.status')"
                            />

                            <x-superadmin::form.control-group.error control-name="status" />
                        </x-superadmin::form.control-group>
                    </x-slot>
                </x-superadmin::accordion>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.accordion.setting.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.after') !!}

    </x-superadmin::form>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.after') !!}

</x-superadmin::layouts>
