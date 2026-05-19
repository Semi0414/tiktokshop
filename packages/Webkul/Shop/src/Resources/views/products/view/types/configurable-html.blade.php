@if (\Webkul\Product\Helpers\ProductType::hasVariants($product->type))
    @php
        $variants = $product->variants->filter(fn ($v) => (bool) $v->status)->values();
    @endphp

    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    @if ($variants->count() === 1)
        <input type="hidden" name="selected_configurable_option" value="{{ $variants->first()->id }}">
    @elseif ($variants->isNotEmpty())
        <div class="mt-5 w-full max-w-[455px] max-sm:w-full">
            <label for="selected_configurable_option" class="mb-2 block text-base font-medium text-zinc-800 max-sm:text-sm">
                @lang('shop::app.products.view.type.configurable.select-options')
            </label>
            <select
                id="selected_configurable_option"
                name="selected_configurable_option"
                class="custom-select mb-3 block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-zinc-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
            >
                <option value="">
                    @lang('shop::app.products.view.type.configurable.select-options')
                </option>
                @foreach ($variants as $variant)
                    <option value="{{ $variant->id }}">
                        {{ $variant->name ?: $variant->sku }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}
@endif
