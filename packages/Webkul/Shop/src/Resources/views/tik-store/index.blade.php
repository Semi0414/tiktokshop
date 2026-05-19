<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $__sfStoreLabel }} — {{ $seller ? $seller->name : __('Browse') }}</title>
    <link rel="icon" type="image/webp" href="{{ asset('storage/theme/1/favicon.webp') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @include('shop::tik-store.partials.styles')
    @include('shop::tik-store.partials.styles-mobile')
</head>
<body class="tik-store-page">
@php
    $sellerParam = $seller ? ['seller' => $seller->id] : [];
    $f = $filters ?? [];
    /** Official / full-catalog TikStore (ignores seller session). */
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
                <div class="logo-text-sub">{{ $seller ? $seller->name : __('Official Shop') }}</div>
            </div>
        </a>

        <div class="search-wrapper">
            <form class="search-input" id="tikStoreSearchForm" action="{{ route('shop.tik-store.index', $linkParams ?? []) }}" method="get" autocomplete="off">
                @if($seller)
                    <input type="hidden" name="seller" value="{{ $seller->id }}" />
                @endif
                @foreach($linkParams ?? [] as $fk => $fv)
                    @if(!in_array($fk, ['seller', 'q'], true))
                        <input type="hidden" name="{{ $fk }}" value="{{ $fv }}" />
                    @endif
                @endforeach
                <span class="search-icon" aria-hidden="true">🔍</span>
                <input
                    id="tikStoreSellerSearch"
                    name="q"
                    type="search"
                    placeholder="{{ __('Search seller store by name…') }}"
                    minlength="2"
                />
            </form>
            <div class="seller-search-dropdown" id="sellerSearchDropdown" role="listbox"></div>
        </div>

        @include('shop::tik-store.partials.header-actions', [
            'globalTikStoreUrl' => $globalTikStoreUrl,
            'showBack' => false,
        ])
    </div>
</header>

@if(!empty($heroSlides) && count($heroSlides) > 0)
<div class="tik-hero-wrap">
    <div class="tik-hero" id="tikHero">
        <div class="tik-hero-viewport">
            <div class="tik-hero-track" id="tikHeroTrack">
                @foreach($heroSlides as $slide)
                    <div class="tik-hero-slide">
                        <img src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async" />
                    </div>
                @endforeach
            </div>
        </div>
        <button type="button" class="tik-hero-nav tik-hero-prev" id="tikHeroPrev" aria-label="{{ __('Previous slide') }}">‹</button>
        <button type="button" class="tik-hero-nav tik-hero-next" id="tikHeroNext" aria-label="{{ __('Next slide') }}">›</button>
        <div class="tik-hero-dots" id="tikHeroDots" aria-hidden="true"></div>
    </div>
</div>
@endif

