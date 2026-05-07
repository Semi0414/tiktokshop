@props([
    'context' => 'desktop',
    'inputId' => 'organic-search',
    'wrapperClass' => 'relative w-full',
    'formClass' => 'flex max-w-[445px] items-center',
    'inputClass' => 'block w-full py-3 text-xs font-medium text-gray-900 transition-all border border-transparent rounded-lg bg-zinc-100 px-11 hover:border-gray-400 focus:border-gray-400',
])

@php
    $minLen = (int) core()->getConfigData('catalog.products.search.min_query_length') ?: 1;
    $maxLen = (int) core()->getConfigData('catalog.products.search.max_query_length') ?: 200;
    $productBase = rtrim((string) route('shop.product_or_category.index', ''), '/');

    if ($context === 'mobile') {
        $srSearch = __('shop::app.components.layouts.header.mobile.search');
        $placeholder = __('shop::app.components.layouts.header.mobile.search-text');
        $srSubmit = __('shop::app.components.layouts.header.desktop.bottom.submit');
    } else {
        $srSearch = __('shop::app.components.layouts.header.desktop.bottom.search');
        $placeholder = __('shop::app.components.layouts.header.desktop.bottom.search-text');
        $srSubmit = __('shop::app.components.layouts.header.desktop.bottom.submit');
    }
@endphp

<v-storefront-search
    context="{{ $context }}"
    search-action="{{ route('shop.search.index') }}"
    products-url="{{ route('shop.api.products.index') }}"
    sellers-url="{{ route('shop.api.sellers.search') }}"
    product-base="{{ $productBase }}"
    input-id="{{ $inputId }}"
    min-len="{{ $minLen }}"
    max-len="{{ $maxLen }}"
    form-class="{{ $formClass }}"
    wrapper-class="{{ $wrapperClass }}"
    input-class="{{ $inputClass }}"
    placeholder="{{ $placeholder }}"
    sr-search="{{ $srSearch }}"
    sr-submit="{{ $srSubmit }}"
    loading-label="{{ __('shop::app.components.layouts.header.search.loading') }}"
    stores-label="{{ __('shop::app.components.layouts.header.search.stores') }}"
    visit-store-label="{{ __('shop::app.components.layouts.header.search.visit-store') }}"
    products-label="{{ __('shop::app.components.layouts.header.search.products') }}"
    empty-label="{{ __('shop::app.components.layouts.header.search.empty') }}"
>
    @if (core()->getConfigData('catalog.products.settings.image_search'))
        @include('shop::search.images.index')
    @endif
</v-storefront-search>
