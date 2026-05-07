<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.products.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

    <form
        method="POST"
        action="{{ route('superadmin.catalog.products.update', $product->id) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.before', ['product' => $product]) !!}

        <!-- Page Header -->
        <div class="grid gap-2.5">
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <div class="grid gap-1.5">
                    <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                        @lang('superadmin::app.catalog.products.edit.title')
                    </p>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!-- Back Button -->
                    <a
                        href="{{ route('superadmin.catalog.products.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('superadmin::app.account.edit.back-btn')
                    </a>

                    <!-- Preview Button -->
                    @if (
                        $product->status
                        && $product->visible_individually
                        && $product->url_key
                    )
                        <a
                            href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                            class="secondary-button"
                            target="_blank"
                        >
                            @lang('superadmin::app.catalog.products.edit.preview')
                        </a>
                    @endif

                    <!-- Save Button -->
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('superadmin::app.catalog.products.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-3 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700 dark:border-green-800 dark:bg-green-950/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-3 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/30 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-3 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/30 dark:text-red-300">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $channels = core()->getAllChannels();

            $currentChannel = core()->getRequestedChannel();

            $currentLocale = core()->getRequestedLocale();

            $product->loadMissing('inventories');

            $inventorySources = app(\Webkul\Inventory\Repositories\InventorySourceRepository::class)
                ->findWhere(['status' => 1]);
            $allCategories = \Illuminate\Support\Facades\DB::table('categories')
                ->join('category_translations', function ($join) use ($currentLocale) {
                    $join->on('categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', $currentLocale->code);
                })
                ->select('categories.id', 'category_translations.name')
                ->orderBy('category_translations.name')
                ->get();
            $selectedCategoryId = old('categories.0', $product->categories->pluck('id')->first());
            $primaryInventorySource = $inventorySources->first();
            $resolvedPrice = $attributeValuesByCode['price'] ?? $product->price ?? null;
            $oldPrice = old('price');
            $currentPrice = ($oldPrice !== null && $oldPrice !== '') ? $oldPrice : $resolvedPrice;

            $resolvedQuantity = $primaryInventorySource
                ? ($product->inventories->where('inventory_source_id', $primaryInventorySource->id)->pluck('qty')->first() ?? 0)
                : 0;
            $oldQuantity = old('inventories.'.$primaryInventorySource?->id);
            $currentQuantity = ($oldQuantity !== null && $oldQuantity !== '') ? $oldQuantity : $resolvedQuantity;
        @endphp

        <!-- Channel and Locale Switcher -->
        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
            <div class="flex items-center gap-x-1">
                <!-- Channel Switcher -->
                <x-superadmin::dropdown :class="$channels->count() <= 1 ? 'hidden' : ''">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-store text-2xl"></span>

                            <span v-pre>{{ $currentChannel->name }}</span>

                            <input
                                type="hidden"
                                name="channel"
                                value="{{ $currentChannel->code }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach ($channels as $channel)
                            <a
                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $channel->default_locale?->code ?? $currentLocale->code ]) }}"
                                class="flex cursor-pointer gap-2.5 px-5 py-2 text-base hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                v-pre
                            >
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-superadmin::dropdown>

                <!-- Locale Switcher -->
                <x-superadmin::dropdown :class="$currentChannel->locales->count() <= 1 ? 'hidden' : ''">
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
                                class="flex cursor-pointer gap-2.5 px-5 py-2 text-base hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : '' }}"
                                v-pre
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-superadmin::dropdown>
            </div>

            {{-- Uneditable summary badges hidden per request. --}}
            {{-- <div class="flex items-center gap-2.5">
                <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-gray-500 dark:text-gray-400">Price:</span>
                    <span class="font-semibold text-gray-800 dark:text-white">
                        {{ $currentPrice !== null ? core()->formatBasePrice((float) $currentPrice) : 'N/A' }}
                    </span>
                </div>

                <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-gray-500 dark:text-gray-400">Quantity:</span>
                    <span class="font-semibold text-gray-800 dark:text-white">
                        {{ $currentQuantity }}
                    </span>
                </div>
            </div> --}}
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.after', ['product' => $product]) !!}

        <!-- body content -->
        {!! view_render_event('bagisto.admin.catalog.product.edit.form.before', ['product' => $product]) !!}

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            @php
                $groupedColumns = $product->attribute_family->attribute_groups->groupBy('column');

                $isSingleColumn = $groupedColumns->count() !== 2;
            @endphp

            @foreach ($groupedColumns as $column => $groups)

                {!! view_render_event("bagisto.admin.catalog.product.edit.form.column_{$column}.before", ['product' => $product]) !!}

                <div class="flex flex-col gap-2 {{ $column == 1 ? 'flex-1 max-xl:flex-auto' : 'w-[360px] max-w-full max-sm:w-full' }}">
                    @foreach ($groups as $group)
                        @php $customAttributes = $product->getEditableAttributes($group); @endphp

                        @if (
                            $group->code === 'inventories'
                            && (
                                $product->getTypeInstance()->isComposite()
                                || $product->type === 'downloadable'
                            )
                        )
                            @continue
                        @endif

                        @if ($customAttributes->isNotEmpty())
                            {!! view_render_event("bagisto.admin.catalog.product.edit.form.{$group->code}.before", ['product' => $product]) !!}

                            <div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900">
                                <p
                                    class="mb-4 text-base font-semibold text-gray-800 dark:text-white"
                                    v-pre
                                >
                                    {{ $group->name }}
                                </p>

                                @if ($group->code == 'meta_description')
                                    <x-superadmin::seo />
                                @endif

                                @if ($group->code === 'general')
                                    <x-superadmin::form.control-group>
                                        <x-superadmin::form.control-group.label class="required">
                                            Category
                                        </x-superadmin::form.control-group.label>

                                        <x-superadmin::form.control-group.control
                                            type="select"
                                            name="categories[]"
                                            rules="required"
                                            label="Category"
                                        >
                                            <option value="">Select Category</option>

                                            @foreach ($allCategories as $category)
                                                <option value="{{ $category->id }}" {{ (string) $selectedCategoryId === (string) $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </x-superadmin::form.control-group.control>

                                        <x-superadmin::form.control-group.error control-name="categories.0" />
                                    </x-superadmin::form.control-group>

                                    <x-superadmin::form.control-group>
                                        <x-superadmin::form.control-group.label class="required">
                                            Price
                                        </x-superadmin::form.control-group.label>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price"
                                            value="{{ old('price', $currentPrice) }}"
                                            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                            required
                                        >

                                        <x-superadmin::form.control-group.error control-name="price" />
                                    </x-superadmin::form.control-group>

                                    @if ($primaryInventorySource)
                                        <x-superadmin::form.control-group>
                                            <x-superadmin::form.control-group.label class="required">
                                                Quantity
                                            </x-superadmin::form.control-group.label>

                                            <input
                                                type="number"
                                                min="0"
                                                name="inventories[{{ $primaryInventorySource->id }}]"
                                                value="{{ old('inventories.'.$primaryInventorySource->id, $currentQuantity) }}"
                                                class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                required
                                            >

                                            <x-superadmin::form.control-group.error :control-name="'inventories['.$primaryInventorySource->id.']'" />
                                        </x-superadmin::form.control-group>
                                    @endif
                                @endif

                                @foreach ($customAttributes as $attribute)
                                    @if (
                                        in_array($attribute->code, ['brand', 'brand_id', 'tax_category', 'tax_category_id'])
                                        || ($group->code === 'general' && $attribute->code === 'price')
                                    )
                                        {{-- Keep hidden values for required hidden attributes to avoid update validation failures. --}}
                                        @if (in_array($attribute->code, ['brand', 'brand_id', 'tax_category', 'tax_category_id']))
                                            <input
                                                type="hidden"
                                                name="{{ $attribute->code }}"
                                                value="{{ old($attribute->code, $attributeValuesByCode[$attribute->code] ?? $product[$attribute->code] ?? '') }}"
                                            >
                                        @endif

                                        {{-- Requested: hide/comment Brand and Tax Category fields in edit form. --}}
                                        @continue
                                    @endif

                                    {!! view_render_event("bagisto.admin.catalog.product.edit.form.{$group->code}.controls.before", ['product' => $product]) !!}

                                    <x-superadmin::form.control-group class="last:!mb-0">
                                        <x-superadmin::form.control-group.label>
                                            {!! $attribute->admin_name . ($attribute->is_required ? '<span class="required"></span>' : '') !!}

                                            @if (
                                                $attribute->value_per_channel
                                                && $channels->count() > 1
                                            )
                                                <span
                                                    class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                                                    v-pre
                                                >
                                                    {{ $currentChannel->name }}
                                                </span>
                                            @endif

                                            @if ($attribute->value_per_locale)
                                                <span
                                                    class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                                                    v-pre
                                                >
                                                    {{ $currentLocale->name }}
                                                </span>
                                            @endif
                                        </x-superadmin::form.control-group.label>

                                        @include ('superadmin::catalog.products.edit.controls', [
                                            'attribute' => $attribute,
                                            'product'   => $product,
                                        ])

                                        <x-superadmin::form.control-group.error :control-name="$attribute->code . (in_array($attribute->type, ['multiselect', 'checkbox']) ? '[]' : '')" />
                                    </x-superadmin::form.control-group>

                                    {!! view_render_event("bagisto.admin.catalog.product.edit.form.{$group->code}.controls.after", ['product' => $product]) !!}
                                @endforeach

                                @includeWhen($group->code == 'price', 'superadmin::catalog.products.edit.price.group')

                                @includeWhen($group->code === 'inventories', 'superadmin::catalog.products.edit.inventories')
                            </div>

                            {!! view_render_event("bagisto.admin.catalog.product.edit.form.{$group->code}.after", ['product' => $product]) !!}
                        @endif
                    @endforeach

                    @if ($column == 1)
                        <!-- Images View Blade File -->
                        @include('superadmin::catalog.products.edit.images')

                        <!-- Videos View Blade File -->
                        @include('superadmin::catalog.products.edit.videos')

                        <!-- Product Type View Blade File -->
                        @includeIf('superadmin::catalog.products.edit.types.' . $product->type)

                        <!-- Related, Cross Sells, Up Sells View Blade File -->
                        @include('superadmin::catalog.products.edit.links')

                        <!-- Include Product Type Additional Blade Files If Any -->
                        @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                            @includeIf($view)
                        @endforeach
                    @elseif (! $isSingleColumn)
                        <!-- Channels View Blade File -->
                        @include('superadmin::catalog.products.edit.channels')

                        {{-- Categories tree panel replaced with General category dropdown. --}}
                        {{-- @include('superadmin::catalog.products.edit.categories') --}}
                    @endif
                </div>

                @if ($isSingleColumn && ($column == 1 || $column == 2))
                    <div class="w-[360px] max-w-full max-sm:w-full">
                        @if ($column == 2)
                            <!-- Images View Blade File -->
                            @include('superadmin::catalog.products.edit.images')

                            <!-- Videos View Blade File -->
                            @include('superadmin::catalog.products.edit.videos')

                            <!-- Product Type View Blade File -->
                            @includeIf('superadmin::catalog.products.edit.types.' . $product->type)

                            <!-- Related, Cross Sells, Up Sells View Blade File -->
                            @include('superadmin::catalog.products.edit.links')

                            <!-- Include Product Type Additional Blade Files If Any -->
                            @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                                @includeIf($view)
                            @endforeach
                        @endif

                        <!-- Channels View Blade File -->
                        @include('superadmin::catalog.products.edit.channels')

                        {{-- Categories tree panel replaced with General category dropdown. --}}
                        {{-- @include('superadmin::catalog.products.edit.categories') --}}
                    </div>
                @endif

                {!! view_render_event("bagisto.admin.catalog.product.edit.form.column_{$column}.after", ['product' => $product]) !!}

            @endforeach
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.after', ['product' => $product]) !!}

    </form>

    {!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

</x-superadmin::layouts>