<main class="page">
    <div class="mobile-filter-backdrop" id="mobileFilterBackdrop" aria-hidden="true"></div>

    <aside class="sidebar sidebar-filters" id="tikStoreSidebar">
        <button type="button" class="sidebar-mobile-close" id="sidebarMobileClose" aria-label="{{ __('Close') }}">×</button>

        <form class="sidebar-card filter-form" method="get" action="{{ route('shop.tik-store.index', $linkParams ?? []) }}" id="tikStoreFilterForm">
            @if($seller)
                <input type="hidden" name="seller" value="{{ $seller->id }}" />
            @endif
            @if(request('global') === '1')
                <input type="hidden" name="global" value="1" />
            @endif
            @if(request()->filled('ref'))
                <input type="hidden" name="ref" value="{{ request('ref') }}" />
            @endif
            @if($selectedCategoryId)
                <input type="hidden" name="category" value="{{ $selectedCategoryId }}" />
            @endif

            <div class="sidebar-title">{{ __('Filters') }}</div>
            <div class="sidebar-subtitle">{{ __('Price, rating & delivery') }}</div>

            <div class="filter-row">
                <label for="min_price">{{ __('Min price') }}</label>
                <input type="number" step="0.01" min="0" name="min_price" id="min_price" value="{{ $f['min_price'] ?? '' }}" placeholder="0" />
            </div>
            <div class="filter-row">
                <label for="max_price">{{ __('Max price') }}</label>
                <input type="number" step="0.01" min="0" name="max_price" id="max_price" value="{{ $f['max_price'] ?? '' }}" placeholder="—" />
            </div>

            <div class="filter-row">
                <label for="min_rating">{{ __('Minimum rating') }}</label>
                <select name="min_rating" id="min_rating">
                    <option value="">{{ __('Any') }}</option>
                    <option value="3" @selected(($f['min_rating'] ?? '') == '3')>3+ ★</option>
                    <option value="3.5" @selected(($f['min_rating'] ?? '') == '3.5')>3.5+ ★</option>
                    <option value="4" @selected(($f['min_rating'] ?? '') == '4')>4+ ★</option>
                    <option value="4.5" @selected(($f['min_rating'] ?? '') == '4.5')>4.5+ ★</option>
                </select>
            </div>

            <label class="filter-check">
                <input type="checkbox" name="free_shipping" value="1" @checked(!empty($f['free_shipping'])) />
                {{ __('Free shipping') }}
            </label>
            <label class="filter-check">
                <input type="checkbox" name="dispatch_24h" value="1" @checked(!empty($f['dispatch_24h'])) />
                {{ __('24h dispatch') }}
            </label>

            <div class="filter-row" style="margin-top:0.5rem;">
                <label for="sort">{{ __('Sort by') }}</label>
                <select name="sort" id="sort">
                    <option value="newest" @selected(($f['sort'] ?? 'newest') === 'newest')>{{ __('Newest') }}</option>
                    <option value="price_asc" @selected(($f['sort'] ?? '') === 'price_asc')>{{ __('Price: Low to High') }}</option>
                    <option value="price_desc" @selected(($f['sort'] ?? '') === 'price_desc')>{{ __('Price: High to Low') }}</option>
                    <option value="top_rated" @selected(($f['sort'] ?? '') === 'top_rated')>{{ __('Top rated') }}</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="filter-btn">{{ __('Apply') }}</button>
                <a href="{{ route('shop.tik-store.index', array_filter(['seller' => $seller?->id, 'global' => request('global') === '1' ? '1' : null, 'ref' => request('ref')])) }}" class="filter-btn filter-btn-secondary" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">{{ __('Reset') }}</a>
            </div>
        </form>

        <section class="sidebar-card">
            <div class="sidebar-title">{{ __('Categories') }}</div>
            <div class="sidebar-subtitle">{{ __('Browse by name') }}</div>
            <ul class="category-nav-list">
                <li>
                    <a href="{{ route('shop.tik-store.index', $linkParams ?? []) }}" class="{{ !$selectedCategoryId ? 'active' : '' }}">{{ __('All categories') }}</a>
                </li>
                @foreach($carouselCategories as $cat)
                    <li>
                        <a
                            href="{{ route('shop.tik-store.index', array_merge($linkParams ?? [], ['category' => $cat->id])) }}"
                            class="{{ (int) $selectedCategoryId === (int) $cat->id ? 'active' : '' }}"
                        >{{ $cat->name }}</a>
                    </li>
                @endforeach
            </ul>
        </section>
    </aside>

    <section class="content">
        <div class="breadcrumb-row">
            <div class="breadcrumb">
                <a href="{{ route('shop.landing.index') }}">{{ __('Home') }}</a>
                <span>›</span>
                <a href="{{ $globalTikStoreUrl }}">{{ $__sfStoreLabel }}</a>
                @if($seller)
                    <span>›</span>
                    <a href="{{ $globalTikStoreUrl }}" class="breadcrumb-to-global" style="font-weight:600;text-decoration:none;color:inherit;">{{ $seller->name }}</a>
                @endif
            </div>
            @if($seller)
                <a href="{{ $globalTikStoreUrl }}" class="tik-store-id-link" style="text-decoration:none;color:inherit;">
                    <strong>{{ __('Store ID') }}:</strong> {{ $seller->id }}
                </a>
            @endif
        </div>

        <button type="button" class="mobile-filter-toggle" id="mobileFilterToggle" aria-expanded="false" aria-controls="tikStoreSidebar">
            <span class="mobile-filter-toggle__icon" aria-hidden="true">☰</span>
            <span>{{ __('Filters & categories') }}</span>
        </button>

        <div class="category-carousel-wrap">
            <div class="category-carousel-title">{{ __('Shop by category') }}</div>
            <div class="category-carousel" role="navigation" aria-label="{{ __('Categories') }}">
                <a href="{{ route('shop.tik-store.index', $linkParams ?? []) }}" class="cat-card {{ !$selectedCategoryId ? 'active' : '' }}" style="text-decoration:none;color:inherit;">
                    <div class="cat-card-img" style="display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;">{{ __('All') }}</div>
                    <div class="cat-card-name">{{ __('All products') }}</div>
                </a>
                @foreach($carouselCategories as $cat)
                    <a
                        href="{{ route('shop.tik-store.index', array_merge($linkParams ?? [], ['category' => $cat->id])) }}"
                        class="cat-card {{ (int) $selectedCategoryId === (int) $cat->id ? 'active' : '' }}"
                        style="text-decoration:none;color:inherit;"
                    >
                        @if($cat->logo_url)
                            <img class="cat-card-img" src="{{ $cat->logo_url }}" alt="" loading="lazy" />
                        @else
                            <div class="cat-card-img" style="display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:1.2rem;">◆</div>
                        @endif
                        <div class="cat-card-name">{{ $cat->name }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        <section class="section-toolbar">
            <div>
                <h2>{{ __('Recommended') }}</h2>
                <p class="muted">{{ __('First picks') }} · {{ $seller ? __('This store') : __('Catalog') }}</p>
            </div>
            <a href="{{ route('shop.tik-store.recommended', $linkParams ?? []) }}" class="view-all-btn">{{ __('View all recommended') }} →</a>
        </section>
        <div class="horizontal-scroll-grid">
            @forelse($recommendedPreview as $product)
                @include('shop::tik-store.partials.product-card', ['product' => $product])
            @empty
                <p class="muted" style="padding:0.5rem;">{{ __('No recommended products yet.') }}</p>
            @endforelse
        </div>

        <section class="section-toolbar" style="margin-top:0.5rem;">
            <div>
                <h2>{{ __('Newest') }}</h2>
                <p class="muted">{{ __('Latest catalog') }}</p>
            </div>
            <a href="{{ route('shop.tik-store.products', $linkParams ?? []) }}" class="view-all-btn">{{ __('View all products') }} →</a>
        </section>
        <div class="horizontal-scroll-grid">
            @forelse($latestPreview as $product)
                @include('shop::tik-store.partials.product-card', ['product' => $product])
            @empty
                <p class="muted" style="padding:0.5rem;">{{ __('No products yet.') }}</p>
            @endforelse
        </div>

        <section class="toolbar-card">
            <div>
                <div class="toolbar-title">{{ __('All products') }}</div>
                <div class="toolbar-count">
                     {{ __('All products catalog') }}
                    @if($selectedCategoryId)
                        · {{ __('filtered by category') }}
                    @endif
                </div>
            </div>
        </section>

        <section class="grid">
            @forelse($gridProducts as $product)
                @include('shop::tik-store.partials.product-card', ['product' => $product])
            @empty
                <p class="muted">{{ __('No products match these filters.') }}</p>
            @endforelse
        </section>

        @include('shop::tik-store.partials.pagination', ['paginator' => $gridProducts])
    </section>
</main>

<div id="ts-ml-skin" class="tik-store-ml-footer-shell">
    <footer class="footer">
        <div class="footer-inner">
            <div>{{ config('app.name') }} · {{ $__sfStoreLabel }}</div>
            <div>© {{ date('Y') }}</div>
        </div>
    </footer>
</div>

<button type="button" class="back-to-top" id="backToTop" aria-label="{{ __('Back to top') }}">↑</button>

<script>
(function () {
    const sellersUrl = @json($apiSellersSearchUrl);
    const tikStoreIndex = @json(route('shop.tik-store.index'));
    const input = document.getElementById('tikStoreSellerSearch');
    const dropdown = document.getElementById('sellerSearchDropdown');
    const form = document.getElementById('tikStoreSearchForm');
    let timer = null;

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

    if (input && dropdown && sellersUrl) {
        input.addEventListener('input', function () {
            const q = (input.value || '').trim();
            clearTimeout(timer);
            if (q.length < 2) {
                hideDropdown();
                return;
            }
            timer = setTimeout(function () {
                var u = new URL(sellersUrl, window.location.origin);
                u.searchParams.set('query', q);
                fetch(u.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        const rows = (data && data.data) ? data.data : [];
                        if (!rows.length) {
                            hideDropdown();
                            return;
                        }
                        dropdown.innerHTML = rows.map(function (s) {
                            const img = s.image_url
                                ? '<img src="' + s.image_url.replace(/"/g, '&quot;') + '" alt="">'
                                : '<span style="width:32px;height:32px;border-radius:999px;background:#eee;display:inline-block;"></span>';
                            return '<div class="seller-search-item" role="option" data-id="' + s.id + '">' + img + '<div><div style="font-weight:600;">' + (s.name || '') + '</div></div></div>';
                        }).join('');
                        dropdown.classList.add('open');
                        dropdown.querySelectorAll('.seller-search-item').forEach(function (el) {
                            el.addEventListener('click', function () {
                                goSeller(el.getAttribute('data-id'));
                            });
                        });
                    })
                    .catch(function () { hideDropdown(); });
            }, 250);
        });

        document.addEventListener('click', function (e) {
            if (!dropdown || !input) return;
            if (e.target === input || dropdown.contains(e.target)) return;
            hideDropdown();
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });
    }

    const track = document.getElementById('tikHeroTrack');
    const hero = document.getElementById('tikHero');
    const prev = document.getElementById('tikHeroPrev');
    const next = document.getElementById('tikHeroNext');
    const dotsWrap = document.getElementById('tikHeroDots');
    if (track && hero && prev && next && dotsWrap) {
        const slides = track.children.length;
        let idx = 0;
        let auto = null;

        function renderDots() {
            dotsWrap.innerHTML = '';
            for (let i = 0; i < slides; i++) {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'tik-hero-dot' + (i === idx ? ' active' : '');
                b.addEventListener('click', function () { go(i); });
                dotsWrap.appendChild(b);
            }
        }

        function go(i) {
            idx = (i + slides) % slides;
            track.style.transform = 'translateX(-' + (idx * 100) + '%)';
            renderDots();
        }

        function nextSlide() { go(idx + 1); }

        prev.addEventListener('click', function () { go(idx - 1); resetAuto(); });
        next.addEventListener('click', function () { go(idx + 1); resetAuto(); });

        function resetAuto() {
            clearInterval(auto);
            auto = setInterval(nextSlide, 6000);
        }

        renderDots();
        resetAuto();
    }

    const filterToggle = document.getElementById('mobileFilterToggle');
    const filterBackdrop = document.getElementById('mobileFilterBackdrop');

    function setMobileFiltersOpen(open) {
        document.body.classList.toggle('tik-store-filters-open', open);
        if (filterToggle) {
            filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        if (filterBackdrop) {
            filterBackdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
        document.body.style.overflow = open ? 'hidden' : '';
    }

    if (filterToggle) {
        filterToggle.addEventListener('click', function () {
            setMobileFiltersOpen(!document.body.classList.contains('tik-store-filters-open'));
        });
    }

    if (filterBackdrop) {
        filterBackdrop.addEventListener('click', function () {
            setMobileFiltersOpen(false);
        });
    }

    const sidebarClose = document.getElementById('sidebarMobileClose');
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function () {
            setMobileFiltersOpen(false);
        });
    }

    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) backToTop.classList.add('visible');
            else backToTop.classList.remove('visible');
        });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
</script>
@include('shop::tik-store.partials.wishlist-script')
@include('shop::tik-store.partials.cart-script')
@include('shop::components.layouts.storefront-chat-widgets')
</body>
</html>
