@if ($product->type == 'grouped')
    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}

    <div class="w-[455px] max-w-full max-sm:w-full">
        @php
            $groupedProducts = $product->grouped_products()->orderBy('sort_order')->get();
        @endphp

        @if ($groupedProducts->count())
            <div class="mt-8 grid gap-5 max-sm:mt-3 max-sm:gap-3">
                @foreach ($groupedProducts as $groupedProduct)
                    @if ($groupedProduct->associated_product->getTypeInstance()->isSaleable())
                        <div class="flex items-center justify-between gap-5">
                            <div class="text-sm font-medium">
                                <p class="">
                                    @lang('shop::app.products.view.type.grouped.name')
                                </p>

                                <p
                                    class="mt-1.5 text-zinc-500"
                                    v-pre
                                >
                                    {{ $groupedProduct->associated_product->name . ' + ' . core()->currency($groupedProduct->associated_product->getTypeInstance()->getFinalPrice()) }}
                                </p>

                            </div>

                            <label class="sr-only" for="grouped_qty_{{ $groupedProduct->associated_product_id }}">
                                @lang('shop::app.products.view.type.grouped.name')
                            </label>
                            <input
                                id="grouped_qty_{{ $groupedProduct->associated_product_id }}"
                                type="number"
                                name="qty[{{ $groupedProduct->associated_product_id }}]"
                                value="{{ $groupedProduct->qty }}"
                                min="0"
                                step="1"
                                class="w-24 rounded-xl border border-navyBlue px-3 py-2.5 text-center max-sm:!py-1.5"
                            >
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        
    </div>

    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}
@endif