<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="reviews" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.reviews.title')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

        @if ($reviews->isNotEmpty())
            <div class="mt-2 grid gap-5 max-md:mt-0">
                @foreach ($reviews as $review)
                    @php
                        $reviewProduct = $review->product;
                        $reviewImg = $reviewProduct
                            ? product_image()->getProductBaseImage($reviewProduct)
                            : [];
                    @endphp
                    <a
                        href="{{ $reviewProduct ? route('shop.product_or_category.index', $reviewProduct->url_key) : '#' }}"
                        class="block rounded-xl border border-zinc-200 p-6 transition-shadow hover:shadow-md max-md:p-4"
                        id="customer-review-{{ $review->id }}"
                        aria-label="{{ $review->title }}"
                    >
                        <div class="flex gap-5 max-md:flex-col max-md:gap-3">
                            @if ($reviewProduct)
                                <x-shop::media.images.lazy
                                    class="h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 shrink-0 rounded-xl max-md:mx-auto max-md:h-20 max-md:w-20 max-md:min-w-20 max-md:max-w-20 max-md:rounded-lg"
                                    src="{{ $reviewImg['small_image_url'] ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                    alt="{{ $reviewProduct->name }}"
                                />
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <p class="text-xl font-medium max-md:text-base">
                                        {{ $review->title }}
                                    </p>
                                    <div class="flex shrink-0 items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="icon-star-fill text-2xl {{ $review->rating >= $i ? 'text-amber-500' : 'text-zinc-400' }}"></span>
                                        @endfor
                                    </div>
                                </div>

                                <p class="mt-2 text-sm font-medium text-zinc-500">
                                    {{ $review->created_at }}
                                </p>

                                <p class="mt-4 text-base text-zinc-600 max-md:mt-2 max-md:text-sm">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                <img
                    class="max-md:h-[100px] max-md:w-[100px]"
                    src="{{ bagisto_asset('images/review.png') }}"
                    alt=""
                    loading="lazy"
                >

                <p class="text-xl max-md:text-sm" role="heading">
                    @lang('shop::app.customers.account.reviews.empty-review')
                </p>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}
    </div>
</x-shop::layouts.account>
