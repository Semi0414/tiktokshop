<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo e($__sfStoreLabel); ?> — <?php echo e($seller ? $seller->name : __('Browse')); ?></title>
    <link rel="icon" type="image/webp" href="<?php echo e(asset('storage/theme/1/favicon.webp')); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <?php echo $__env->make('shop::tik-store.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('shop::tik-store.partials.styles-mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="tik-store-page">
<?php
    $sellerParam = $seller ? ['seller' => $seller->id] : [];
    $f = $filters ?? [];
    /** Official / full-catalog TikStore (ignores seller session). */
    $globalTikStoreUrl = route('shop.tik-store.index', array_filter([
        'global' => '1',
        'ref' => request('ref'),
    ]));
?>

<header class="header">
    <div class="header-inner">
        <a href="<?php echo e($globalTikStoreUrl); ?>" class="logo-area" style="text-decoration:none;">
            <div class="logo-mark"><?php echo e($__sfLogoMark); ?></div>
            <div>
                <div class="logo-text-main"><?php echo e($__sfStoreLabel); ?></div>
                <div class="logo-text-sub"><?php echo e($seller ? $seller->name : __('Official Shop')); ?></div>
            </div>
        </a>

        <div class="search-wrapper">
            <form class="search-input" id="tikStoreSearchForm" action="<?php echo e(route('shop.tik-store.index', $linkParams ?? [])); ?>" method="get" autocomplete="off">
                <?php if($seller): ?>
                    <input type="hidden" name="seller" value="<?php echo e($seller->id); ?>" />
                <?php endif; ?>
                <?php $__currentLoopData = $linkParams ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fk => $fv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!in_array($fk, ['seller', 'q'], true)): ?>
                        <input type="hidden" name="<?php echo e($fk); ?>" value="<?php echo e($fv); ?>" />
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <span class="search-icon" aria-hidden="true">🔍</span>
                <input
                    id="tikStoreSellerSearch"
                    name="q"
                    type="search"
                    placeholder="<?php echo e(__('Search seller store by name…')); ?>"
                    minlength="2"
                />
            </form>
            <div class="seller-search-dropdown" id="sellerSearchDropdown" role="listbox"></div>
        </div>

        <?php echo $__env->make('shop::tik-store.partials.header-actions', [
            'globalTikStoreUrl' => $globalTikStoreUrl,
            'showBack' => false,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</header>

<?php if(!empty($heroSlides) && count($heroSlides) > 0): ?>
<div class="tik-hero-wrap">
    <div class="tik-hero" id="tikHero">
        <div class="tik-hero-viewport">
            <div class="tik-hero-track" id="tikHeroTrack">
                <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="tik-hero-slide">
                        <img src="<?php echo e($slide['src']); ?>" alt="<?php echo e($slide['alt']); ?>" loading="<?php echo e($loop->first ? 'eager' : 'lazy'); ?>" decoding="async" />
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <button type="button" class="tik-hero-nav tik-hero-prev" id="tikHeroPrev" aria-label="<?php echo e(__('Previous slide')); ?>">‹</button>
        <button type="button" class="tik-hero-nav tik-hero-next" id="tikHeroNext" aria-label="<?php echo e(__('Next slide')); ?>">›</button>
        <div class="tik-hero-dots" id="tikHeroDots" aria-hidden="true"></div>
    </div>
</div>
<?php endif; ?>

<main class="page">
    <div class="mobile-filter-backdrop" id="mobileFilterBackdrop" aria-hidden="true"></div>

    <aside class="sidebar sidebar-filters" id="tikStoreSidebar">
        <button type="button" class="sidebar-mobile-close" id="sidebarMobileClose" aria-label="<?php echo e(__('Close')); ?>">×</button>

        <form class="sidebar-card filter-form" method="get" action="<?php echo e(route('shop.tik-store.index', $linkParams ?? [])); ?>" id="tikStoreFilterForm">
            <?php if($seller): ?>
                <input type="hidden" name="seller" value="<?php echo e($seller->id); ?>" />
            <?php endif; ?>
            <?php if(request('global') === '1'): ?>
                <input type="hidden" name="global" value="1" />
            <?php endif; ?>
            <?php if(request()->filled('ref')): ?>
                <input type="hidden" name="ref" value="<?php echo e(request('ref')); ?>" />
            <?php endif; ?>
            <?php if($selectedCategoryId): ?>
                <input type="hidden" name="category" value="<?php echo e($selectedCategoryId); ?>" />
            <?php endif; ?>

            <div class="sidebar-title"><?php echo e(__('Filters')); ?></div>
            <div class="sidebar-subtitle"><?php echo e(__('Price, rating & delivery')); ?></div>

            <div class="filter-row">
                <label for="min_price"><?php echo e(__('Min price')); ?></label>
                <input type="number" step="0.01" min="0" name="min_price" id="min_price" value="<?php echo e($f['min_price'] ?? ''); ?>" placeholder="0" />
            </div>
            <div class="filter-row">
                <label for="max_price"><?php echo e(__('Max price')); ?></label>
                <input type="number" step="0.01" min="0" name="max_price" id="max_price" value="<?php echo e($f['max_price'] ?? ''); ?>" placeholder="—" />
            </div>

            <div class="filter-row">
                <label for="min_rating"><?php echo e(__('Minimum rating')); ?></label>
                <select name="min_rating" id="min_rating">
                    <option value=""><?php echo e(__('Any')); ?></option>
                    <option value="3" <?php if(($f['min_rating'] ?? '') == '3'): echo 'selected'; endif; ?>>3+ ★</option>
                    <option value="3.5" <?php if(($f['min_rating'] ?? '') == '3.5'): echo 'selected'; endif; ?>>3.5+ ★</option>
                    <option value="4" <?php if(($f['min_rating'] ?? '') == '4'): echo 'selected'; endif; ?>>4+ ★</option>
                    <option value="4.5" <?php if(($f['min_rating'] ?? '') == '4.5'): echo 'selected'; endif; ?>>4.5+ ★</option>
                </select>
            </div>

            <label class="filter-check">
                <input type="checkbox" name="free_shipping" value="1" <?php if(!empty($f['free_shipping'])): echo 'checked'; endif; ?> />
                <?php echo e(__('Free shipping')); ?>

            </label>
            <label class="filter-check">
                <input type="checkbox" name="dispatch_24h" value="1" <?php if(!empty($f['dispatch_24h'])): echo 'checked'; endif; ?> />
                <?php echo e(__('24h dispatch')); ?>

            </label>

            <div class="filter-row" style="margin-top:0.5rem;">
                <label for="sort"><?php echo e(__('Sort by')); ?></label>
                <select name="sort" id="sort">
                    <option value="newest" <?php if(($f['sort'] ?? 'newest') === 'newest'): echo 'selected'; endif; ?>><?php echo e(__('Newest')); ?></option>
                    <option value="price_asc" <?php if(($f['sort'] ?? '') === 'price_asc'): echo 'selected'; endif; ?>><?php echo e(__('Price: Low to High')); ?></option>
                    <option value="price_desc" <?php if(($f['sort'] ?? '') === 'price_desc'): echo 'selected'; endif; ?>><?php echo e(__('Price: High to Low')); ?></option>
                    <option value="top_rated" <?php if(($f['sort'] ?? '') === 'top_rated'): echo 'selected'; endif; ?>><?php echo e(__('Top rated')); ?></option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="filter-btn"><?php echo e(__('Apply')); ?></button>
                <a href="<?php echo e(route('shop.tik-store.index', array_filter(['seller' => $seller?->id, 'global' => request('global') === '1' ? '1' : null, 'ref' => request('ref')]))); ?>" class="filter-btn filter-btn-secondary" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;"><?php echo e(__('Reset')); ?></a>
            </div>
        </form>

        <section class="sidebar-card">
            <div class="sidebar-title"><?php echo e(__('Categories')); ?></div>
            <div class="sidebar-subtitle"><?php echo e(__('Browse by name')); ?></div>
            <ul class="category-nav-list">
                <li>
                    <a href="<?php echo e(route('shop.tik-store.index', $linkParams ?? [])); ?>" class="<?php echo e(!$selectedCategoryId ? 'active' : ''); ?>"><?php echo e(__('All categories')); ?></a>
                </li>
                <?php $__currentLoopData = $carouselCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a
                            href="<?php echo e(route('shop.tik-store.index', array_merge($linkParams ?? [], ['category' => $cat->id]))); ?>"
                            class="<?php echo e((int) $selectedCategoryId === (int) $cat->id ? 'active' : ''); ?>"
                        ><?php echo e($cat->name); ?></a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </section>
    </aside>

    <section class="content">
        <div class="breadcrumb-row">
            <div class="breadcrumb">
                <a href="<?php echo e(route('shop.landing.index')); ?>"><?php echo e(__('Home')); ?></a>
                <span>›</span>
                <a href="<?php echo e($globalTikStoreUrl); ?>"><?php echo e($__sfStoreLabel); ?></a>
                <?php if($seller): ?>
                    <span>›</span>
                    <a href="<?php echo e($globalTikStoreUrl); ?>" class="breadcrumb-to-global" style="font-weight:600;text-decoration:none;color:inherit;"><?php echo e($seller->name); ?></a>
                <?php endif; ?>
            </div>
            <?php if($seller): ?>
                <a href="<?php echo e($globalTikStoreUrl); ?>" class="tik-store-id-link" style="text-decoration:none;color:inherit;">
                    <strong><?php echo e(__('Store ID')); ?>:</strong> <?php echo e($seller->id); ?>

                </a>
            <?php endif; ?>
        </div>

        <button type="button" class="mobile-filter-toggle" id="mobileFilterToggle" aria-expanded="false" aria-controls="tikStoreSidebar">
            <span class="mobile-filter-toggle__icon" aria-hidden="true">☰</span>
            <span><?php echo e(__('Filters & categories')); ?></span>
        </button>

        <div class="category-carousel-wrap">
            <div class="category-carousel-title"><?php echo e(__('Shop by category')); ?></div>
            <div class="category-carousel" role="navigation" aria-label="<?php echo e(__('Categories')); ?>">
                <a href="<?php echo e(route('shop.tik-store.index', $linkParams ?? [])); ?>" class="cat-card <?php echo e(!$selectedCategoryId ? 'active' : ''); ?>" style="text-decoration:none;color:inherit;">
                    <div class="cat-card-img" style="display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;"><?php echo e(__('All')); ?></div>
                    <div class="cat-card-name"><?php echo e(__('All products')); ?></div>
                </a>
                <?php $__currentLoopData = $carouselCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a
                        href="<?php echo e(route('shop.tik-store.index', array_merge($linkParams ?? [], ['category' => $cat->id]))); ?>"
                        class="cat-card <?php echo e((int) $selectedCategoryId === (int) $cat->id ? 'active' : ''); ?>"
                        style="text-decoration:none;color:inherit;"
                    >
                        <?php if($cat->logo_url): ?>
                            <img class="cat-card-img" src="<?php echo e($cat->logo_url); ?>" alt="" loading="lazy" />
                        <?php else: ?>
                            <div class="cat-card-img" style="display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:1.2rem;">◆</div>
                        <?php endif; ?>
                        <div class="cat-card-name"><?php echo e($cat->name); ?></div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <section class="section-toolbar">
            <div>
                <h2><?php echo e(__('Recommended')); ?></h2>
                <p class="muted"><?php echo e(__('First picks')); ?> · <?php echo e($seller ? __('This store') : __('Catalog')); ?></p>
            </div>
            <a href="<?php echo e(route('shop.tik-store.recommended', $linkParams ?? [])); ?>" class="view-all-btn"><?php echo e(__('View all recommended')); ?> →</a>
        </section>
        <div class="horizontal-scroll-grid">
            <?php $__empty_1 = true; $__currentLoopData = $recommendedPreview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('shop::tik-store.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted" style="padding:0.5rem;"><?php echo e(__('No recommended products yet.')); ?></p>
            <?php endif; ?>
        </div>

        <section class="section-toolbar" style="margin-top:0.5rem;">
            <div>
                <h2><?php echo e(__('Newest')); ?></h2>
                <p class="muted"><?php echo e(__('Latest catalog')); ?></p>
            </div>
            <a href="<?php echo e(route('shop.tik-store.products', $linkParams ?? [])); ?>" class="view-all-btn"><?php echo e(__('View all products')); ?> →</a>
        </section>
        <div class="horizontal-scroll-grid">
            <?php $__empty_1 = true; $__currentLoopData = $latestPreview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('shop::tik-store.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted" style="padding:0.5rem;"><?php echo e(__('No products yet.')); ?></p>
            <?php endif; ?>
        </div>

        <section class="toolbar-card">
            <div>
                <div class="toolbar-title"><?php echo e(__('All products')); ?></div>
                <div class="toolbar-count">
                     <?php echo e(__('All products catalog')); ?>

                    <?php if($selectedCategoryId): ?>
                        · <?php echo e(__('filtered by category')); ?>

                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="grid">
            <?php $__empty_1 = true; $__currentLoopData = $gridProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('shop::tik-store.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted"><?php echo e(__('No products match these filters.')); ?></p>
            <?php endif; ?>
        </section>

        <?php echo $__env->make('shop::tik-store.partials.pagination', ['paginator' => $gridProducts], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
</main>

<div id="ts-ml-skin" class="tik-store-ml-footer-shell">
    <footer class="footer">
        <div class="footer-inner">
            <div><?php echo e(config('app.name')); ?> · <?php echo e($__sfStoreLabel); ?></div>
            <div>© <?php echo e(date('Y')); ?></div>
        </div>
    </footer>
</div>

<button type="button" class="back-to-top" id="backToTop" aria-label="<?php echo e(__('Back to top')); ?>">↑</button>

<script>
(function () {
    const sellersUrl = <?php echo json_encode($apiSellersSearchUrl, 15, 512) ?>;
    const tikStoreIndex = <?php echo json_encode(route('shop.tik-store.index'), 15, 512) ?>;
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
<?php echo $__env->make('shop::tik-store.partials.wishlist-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('shop::tik-store.partials.cart-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('shop::components.layouts.storefront-chat-widgets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/tik-store/index.blade.php ENDPATH**/ ?>