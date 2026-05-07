@php
    $resolvedValue = $attributeValuesByCode[$attribute->code] ?? $product[$attribute->code];
@endphp

@switch($attribute->type)
    @case('text')
        <input
            type="text"
            id="{{ $attribute->code }}"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
            name="{{ $attribute->code }}"
            value="{{ old($attribute->code) ?: $resolvedValue }}"
            @if ($attribute->code == 'url_key') v-slugify @endif
            @if ($attribute->code == 'name') v-slugify-target:url_key="setValues" @endif
        >

        @break
    @case('price')
        <input
            type="text"
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}"
            value="{{ old($attribute->code) ?: $resolvedValue }}"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        @break
    @case('textarea')
        <textarea
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
            rows="4"
        >{{ old($attribute->code) ?: $resolvedValue }}</textarea>

        @break
    @case('date')
        <input
            type="date"
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}"
            value="{{ old($attribute->code) ?: $resolvedValue }}"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        @break
    @case('datetime')
        <input
            type="datetime-local"
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}"
            value="{{ old($attribute->code) ?: $resolvedValue }}"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        @break
    @case('select')
        @php
            $selectedOption = old($attribute->code) ?: $resolvedValue;

            if ($attribute->code != 'tax_category_id') {
                $options = $attribute->options()->orderBy('sort_order')->get();
            } else {
                $options = app('Webkul\Tax\Repositories\TaxCategoryRepository')->all();
            }
        @endphp

        <select
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}"
            class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >
            @foreach ($options as $option)
                <option
                    value="{{ $option->id }}"
                    {{
                        (
                            (string) $selectedOption === (string) $option->id
                            || (string) $selectedOption === (string) ($option->admin_name ?? $option->name)
                        ) ? 'selected' : ''
                    }}
                    v-pre
                >
                    {{ $option->admin_name ?? $option->name }}
                </option>
            @endforeach
        </select>

        @if ($attribute->code === 'tax_category_id')
            @php
                $selectedCategoryIds = old('categories') ?? $product->categories->pluck('id')->toArray();
                $allCategories = app('Webkul\Category\Repositories\CategoryRepository')->all();
            @endphp

            <div class="mt-3">
                <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                    Categories
                </label>

                <select
                    name="categories[]"
                    class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                >
                    <option value="">-- Select Category --</option>
                    @foreach ($allCategories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategoryIds) ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        @break
    @case('multiselect')
        @php
            $selectedOption = old($attribute->code) ?: explode(',', (string) $resolvedValue);
        @endphp

        <select
            id="{{ $attribute->code }}"
            name="{{ $attribute->code }}[]"
            multiple
            class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >
            @foreach ($attribute->options()->orderBy('sort_order')->get() as $option)
                <option
                    value="{{ $option->id }}"
                    {{
                        (
                            in_array((string) $option->id, array_map('strval', $selectedOption), true)
                            || in_array((string) $option->admin_name, array_map('strval', $selectedOption), true)
                        ) ? 'selected' : ''
                    }}
                    v-pre
                >
                    {{ $option->admin_name }}
                </option>
            @endforeach
        </select>

        @break
    @case('checkbox')
        @php
            $selectedOption = old($attribute->code) ?: explode(',', (string) $resolvedValue);
        @endphp

        @foreach ($attribute->options()->orderBy('sort_order')->get() as $option)
            <div class="mb-2 flex items-center gap-2.5 last:!mb-0">
                <input
                    type="checkbox"
                    id="{{ $attribute->code . '_' . $option->id }}"
                    name="{{ $attribute->code }}[]"
                    value="{{ $option->id }}"
                    {{
                        (
                            in_array((string) $option->id, array_map('strval', $selectedOption), true)
                            || in_array((string) $option->admin_name, array_map('strval', $selectedOption), true)
                        ) ? 'checked' : ''
                    }}
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >

                <label
                    class="cursor-pointer select-none text-xs font-medium text-gray-600 dark:text-gray-300"
                    for="{{ $attribute->code . '_' . $option->id }}"
                    v-pre
                >
                    {{ $option->admin_name }}
                </label>
            </div>
        @endforeach

        @break
    @case('boolean')
        @php $selectedValue = old($attribute->code) ?: $resolvedValue @endphp

        <input
            type="hidden"
            name="{{ $attribute->code }}"
            value="0"
        >

        <label class="inline-flex cursor-pointer items-center gap-2">
            <input
                type="checkbox"
                id="{{ $attribute->code }}"
                name="{{ $attribute->code }}"
                value="1"
                {{ (boolean) $selectedValue ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
        </label>

        @break
    @case('image')
    @case('file')
        <div class="flex gap-2.5">
            @if ($resolvedValue)
                <a
                    href="{{ route('superadmin.catalog.products.file.download', [$product->id, $attribute->id] )}}"
                    class="flex"
                >
                    @if ($attribute->type == 'image')
                        @if (Storage::exists($resolvedValue))
                            <img
                                src="{{ Storage::url($resolvedValue) }}"
                                class="h-[45px] w-[45px] overflow-hidden rounded border hover:border-gray-400 dark:border-gray-800"
                            />
                        @endif
                    @else
                        <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                            <i class="icon-down-stat text-2xl"></i>
                        </div>
                    @endif
                </a>

                <input
                    type="hidden"
                    name="{{ $attribute->code }}"
                    value="{{ $resolvedValue }}"
                />
            @endif

            <v-field
                type="file"
                class="w-full"
                name="{{ $attribute->code }}"
                :rules="{{ $attribute->validations }}"
                v-slot="{ handleChange, handleBlur }"
                label="{{ $attribute->admin_name }}"
            >
                <input
                    type="file"
                    id="{{ $attribute->code }}"
                    :class="[errors['{{ $attribute->code }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:file:bg-gray-800 dark:file:dark:text-white dark:hover:border-gray-400 dark:focus:border-gray-400"
                    name="{{ $attribute->code }}"
                    @change="handleChange"
                    @blur="handleBlur"
                >
            </v-field>
        </div>

        @if ($resolvedValue)
            <div class="mt-2.5 flex items-center gap-2.5">
                <x-superadmin::form.control-group.control
                    type="checkbox"
                    :id="$attribute->code . '_delete'"
                    :name="$attribute->code . '[delete]'"
                    value="1"
                    :for="$attribute->code . '_delete'"
                />

                <label
                    for="{{ $attribute->code . '_delete' }}"
                    class="cursor-pointer select-none text-sm text-gray-600 dark:text-gray-300"
                >
                    @lang('superadmin::app.catalog.products.edit.remove')
                </label>
            </div>
        @endif

        @break
@endswitch
