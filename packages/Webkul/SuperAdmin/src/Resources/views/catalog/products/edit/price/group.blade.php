<!-- Separator -->
<span class="absolute my-1.5 block w-full border border-gray-200 ltr:left-0 rtl:right-0"></span>

@inject('customerGroupRepository', 'Webkul\Customer\Repositories\CustomerGroupRepository')
@php
    $groupPrices = collect(old('customer_group_prices', $product->customer_group_prices?->toArray() ?? []))
        ->values();
@endphp

<div class="mt-1.5 py-4">
    <p class="py-2.5 text-base font-semibold text-gray-800 dark:text-white">
        @lang('superadmin::app.catalog.products.edit.price.group.title')
    </p>
</div>

@if ($groupPrices->isEmpty())
    <div class="flex items-center gap-5 py-2.5">
        <img
            src="{{ bagisto_asset('images/icon-discount.svg') }}"
            class="h-20 w-20 rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
        />

        <div class="flex flex-col gap-1.5">
            <p class="text-base font-semibold text-gray-400">
                @lang('superadmin::app.catalog.products.edit.price.group.add-group-price')
            </p>

            <p class="text-gray-400">
                @lang('superadmin::app.catalog.products.edit.price.group.empty-info')
            </p>
        </div>
    </div>
@else
    <div class="grid gap-4">
        @foreach ($groupPrices as $index => $item)
            @php
                $itemId = data_get($item, 'id', 'price_' . $index);
                $selectedGroupId = (string) data_get($item, 'customer_group_id', '');
                $qty = data_get($item, 'qty', 0);
                $valueType = data_get($item, 'value_type', 'fixed');
                $value = data_get($item, 'value', 0);
            @endphp

            <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                <input
                    type="hidden"
                    name="customer_group_prices[{{ $itemId }}][id]"
                    value="{{ $itemId }}"
                />

                <div class="grid gap-4">
                    <x-superadmin::form.control-group class="mb-0">
                        <x-superadmin::form.control-group.label>
                            @lang('superadmin::app.catalog.products.edit.price.group.create.customer-group')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="select"
                            :name="'customer_group_prices[' . $itemId . '][customer_group_id]'"
                            :value="$selectedGroupId"
                            :label="trans('superadmin::app.catalog.products.edit.price.group.create.customer-group')"
                        >
                            <option value="">
                                @lang('superadmin::app.catalog.products.edit.price.group.create.all-groups')
                            </option>

                            @foreach ($customerGroupRepository->all() as $group)
                                <option
                                    value="{{ $group->id }}"
                                    @selected((string) $group->id === $selectedGroupId)
                                >
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </x-superadmin::form.control-group.control>
                    </x-superadmin::form.control-group>

                    <x-superadmin::form.control-group class="mb-0">
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.catalog.products.edit.price.group.create.qty')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            :name="'customer_group_prices[' . $itemId . '][qty]'"
                            rules="required|numeric|min_value:1"
                            :value="$qty"
                            :label="trans('superadmin::app.catalog.products.edit.price.group.create.qty')"
                        />
                    </x-superadmin::form.control-group>

                    <x-superadmin::form.control-group class="mb-0">
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.catalog.products.edit.price.group.create.price-type')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="select"
                            :name="'customer_group_prices[' . $itemId . '][value_type]'"
                            rules="required"
                            :value="$valueType"
                            :label="trans('superadmin::app.catalog.products.edit.price.group.create.price-type')"
                        >
                            <option value="fixed" @selected($valueType === 'fixed')>
                                @lang('superadmin::app.catalog.products.edit.price.group.create.fixed')
                            </option>

                            <option value="discount" @selected($valueType === 'discount')>
                                @lang('superadmin::app.catalog.products.edit.price.group.create.discount')
                            </option>
                        </x-superadmin::form.control-group.control>
                    </x-superadmin::form.control-group>

                    <x-superadmin::form.control-group class="mb-0">
                        <x-superadmin::form.control-group.label class="required">
                            @lang('superadmin::app.catalog.products.edit.price.group.create.price')
                        </x-superadmin::form.control-group.label>

                        <x-superadmin::form.control-group.control
                            type="text"
                            :name="'customer_group_prices[' . $itemId . '][value]'"
                            rules="required|decimal|min_value:0"
                            :value="$value"
                            :label="trans('superadmin::app.catalog.products.edit.price.group.create.price')"
                        />
                    </x-superadmin::form.control-group>
                </div>
            </div>
        @endforeach
    </div>
@endif
