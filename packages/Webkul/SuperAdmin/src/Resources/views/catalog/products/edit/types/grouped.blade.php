{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.grouped.before', ['product' => $product]) !!}

@php
    $groupProducts = $product->grouped_products()
        ->with(['associated_product.inventory_indices', 'associated_product.images'])
        ->orderBy('sort_order', 'asc')
        ->get();
@endphp

<div class="box-shadow relative rounded bg-white dark:bg-gray-900">
    <div class="mb-2.5 flex justify-between gap-5 p-4">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('superadmin::app.catalog.products.edit.types.grouped.title')
            </p>

            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                @lang('superadmin::app.catalog.products.edit.types.grouped.info')
            </p>
        </div>
    </div>

    @if ($groupProducts->isNotEmpty())
        <div class="grid">
            @foreach ($groupProducts as $index => $groupProduct)
                @php $associated = $groupProduct->associated_product; @endphp

                <div class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800">
                    <div class="flex gap-2.5">
                        <div
                            class="relative h-[60px] max-h-[60px] w-full max-w-[60px] overflow-hidden rounded {{ $associated && $associated->images->isNotEmpty() ? '' : 'border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert' }}"
                        >
                            @if ($associated && $associated->images->isNotEmpty())
                                <img src="{{ $associated->images->first()->url }}">
                            @else
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                            @endif
                        </div>

                        <div class="grid place-content-start gap-1.5">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $associated?->name }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('superadmin::app.catalog.products.edit.types.grouped.sku', ['sku' => $associated?->sku])
                            </p>
                        </div>
                    </div>

                    <div class="grid place-content-start gap-1 ltr:text-right rtl:text-left">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ core()->formatBasePrice($associated?->price ?? 0) }}
                        </p>

                        <input
                            type="hidden"
                            name="links[{{ $groupProduct->id }}][associated_product_id]"
                            value="{{ $associated?->id }}"
                        />

                        <input
                            type="hidden"
                            name="links[{{ $groupProduct->id }}][sort_order]"
                            value="{{ $index }}"
                        />

                        <div class="!mb-0">
                            <label class="required !mb-1 !block text-xs font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.catalog.products.edit.types.grouped.default-qty')
                            </label>

                            <input
                                type="number"
                                min="1"
                                name="links[{{ $groupProduct->id }}][qty]"
                                value="{{ old('links.' . $groupProduct->id . '.qty', $groupProduct->qty ?? 1) }}"
                                class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            >
                        </div>

                        <label class="inline-flex items-center gap-1 text-xs text-red-600">
                            <input type="checkbox" name="links[{{ $groupProduct->id }}][delete]" value="1">
                            @lang('superadmin::app.catalog.products.edit.types.grouped.delete')
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
            <img
                src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
            />

            <div class="flex flex-col items-center gap-1.5">
                <p class="text-base font-semibold text-gray-400">
                    @lang('superadmin::app.catalog.products.edit.types.grouped.empty-title')
                </p>

                <p class="text-gray-400">
                    @lang('superadmin::app.catalog.products.edit.types.grouped.empty-info')
                </p>
            </div>
        </div>
    @endif
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.grouped.after', ['product' => $product]) !!}
