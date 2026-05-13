<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.compare.title')"/>
    <meta name="keywords" content="@lang('shop::app.compare.title')"/>
@endPush

<x-shop::layouts>
    <x-slot:title>
        @lang('shop::app.compare.title')
    </x-slot>

    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div class="mt-5 flex justify-center max-lg:hidden">
            <div class="flex items-center gap-x-2.5">
                <x-shop::breadcrumbs name="compare" />
            </div>
        </div>
    @endif

    <div class="container mt-8 px-[60px] max-lg:px-8 max-md:mt-7 max-md:px-4">
        {!! view_render_event('bagisto.shop.customers.account.compare.before') !!}

        <h1 class="text-2xl font-medium max-sm:text-base">@lang('shop::app.compare.title')</h1>

        @if ($compareProducts->isEmpty())
            <div class="m-auto mt-16 grid w-full max-w-lg place-content-center items-center justify-items-center py-16 text-center">
                <img class="max-sm:h-[100px] max-sm:w-[100px]" src="{{ bagisto_asset('images/thank-you.png') }}" alt="" loading="lazy">
                <p class="mt-4 text-xl max-sm:text-sm" role="heading">@lang('shop::app.compare.empty-text')</p>
            </div>
        @else
            <div class="journal-scroll mt-10 overflow-x-auto">
                <div class="flex min-w-max gap-3 border-b border-zinc-200 pb-4">
                    <div class="min-w-[160px] shrink-0 font-medium text-zinc-700">{{ __('Product') }}</div>
                    @foreach ($compareProducts as $product)
                        @php $img = product_image()->getProductBaseImage($product); @endphp
                        <div class="relative w-[280px] shrink-0 border-l border-zinc-100 px-3 max-md:w-[200px]">
                            <a href="{{ route('shop.product_or_category.index', $product->url_key) }}" class="block">
                                <img
                                    src="{{ $img['small_image_url'] ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                    alt="{{ $product->name }}"
                                    class="mx-auto h-32 w-32 rounded-lg object-cover"
                                    loading="lazy"
                                >
                                <p class="mt-2 text-center text-sm font-medium text-zinc-900">{{ $product->name }}</p>
                                <div class="mt-1 text-center text-sm">{!! $product->getTypeInstance()->getPriceHtml() !!}</div>
                            </a>
                        </div>
                    @endforeach
                </div>

                @foreach ($comparableAttributes as $attribute)
                    @continue(in_array($attribute->code, ['name', 'price'], true))
                    <div class="flex min-w-max border-b border-zinc-200">
                        <div class="min-w-[160px] shrink-0 bg-zinc-50 px-3 py-3 text-sm font-medium text-zinc-800">
                            {{ $attribute->admin_name ?? $attribute->name }}
                        </div>
                        @foreach ($compareProducts as $product)
                            <div class="w-[280px] shrink-0 border-l border-zinc-100 px-3 py-3 text-sm text-zinc-700 max-md:w-[200px]">
                                @php
                                    $raw = $product->{$attribute->code} ?? null;
                                    if ($attribute->enable_wysiwyg && is_string($raw)) {
                                        $out = $raw;
                                    } elseif (is_string($raw) || is_numeric($raw)) {
                                        $out = e(strip_tags((string) $raw));
                                    } else {
                                        $out = 'N/A';
                                    }
                                @endphp
                                @if ($attribute->enable_wysiwyg && is_string($raw))
                                    <div class="prose prose-sm max-w-none">{!! $out !!}</div>
                                @else
                                    {{ $out }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.compare.after') !!}
    </div>
</x-shop::layouts>
