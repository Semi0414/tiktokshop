<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.themes.edit.title')
    </x-slot>

    @php
        $channels = core()->getAllChannels();

        $currentChannel = core()->getRequestedChannel();

        $currentLocale = core()->getRequestedLocale();
    @endphp

    <x-superadmin::form
        :action="route('superadmin.settings.themes.update', $theme->id)"
        enctype="multipart/form-data"
        v-slot="{ errors }"
    >
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.settings.themes.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <div class="flex items-center gap-x-2.5">
                    <a
                        href="{{ route('superadmin.settings.themes.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('superadmin::app.settings.themes.edit.back')
                    </a>
                </div>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.settings.themes.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Channel and Locale Switcher -->
        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
            <div class="flex items-center gap-x-1">
                <!-- Locale Switcher -->
                <x-superadmin::dropdown 
                    position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}" 
                    :class="$currentChannel->locales->count() <= 1 ? 'hidden' : ''"
                >
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-language text-2xl"></span>

                            <span v-pre>{{ $currentLocale->name }}</span>

                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach ($currentChannel->locales->sortBy('name') as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base  cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                                v-pre
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-superadmin::dropdown>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <div class="w-full">
                <!-- Image-Carousel Template -->
                @includeWhen($theme->type === 'image_carousel', 'superadmin::settings.themes.edit.image-carousel')

                <!-- Product-Carousel Template -->
                @includeWhen($theme->type === 'product_carousel', 'superadmin::settings.themes.edit.product-carousel')

                <!-- Category Template -->
                @includeWhen($theme->type === 'category_carousel', 'superadmin::settings.themes.edit.category-carousel')

                <!-- Static-Content Template -->
                @includeWhen($theme->type === 'static_content', 'superadmin::settings.themes.edit.static-content')

                <!-- Footer Template -->
                @includeWhen($theme->type === 'footer_links', 'superadmin::settings.themes.edit.footer-links')

                <!-- Services-content Template -->
                @includeWhen($theme->type === 'services_content', 'superadmin::settings.themes.edit.services-content')
            </div>

            <!-- General -->
            <div>
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    <x-superadmin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.settings.themes.edit.general')
                            </p>
                        </x-slot>
                    
                        <x-slot:content>
                            <input
                                type="hidden"
                                name="type"
                                value="{{ $theme->type }}"
                            >
        
                            <!-- Name -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.themes.edit.name')
                                </x-superadmin::form.control-group.label>
        
                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('superadmin::app.settings.themes.edit.name')"
                                    placeholder="@lang('superadmin::app.settings.themes.edit.name')"
                                >
                                </v-field>
        
                                <x-superadmin::form.control-group.error control-name="name" />
                            </x-superadmin::form.control-group>
        
                            <!-- Sort Order -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.themes.edit.sort-order')
                                </x-superadmin::form.control-group.label>
        
                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required|min_value:1"
                                    label="@lang('superadmin::app.settings.themes.edit.sort-order')"
                                    placeholder="@lang('superadmin::app.settings.themes.edit.sort-order')"
                                >
                                </v-field>
        
                                <x-superadmin::form.control-group.error control-name="sort_order" />
                            </x-superadmin::form.control-group>
        
                            <!-- Channel -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.themes.edit.channels')
                                </x-superadmin::form.control-group.label>
        
                                <x-superadmin::form.control-group.control
                                    type="select"
                                    name="channel_id"
                                    rules="required"
                                    :value="$theme->channel_id"
                                >
                                    @foreach($channels as $channel)
                                        <option 
                                            value="{{ $channel->id }}"
                                            v-pre
                                        >
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach 
                                </x-superadmin::form.control-group.control>
        
                                <x-superadmin::form.control-group.error control-name="channel_id" />
                            </x-superadmin::form.control-group>
        
                            <!-- Themes -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.themes.edit.themes')
                                </x-superadmin::form.control-group.label>
        
                                <x-superadmin::form.control-group.control
                                    type="select"
                                    id="theme_code"
                                    name="theme_code"
                                    :value="$theme->theme_code"
                                    rules="required"
                                    :label="trans('superadmin::app.settings.themes.edit.themes')"
                                >
                                    @foreach (config('themes.shop') as $themeCode => $shopTheme)
                                        <option value="{{ $themeCode }}">
                                            {{ $shopTheme['name'] }}
                                        </option>
                                    @endforeach
                                </x-superadmin::form.control-group.control>
        
                                <x-superadmin::form.control-group.error control-name="theme" />
                            </x-superadmin::form.control-group>
        
                            <!-- Status -->
                            <x-superadmin::form.control-group class="!mb-0">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.settings.themes.edit.status')
                                </x-superadmin::form.control-group.label>
        
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        value="{{ $theme->status }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            class="peer sr-only"
                                            v-bind="field"
                                            :checked="{{ $theme->status }}"
                                        />
                                    </v-field>
                        
                                    <label
                                        class="peer h-5 w-9 cursor-pointer rounded-full bg-gray-200 after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-blue-300 dark:bg-gray-800 dark:after:border-white dark:after:bg-white dark:peer-checked:bg-gray-950 after:ltr:left-0.5 peer-checked:after:ltr:translate-x-full after:rtl:right-0.5 peer-checked:after:rtl:-translate-x-full"
                                        for="status"
                                    ></label>
                                </label>
        
                                <x-superadmin::form.control-group.error control-name="status" />
                            </x-superadmin::form.control-group>
                        </x-slot>
                    </x-superadmin::accordion>
                </div>
            </div>
        </div>
    </x-superadmin::form>
</x-superadmin::layouts>
