@props([
    'headerCategoryTree'     => null,
    'hideHeaderCategories'    => true,
])

<div class="flex w-full flex-wrap">
    <x-shop::layouts.header.desktop.bottom
        :headerCategoryTree="$headerCategoryTree"
        :hideHeaderCategories="$hideHeaderCategories"
    />
</div>
