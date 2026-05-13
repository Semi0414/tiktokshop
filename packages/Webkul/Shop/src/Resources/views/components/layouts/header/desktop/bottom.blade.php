@props([
    'headerCategoryTree'      => null,
    'hideHeaderCategories'    => true,
])

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[78px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        @php
            $__sfLegacyHeader = (bool) config('storefront-branding.legacy_tiktok_branding');
            $__shopNavLogoSrc = $__sfLegacyHeader
                ? asset('storage/theme/1/TikTok_logo.svg.png')
                : (core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg'));
        @endphp
        <a
            href="{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}"
            aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.bagisto')"
        >
            <img
                src="{{ $__shopNavLogoSrc }}"
                width="131"
                height="29"
                @if ($__sfLegacyHeader) style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" @endif
                alt="{{ config('app.name') }}"
            >
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        @if (! $hideHeaderCategories)
            <div
                class="flex max-[1300px]:hidden items-center gap-x-6 overflow-x-auto whitespace-nowrap"
                data-category-scroller
            >
                @include('shop::components.layouts.header.partials.desktop-categories-html', [
                    'categories' => $headerCategoryTree ?? app(\Webkul\Category\Repositories\CategoryRepository::class)
                        ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id),
                ])
            </div>
        @endif
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar (products + seller stores) -->
        <x-shop::layouts.header.search-smart context="desktop" />

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <!-- Right Navigation Links -->
        <div class="mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            <!-- Compare -->
            @if(core()->getConfigData('catalog.products.settings.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.compare')"
                >
                    <span
                        class="inline-block text-2xl cursor-pointer icon-compare"
                        role="presentation"
                    ></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {{-- Mini cart hidden (direct order flow) --}}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            @php
                $_profileFlyoutAlign = core()->getCurrentLocale()->direction === 'ltr'
                    ? 'right-0 left-auto'
                    : 'left-0 right-auto';
            @endphp

            <!-- user profile — native <details> (Vue v-dropdown was not reliable after layout changes) -->
            <details class="relative mt-1.5">
                <summary
                    class="block cursor-pointer list-none [&::-webkit-details-marker]:hidden"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.profile')"
                >
                    <span
                        class="icon-users inline-block cursor-pointer text-2xl"
                        role="presentation"
                    ></span>
                </summary>

                <div
                    role="dialog"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.profile')"
                    class="absolute {{ $_profileFlyoutAlign }} top-full z-[100] mt-1.5 w-max min-w-[260px] rounded-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)]"
                >
                    @guest('customer')
                        <div class="p-5">
                            <div class="grid gap-2.5">
                                <p class="font-dmserif text-xl">
                                    @lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')
                                </p>

                                <p class="text-sm">
                                    @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                                </p>
                            </div>

                            <p class="mt-3 w-full border border-zinc-200"></p>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before') !!}

                            <div class="mt-6 flex gap-4">
                                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                                <a
                                    href="{{ route('shop.customer.session.index') }}"
                                    class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 py-3 text-center text-base ltr:ml-0 rtl:mr-0"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.sign-in')
                                </a>

                                <a
                                    href="{{ route('shop.customers.register.index') }}"
                                    class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 py-3 text-center text-base max-md:py-3 ltr:ml-0 rtl:mr-0"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.sign-up')
                                </a>

                                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after') !!}
                            </div>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after') !!}
                        </div>
                    @endguest

                    @auth('customer')
                        <div class="overflow-hidden rounded-[20px]">
                            <div class="grid gap-2.5 p-5 pb-0">
                                <p class="font-dmserif text-xl">
                                    @lang('shop::app.components.layouts.header.desktop.bottom.welcome')
                                    {{ auth()->guard('customer')->user()->first_name }}
                                </p>

                                <p class="text-sm">
                                    @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                                </p>
                            </div>

                            <p class="mt-3 w-full border border-zinc-200"></p>

                            <div class="mt-2.5 grid gap-1 pb-2.5">
                                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.profile.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.profile')
                                </a>

                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.orders.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.orders')
                                </a>

                                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                    <a
                                        class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                        href="{{ route('shop.customers.account.wishlist.index') }}"
                                    >
                                        @lang('shop::app.components.layouts.header.desktop.bottom.wishlist')
                                    </a>
                                @endif

                                <form
                                    id="customerLogoutHeaderDesktop"
                                    method="POST"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    class="contents"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="w-full cursor-pointer px-5 py-2 text-start text-base hover:bg-gray-100"
                                    >
                                        @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                                    </button>
                                </form>

                                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
                            </div>
                        </div>
                    @endauth
                </div>
            </details>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
