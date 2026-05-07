<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} — {{ $__sfStoreLabel }}</title>
    <link rel="icon" type="image/webp" href="{{ asset('storage/theme/1/favicon.webp') }}" />
    @include('shop::tik-store.partials.styles')
</head>
<body class="tik-store-page">
@php
    $sellerParam = $seller ? ['seller' => $seller->id] : [];
    $globalTikStoreUrl = route('shop.tik-store.index', array_filter([
        'global' => '1',
        'ref' => request('ref'),
    ]));
@endphp

<header class="header">
    <div class="header-inner">
        <a href="{{ $globalTikStoreUrl }}" class="logo-area" style="text-decoration:none;">
            <div class="logo-mark">{{ $__sfLogoMark }}</div>
            <div>
                <div class="logo-text-main">{{ $__sfStoreLabel }}</div>
                <div class="logo-text-sub">{{ $seller ? $seller->name : __('Browse') }}</div>
            </div>
        </a>
        <div class="search-wrapper">
            <form class="search-input" id="tikStoreSearchForm" action="{{ route('shop.tik-store.index') }}" method="get" autocomplete="off">
                @if($seller)
                    <input type="hidden" name="seller" value="{{ $seller->id }}" />
                @endif
                <span class="search-icon">🔍</span>
                <input id="tikStoreSellerSearch" name="q" type="search" placeholder="{{ __('Search seller store…') }}" minlength="2" />
            </form>
            <div class="seller-search-dropdown" id="sellerSearchDropdown"></div>
        </div>
        @include('shop::tik-store.partials.header-actions', [
            'globalTikStoreUrl' => $globalTikStoreUrl,
            'showBack' => true,
            'backUrl' => $backUrl,
        ])
    </div>
</header>

<main class="page" style="display:block;max-width:1240px;margin:0 auto;padding:1.25rem;">
    <a href="{{ $backUrl }}" class="back-link">← Back to {{ $__sfStoreLabel }}</a>
    <h1 class="list-page-title">{{ $title }}</h1>
    @if($seller)
        <p class="muted" style="font-size:0.85rem;color:#666;margin-bottom:1rem;">{{ __('Store') }}: <strong>{{ $seller->name }}</strong> (ID {{ $seller->id }})</p>
    @endif

    <section class="grid">
        @forelse($products as $product)
            @include('shop::tik-store.partials.product-card', ['product' => $product])
        @empty
            <p class="muted">{{ __('No products found.') }}</p>
        @endforelse
    </section>

    @include('shop::tik-store.partials.pagination', ['paginator' => $products])
</main>

<footer class="footer">
    <div class="footer-inner">
        <div>{{ config('app.name') }}</div>
        <div>© {{ date('Y') }}</div>
    </div>
</footer>

<script>
(function () {
    const sellersUrl = @json($apiSellersSearchUrl);
    const tikStoreIndex = @json(route('shop.tik-store.index'));
    const input = document.getElementById('tikStoreSellerSearch');
    const dropdown = document.getElementById('sellerSearchDropdown');
    const form = document.getElementById('tikStoreSearchForm');

    function hideDropdown() {
        if (!dropdown) return;
        dropdown.classList.remove('open');
        dropdown.innerHTML = '';
    }

    function goSeller(id) {
        const url = new URL(tikStoreIndex, window.location.origin);
        url.searchParams.set('seller', id);
        window.location.href = url.toString();
    }

    if (input && dropdown) {
        let timer = null;
        input.addEventListener('input', function () {
            const q = (input.value || '').trim();
            clearTimeout(timer);
            if (q.length < 2) { hideDropdown(); return; }
            timer = setTimeout(function () {
                var u = new URL(sellersUrl, window.location.origin);
                u.searchParams.set('query', q);
                fetch(u.toString(), { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        const rows = (data && data.data) ? data.data : [];
                        if (!rows.length) { hideDropdown(); return; }
                        dropdown.innerHTML = rows.map(function (s) {
                            const img = s.image_url
                                ? '<img src="' + s.image_url.replace(/"/g, '&quot;') + '" alt="">'
                                : '<span style="width:32px;height:32px;border-radius:999px;background:#eee;display:inline-block;"></span>';
                            return '<div class="seller-search-item" data-id="' + s.id + '">' + img + '<div><div style="font-weight:600;">' + (s.name || '') + '</div></div></div>';
                        }).join('');
                        dropdown.classList.add('open');
                        dropdown.querySelectorAll('.seller-search-item').forEach(function (el) {
                            el.addEventListener('click', function () { goSeller(el.getAttribute('data-id')); });
                        });
                    }).catch(function () { hideDropdown(); });
            }, 250);
        });
        document.addEventListener('click', function (e) {
            if (!dropdown || !input) return;
            if (e.target === input || dropdown.contains(e.target)) return;
            hideDropdown();
        });
    }
    if (form) form.addEventListener('submit', function (e) { e.preventDefault(); });
})();
</script>
@include('shop::tik-store.partials.wishlist-script')
@include('shop::components.layouts.storefront-chat-widgets')
</body>
</html>
