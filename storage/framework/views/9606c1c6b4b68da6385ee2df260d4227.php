<style>
    :root {
      --bg-main: #f5f5f7;
      --bg-card: #ffffff;
      --text-main: #111111;
      --text-muted: #666666;
      --border-subtle: #e3e3e7;
      --primary: #fe2c55;
      --primary-soft: rgba(254, 44, 85, 0.08);
      --accent: #00f2ea;
      --radius-sm: 6px;
      --radius-md: 10px;
      --shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.06);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body.tik-store-page {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: var(--bg-main);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .tik-store-page a { text-decoration: none; color: inherit; }
    .tik-store-page img { max-width: 100%; display: block; }
    .header {
      position: sticky; top: 0; z-index: 20;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid #eee;
    }
    .header-inner {
      max-width: 1240px; margin: 0 auto; padding: 0.85rem 1.25rem;
      display: flex; align-items: center; gap: 1rem;
    }
    .logo-area { display: flex; align-items: center; gap: 0.55rem; white-space: nowrap; cursor: pointer; }
    .logo-mark {
      width: 30px; height: 30px; border-radius: 0.85rem;
      background: radial-gradient(circle at 0 0, var(--accent), transparent 50%),
        radial-gradient(circle at 100% 100%, var(--primary), transparent 55%), #000;
      display: flex; align-items: center; justify-content: center; color: #fff;
      font-weight: 700; font-size: 0.9rem;
    }
    .logo-text-main { font-weight: 700; font-size: 1.05rem; }
    .logo-text-sub { font-size: 0.8rem; color: var(--text-muted); }
    .search-wrapper { flex: 1; max-width: 520px; margin: 0 auto; position: relative; }
    .search-input {
      width: 100%; display: flex; align-items: center; gap: 0.6rem;
      padding: 0.55rem 0.75rem; background: var(--bg-main); border-radius: 999px;
      border: 1px solid transparent; transition: border-color 0.18s ease, box-shadow 0.18s ease;
      font-size: 0.9rem; color: var(--text-main);
    }
    .search-input:focus-within {
      border-color: rgba(254, 44, 85, 0.55);
      box-shadow: 0 0 0 1px rgba(254, 44, 85, 0.25);
      background: #ffffff;
    }
    .search-input input { border: none; outline: none; flex: 1; background: transparent; font-size: 0.9rem; }
    .search-icon { font-size: 1rem; color: var(--text-muted); }
    .seller-search-dropdown {
      position: absolute; left: 0; right: 0; top: calc(100% + 6px);
      background: #fff; border-radius: var(--radius-md); box-shadow: var(--shadow-soft);
      border: 1px solid rgba(148, 163, 184, 0.25); max-height: 280px; overflow-y: auto; z-index: 50;
      display: none;
    }
    .seller-search-dropdown.open { display: block; }
    .seller-search-item {
      display: flex; align-items: center; gap: 0.6rem; padding: 0.55rem 0.75rem;
      cursor: pointer; font-size: 0.85rem; border-bottom: 1px solid #f3f4f6;
    }
    .seller-search-item:hover { background: var(--primary-soft); color: var(--primary); }
    .seller-search-item img { width: 32px; height: 32px; border-radius: 999px; object-fit: cover; background: #eee; }
    .header-actions { display: flex; align-items: center; gap: 0.75rem; margin-left: auto; }
    .tik-account-menu { position: relative; list-style: none; }
    .tik-account-menu > summary { list-style: none; }
    .tik-account-menu > summary::-webkit-details-marker { display: none; }
    .tik-account-summary { cursor: pointer; }
    .tik-account-dropdown {
        position: absolute; right: 0; top: calc(100% + 8px); min-width: 220px;
        background: #fff; border-radius: var(--radius-md); box-shadow: var(--shadow-soft);
        border: 1px solid rgba(148, 163, 184, 0.35); padding: 0.65rem 0; z-index: 100;
        display: flex; flex-direction: column; gap: 0;
    }
    .tik-account-menu:not([open]) .tik-account-dropdown { display: none; }
    .tik-account-welcome { padding: 0.5rem 1rem 0.35rem; font-size: 0.88rem; }
    .tik-account-welcome strong { display: block; font-weight: 600; color: #111; }
    .tik-account-sub { font-size: 0.78rem; color: var(--text-muted); display: block; margin-top: 0.2rem; }
    .tik-account-divider { height: 1px; background: #e5e7eb; margin: 0.35rem 0; }
    .tik-account-dropdown a {
        padding: 0.5rem 1rem; font-size: 0.88rem; color: #be123c; text-decoration: none;
    }
    .tik-account-dropdown a:hover { background: var(--primary-soft); }
    .tik-account-dropdown a.tik-account-primary { font-weight: 600; color: var(--primary); }
    .icon-button {
      position: relative; width: 34px; height: 34px; border-radius: 999px;
      border: 1px solid var(--border-subtle); background: #ffffff;
      display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
      cursor: pointer; transition: background 0.15s ease, transform 0.12s ease;
    }
    .icon-button:hover {
      background: #fdf2f5; transform: translateY(-1px);
      border-color: rgba(254, 44, 85, 0.6); box-shadow: var(--shadow-soft);
    }
    .pill-button {
      display: inline-flex; align-items: center; gap: 0.4rem;
      padding: 0.4rem 0.9rem; border-radius: 999px; border: 1px solid var(--border-subtle);
      background: #ffffff; font-size: 0.8rem; cursor: pointer; white-space: nowrap;
    }
    .pill-button:hover {
      border-color: rgba(254, 44, 85, 0.7); background: var(--primary-soft); color: var(--primary);
    }
    .page { max-width: 1240px; width: 100%; margin: 0 auto; padding: 1.25rem 1.25rem 1.75rem; display: flex; flex: 1; gap: 1.25rem; flex-direction: row; align-items: flex-start; }
    .page--list { display: block; flex: 1; }
    .sidebar { width: 260px; flex-shrink: 0; display: flex; flex-direction: column; gap: 0.9rem; }
    .sidebar-filters { position: sticky; top: 72px; }
    .sidebar-card {
      background: var(--bg-card); border-radius: var(--radius-md); padding: 0.9rem 0.95rem;
      box-shadow: var(--shadow-soft); border: 1px solid rgba(148, 163, 184, 0.15);
    }
    .sidebar-title { font-size: 0.9rem; font-weight: 600; }
    .sidebar-subtitle { font-size: 0.8rem; color: var(--text-muted); }
    .sidebar-chip-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.4rem; }
    .chip {
      font-size: 0.76rem; padding: 0.2rem 0.55rem; border-radius: 999px; background: #f3f4f6;
      color: #4b5563; cursor: pointer; border: 1px solid transparent;
    }
    .chip:hover { background: var(--primary-soft); color: var(--primary); border-color: rgba(254, 44, 85, 0.6); }
    .chip.chip-active { background: #000; color: #fff; border-color: #000; }
    .content { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 0.85rem; }
    .breadcrumb-row {
      display: flex; align-items: center; justify-content: space-between;
      font-size: 0.8rem; color: var(--text-muted); flex-wrap: wrap; gap: 0.5rem;
    }
    .breadcrumb { display: flex; align-items: center; gap: 0.25rem; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb a.breadcrumb-to-global:hover { color: var(--primary); }
    .tik-store-id-link { cursor: pointer; }
    .tik-store-id-link:hover { color: var(--primary); }
    .category-carousel-wrap {
      background: var(--bg-card); border-radius: var(--radius-md); padding: 0.75rem 0.9rem;
      box-shadow: var(--shadow-soft); border: 1px solid rgba(148, 163, 184, 0.15);
    }
    .category-carousel-title { font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; }
    .category-carousel {
      display: flex; gap: 0.65rem; overflow-x: auto; padding-bottom: 0.25rem;
      scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
    }
    .category-carousel::-webkit-scrollbar { height: 6px; }
    .category-carousel::-webkit-scrollbar-thumb { background: #ddd; border-radius: 999px; }
    .cat-card {
      flex: 0 0 auto; scroll-snap-align: start; width: 120px;
      background: #f9fafb; border-radius: var(--radius-md); overflow: hidden;
      border: 1px solid rgba(148, 163, 184, 0.2); cursor: pointer; transition: transform 0.12s ease;
    }
    .cat-card:hover { transform: translateY(-2px); border-color: rgba(254, 44, 85, 0.45); }
    .cat-card.active { border-color: #000; box-shadow: 0 0 0 1px #000; }
    .cat-card-img { height: 72px; background: linear-gradient(135deg, #0f172a, #334155); object-fit: cover; width: 100%; }
    .cat-card-name { font-size: 0.72rem; padding: 0.4rem 0.45rem; text-align: center; line-height: 1.25; max-height: 2.6em; overflow: hidden; }
    .section-toolbar {
      background: var(--bg-card); border-radius: var(--radius-md); box-shadow: var(--shadow-soft);
      border: 1px solid rgba(148, 163, 184, 0.15); padding: 0.75rem 0.9rem;
      display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.6rem;
    }
    .section-toolbar h2 { font-size: 0.9rem; font-weight: 600; }
    .section-toolbar .muted { font-size: 0.8rem; color: var(--text-muted); }
    .view-all-btn {
      font-size: 0.8rem; font-weight: 600; color: var(--primary); cursor: pointer;
      background: none; border: none; padding: 0.25rem 0.5rem;
    }
    .view-all-btn:hover { text-decoration: underline; }
    .toolbar-card {
      background: var(--bg-card); border-radius: var(--radius-md); box-shadow: var(--shadow-soft);
      border: 1px solid rgba(148, 163, 184, 0.15); padding: 0.75rem 0.9rem;
      display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.6rem;
    }
    .toolbar-title { font-size: 0.9rem; font-weight: 600; }
    .toolbar-count { font-size: 0.8rem; color: var(--text-muted); }
    .grid {
      display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0.85rem; margin-top: 0.1rem;
      align-items: stretch;
    }
    .grid > .product-card,
    .grid > p.muted { align-self: start; }
    @media (max-width: 1080px) { .grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 880px) { .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .tik-hero-wrap {
      max-width: 1240px; width: 100%; margin: 0 auto; padding: 0 1.25rem;
    }
    .tik-hero {
      position: relative; border-radius: var(--radius-md); overflow: hidden;
      box-shadow: var(--shadow-soft); border: 1px solid rgba(148, 163, 184, 0.15);
      background: #0f172a;
    }
    .tik-hero-viewport { overflow: hidden; width: 100%; }
    .tik-hero-track {
      display: flex; transition: transform 0.65s ease-out; will-change: transform;
    }
    .tik-hero-slide {
      flex: 0 0 100%; min-width: 100%;
    }
    .tik-hero-slide img {
      width: 100%; height: auto; aspect-ratio: 2.743 / 1; object-fit: cover; display: block;
    }
    .tik-hero-nav {
      position: absolute; top: 50%; transform: translateY(-50%);
      width: 36px; height: 36px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.35);
      background: rgba(15, 23, 42, 0.45); color: #fff; cursor: pointer; font-size: 1.25rem;
      display: flex; align-items: center; justify-content: center; z-index: 3;
    }
    .tik-hero-nav:hover { background: rgba(254, 44, 85, 0.85); border-color: transparent; }
    .tik-hero-prev { left: 0.65rem; }
    .tik-hero-next { right: 0.65rem; }
    .tik-hero-dots {
      position: absolute; bottom: 0.65rem; left: 50%; transform: translateX(-50%);
      display: flex; gap: 0.35rem; z-index: 3;
    }
    .tik-hero-dot {
      width: 7px; height: 7px; border-radius: 999px; background: rgba(255,255,255,0.45); border: none; padding: 0; cursor: pointer;
    }
    .tik-hero-dot.active { background: #fff; width: 18px; }
    .filter-form label { font-size: 0.78rem; color: var(--text-muted); display: block; margin-bottom: 0.2rem; }
    .filter-form input[type="number"], .filter-form select {
      width: 100%; padding: 0.35rem 0.45rem; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle); font-size: 0.8rem;
    }
    .filter-form .filter-row { margin-bottom: 0.65rem; }
    .filter-form .filter-check { display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; margin-top: 0.35rem; cursor: pointer; }
    .filter-form .filter-actions { display: flex; gap: 0.4rem; margin-top: 0.75rem; flex-wrap: wrap; }
    .filter-btn {
      flex: 1; min-width: 100px; padding: 0.45rem 0.65rem; border-radius: 999px; border: none; font-size: 0.8rem; font-weight: 600; cursor: pointer;
      background: #000; color: #fff;
    }
    .filter-btn:hover { background: var(--primary); }
    .filter-btn-secondary {
      background: #fff; color: var(--text-main); border: 1px solid var(--border-subtle);
    }
    .category-nav-list { list-style: none; margin-top: 0.35rem; }
    .category-nav-list li { margin-bottom: 0.2rem; }
    .category-nav-list a {
      display: block; font-size: 0.82rem; padding: 0.28rem 0; color: var(--text-muted);
      border-radius: 4px;
    }
    .category-nav-list a:hover { color: var(--primary); }
    .category-nav-list a.active { color: var(--text-main); font-weight: 600; }
    .product-card {
      position: relative; background: var(--bg-card); border-radius: var(--radius-md); overflow: hidden;
      box-shadow: var(--shadow-soft); border: 1px solid rgba(148, 163, 184, 0.15);
      display: flex; flex-direction: column; height: 100%; min-height: 0;
      cursor: default; transition: transform 0.12s ease, box-shadow 0.15s ease;
    }
    .product-card:hover {
      transform: translateY(-4px); box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
      border-color: rgba(254, 44, 85, 0.65);
    }
    .product-title-link { color: inherit; text-decoration: none; }
    .product-title-link:hover .product-title { color: var(--primary); }
    .product-badge {
      position: absolute; top: 0.55rem; left: 0.55rem; font-size: 0.7rem; padding: 0.18rem 0.5rem; border-radius: 999px;
      background: rgba(15, 23, 42, 0.82); color: #f9fafb; display: inline-flex; align-items: center; gap: 0.25rem;
      backdrop-filter: blur(6px); z-index: 2;
    }
    .product-badge-sale { background: rgba(254, 44, 85, 0.92); }
    .badge-dot { width: 6px; height: 6px; border-radius: 999px; background: #facc15; }
    .wishlist-btn {
      position: absolute; top: 0.55rem; right: 0.55rem; width: 26px; height: 26px; border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.6); background: rgba(15, 23, 42, 0.8);
      display: flex; align-items: center; justify-content: center; color: #e5e7eb; font-size: 0.82rem;
      backdrop-filter: blur(6px); z-index: 2; cursor: pointer;
    }
    .wishlist-btn:hover { background: #fdf2f5; color: var(--primary); }
    .wishlist-btn.is-active {
      color: #fe2c55;
      border-color: rgba(254, 44, 85, 0.85);
      background: rgba(255, 255, 255, 0.95);
      font-size: 0.78rem;
    }
    .wishlist-btn:disabled { opacity: 0.65; cursor: wait; }
    .product-meta-row {
      display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: var(--text-muted);
    }
    .rating { display: inline-flex; align-items: center; gap: 0.15rem; }
    .rating .star-icon { color: #f59e0b; font-size: 0.85rem; }
    .review-muted { color: #9ca3af; }
    .sold-count { font-size: 0.78rem; color: var(--text-muted); }
    .product-tags { display: flex; flex-wrap: wrap; gap: 0.25rem; margin-top: 0.25rem; }
    .product-tag {
      font-size: 0.72rem; padding: 0.12rem 0.45rem; border-radius: 999px; background: #f3f4f6; color: #4b5563;
    }
    .product-footer {
      display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;
      margin-top: auto; padding-top: 0.45rem; flex-shrink: 0;
      font-size: 0.75rem; color: var(--text-muted); width: 100%;
    }
    .ship-label {
      display: inline-flex; align-items: center; gap: 0.25rem;
      flex: 1 1 auto; min-width: 0; line-height: 1.25;
    }
    .ship-label span:first-child { width: 6px; height: 6px; border-radius: 999px; background: var(--accent); }
    .add-btn {
      border-radius: 999px; border: none; background: #000; color: #fff; padding: 0.2rem 0.7rem; font-size: 0.75rem; cursor: pointer;
      display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem; text-decoration: none; font-weight: 600;
      flex: 0 0 auto; white-space: nowrap; min-height: 30px;
    }
    .add-btn:hover { background: var(--primary); color: #fff; }
    .add-btn:disabled { opacity: 0.6; cursor: wait; }
    .product-image-wrap {
      position: relative; padding: 0.4rem;
      background: radial-gradient(circle at 0 0, var(--accent), transparent 55%),
        radial-gradient(circle at 100% 100%, var(--primary), transparent 55%), #0f172a;
    }
    .product-image-inner { border-radius: var(--radius-md); overflow: hidden; background: #020617; }
    .product-image { width: 100%; height: 170px; object-fit: cover; transition: transform 0.3s ease; }
    .product-card:hover .product-image { transform: scale(1.04); }
    .product-body {
      padding: 0.65rem 0.7rem 0.6rem; display: flex; flex-direction: column; gap: 0.25rem;
      flex: 1 1 auto; min-height: 0;
    }
    .product-title {
      font-size: 0.85rem; font-weight: 500; line-height: 1.3; max-height: 2.2em;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .price-row { display: flex; align-items: baseline; gap: 0.3rem; margin-top: 0.15rem; flex-wrap: wrap; }
    .price { font-weight: 700; font-size: 0.95rem; }
    .price-original { font-size: 0.75rem; color: #9ca3af; text-decoration: line-through; }
    .price-discount { font-size: 0.75rem; color: var(--primary); font-weight: 600; }
    .footer {
      border-top: 1px solid #e5e7eb; padding: 0.8rem 1.25rem 1rem; font-size: 0.78rem;
      color: var(--text-muted); background: #ffffff; margin-top: auto;
    }
    .footer-inner {
      max-width: 1240px; margin: 0 auto; display: flex; align-items: center;
      justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;
    }
    .pagination { display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin-top: 0.85rem; font-size: 0.8rem; flex-wrap: wrap; }
    .page-pill {
      min-width: 28px; height: 28px; padding: 0 0.5rem; border-radius: 999px;
      border: 1px solid var(--border-subtle); background: #ffffff;
      display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
    }
    .page-pill:hover { border-color: rgba(254, 44, 85, 0.6); color: var(--primary); }
    .page-pill.active { background: #000; border-color: #000; color: #ffffff; }
    .page-pill.muted { opacity: 0.45; cursor: default; }
    .back-to-top {
      position: fixed; right: 1.3rem; bottom: 1.4rem; width: 38px; height: 38px; border-radius: 999px;
      background: #000; color: #ffffff; display: flex; align-items: center; justify-content: center;
      font-size: 1rem; cursor: pointer; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
      opacity: 0; pointer-events: none; transform: translateY(10px); transition: opacity 0.15s ease, transform 0.15s ease; z-index: 40; border: none;
    }
    .back-to-top.visible { opacity: 1; pointer-events: auto; transform: translateY(0); }
    .horizontal-scroll-grid {
      display: flex; gap: 0.85rem; overflow-x: auto; padding-bottom: 0.35rem;
      scroll-snap-type: x mandatory; align-items: stretch;
    }
    /* Recommended / Newest rows — equal card height so + Add sits on the same baseline */
    .horizontal-scroll-grid .product-card {
      flex: 0 0 220px; scroll-snap-align: start; max-width: 240px; width: 220px;
      display: flex; flex-direction: column; height: 336px; min-height: 336px; max-height: 336px;
      box-sizing: border-box;
    }
    .horizontal-scroll-grid .product-image-wrap { flex-shrink: 0; }
    .horizontal-scroll-grid .product-image { height: 150px; flex-shrink: 0; }
    .horizontal-scroll-grid .product-body {
      flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column; overflow: hidden;
    }
    .horizontal-scroll-grid .product-footer {
      margin-top: auto; flex-shrink: 0; padding-top: 0.4rem;
    }
    .mobile-filter-toggle {
      display: none;
      width: 100%;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.65rem 1rem;
      border-radius: var(--radius-md);
      border: 1px solid var(--border-subtle);
      background: var(--bg-card);
      font-size: 0.88rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: var(--shadow-soft);
      -webkit-tap-highlight-color: transparent;
    }
    .mobile-filter-toggle__icon { font-size: 1.1rem; line-height: 1; }
    .sidebar-mobile-close {
      display: none;
      position: sticky;
      top: 0;
      z-index: 2;
      width: 100%;
      margin-bottom: 0.5rem;
      padding: 0.5rem;
      border: none;
      border-radius: var(--radius-sm);
      background: #000;
      color: #fff;
      font-size: 1.25rem;
      line-height: 1;
      cursor: pointer;
      min-height: 44px;
    }
    .mobile-filter-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 45;
      background: rgba(15, 23, 42, 0.5);
      backdrop-filter: blur(2px);
    }
    body.tik-store-filters-open .mobile-filter-backdrop { display: block; }

    @media (max-width: 900px) {
      .page {
        flex-direction: column;
        padding: 0.85rem 0.85rem 1.25rem;
        gap: 0.75rem;
      }
      .content { gap: 0.65rem; }
      .sidebar {
        width: min(320px, 88vw);
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 50;
        padding: calc(0.75rem + env(safe-area-inset-top, 0px)) 0.75rem calc(0.75rem + env(safe-area-inset-bottom, 0px));
        background: var(--bg-main);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        transform: translateX(-105%);
        transition: transform 0.28s ease;
        box-shadow: 8px 0 32px rgba(15, 23, 42, 0.18);
      }
      body.tik-store-filters-open .sidebar-filters { transform: translateX(0); }
      .sidebar-filters { position: fixed; top: 0; }
      .mobile-filter-toggle { display: flex; }
      .sidebar-mobile-close { display: block; }
      .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.65rem; }
      .horizontal-scroll-grid { gap: 0.65rem; margin: 0 -0.15rem; padding-left: 0.15rem; padding-right: 0.15rem; }
      .horizontal-scroll-grid .product-card {
        flex: 0 0 min(168px, 46vw); width: min(168px, 46vw); max-width: min(200px, 48vw);
        height: 300px; min-height: 300px; max-height: 300px;
      }
      .horizontal-scroll-grid .product-image { height: 130px; }
      .product-image { height: 140px; }
      .tik-hero-wrap { padding: 0 0.85rem; }
      .tik-hero-slide img { aspect-ratio: 16 / 9; }
      .tik-hero-nav { width: 40px; height: 40px; font-size: 1.35rem; }
      .section-toolbar, .toolbar-card, .category-carousel-wrap { padding: 0.65rem 0.75rem; }
      .view-all-btn { padding: 0.35rem 0.65rem; min-height: 36px; }
      .breadcrumb-row { font-size: 0.75rem; }
      .header-inner { padding: 0.65rem 0.85rem; }
    }

    @media (max-width: 640px) {
      .header-inner {
        flex-wrap: wrap;
        row-gap: 0.45rem;
        padding: 0.55rem 0.75rem;
        gap: 0.5rem;
      }
      .logo-area { flex: 1 1 auto; min-width: 0; }
      .logo-text-main { font-size: 0.95rem; }
      .logo-text-sub {
        font-size: 0.72rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 42vw;
      }
      .search-wrapper { order: 3; flex-basis: 100%; max-width: none; margin: 0; }
      .header-actions { order: 2; gap: 0.4rem; margin-left: 0; }
      .header-actions .pill-button {
        padding: 0.35rem 0.55rem;
        font-size: 0.72rem;
        max-width: 5.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .icon-button { width: 40px; height: 40px; font-size: 1rem; }
      .search-input { padding: 0.6rem 0.8rem; font-size: 16px; }
      .search-input input { font-size: 16px; }
      .page { padding: 0.65rem 0.65rem 1rem; }
      .grid { gap: 0.5rem; }
      .product-body { padding: 0.55rem 0.6rem 0.55rem; }
      .product-title { font-size: 0.8rem; }
      .price { font-size: 0.88rem; }
      .add-btn { padding: 0.35rem 0.75rem; min-height: 32px; font-size: 0.78rem; }
      .wishlist-btn { width: 32px; height: 32px; }
      .cat-card { width: 96px; }
      .cat-card-img { height: 60px; }
      .cat-card-name { font-size: 0.68rem; padding: 0.35rem 0.3rem; }
      .back-to-top {
        right: max(0.75rem, env(safe-area-inset-right, 0px));
        bottom: max(1rem, env(safe-area-inset-bottom, 0px));
        width: 44px;
        height: 44px;
      }
      .footer { padding: 0.75rem; padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px)); }
      .footer-inner { flex-direction: column; align-items: flex-start; gap: 0.35rem; }
      .pagination { gap: 0.25rem; }
      .page-pill { min-width: 36px; height: 36px; }
    }

    @media (max-width: 380px) {
      .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .horizontal-scroll-grid .product-card {
        flex: 0 0 44vw; width: 44vw;
        height: 288px; min-height: 288px; max-height: 288px;
      }
      .horizontal-scroll-grid .product-image { height: 115px; }
      .header-actions .pill-button { display: none; }
    }
    .list-page-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.75rem; }
    .back-link { font-size: 0.85rem; color: var(--primary); margin-bottom: 0.75rem; display: inline-block; }
    .muted { color: var(--text-muted); }

    /*
     * “ML” token shell — ONLY #ts-ml-skin descendants (footer block). Does not override .header / main.
     */
    #ts-ml-skin {
      --ts-ml-navy: #0A1628;
      --ts-ml-border: #E2E8F4;
      --ts-ml-surface: #F7F9FD;
    }
    #ts-ml-skin .footer {
      border-top: 0.5px solid var(--ts-ml-border);
      background: linear-gradient(180deg, #ffffff 0%, var(--ts-ml-surface) 100%);
      font-family: 'DM Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      color: #3d5080;
      margin-top: auto;
    }
    #ts-ml-skin .footer-inner { max-width: 1240px; margin: 0 auto; }
</style>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/tik-store/partials/styles.blade.php ENDPATH**/ ?>