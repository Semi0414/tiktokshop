@props(['hideHeaderCategories' => true])

{!! view_render_event('bagisto.shop.layout.header.before') !!}

@php
    if ($hideHeaderCategories) {
        $headerCategoryTree = collect();
    } else {
        $headerCategoryTree ??= app(\Webkul\Category\Repositories\CategoryRepository::class)
            ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
    }
@endphp

@include('shop::components.layouts.header.search-storefront')

@if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
    </div>
@endif

<header class="shadow-gray sticky top-0 z-10 bg-white shadow-sm max-lg:shadow-none">
    {{-- Same visibility pattern as the old Vue v-header-switcher (max-lg:hidden / lg:hidden) --}}
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop
            :headerCategoryTree="$headerCategoryTree"
            :hideHeaderCategories="$hideHeaderCategories"
        />
    </div>

    <div class="lg:hidden">
        <x-shop::layouts.header.mobile
            :headerCategoryTree="$headerCategoryTree"
            :hideHeaderCategories="$hideHeaderCategories"
        />
    </div>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
