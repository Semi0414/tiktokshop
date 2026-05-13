<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="wishlist" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto">
        <div class="mb-8 flex items-center justify-between max-md:mb-5">
            <div class="flex items-center">
                <a class="grid md:hidden" href="{{ route('shop.customers.account.index') }}">
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>
                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.customers.account.wishlist.page-title')
                </h2>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

        @if ($wishlistItems->isEmpty())
            <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                <img class="max-md:h-[100px] max-md:w-[100px]" src="{{ bagisto_asset('images/wishlist.png') }}" alt="">
                <p class="text-xl max-md:text-sm" role="heading">@lang('shop::app.customers.account.wishlist.empty')</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($wishlistItems as $wishlist)
                    @php $product = $wishlist->product; @endphp
                    @continue(! $product)
                    <div class="flex flex-wrap gap-6 border-b border-zinc-200 pb-6">
                        <a href="{{ route('shop.product_or_category.index', $product->url_key) }}" class="shrink-0">
                            @php
                                $img = product_image()->getProductBaseImage($product);
                            @endphp
                            <img
                                class="h-28 w-28 rounded-xl object-cover max-md:h-20 max-md:w-20"
                                src="{{ $img['small_image_url'] ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                alt="{{ $product->name }}"
                            >
                        </a>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('shop.product_or_category.index', $product->url_key) }}" class="text-lg font-medium text-zinc-900 hover:underline">
                                {{ $product->name }}
                            </a>
                            <div class="mt-2 text-base font-semibold text-zinc-800">
                                {!! $product->getTypeInstance()->getPriceHtml() !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}
    </div>
</x-shop::layouts.account>
