{{-- Tik Store — mobile-only UI (strict CSS, no backend). Loaded after base styles. --}}
<style id="tik-store-mobile-css">
@media screen and (max-width: 1024px) {
  body.tik-store-page {
    --tik-card-height: 302px;
    --tik-card-image-height: 128px;
    overflow-x: hidden !important;
    width: 100% !important;
    max-width: 100vw !important;
  }

  body.tik-store-page .header {
    position: sticky !important;
    top: 0 !important;
    z-index: 100 !important;
  }

  body.tik-store-page .header-inner {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 0.6rem 0.75rem !important;
    max-width: 100% !important;
  }

  body.tik-store-page .logo-area {
    flex: 1 1 auto !important;
    min-width: 0 !important;
    max-width: calc(100% - 120px) !important;
  }

  body.tik-store-page .logo-text-main {
    font-size: 0.95rem !important;
  }

  body.tik-store-page .logo-text-sub {
    font-size: 0.72rem !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    max-width: 55vw !important;
  }

  body.tik-store-page .header-actions {
    order: 2 !important;
    margin-left: auto !important;
    flex-shrink: 0 !important;
    gap: 0.35rem !important;
  }

  body.tik-store-page .search-wrapper {
    order: 3 !important;
    flex: 1 1 100% !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
  }

  body.tik-store-page .search-input {
    width: 100% !important;
    font-size: 16px !important;
    padding: 0.65rem 0.85rem !important;
  }

  body.tik-store-page .search-input input {
    font-size: 16px !important;
    min-height: 22px !important;
  }

  body.tik-store-page .icon-button {
    width: 42px !important;
    height: 42px !important;
    min-width: 42px !important;
    min-height: 42px !important;
  }

  body.tik-store-page .pill-button {
    font-size: 0.72rem !important;
    padding: 0.4rem 0.55rem !important;
    max-width: 6rem !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
  }

  body.tik-store-page .tik-hero-wrap {
    max-width: 100% !important;
    padding: 0 0.75rem !important;
    box-sizing: border-box !important;
  }

  body.tik-store-page .tik-hero-slide img {
    aspect-ratio: 16 / 9 !important;
    height: auto !important;
    max-height: 220px !important;
    object-fit: cover !important;
  }

  body.tik-store-page main.page {
    display: block !important;
    flex-direction: column !important;
    max-width: 100% !important;
    width: 100% !important;
    padding: 0.65rem 0.75rem 1.25rem !important;
    box-sizing: border-box !important;
  }

  body.tik-store-page main.page > aside.sidebar,
  body.tik-store-page main.page > aside.sidebar-filters {
    display: flex !important;
    flex-direction: column !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    bottom: 0 !important;
    width: min(300px, 88vw) !important;
    max-width: 88vw !important;
    z-index: 200 !important;
    margin: 0 !important;
    padding: calc(0.75rem + env(safe-area-inset-top, 0px)) 0.75rem calc(1rem + env(safe-area-inset-bottom, 0px)) !important;
    background: #f5f5f7 !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch !important;
    transform: translate3d(-110%, 0, 0) !important;
    transition: transform 0.28s ease !important;
    box-shadow: none !important;
  }

  body.tik-store-page.tik-store-filters-open main.page > aside.sidebar-filters {
    transform: translate3d(0, 0, 0) !important;
    box-shadow: 8px 0 40px rgba(0, 0, 0, 0.25) !important;
  }

  body.tik-store-page main.page > .content {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 0.65rem !important;
  }

  body.tik-store-page .mobile-filter-toggle {
    display: flex !important;
    width: 100% !important;
    box-sizing: border-box !important;
    min-height: 44px !important;
  }

  body.tik-store-page .mobile-filter-backdrop {
    display: none !important;
    position: fixed !important;
    inset: 0 !important;
    z-index: 150 !important;
    background: rgba(15, 23, 42, 0.55) !important;
  }

  body.tik-store-page.tik-store-filters-open .mobile-filter-backdrop {
    display: block !important;
  }

  body.tik-store-page .sidebar-mobile-close {
    display: block !important;
    flex-shrink: 0 !important;
  }

  body.tik-store-page .grid {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 0.55rem !important;
    width: 100% !important;
  }

  body.tik-store-page .horizontal-scroll-grid {
    display: flex !important;
    flex-wrap: nowrap !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch !important;
    scroll-snap-type: x mandatory !important;
    gap: 0.55rem !important;
    width: 100% !important;
    padding-bottom: 0.5rem !important;
  }

  /* All product cards (grid + Recommended + Newest) — same size on mobile */
  body.tik-store-page .product-card {
    display: flex !important;
    flex-direction: column !important;
    box-sizing: border-box !important;
    width: 100% !important;
    min-width: 0 !important;
    height: var(--tik-card-height) !important;
    min-height: var(--tik-card-height) !important;
    max-height: var(--tik-card-height) !important;
    overflow: hidden !important;
  }

  body.tik-store-page .horizontal-scroll-grid .product-card {
    flex: 0 0 46vw !important;
    width: 46vw !important;
    max-width: 180px !important;
    min-width: 140px !important;
    scroll-snap-align: start !important;
  }

  body.tik-store-page .grid > .product-card {
    width: 100% !important;
  }

  body.tik-store-page .product-image-wrap {
    flex-shrink: 0 !important;
  }

  body.tik-store-page .product-image {
    width: 100% !important;
    height: var(--tik-card-image-height) !important;
    min-height: var(--tik-card-image-height) !important;
    max-height: var(--tik-card-image-height) !important;
    object-fit: cover !important;
    flex-shrink: 0 !important;
  }

  body.tik-store-page .product-body {
    flex: 1 1 auto !important;
    display: flex !important;
    flex-direction: column !important;
    min-height: 0 !important;
    overflow: hidden !important;
    padding: 0.5rem 0.55rem !important;
  }

  body.tik-store-page .product-footer {
    margin-top: auto !important;
    padding-top: 0.45rem !important;
    align-items: flex-end !important;
    flex-shrink: 0 !important;
    width: 100% !important;
  }

  body.tik-store-page .ship-label {
    flex: 1 1 auto !important;
    min-width: 0 !important;
  }

  body.tik-store-page .add-btn {
    flex: 0 0 auto !important;
    white-space: nowrap !important;
  }

  body.tik-store-page .product-title {
    font-size: 0.78rem !important;
    line-height: 1.25 !important;
    -webkit-line-clamp: 2 !important;
  }

  body.tik-store-page .product-tags {
    max-height: 2.4em !important;
    overflow: hidden !important;
  }

  body.tik-store-page .grid {
    align-items: stretch !important;
    grid-auto-rows: var(--tik-card-height) !important;
  }

  body.tik-store-page .price {
    font-size: 0.85rem !important;
  }

  body.tik-store-page .price-original,
  body.tik-store-page .price-discount {
    font-size: 0.68rem !important;
  }

  body.tik-store-page .product-meta-row,
  body.tik-store-page .sold-count,
  body.tik-store-page .product-footer {
    font-size: 0.7rem !important;
  }

  body.tik-store-page .add-btn {
    min-height: 34px !important;
    padding: 0.35rem 0.65rem !important;
    font-size: 0.75rem !important;
  }

  body.tik-store-page .horizontal-scroll-grid {
    align-items: stretch !important;
  }

  body.tik-store-page .wishlist-btn {
    width: 32px !important;
    height: 32px !important;
  }

  body.tik-store-page .category-carousel-wrap,
  body.tik-store-page .section-toolbar,
  body.tik-store-page .toolbar-card {
    padding: 0.6rem 0.7rem !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  body.tik-store-page .section-toolbar {
    flex-direction: column !important;
    align-items: flex-start !important;
  }

  body.tik-store-page .view-all-btn {
    align-self: flex-end !important;
    min-height: 40px !important;
    padding: 0.4rem 0.75rem !important;
  }

  body.tik-store-page .cat-card {
    width: 88px !important;
    flex: 0 0 88px !important;
  }

  body.tik-store-page .cat-card-img {
    height: 56px !important;
  }

  body.tik-store-page .cat-card-name {
    font-size: 0.65rem !important;
    padding: 0.3rem !important;
  }

  body.tik-store-page .breadcrumb-row {
    flex-direction: column !important;
    align-items: flex-start !important;
    font-size: 0.72rem !important;
  }

  body.tik-store-page .breadcrumb {
    flex-wrap: wrap !important;
  }

  body.tik-store-page .back-to-top {
    right: max(0.75rem, env(safe-area-inset-right, 0px)) !important;
    bottom: max(1rem, env(safe-area-inset-bottom, 0px)) !important;
    width: 44px !important;
    height: 44px !important;
  }

  body.tik-store-page #ts-ml-skin .footer,
  body.tik-store-page .footer {
    padding: 0.75rem !important;
    padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px)) !important;
  }

  body.tik-store-page .footer-inner {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 0.35rem !important;
    font-size: 0.75rem !important;
  }

  body.tik-store-page .pagination {
    flex-wrap: wrap !important;
    justify-content: center !important;
  }

  body.tik-store-page .page-pill {
    min-width: 36px !important;
    height: 36px !important;
  }

  body.tik-store-page main.page.page--list {
    padding: 0.65rem 0.75rem 1.25rem !important;
  }
}

@media screen and (max-width: 480px) {
  body.tik-store-page {
    --tik-card-height: 288px;
    --tik-card-image-height: 115px;
  }

  body.tik-store-page .header-actions .pill-button {
    display: none !important;
  }

  body.tik-store-page .horizontal-scroll-grid .product-card {
    flex: 0 0 44vw !important;
    width: 44vw !important;
  }

  body.tik-store-page .grid {
    gap: 0.45rem !important;
    grid-auto-rows: var(--tik-card-height) !important;
  }
}
</style>
