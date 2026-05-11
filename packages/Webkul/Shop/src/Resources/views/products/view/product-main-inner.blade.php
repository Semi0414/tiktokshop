@php
    $cartFormOk = !!($supportsHtmlCartForm && core()->getConfigData('sales.checkout.shopping_cart.cart_page'));
    $tikRatingTotal = isset($tikStoreDisplay) ? (string) ($tikStoreDisplay->tik_reviews_total ?? '') : '';
    $tikRatingAvg = isset($tikStoreDisplay) ? (string) ($tikStoreDisplay->tik_rating ?? '0') : '0';
@endphp

<div class="container px-[60px] max-1180:px-0">
    <div class="flex mt-12 gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
        @include('shop::products.view.gallery-html')

        <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4">
            {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

            <header class="flex justify-between gap-4">
                <h1 id="product-main-title" class="text-3xl font-medium leading-tight text-zinc-950 break-words max-sm:text-xl" itemprop="name">
                    {{ $product->name }}
                </h1>

                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <p class="text-sm">
                        @auth('customer')
                            <a class="underline text-zinc-600 hover:text-zinc-900" href="{{ route('shop.customers.account.wishlist.index') }}">
                                @lang('shop::app.products.view.add-to-wishlist')
                            </a>
                        @else
                            <a class="underline text-zinc-600 hover:text-zinc-900" href="{{ route('shop.customer.session.index', ['redirect_url' => url()->current()]) }}">
                                @lang('shop::app.products.view.add-to-wishlist')
                            </a>
                        @endauth
                    </p>
                @endif
            </header>

            {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!}

            {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

            @if (! empty($tikStoreDisplay))
                <div class="mt-1 flex flex-col gap-2 max-sm:mt-1.5">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                        <a href="#product-reviews-section" class="inline-flex w-max rounded-md border border-zinc-200 px-4 py-2 transition-colors hover:border-zinc-400 max-sm:px-3 max-sm:py-1">
                            <x-shop::products.ratings-static
                                class="transition-all hover:border-transparent max-sm:px-3 max-sm:py-1"
                                :average="$tikRatingAvg"
                                :total="(int) $tikRatingTotal"
                            />
                        </a>
                        <span class="text-sm font-medium text-zinc-600">
                            {{ $tikStoreDisplay->tik_sold_fmt }} {{ __('sold') }}
                        </span>
                        @if (! empty($tikStoreDisplay->tik_badge_text))
                            @if (! empty($tikStoreDisplay->tik_badge_is_percent))
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-semibold text-rose-600">
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-800 px-2.5 py-0.5 text-xs font-semibold text-white">
                            @endif
                                @if (! empty($tikStoreDisplay->tik_badge_dot))
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                @endif
                                {{ $tikStoreDisplay->tik_badge_text }}
                            </span>
                        @endif
                    </div>
                    @if (! empty($tikStoreDisplay->tik_tags))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tikStoreDisplay->tik_tags as $tag)
                                <span class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                    <p class="text-xs text-zinc-500">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            {{ $tikStoreDisplay->tik_ship_label }}
                        </span>
                    </p>
                </div>
            @elseif ($reviewTotalFeedback)
                <div class="mt-1">
                    <a href="#product-reviews-section" class="inline-flex w-max rounded-md border border-zinc-200 px-4 py-2 max-sm:px-3 max-sm:py-1">
                        <x-shop::products.ratings-static
                            class="transition-all max-sm:text-xs"
                            :average="(string) $avgRatings"
                            :total="(int) $reviewTotalFeedback"
                        />
                    </a>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

            {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

            <div id="product-main-price">
                <div class="mt-[22px] flex flex-wrap items-center gap-2.5 text-2xl font-medium max-sm:mt-2 max-sm:text-lg">
                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                </div>
            </div>

            @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                <p class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                    (@lang('shop::app.products.view.tax-inclusive'))
                </p>
            @endif

            @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                <div class="mt-2.5 grid gap-1.5">
                    @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                        <div class="text-zinc-500 [&_*]:text-inherit [&_strong]:text-black">
                            {!! $offer !!}
                        </div>
                    @endforeach
                </div>
            @endif

            {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

            {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

            @if ($product->short_description)
                <div class="product-short-description mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm" itemprop="description">
                    {!! $product->short_description !!}
                </div>
            @endif

            {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

            @if (! $supportsHtmlCartForm)
                @if ($isProductCustomizable ?? false)
                    <p role="alert" class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        {{ __('This product has customization options. Please finish your purchase using the storefront tools that support them.') }}
                    </p>
                @else
                    <p role="alert" class="mt-6 rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-800">
                        {{ __('This product type cannot be ordered from this simplified product page.') }}
                    </p>
                @endif
            @else
                @if ($product->type === 'configurable')
                    @include('shop::products.view.types.configurable-html')
                @elseif ($product->type === 'simple' || $product->type === 'virtual')
                    @include('shop::products.view.types.simple')
                @elseif ($product->type === 'grouped')
                    @include('shop::products.view.types.grouped')
                @endif
            @endif

            @if ($cartFormOk && $supportsHtmlCartForm && $product->isSaleable())
                <div class="mt-8 flex max-w-[470px] flex-wrap items-center gap-4 max-sm:mt-4">
                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                    @if ($product->getTypeInstance()->showQuantityBox())
                        <label for="product_quantity" class="sr-only">@lang('shop::app.checkout.cart.index.quantity')</label>
                        <input
                            id="product_quantity"
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            step="1"
                            class="w-28 rounded-xl border border-navyBlue px-4 py-3 text-center max-sm:py-1.5"
                            required
                        >
                    @endif

                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                    {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

                    <button
                        type="submit"
                        name="checkout_action"
                        value="cart"
                        class="primary-button inline-flex justify-center rounded-lg px-10 py-3 text-center text-base font-semibold text-white max-sm:rounded-lg max-sm:py-1.5 disabled:opacity-50"
                        @disabled(! $product->isSaleable())
                    >
                        @lang('shop::app.products.view.add-to-cart')
                    </button>

                    @if ($product->type === 'configurable' || $product->type === 'grouped')
                        {{-- configurable / grouped validated server-side via Cart::addProduct --}}
                    @endif

                    {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
                </div>
            @elseif ($cartFormOk && $supportsHtmlCartForm && ! $product->isSaleable())
                <p class="mt-8 text-base font-medium text-red-600">
                    {{ __('This product cannot be ordered right now.') }}
                </p>
            @endif

            @if (! empty(core()->getConfigData('catalog.products.settings.compare_option')))
                <nav class="mt-10 flex flex-wrap gap-x-9 gap-y-3 text-base max-md:text-sm" aria-label="Compare">
                    <a href="{{ route('shop.compare.index') }}" class="inline-flex items-center gap-2.5 underline text-zinc-700 hover:text-zinc-900">
                        <span class="icon-compare text-2xl" aria-hidden="true"></span>
                        @lang('shop::app.products.view.compare')
                    </a>
                </nav>
            @endif
        </div>
    </div>
</div>
