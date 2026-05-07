<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.attributes.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.attributes.create.before') !!}

    <!-- Input Form -->
    <x-superadmin::form
        :action="route('superadmin.catalog.attributes.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.catalog.attributes.create.create_form_controls.before') !!}

        <!-- Actions Buttons -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.catalog.attributes.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('superadmin.catalog.attributes.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('superadmin::app.catalog.attributes.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('superadmin::app.catalog.attributes.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Create Attributes Vue Components -->
        <v-create-attributes>
            <!-- Shimmer Effect -->
            <x-superadmin::shimmer.catalog.attributes />
        </v-create-attributes>

        {!! view_render_event('bagisto.admin.catalog.attributes.create_form_controls.after') !!}
    </x-superadmin::form>

    {!! view_render_event('bagisto.admin.catalog.attributes.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-attributes-template"
        >
            <!-- Body Content -->
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">

                {!! view_render_event('bagisto.admin.catalog.attributes.create.card.label.before') !!}

                <!-- Left Sub Component -->
                <div class="flex flex-1 flex-col gap-2 overflow-auto max-xl:flex-auto">
                    <!-- Label -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('superadmin::app.catalog.attributes.create.label')
                        </p>

                        <!-- Admin Name -->
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.catalog.attributes.create.admin')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="admin_name"
                                rules="required"
                                :value="old('admin_name')"
                                :label="trans('superadmin::app.catalog.attributes.create.admin')"
                                :placeholder="trans('superadmin::app.catalog.attributes.create.admin')"
                            />

                            <x-superadmin::form.control-group.error control-name="admin_name" />
                        </x-superadmin::form.control-group>

                        <!-- Locales Inputs -->
                        @foreach ($locales as $locale)
                            <x-superadmin::form.control-group class="last:!mb-0">
                                <x-superadmin::form.control-group.label v-pre>
                                    {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    :name="$locale->code . '[name]'"
                                    :value="old($locale->code . '[name]')"
                                    :placeholder="$locale->name"
                                />
                            </x-superadmin::form.control-group>
                        @endforeach
                    </div>

                    <!-- Options -->
                    <div
                        class="box-shadow rounded bg-white p-4 dark:bg-gray-900"
                        v-if="swatchAttribute && (
                            attributeType == 'select'
                            || attributeType == 'multiselect'
                            || attributeType == 'checkbox'
                        )"
                    >
                        <div class="mb-3 flex items-center justify-between">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.attributes.create.options')
                            </p>

                            <!-- Add Row Button -->
                            <div
                                class="secondary-button text-sm"
                                @click="$refs.addOptionsRow.toggle();swatchValue=''"
                            >
                                @lang('superadmin::app.catalog.attributes.create.add-row')
                            </div>
                        </div>

                        <!-- For Attribute Options If Data Exist -->
                        <div class="mt-4 overflow-x-auto">
                            <div
                                class="flex gap-4 max-sm:flex-wrap"
                                v-if="swatchAttribute && (attributeType == 'select')"
                            >
                                <!-- Input Options -->
                                <x-superadmin::form.control-group class="mb-2.5 w-full">
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.catalog.attributes.create.input-options')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="select"
                                        id="swatchType"
                                        name="swatch_type"
                                        :value="old('swatch_type')"
                                        v-model="swatchType"
                                        @change="showSwatch=true"
                                    >
                                        @foreach ($swatchTypes as $swatchType)
                                            <option value="{{ $swatchType }}">
                                                @lang('superadmin::app.catalog.attributes.create.option.' . $swatchType)
                                            </option>
                                        @endforeach
                                    </x-superadmin::form.control-group.control>

                                    <x-superadmin::form.control-group.error
                                        class="mt-3"
                                        control-name="admin"
                                    />
                                </x-superadmin::form.control-group>

                                <div class="mb-2.5 w-full">
                                    <!-- Checkbox -->
                                    <x-superadmin::form.control-group.label class="invisible">
                                        @lang('superadmin::app.catalog.attributes.create.input-options')
                                    </x-superadmin::form.control-group.label>

                                    <div class="!mb-0 flex w-max cursor-pointer select-none items-center gap-2.5 p-1.5">
                                        <input
                                            type="checkbox"
                                            class="peer hidden"
                                            id="empty_option"
                                            name="empty_option"
                                            v-model="isNullOptionChecked"
                                            for="empty_option"
                                            @click="$refs.addOptionsRow.toggle()"
                                        />

                                        <label
                                            for="empty_option"
                                            class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"
                                        >
                                        </label>

                                        <label
                                            for="empty_option"
                                            class="cursor-pointer text-sm font-semibold text-gray-600 dark:text-gray-300"
                                        >
                                            @lang('superadmin::app.catalog.attributes.create.create-empty-option')
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <template v-if="this.options?.length">
                                <!-- Table Information -->
                                <x-superadmin::table>
                                    <x-superadmin::table.thead class="text-sm font-medium dark:bg-gray-800">
                                        <x-superadmin::table.thead.tr>
                                            <!-- Draggable Icon -->
                                            <x-superadmin::table.th class="!p-0" />

                                            <!-- Swatch Select -->
                                            <x-superadmin::table.th v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                @lang('superadmin::app.catalog.attributes.create.swatch')
                                            </x-superadmin::table.th>

                                            <!-- Admin Tables Heading -->
                                            <x-superadmin::table.th>
                                                @lang('superadmin::app.catalog.attributes.create.admin-name')
                                            </x-superadmin::table.th>

                                            <!-- Locales Tables Heading -->
                                            @foreach ($locales as $locale)
                                                <x-superadmin::table.th v-pre>
                                                    {{ $locale->name . ' (' . $locale->code . ')' }}
                                                </x-superadmin::table.th>
                                            @endforeach

                                            <!-- Action Tables Heading -->
                                            <x-superadmin::table.th />
                                        </x-superadmin::table.thead.tr>
                                    </x-superadmin::table.thead>

                                    <!-- Draggable Component -->
                                    <draggable
                                        tag="tbody"
                                        ghost-class="draggable-ghost"
                                        handle=".icon-drag"
                                        v-bind="{animation: 200}"
                                        :list="options"
                                        item-key="id"
                                    >
                                        <template #item="{ element, index }">
                                            <x-superadmin::table.thead.tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                                                <!-- Draggable Icon -->
                                                <x-superadmin::table.td class="!px-0 text-center">
                                                    <i class="icon-drag cursor-grab text-xl transition-all group-hover:text-gray-700"></i>

                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][position]'"
                                                        :value="index"
                                                    />
                                                </x-superadmin::table.td>

                                                <!-- Swatch Type Image / Color -->
                                                <x-superadmin::table.td v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                    <!-- Swatch Image -->
                                                    <div v-if="swatchType == 'image'">
                                                        <img
                                                            src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                                            class="h-[50px] w-[50px] dark:mix-blend-exclusion dark:invert"
                                                            :ref="'image_' + element.params.id"
                                                        />

                                                        <input
                                                            type="file"
                                                            class="hidden"
                                                            :name="'options[' + element.id + '][swatch_value]'"
                                                            :ref="'imageInput_' + element.id"
                                                        />
                                                    </div>

                                                    <!-- Swatch Color -->
                                                    <div v-if="swatchType == 'color'">
                                                        <div
                                                            class="h-[25px] w-[25px] rounded-md border border-gray-200 dark:border-gray-800"
                                                            :style="{ background: element.params.swatch_value }"
                                                        >
                                                        </div>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][swatch_value]'"
                                                            v-model="element.params.swatch_value"
                                                        />
                                                    </div>
                                                </x-superadmin::table.td>

                                                <!-- Admin -->
                                                <x-superadmin::table.td>
                                                    <p class="dark:text-white">
                                                        @{{ element.params.admin_name }}
                                                    </p>

                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][admin_name]'"
                                                        v-model="element.params.admin_name"
                                                    />
                                                </x-superadmin::table.td>

                                                <!-- Locales -->
                                                <x-superadmin::table.td v-for="locale in locales">
                                                    <p class="dark:text-white">
                                                        @{{ element.params[locale.code] }}
                                                    </p>

                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][' + locale.code + '][label]'"
                                                        v-model="element.params[locale.code]"
                                                    />
                                                </x-superadmin::table.td>

                                                <!-- Action Buttons -->
                                                <x-superadmin::table.td class="!px-0">
                                                    <span
                                                        class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                        @click="editModal(element)"
                                                    >
                                                    </span>

                                                    <span
                                                        class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                        @click="removeOption(element.id)"
                                                    >
                                                    </span>
                                                </x-superadmin::table.td>
                                            </x-superadmin::table.thead.tr>
                                        </template>
                                    </draggable>
                                </x-superadmin::table>
                            </template>

                            <!-- For Empty Attribute Options -->
                            <template v-else>
                                <div class="grid justify-items-center gap-3.5 px-2.5 py-10">
                                    <!-- Attribute Option Image -->
                                    <img
                                        class="h-[120px] w-[120px] dark:mix-blend-exclusion dark:invert"
                                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                        alt="@lang('superadmin::app.catalog.attributes.create.add-attribute-options')"
                                    />

                                    <!-- Add Attribute Options Information -->
                                    <div class="flex flex-col items-center gap-1.5">
                                        <p class="text-base font-semibold text-gray-400">
                                            @lang('superadmin::app.catalog.attributes.create.add-attribute-options')
                                        </p>

                                        <p class="text-gray-400">
                                            @lang('superadmin::app.catalog.attributes.create.add-options-info')
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.attributes.create.card.label.after') !!}

                {!! view_render_event('bagisto.admin.catalog.attributes.create.card.general.before') !!}

                <!-- Right Sub Component -->
                <div class="flex w-[360px] max-w-full flex-col gap-2">
                    <!-- General -->
                    <x-superadmin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.attributes.create.general')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <!-- Attribute Code -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.catalog.attributes.create.code')
                                </x-superadmin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="code"
                                    rules="required"
                                    value="{{ old('code') }}"
                                    v-slot="{ field }"
                                    label="{{ trans('superadmin::app.catalog.attributes.create.code') }}"
                                >
                                    <input
                                        type="text"
                                        id="code"
                                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                        name="code"
                                        v-bind="field"
                                        placeholder="{{ trans('superadmin::app.catalog.attributes.create.code') }}"
                                    />
                                </v-field>

                                <x-superadmin::form.control-group.error control-name="code" />
                            </x-superadmin::form.control-group>

                            <!-- Attribute Type -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.catalog.attributes.create.type')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    id="type"
                                    class="cursor-pointer"
                                    name="type"
                                    rules="required"
                                    :value="old('type')"
                                    v-model="attributeType"
                                    :label="trans('superadmin::app.catalog.attributes.create.type')"
                                    @change="swatchAttribute=true"
                                >
                                    @foreach($attributeTypes as $attributeType)
                                        <option
                                            value="{{ $attributeType }}"
                                            {{ $attributeType === 'text' ? "selected" : '' }}
                                        >
                                            @lang('superadmin::app.catalog.attributes.create.'. $attributeType)
                                        </option>
                                    @endforeach
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="type" />
                            </x-superadmin::form.control-group>

                            <!-- Textarea Switcher -->
                            <x-superadmin::form.control-group v-show="swatchAttribute && (attributeType == 'textarea')">
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.catalog.attributes.create.enable-wysiwyg')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="switch"
                                    name="enable_wysiwyg"
                                    value="1"
                                    :label="trans('superadmin::app.catalog.attributes.create.enable-wysiwyg')"
                                />
                            </x-superadmin::form.control-group>

                            <!-- Default Value -->
                            <x-superadmin::form.control-group
                                class="!mb-0"
                                v-if="canHaveDefaultValue"
                            >
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.catalog.attributes.create.default-value')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="default_value"
                                    :label="trans('superadmin::app.catalog.attributes.create.default-value')"
                                />

                                <x-superadmin::form.control-group.error control-name="default_value" />
                            </x-superadmin::form.control-group>
                        </x-slot>
                    </x-superadmin::accordion>

                    <!-- Validations -->
                    <x-superadmin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.attributes.create.validations')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <!-- Input Validation -->
                            <x-superadmin::form.control-group v-if="swatchAttribute && (attributeType == 'text')">
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.catalog.attributes.create.input-validation')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    class="cursor-pointer"
                                    id="validation"
                                    name="validation"
                                    :value="old('validation')"
                                    v-model="validationType"
                                    :label="trans('superadmin::app.catalog.attributes.create.input-validation')"
                                    refs="validation"
                                    @change="inputValidation=true"
                                >
                                    @foreach($validations as $validation)
                                        <option value="{{ $validation }}">
                                            @lang('superadmin::app.catalog.attributes.create.' . $validation)
                                        </option>
                                    @endforeach
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="validation" />
                            </x-superadmin::form.control-group>

                            <!-- REGEX -->
                            <x-superadmin::form.control-group v-show="inputValidation && (validationType == 'regex')">
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.catalog.attributes.create.regex')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="regex"
                                    :value="old('regex')"
                                    :placeholder="trans('superadmin::app.catalog.attributes.create.regex')"
                                />

                                <x-superadmin::form.control-group.error control-name="regex" />
                            </x-superadmin::form.control-group>

                            <!-- Is Required -->
                                <x-superadmin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_required"
                                    name="is_required"
                                    value="1"
                                    for="is_required"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_required"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.is-required')
                                </label>
                            </x-superadmin::form.control-group>

                            <!-- Is Unique -->
                            <x-superadmin::form.control-group class="!mb-0 flex select-none items-center gap-2.5">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_unique"
                                    name="is_unique"
                                    value="1"
                                    for="is_unique"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_unique"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.is-unique')
                                </label>
                            </x-superadmin::form.control-group>
                        </x-slot>
                    </x-superadmin::accordion>

                    <!-- Configurations -->
                    <x-superadmin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.attributes.create.configuration')
                            </p>
                        </x-slot>

                            <x-slot:content>
                                <!-- Value Per Locale -->
                                <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                    <x-superadmin::form.control-group.control
                                        type="checkbox"
                                        id="value_per_locale"
                                        name="value_per_locale"
                                        value="1"
                                        for="value_per_locale"
                                    />

                                    <label
                                        class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                        for="value_per_locale"
                                    >
                                        @lang('superadmin::app.catalog.attributes.edit.value-per-locale')
                                    </label>
                                </x-superadmin::form.control-group>

                            <!-- Value Per Channel -->
                            <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="value_per_channel"
                                    name="value_per_channel"
                                    value="1"
                                    for="value_per_channel"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="value_per_channel"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.value-per-channel')
                                </label>
                            </x-superadmin::form.control-group>

                            <!-- Use to create configurable product -->
                            <x-superadmin::form.control-group
                                class="!mb-2 flex select-none items-center gap-2.5"
                                ::class="{ 'opacity-70' : ! isConfigurable }"
                            >
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_configurable"
                                    name="is_configurable"
                                    value="1"
                                    for="is_configurable"
                                    ::disabled="! isConfigurable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_configurable"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.is-configurable')
                                </label>
                            </x-superadmin::form.control-group>

                            <!-- Visible On Product View Page On Front End -->
                            <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_visible_on_front"
                                    name="is_visible_on_front"
                                    value="1"
                                    for="is_visible_on_front"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_visible_on_front"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.is-visible-on-front')
                                </label>
                            </x-superadmin::form.control-group>

                            <!-- Attribute is Comparable -->
                            <x-superadmin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_comparable"
                                    name="is_comparable"
                                    value="1"
                                    for="is_comparable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_comparable"
                                >
                                    @lang('superadmin::app.catalog.attributes.edit.is-comparable')
                                </label>
                            </x-superadmin::form.control-group>

                            <!-- Use in Layered -->
                            <x-superadmin::form.control-group
                                class="!mb-2 flex select-none items-center gap-2.5"
                                ::class="{ 'opacity-70' : ! isFilterable }"
                            >
                                <x-superadmin::form.control-group.control
                                    type="checkbox"
                                    id="is_filterable"
                                    name="is_filterable"
                                    value="1"
                                    for="is_filterable"
                                    ::disabled="! isFilterable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_filterable"
                                >
                                    @lang('superadmin::app.catalog.attributes.create.is-filterable')
                                </label>
                            </x-superadmin::form.control-group>
                        </x-slot>
                    </x-superadmin::accordion>
                </div>

                {!! view_render_event('bagisto.admin.catalog.attributes.create.card.general.after') !!}

            </div>

            <!-- Add Options Model Form -->
            <x-superadmin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modelForm"
            >
                <form
                    @submit.prevent="handleSubmit($event, storeOptions)"
                    enctype="multipart/form-data"
                    ref="createOptionsForm"
                >
                    <x-superadmin::modal
                        @toggle="listenModal"
                        ref="addOptionsRow"
                    >
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('superadmin::app.catalog.attributes.create.add-option')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <div
                                class="grid"
                                v-if="swatchType == 'image' || swatchType == 'color'"
                            >
                                <!-- Image Input -->
                                <x-superadmin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'image'"
                                >
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.catalog.attributes.create.image')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="image"
                                        name="swatch_value"
                                        :placeholder="trans('superadmin::app.catalog.attributes.create.image')"
                                    />

                                    <div class="hidden">
                                        <x-superadmin::media.images
                                            name="swatch_value"
                                            ::uploaded-images='swatchValue.image'
                                        />
                                    </div>

                                    <x-superadmin::form.control-group.error control-name="swatch_value" />
                                </x-superadmin::form.control-group>

                                <!-- Color Input -->
                                <x-superadmin::form.control-group
                                    class="w-2/6"
                                    v-if="swatchType == 'color'"
                                >
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.catalog.attributes.create.color')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="color"
                                        name="swatch_value"
                                        :placeholder="trans('superadmin::app.catalog.attributes.create.color')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="swatch_value" />
                                </x-superadmin::form.control-group>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Hidden Id Input -->
                                <x-superadmin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                />

                                <!-- Admin Input -->
                                <x-superadmin::form.control-group class="!mb-2.5 w-full">
                                    <x-superadmin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('superadmin::app.catalog.attributes.create.admin')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        :label="trans('superadmin::app.catalog.attributes.create.admin')"
                                        :placeholder="trans('superadmin::app.catalog.attributes.create.admin')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="admin_name" />
                                </x-superadmin::form.control-group>

                                <!-- Locales Input -->
                                @foreach ($locales as $locale)
                                    <x-superadmin::form.control-group class="!mb-2.5 w-full">
                                        <x-superadmin::form.control-group.label 
                                            ::class="{ '{{core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }"
                                            v-pre
                                        >
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-superadmin::form.control-group.label>

                                        <x-superadmin::form.control-group.control
                                            type="text"
                                            :name="$locale->code"
                                            ::rules="{ '{{core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        />

                                        <x-superadmin::form.control-group.error :control-name="$locale->code" />
                                    </x-superadmin::form.control-group>
                                @endforeach
                            </div>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-superadmin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('superadmin::app.catalog.attributes.create.option.save-btn')"
                            />
                        </x-slot>
                    </x-superadmin::modal>
                </form>
            </x-superadmin::form>
        </script>

        <script type="module">
            window.app.component('v-create-attributes', {
                template: '#v-create-attributes-template',

                data() {
                    return {
                        optionRowCount: 1,

                        attributeType: '{{ old('type') }}',

                        validationType: '',

                        inputValidation: false,

                        swatchType: 'dropdown',

                        swatchAttribute: false,

                        showSwatch: false,

                        isNullOptionChecked: false,

                        options: [],

                        locales: @json($locales),

                        swatchValue: [
                            {
                                image: [],
                            }
                        ],
                    }
                },

                computed: {
                    isFilterable() {
                        return this.attributeType == 'checkbox'
                            || this.attributeType == 'select'
                            || this.attributeType == 'multiselect'
                            || this.attributeType == 'boolean';
                    },

                    isConfigurable() {
                        return this.attributeType == 'select';
                    },

                    canHaveDefaultValue() {
                        return this.attributeType == 'boolean';
                    },
                },

                methods: {
                    storeOptions(params, { resetForm }) {
                        const sortedLocales = Object.values(this.locales).sort((a, b) => a.name.localeCompare(b.name));

                        this.locales = sortedLocales.map(({ code, name }) => ({ code, name }));

                        const sortedParams = sortedLocales.reduce((acc, locale) => {
                            acc[locale.code] = params[locale.code] || null;
                            return acc;
                        }, {});

                        if (params.id) {
                            let foundIndex = this.options.findIndex(item => item.id === params.id);

                            if (foundIndex !== -1) {
                                Object.assign(this.options[foundIndex].params, sortedParams);
                            }
                        } else {
                            this.options.push({
                                id: `option_${this.optionRowCount}`,
                                params: { admin_name: params.admin_name, ...sortedParams }
                            });

                            params.id = `option_${this.optionRowCount}`;
                            this.optionRowCount++;
                        }

                        const formData = new FormData(this.$refs.createOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        if (sliderImage) params.swatch_value = sliderImage;

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(params);
                        }

                        resetForm();
                    },

                    editModal(values) {
                        values.params.id = values.id;

                        this.swatchValue = {
                            image: values.swatch_value_url
                            ? [{ id: values.id, url: values.swatch_value_url }]
                            : [],
                        };

                        this.$refs.modelForm.setValues(values.params);

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.options = this.options.filter(option => option.id !== id);

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('superadmin::app.catalog.attributes.create.option-deleted')" });
                            }
                        });
                    },

                    listenModal(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;
                        }
                    },

                    setFile(event) {
                        let dataTransfer = new DataTransfer();

                        dataTransfer.items.add(event.swatch_value);

                        // use set timeout because need to wait for render dom before set the src or get the ref value
                        setTimeout(() => {
                            this.$refs['image_' + event.id].src =  URL.createObjectURL(event.swatch_value);

                            this.$refs['imageInput_' + event.id].files = dataTransfer.files;
                        }, 0);
                    }
                },
            });
        </script>
    @endPushOnce
</x-superadmin::layouts>
