@php
    $globalTikStoreUrl = $globalTikStoreUrl ?? route('shop.tik-store.index', array_filter([
        'global' => '1',
        'ref' => request('ref'),
    ]));
@endphp

<div class="header-actions">
    <a class="icon-button" href="{{ route('shop.checkout.cart.index') }}" title="{{ __('Cart') }}">🛒</a>

    <details class="tik-account-menu">
        <summary class="icon-button tik-account-summary" title="{{ __('Account') }}" aria-label="{{ __('Account') }}">👤</summary>
        <div class="tik-account-dropdown" role="menu">
            @auth('customer')
                <div class="tik-account-welcome">
                    <strong>@lang('shop::app.components.layouts.header.desktop.bottom.welcome') {{ auth()->guard('customer')->user()->first_name }}</strong>
                    <span class="tik-account-sub">@lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')</span>
                </div>
                <div class="tik-account-divider"></div>
                <a href="{{ route('shop.customers.account.profile.index') }}" role="menuitem">@lang('shop::app.components.layouts.header.desktop.bottom.profile')</a>
                <a href="{{ route('shop.customers.account.orders.index') }}" role="menuitem">@lang('shop::app.components.layouts.header.desktop.bottom.orders')</a>
                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <a href="{{ route('shop.customers.account.wishlist.index') }}" role="menuitem">@lang('shop::app.components.layouts.header.desktop.bottom.wishlist')</a>
                @endif
                <form
                    id="tikStoreCustomerLogout"
                    method="POST"
                    action="{{ route('shop.customer.session.destroy') }}"
                    style="display:none"
                >
                    @csrf
                    @method('DELETE')
                </form>
                <a
                    href="{{ route('shop.customer.session.destroy') }}"
                    role="menuitem"
                    onclick="event.preventDefault(); document.getElementById('tikStoreCustomerLogout').submit();"
                >@lang('shop::app.components.layouts.header.desktop.bottom.logout')</a>
            @else
                <div class="tik-account-welcome">
                    <strong>@lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')</strong>
                    <span class="tik-account-sub">@lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')</span>
                </div>
                <div class="tik-account-divider"></div>
                <a href="{{ route('shop.customer.session.index') }}" role="menuitem" class="tik-account-primary">@lang('shop::app.components.layouts.header.desktop.bottom.sign-in')</a>
                <a href="{{ route('shop.customers.register.index') }}" role="menuitem">@lang('shop::app.components.layouts.header.desktop.bottom.sign-up')</a>
            @endauth
        </div>
    </details>

    @if (! empty($showBack) && ! empty($backUrl))
        <a class="pill-button" href="{{ $backUrl }}"><strong>{{ __('Back') }}</strong></a>
    @else
        <a class="pill-button" href="{{ $globalTikStoreUrl }}"><strong>{{ __('Main shop') }}</strong></a>
    @endif
</div>
