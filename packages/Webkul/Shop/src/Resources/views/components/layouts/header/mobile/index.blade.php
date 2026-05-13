<!--
    Mobile header — HTML drawer + server-rendered categories (no Vue drawer/category).
-->
@props([
    'headerCategoryTree'      => null,
    'hideHeaderCategories'    => true,
])

@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');

    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');

    $__sfLegacyHeader = (bool) config('storefront-branding.legacy_tiktok_branding');
    $__shopNavLogoSrc = $__sfLegacyHeader
        ? asset('storage/theme/1/TikTok_logo.svg.png')
        : (core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg'));

    $__navCategories = $hideHeaderCategories
        ? collect()
        : ($headerCategoryTree ?? app(\Webkul\Category\Repositories\CategoryRepository::class)
            ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id));

    $customerUser = auth()->guard('customer')->user();

    $mobileLocales = core()->getCurrentChannel()->locales()->orderBy('name')->get();
@endphp

<div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
    <div class="flex w-full items-center justify-between">
        <!-- Left Navigation -->
        <div class="flex items-center gap-x-1.5">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <input
                type="checkbox"
                id="shop-mobile-drawer-toggle"
                class="peer sr-only"
                autocomplete="off"
            >

            <label
                for="shop-mobile-drawer-toggle"
                class="icon-hamburger cursor-pointer text-2xl"
                role="button"
                tabindex="0"
            ></label>

            <!-- Backdrop -->
            <label
                for="shop-mobile-drawer-toggle"
                class="pointer-events-none fixed inset-0 z-[45] bg-black/40 opacity-0 transition-opacity duration-200 peer-checked:pointer-events-auto peer-checked:opacity-100"
                aria-hidden="true"
            ></label>

            <!-- Panel -->
            <aside
                class="fixed top-0 z-[50] flex h-full w-full max-w-sm -translate-x-full flex-col bg-white shadow transition-transform duration-200 peer-checked:translate-x-0 ltr:left-0 rtl:right-0 rtl:translate-x-full rtl:peer-checked:translate-x-0"
            >
                <div class="flex items-center justify-between border-b border-zinc-200 p-4">
                    <a href="{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}">
                        <img
                            src="{{ $__shopNavLogoSrc }}"
                            alt="{{ config('app.name') }}"
                            width="131"
                            height="29"
                            @if ($__sfLegacyHeader) style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" @endif
                        >
                    </a>

                    <label
                        for="shop-mobile-drawer-toggle"
                        class="icon-cancel cursor-pointer text-2xl"
                        role="button"
                        tabindex="0"
                    ></label>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto">
                    <div class="border-b border-zinc-200 p-4">
                        <div class="grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 p-2.5">
                            <div>
                                <img
                                    src="{{ $customerUser?->image_url ?? bagisto_asset('images/user-placeholder.png') }}"
                                    alt=""
                                    class="h-[60px] w-[60px] rounded-full max-md:rounded-full"
                                >
                            </div>

                            @guest('customer')
                                <a
                                    href="{{ route('shop.customer.session.index') }}"
                                    class="flex text-base font-medium"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.login')

                                    <i class="icon-double-arrow text-2xl ltr:ml-2.5 rtl:mr-2.5"></i>
                                </a>
                            @endguest

                            @auth('customer')
                                <div class="flex flex-col justify-between gap-2.5 max-md:gap-0">
                                    <p class="break-all text-2xl font-medium max-md:text-xl">
                                        Hello! {{ $customerUser?->first_name }}
                                    </p>

                                    <p class="text-zinc-500 no-underline max-md:text-sm">{{ $customerUser?->email }}</p>
                                </div>
                            @endauth
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                    @if (! $hideHeaderCategories)
                        <nav class="px-6 py-4" aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.categories')">
                            @include('shop::components.layouts.header.partials.mobile-categories-html', [
                                'categories' => $__navCategories,
                                'depth' => 0,
                            ])
                        </nav>
                    @endif

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
                </div>

                @if (core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1)
                    <div class="grid w-full shrink-0 grid-cols-[1fr_auto_1fr] items-center justify-items-center gap-1 border-t border-zinc-200 bg-white px-3 py-2">
                        @if (core()->getCurrentChannel()->currencies()->count() > 1)
                            <details class="relative w-full text-center">
                                <summary
                                    class="cursor-pointer list-none px-1 py-2 text-xs font-medium uppercase max-sm:text-[10px]"
                                >
                                    {{ core()->getCurrentCurrency()->symbol }} {{ core()->getCurrentCurrencyCode() }}
                                </summary>
                                <div
                                    class="absolute bottom-full left-0 right-0 z-20 mb-1 max-h-48 overflow-auto border border-zinc-200 bg-white text-left shadow"
                                >
                                    @foreach (core()->getCurrentChannel()->currencies as $currency)
                                        <a
                                            href="{{ request()->fullUrlWithQuery(['currency' => $currency->code]) }}"
                                            @class([
                                                'block px-3 py-2 text-sm hover:bg-gray-100',
                                                'bg-gray-100' => $currency->code === core()->getCurrentCurrencyCode(),
                                            ])
                                        >
                                            {{ $currency->symbol }} {{ $currency->code }}
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        @else
                            <span class="px-1 py-2 text-xs font-medium uppercase">
                                {{ core()->getCurrentCurrency()->symbol }} {{ core()->getCurrentCurrencyCode() }}
                            </span>
                        @endif

                        <span class="h-5 w-0.5 bg-zinc-200"></span>

                        @if ($mobileLocales->count() > 1)
                            <details class="relative w-full text-center">
                                <summary
                                    class="flex cursor-pointer list-none items-center justify-center gap-1 px-1 py-2 text-xs font-medium uppercase max-sm:text-[10px]"
                                >
                                    <img
                                        src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                            ? core()->getCurrentLocale()->logo_url
                                            : bagisto_asset('images/default-language.svg') }}"
                                        class="h-full"
                                        alt=""
                                        width="24"
                                        height="16"
                                    >

                                    {{ $mobileLocales->firstWhere('code', app()->getLocale())?->name ?? app()->getLocale() }}
                                </summary>
                                <div
                                    class="absolute bottom-full left-0 right-0 z-20 mb-1 max-h-48 overflow-auto border border-zinc-200 bg-white text-left shadow"
                                >
                                    @foreach ($mobileLocales as $locale)
                                        <a
                                            href="{{ request()->fullUrlWithQuery(['locale' => $locale->code]) }}"
                                            @class([
                                                'flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100',
                                                'bg-gray-100' => $locale->code === app()->getLocale(),
                                            ])
                                        >
                                            <img
                                                src="{{ $locale->logo_url ?: bagisto_asset('images/default-language.svg') }}"
                                                width="24"
                                                height="16"
                                                alt=""
                                            >

                                            {{ $locale->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        @else
                            <span class="flex items-center justify-center gap-1 px-1 py-2 text-xs font-medium uppercase">
                                <img
                                    src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                        ? core()->getCurrentLocale()->logo_url
                                        : bagisto_asset('images/default-language.svg') }}"
                                    class="h-full"
                                    alt=""
                                    width="24"
                                    height="16"
                                >
                                {{ $mobileLocales->first()?->name ?? app()->getLocale() }}
                            </span>
                        @endif
                    </div>
                @endif
            </aside>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}"
                class="max-h-[30px]"
                aria-label="@lang('shop::app.components.layouts.header.mobile.bagisto')"
            >
                <img
                    src="{{ $__shopNavLogoSrc }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                    @if ($__sfLegacyHeader) style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" @endif
                >
            </a>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right Navigation -->
        <div>
            <div class="flex items-center gap-x-5 max-md:gap-x-4">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

                @if ($showCompare)
                    <a
                        href="{{ route('shop.compare.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.mobile.compare')"
                    >
                        <span class="icon-compare cursor-pointer text-2xl"></span>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

                {{-- Mini cart hidden (direct order flow) --}}

                <!-- Tablet+ row: native <details> profile menu (avoid Vue dropdown mount order) -->
                <div class="max-md:hidden">
                    @php
                        $_mobProfileFlyoutAlign = core()->getCurrentLocale()->direction === 'ltr'
                            ? 'right-0 left-auto'
                            : 'left-0 right-auto';
                    @endphp

                    <details class="relative">
                        <summary
                            class="block cursor-pointer list-none [&::-webkit-details-marker]:hidden"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            <span
                                class="icon-users cursor-pointer text-2xl"
                                role="presentation"
                            ></span>
                        </summary>

                        <div
                            class="absolute {{ $_mobProfileFlyoutAlign }} top-full z-[100] mt-1.5 w-max min-w-[260px] rounded-lg bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)]"
                            role="dialog"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            @guest('customer')
                                <div class="p-5">
                                    <div class="grid gap-2.5">
                                        <p class="font-dmserif text-xl">
                                            @lang('shop::app.components.layouts.header.mobile.welcome-guest')
                                        </p>

                                        <p class="text-sm">
                                            @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                        </p>
                                    </div>

                                    <p class="mt-3 w-full border border-zinc-200"></p>

                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

                                    <div class="mt-6 flex gap-4">
                                        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before') !!}

                                        <a
                                            href="{{ route('shop.customer.session.index') }}"
                                            class="primary-button mx-auto m-0 block w-max cursor-pointer rounded-2xl px-7 py-4 text-center text-base font-medium text-white ltr:ml-0 rtl:mr-0"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.sign-in')
                                        </a>

                                        <a
                                            href="{{ route('shop.customers.register.index') }}"
                                            class="secondary-button mx-auto m-0 block w-max cursor-pointer rounded-2xl border-2 border-navyBlue bg-white px-7 py-3.5 text-center text-base font-medium text-navyBlue ltr:ml-0 rtl:mr-0"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.sign-up')
                                        </a>

                                        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after') !!}
                                    </div>

                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
                                </div>
                            @endguest

                            @auth('customer')
                                <div class="overflow-hidden rounded-lg">
                                    <div class="grid gap-2.5 p-5 pb-0">
                                        <p class="font-dmserif text-xl">
                                            @lang('shop::app.components.layouts.header.mobile.welcome')
                                            {{ $customerUser?->first_name }}
                                        </p>

                                        <p class="text-sm">
                                            @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                        </p>
                                    </div>

                                    <p class="mt-3 w-full border border-zinc-200"></p>

                                    <div class="mt-2.5 grid gap-1 pb-2.5">
                                        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before') !!}

                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="{{ route('shop.customers.account.profile.index') }}"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.profile')
                                        </a>

                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="{{ route('shop.customers.account.orders.index') }}"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.orders')
                                        </a>

                                        @if ($showWishlist)
                                            <a
                                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                                href="{{ route('shop.customers.account.wishlist.index') }}"
                                            >
                                                @lang('shop::app.components.layouts.header.mobile.wishlist')
                                            </a>
                                        @endif

                                        <form
                                            id="customerLogoutHeaderMobile"
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
                                                @lang('shop::app.components.layouts.header.mobile.logout')
                                            </button>
                                        </form>

                                        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after') !!}
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </details>
                </div>

                <!-- For Medium and small screen -->
                <div class="md:hidden">
                    @guest('customer')
                        <a
                            href="{{ route('shop.customer.session.index') }}"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endguest

                    @auth('customer')
                        <a
                            href="{{ route('shop.customers.account.index') }}"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <x-shop::layouts.header.search-smart
        context="mobile"
        input-id="organic-search-mobile"
        wrapper-class="relative w-full"
        form-class="flex w-full items-center"
        input-class="block w-full rounded-xl border border-['#E3E3E3'] px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs"
    />

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}
</div>
