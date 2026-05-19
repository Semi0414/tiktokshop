<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != '' ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    @php $productBaseImage = product_image()->getProductBaseImage($product); @endphp

    <meta name="twitter:card" content="summary_large_image"/>

    <meta name="twitter:title" content="{{ $product->name }}"/>

    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 200, '') }}"/>

    <meta name="twitter:image:alt" content="{{ $product->name }}"/>

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}"/>

    <meta property="og:type" content="og:product"/>

    <meta property="og:title" content="{{ $product->name }}"/>

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}"/>

    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 200, '') }}"/>

    <meta property="og:url" content="{{ url()->full() }}"/>
@endpush

<x-shop::layouts>
    <x-slot:title>{{ trim((string) $product->meta_title) !== '' ? $product->meta_title : $product->name }}</x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    @if (core()->getConfigData('general.general.breadcrumbs.shop'))
        <div class="flex justify-center px-7 max-lg:hidden">
            <x-shop::breadcrumbs
                name="product"
                :entity="$product"
            />
        </div>
    @endif

    <article
        class="product-view"
        itemscope
        itemtype="https://schema.org/Product"
    >
        <meta itemprop="name" content="{{ $product->name }}">
        <meta itemprop="sku" content="{{ $product->sku }}">

        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
            <form
                class="cart-add-form"
                method="post"
                action="{{ route('shop.checkout.cart.add_from_product') }}"
                accept-charset="UTF-8"
            >
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="pid" value="{{ $product->id }}">
                <input type="hidden" name="is_buy_now" value="0">
                <input type="hidden" name="wallet_auto_order" value="1">

                @include('shop::products.view.product-main-inner', ['insideCartForm' => true])
            </form>
        @else
            @include('shop::products.view.product-main-inner', ['insideCartForm' => false])
        @endif
    </article>

    <div class="container mt-16 max-w-full px-[60px] max-1180:px-5">
        {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

        <section id="product-description-section" aria-labelledby="product-description-heading" class="border-b border-zinc-100 py-10">
            <h2 id="product-description-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                @lang('shop::app.products.view.description')
            </h2>
            <div class="product-description prose max-w-none text-lg text-zinc-600 max-md:text-sm">
                {!! $product->description !!}
            </div>
        </section>

        {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

        @if ($attributeData->isNotEmpty())
            <section id="product-information-section" aria-labelledby="product-information-heading" class="border-b border-zinc-100 py-10">
                <h2 id="product-information-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                    @lang('shop::app.products.view.additional-information')
                </h2>
                <div class="grid max-w-2xl grid-cols-1 gap-4 md:grid-cols-[auto_1fr]" role="list">
                    @foreach ($customAttributeValues as $customAttributeValue)
                        @if (! empty($customAttributeValue['value']))
                            <div class="grid" role="listitem">
                                <div class="text-base font-medium text-zinc-900">
                                    {!! $customAttributeValue['label'] !!}
                                </div>
                            </div>

                            @if ($customAttributeValue['type'] == 'file')
                                <div role="listitem">
                                    <a href="{{ Storage::url($product[$customAttributeValue['code']]) }}" download="{{ strip_tags((string) $customAttributeValue['label']) }}" class="text-blue-600 hover:underline">
                                        <span class="text-2xl icon-download" aria-hidden="true"></span>
                                        {{ __('Download') }}
                                    </a>
                                </div>
                            @elseif ($customAttributeValue['type'] == 'image')
                                <div role="listitem">
                                    <a href="{{ Storage::url($product[$customAttributeValue['code']]) }}" download="{{ strip_tags((string) $customAttributeValue['label']) }}" class="inline-block">
                                        <img
                                            class="h-12 w-12 rounded object-cover"
                                            src="{{ Storage::url($customAttributeValue['value']) }}"
                                            alt="{{ strip_tags((string) $customAttributeValue['label']) }}"
                                        >
                                    </a>
                                </div>
                            @else
                                <div role="listitem">
                                    <div class="text-base text-zinc-600">
                                        {!! $customAttributeValue['value'] !!}
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        <section id="product-reviews-section" aria-labelledby="product-reviews-heading" class="py-10">
            <h2 id="product-reviews-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                @lang('shop::app.products.view.review')
            </h2>
            @include('shop::products.view.reviews-html')
        </section>
    </div>

    @include('shop::products.view.related-products-html')

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @push('scripts')
        <script>
            (function () {
                var form = document.querySelector('form.cart-add-form');

                if (!form || form.dataset.cartSubmitBound === '1') {
                    return;
                }

                form.dataset.cartSubmitBound = '1';

                form.addEventListener('submit', function () {
                    var btn = form.querySelector('button[type="submit"]');

                    if (!btn || btn.disabled) {
                        return;
                    }

                    btn.disabled = true;
                    btn.setAttribute('aria-busy', 'true');

                    if (!btn.dataset.originalLabel) {
                        btn.dataset.originalLabel = btn.textContent.trim();
                    }

                    btn.textContent = @json(__('Processing order…'));
                });
            })();
        </script>
    @endpush
</x-shop::layouts>
