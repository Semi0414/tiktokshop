<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php if($__sfLegacy): ?>TikTok Shop <?php else: ?> <?php echo e($__sfName); ?> — Marketplace <?php endif; ?></title>
  <link rel="icon" type="image/webp" href="<?php echo e(asset('storage/theme/1/favicon.webp')); ?>" />
  <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
  <link href="https://fonts.bunny.net/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --black: #0a0a0a;
      --cyan: #25f4ee;
      --red: #fe2c55;
      --white: #ffffff;
      --gray: #1a1a1a;
      --text-muted: #aaaaaa;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Manrope', sans-serif;
      background: #000000;
      color: var(--white);
      overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      background: rgba(10,10,10,0.85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1.25rem;
      font-weight: 800;
    }

    .logo-icon {
      position: relative;
      font-size: 1.5rem;
      font-weight: 900;
    }
    .logo-icon .tiktok-d { color: var(--cyan); position: absolute; top: 2px; left: -2px; }
    .logo-icon .tiktok-m { color: var(--red);  position: absolute; top: -2px; right: -2px; }
    .logo-icon .tiktok-c { color: var(--white); position: relative; z-index: 2; }

    .logo-text { font-size: 1.1rem; letter-spacing: -0.3px; }
    .logo-text span { color: var(--red); }

    .nav-right { display: flex; align-items: center; gap: 20px; }

    .lang-btn {
      display: flex; align-items: center; gap: 6px;
      color: var(--white); font-size: 0.85rem;
      background: none; border: none; cursor: pointer;
      opacity: 0.7;
    }

    .nav-cta {
      background: var(--red);
      color: var(--white);
      border: none; border-radius: 4px;
      padding: 9px 20px;
      font-size: 0.85rem; font-weight: 700;
      cursor: pointer;
      transition: opacity 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .nav-cta:hover { opacity: 0.85; }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 120px 60px 80px;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      left: -60px; top: 60px;
      width: 220px; height: 80vh;
      border: 6px solid var(--cyan);
      border-radius: 0 0 180px 180px;
      border-top: none;
      opacity: 0.9;
      animation: floatLeft 6s ease-in-out infinite;
    }
    .hero::after {
      content: '';
      position: absolute;
      right: -60px; top: 100px;
      width: 220px; height: 70vh;
      border: 6px solid var(--red);
      border-radius: 180px 180px 0 0;
      border-bottom: none;
      opacity: 0.9;
      animation: floatRight 7s ease-in-out infinite;
    }

    @keyframes floatLeft {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-18px); }
    }
    @keyframes floatRight {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(18px); }
    }

    .hero-content {
      flex: 1;
      max-width: 560px;
      padding-left: 200px;
      animation: fadeUp 0.9s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .hero-tag {
      color: var(--red);
      font-size: 1.25rem;
      font-weight: 800;
      margin-bottom: 16px;
      letter-spacing: -0.2px;
    }

    .hero-title {
      font-size: clamp(2.2rem, 4vw, 3.2rem);
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 24px;
      color: var(--white);
    }

    .hero-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      line-height: 1.7;
      max-width: 400px;
      margin-bottom: 36px;
    }

    .hero-btns {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      margin-bottom: 44px;
    }

    .btn-outline {
      padding: 11px 28px;
      border: 1.5px solid rgba(255,255,255,0.4);
      border-radius: 4px;
      background: none;
      color: var(--white);
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-outline:hover { border-color: var(--white); background: rgba(255,255,255,0.07); }

    .btn-solid {
      padding: 11px 28px;
      border: none; border-radius: 4px;
      background: var(--red);
      color: var(--white);
      font-size: 0.9rem; font-weight: 700;
      cursor: pointer;
      transition: opacity 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-solid:hover { opacity: 0.85; }

    .hero-social-proof {
      display: flex;
      align-items: center;
      gap: 14px;
      color: var(--text-muted);
      font-size: 0.85rem;
    }

    .avatars {
      display: flex;
    }
    .avatars img, .avatar-placeholder {
      width: 34px; height: 34px;
      border-radius: 50%;
      border: 2px solid var(--black);
      margin-left: -10px;
      object-fit: cover;
    }
    .avatars .avatar-placeholder {
      background: linear-gradient(135deg, var(--cyan), var(--red));
      display: flex; align-items: center; justify-content: center;
      font-size: 0.65rem; font-weight: 700; color: var(--white);
    }
    .avatars .avatar-placeholder:first-child { margin-left: 0; }

    /* ── HERO MEDIA — super-admin uploads; only this block: no fill / border / shadow ── */
    .hero-visual {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      animation: fadeUp 1.1s ease both;
      margin-bottom: 3cm;
    }
    .hero-media {
      position: relative;
      z-index: 2;
      width: min(320px, 88vw);
      aspect-ratio: 9 / 16;
      max-height: min(520px, 70vh);
      border-radius: 20px;
      overflow: hidden;
      background: transparent;
      box-shadow: none;
      border: none;
    }
    .hero-media--empty {
      min-height: 360px;
    }
    .hero-media-placeholder {
      position: absolute;
      inset: 0;
      background: transparent;
    }
    .ttk-lp-hero-slideshow {
      position: absolute;
      inset: 0;
      border-radius: 20px;
      overflow: hidden;
    }
    .ttk-lp-hero-slideshow .ttk-lp-hero-slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }
    .ttk-lp-hero-slideshow .ttk-lp-hero-slide.ttk-lp-hero-slide--active {
      opacity: 1;
      pointer-events: auto;
    }
    .ttk-lp-hero-slideshow .ttk-lp-hero-slide img,
    .ttk-lp-hero-slideshow .ttk-lp-hero-slide video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    /* ── FEATURES ── */
    .features {
      background: var(--white);
      padding: 80px 60px;
      color: #111;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 40px;
      max-width: 1100px;
      margin: 0 auto;
    }

    .feature-card {
      text-align: center;
      padding: 10px;
      animation: fadeUp 0.6s ease both;
    }

    .feature-icon {
      width: 70px; height: 70px;
      margin: 0 auto 18px;
      position: relative;
    }

    /* Icon designs */
    .icon-discovery {
      background: linear-gradient(135deg, #ff6b9d, #c0392b);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
    }
    .icon-creator {
      background: linear-gradient(135deg, #25f4ee, #0a7a79);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
    }
    .icon-community {
      background: linear-gradient(135deg, #fe2c55, #8b0020);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
    }
    .icon-safe {
      background: linear-gradient(135deg, #25f4ee, #fe2c55);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
    }

    .feature-card h3 {
      font-size: 1.05rem;
      font-weight: 800;
      margin-bottom: 12px;
      color: #111;
    }

    .feature-card p {
      font-size: 0.82rem;
      line-height: 1.65;
      color: #555;
    }

    /* ── GLOBAL MARKET ── */
    .global {
      background: var(--black);
      padding: 90px 60px;
      display: flex;
      align-items: center;
      gap: 80px;
    }

    .global-text { flex: 1; max-width: 440px; }

    .global-text h2 {
      font-size: clamp(2rem, 3.5vw, 3rem);
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 20px;
    }

    .global-text h2 span { color: var(--cyan); }

    .global-text p {
      color: var(--text-muted);
      font-size: 0.9rem;
      line-height: 1.7;
      margin-bottom: 30px;
    }

    .stats-row {
      display: flex;
      gap: 40px;
      margin-top: 30px;
    }

    .stat { }
    .stat-num {
      font-size: 2rem;
      font-weight: 900;
      color: var(--cyan);
    }
    .stat-label {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .global-visual {
      flex: 1;
      position: relative;
      display: flex;
      justify-content: center;
    }

    .global-card {
      background: #1a1a1a;
      border-radius: 20px;
      overflow: hidden;
      width: 340px;
      position: relative;
      border: 1px solid rgba(255,255,255,0.08);
    }

    .global-card-img {
      width: 100%; height: 200px;
      background: linear-gradient(135deg, #3d2b5e, #1a0f3a, #2d1a4a);
      display: flex; align-items: center; justify-content: center;
      font-size: 4rem;
      position: relative;
      overflow: hidden;
    }
    .global-card-img::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(37,244,238,0.1), rgba(254,44,85,0.1));
    }

    .play-btn {
      width: 52px; height: 52px;
      background: var(--red);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      position: absolute;
      right: 16px; bottom: -26px;
      z-index: 5;
      box-shadow: 0 4px 20px rgba(254,44,85,0.5);
      cursor: pointer;
      font-size: 1.2rem;
    }

    .global-card-body {
      padding: 36px 20px 20px;
    }
    .global-card-body h4 {
      font-size: 1rem; font-weight: 800; margin-bottom: 8px;
    }
    .global-card-body p {
      font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;
    }

    /* Dashed circle decoration */
    .global-deco {
      position: absolute;
      width: 140px; height: 140px;
      border: 2px dashed rgba(254,44,85,0.4);
      border-radius: 50%;
      top: -30px; right: -30px;
      animation: spin 20s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── SELLERS CTA ── */
    .sellers-cta {
      background: linear-gradient(135deg, #0d0d0d 0%, #1a0010 50%, #0d0d0d 100%);
      padding: 90px 60px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .sellers-cta::before {
      content: '';
      position: absolute;
      top: -100px; left: 50%; transform: translateX(-50%);
      width: 600px; height: 300px;
      background: radial-gradient(ellipse, rgba(254,44,85,0.15) 0%, transparent 70%);
    }

    .sellers-cta h2 {
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 900;
      margin-bottom: 16px;
    }
    .sellers-cta h2 span { color: var(--red); }

    .sellers-cta p {
      color: var(--text-muted);
      max-width: 480px;
      margin: 0 auto 40px;
      font-size: 0.95rem;
      line-height: 1.7;
    }

    .cta-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

    .btn-big-red {
      padding: 14px 36px;
      background: var(--red);
      color: white; border: none; border-radius: 6px;
      font-size: 1rem; font-weight: 800;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(254,44,85,0.4);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-big-red:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(254,44,85,0.55); }

    .btn-big-outline {
      padding: 14px 36px;
      background: none;
      color: white; border: 1.5px solid rgba(255,255,255,0.3); border-radius: 6px;
      font-size: 1rem; font-weight: 700;
      cursor: pointer;
      transition: border-color 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-big-outline:hover { border-color: var(--cyan); color: var(--cyan); }

    /* ── FOOTER ── */
    footer {
      background: #050505;
      padding: 50px 60px 30px;
      border-top: 1px solid rgba(255,255,255,0.06);
    }

    .footer-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 40px;
      flex-wrap: wrap;
      gap: 30px;
    }

    .footer-brand { max-width: 280px; }
    .footer-brand p {
      color: var(--text-muted);
      font-size: 0.82rem;
      line-height: 1.7;
      margin-top: 14px;
    }

    .footer-links h5 {
      font-size: 0.85rem; font-weight: 700;
      margin-bottom: 16px;
      color: rgba(255,255,255,0.6);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }
    .footer-links ul { list-style: none; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 0.85rem;
      transition: color 0.2s;
    }
    .footer-links a:hover { color: var(--white); }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 28px;
      border-top: 1px solid rgba(255,255,255,0.06);
      flex-wrap: wrap;
      gap: 14px;
    }
    .footer-bottom p { color: var(--text-muted); font-size: 0.8rem; }

    .footer-powered {
      text-align: center;
      margin: 0;
      padding: 20px 16px 0;
      max-width: 640px;
      margin-left: auto;
      margin-right: auto;
    }
    .footer-powered a {
      color: var(--text-muted);
      font-size: 0.75rem;
      line-height: 1.5;
      text-decoration: none;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .footer-powered a:hover { color: var(--white); border-bottom-color: rgba(255,255,255,0.45); }

    .social-icons { display: flex; gap: 14px; }
    .social-icon {
      width: 36px; height: 36px;
      background: rgba(255,255,255,0.07);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.95rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .social-icon:hover { background: var(--red); }

    /* Responsive */
    @media (max-width: 900px) {
      nav { padding: 16px 24px; }
      .hero { flex-direction: column; padding: 100px 24px 60px; gap: 50px; }
      .hero-content { padding-left: 0; }
      .hero::before, .hero::after { display: none; }
      .features-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
      .global { flex-direction: column; padding: 60px 24px; gap: 40px; }
      .features { padding: 60px 24px; }
      .sellers-cta { padding: 60px 24px; }
      footer { padding: 40px 24px 24px; }
      .footer-top { flex-direction: column; }
    }

    @media (max-width: 576px) {
      nav {
        padding: 12px 16px;
      }

      .logo-text {
        font-size: 1rem;
      }

      .hero {
        padding: 90px 16px 40px;
        gap: 32px;
      }

      .hero-title {
        font-size: 1.8rem;
      }

      .hero-desc {
        font-size: 0.85rem;
        margin-bottom: 24px;
      }

      .hero-btns {
        flex-direction: column;
        align-items: stretch;
      }

      .btn-outline,
      .btn-solid {
        width: 100%;
        justify-content: center;
      }

      .features {
        padding: 40px 16px;
      }

      .features-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .global {
        padding: 40px 16px;
        gap: 24px;
      }

      .sellers-cta {
        padding: 40px 16px;
      }

      footer {
        padding: 32px 16px 20px;
      }
    }

  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="logo">
    <div class="logo-icon" style="width:30px;height:30px;position:relative;display:flex;align-items:center;justify-content:center;">
      <span class="tiktok-d" style="font-size:1.3rem;font-weight:900;">♪</span>
    </div>
    <div class="logo-text"><?php if($__sfLegacy): ?>TikTok <span>Shop</span><?php else: ?> <?php echo e($__sfNameWords[0] ?? $__sfName); ?> <?php if(count($__sfNameWords) > 1): ?><span><?php echo e(implode(' ', array_slice($__sfNameWords, 1))); ?></span><?php endif; ?> <?php endif; ?></div>
  </div>
  <div class="nav-right">
    <button class="lang-btn">🌐 English</button>
    <a class="nav-cta" href="<?php echo e(route('shop.landing.join')); ?>">Get Started</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-tag"><?php if($__sfLegacy): ?>TikTok Shop <?php else: ?> <?php echo e($__sfName); ?> <?php endif; ?></div>
    <h1 class="hero-title"><?php if($__sfLegacy): ?>platform's fully managed operation model <?php else: ?> Your marketplace for buyers and trusted sellers <?php endif; ?></h1>
    <p class="hero-desc">
      <?php if($__sfLegacy): ?>
      Providing quality goods and services to global consumers and comprehensive support for merchants to help them rapidly expand into the global market.
      <?php else: ?>
      Shop quality products, support independent merchants, and grow your business with tools for catalog, orders, and secure checkout — all on <?php echo e($__sfName); ?>.
      <?php endif; ?>
    </p>
    <div class="hero-btns">
      <?php if($__sfLegacy): ?>
      <a class="btn-outline" href="<?php echo e(route('shop.customer.session.index')); ?>">Buyer</a>
      <a class="btn-outline" href="<?php echo e(route('admin.session.create')); ?>">Seller</a>
      <a class="btn-solid" href="<?php echo e(route('shop.landing.join')); ?>">Join</a>
      <?php else: ?>
      <a class="btn-outline" href="<?php echo e(route('shop.customer.session.index')); ?>">Shop as buyer</a>
      <a class="btn-solid" href="<?php echo e(route('shop.landing.join')); ?>">Become a seller</a>
      <a class="btn-outline" href="<?php echo e(route('admin.session.create')); ?>" title="Seller Dashboard">Seller Dashboard</a>
      <?php endif; ?>
    </div>
    <div class="hero-social-proof">
      <div class="avatars">
        <div class="avatar-placeholder">AJ</div>
        <div class="avatar-placeholder">KM</div>
        <div class="avatar-placeholder">SR</div>
        <div class="avatar-placeholder">+</div>
      </div>
      <span><?php if($__sfLegacy): ?>50,000+ Users download our app <?php else: ?> Independent sellers and buyers on <?php echo e($__sfName); ?> <?php endif; ?></span>
    </div>
  </div>

  <div class="hero-visual">
    <div class="hero-media <?php if(!isset($landingHeroMedia) || $landingHeroMedia->isEmpty()): ?> hero-media--empty <?php endif; ?>">
      <?php if(isset($landingHeroMedia) && $landingHeroMedia->isNotEmpty()): ?>
        <div class="ttk-lp-hero-slideshow" id="ttk-lp-hero-slideshow" aria-hidden="true">
          <?php $__currentLoopData = $landingHeroMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="ttk-lp-hero-slide <?php if($loop->first): ?> ttk-lp-hero-slide--active <?php endif; ?>">
              <?php if($m->isVideo()): ?>
                <?php
                  $hpmExt = strtolower(pathinfo((string) $m->path, PATHINFO_EXTENSION));
                  $hpmVmime = match ($hpmExt) {
                    'webm' => 'video/webm',
                    'mov' => 'video/quicktime',
                    'm4v' => 'video/x-m4v',
                    'ogv', 'ogg' => 'video/ogg',
                    default => 'video/mp4',
                  };
                ?>
                <video muted loop playsinline preload="auto">
                  <source src="<?php echo e($m->getPublicUrl()); ?>" type="<?php echo e($hpmVmime); ?>">
                </video>
              <?php else: ?>
                <img src="<?php echo e($m->getPublicUrl()); ?>" alt="" decoding="async" <?php if($loop->first): ?> loading="eager" fetchpriority="high" <?php else: ?> loading="lazy" <?php endif; ?> />
              <?php endif; ?>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php else: ?>
        <div class="hero-media-placeholder" aria-hidden="true"></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features">
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon icon-discovery">🛍️</div>
      <h3>Discovery</h3>
      <p>Entertainment meets commerce through authentic and relatable shopping content, that sparks inspiration leading to purchase.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-creator">🎥</div>
      <h3>Trusted Creators</h3>
      <p><?php if($__sfLegacy): ?>Creators shop up differently on TikTok, bringing a fresh take on shopping by helping brands build trust and influence through authentic content.<?php else: ?> Creators and brands connect with shoppers through authentic content, building trust and repeat purchases.<?php endif; ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-community">👥</div>
      <h3>Engaging Communities</h3>
      <p><?php if($__sfLegacy): ?>TikTok communities drive discovery and influence culture. Brands can build lasting relationships with users by tapping into these existing communities.<?php else: ?> Community and discovery help merchants grow relationships with customers who care about their niche.<?php endif; ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-safe">🛒</div>
      <h3>Easy &amp; Safe Shopping</h3>
      <p><?php if($__sfLegacy): ?>Revolutionising the shopping journey, from the For You Page to a safe and secure check-out, order management, and support all within the app.<?php else: ?> From product discovery to checkout, order management, and support — a straightforward, secure shopping experience.<?php endif; ?></p>
    </div>
  </div>
</section>

<!-- GLOBAL MARKET -->
<section class="global">
  <div class="global-text">
    <h2>Reach the <span>Global Market</span> with ease</h2>
    <p>Connect your brand with millions of buyers across the world. Our platform gives merchants everything they need to scale internationally — from logistics to localized content.</p>
    <div class="hero-btns">
      <a class="btn-solid" href="<?php echo e(route('shop.landing.join')); ?>">Start Selling</a>
      <!-- <a class="btn-outline" href="<?php echo e(route('shop.customer.session.index')); ?>">Learn More</a> -->
    </div>
    <div class="stats-row">
      <?php if($__sfLegacy): ?>
      <div class="stat">
        <div class="stat-num">1B+</div>
        <div class="stat-label">Monthly Users</div>
      </div>
      <div class="stat">
        <div class="stat-num">150+</div>
        <div class="stat-label">Countries</div>
      </div>
      <div class="stat">
        <div class="stat-num">50M+</div>
        <div class="stat-label">Active Sellers</div>
      </div>
      <?php else: ?>
      <div class="stat">
        <div class="stat-num">Seller</div>
        <div class="stat-label">Tools &amp; catalog</div>
      </div>
      <div class="stat">
        <div class="stat-num">Secure</div>
        <div class="stat-label">Checkout flow</div>
      </div>
      <div class="stat">
        <div class="stat-num">Global</div>
        <div class="stat-label">Reach your buyers</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="global-visual">
    <div class="global-deco"></div>
    <div class="global-card">
      <div class="global-card-img">
        <span style="font-size:5rem;position:relative;z-index:2;">🌏</span>
        <div class="play-btn">▶</div>
      </div>
      <div class="global-card-body">
        <h4>Expand Your Business Globally</h4>
        <p><?php if($__sfLegacy): ?>From Southeast Asia to Europe and Americas — TikTok Shop connects you to customers everywhere. Streamlined shipping, payments, and support.<?php else: ?> Reach customers where they are — list products, manage orders, and offer support through <?php echo e($__sfName); ?>. Shipping and payment options depend on your configuration and sellers.<?php endif; ?></p>
      </div>
    </div>
  </div>
</section>

<!-- SELLERS CTA -->
<section class="sellers-cta">
  <h2>Ready to Start <span>Selling?</span></h2>
  <p><?php if($__sfLegacy): ?>Join millions of sellers who have grown their business on TikTok Shop. Set up your store in minutes and start reaching customers today.<?php else: ?> Apply to sell on <?php echo e($__sfName); ?>, complete verification, and list your catalog. Buyers discover your store through our marketplace.<?php endif; ?></p>
  <div class="cta-btns">
    <a class="btn-big-red" href="<?php echo e(route('shop.landing.join')); ?>">Start as Seller</a>
    <a class="btn-big-outline" href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::browseRouteName())); ?>">Browse as Buyer</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <div class="logo">
        <div class="logo-text" style="font-size:1.3rem;"><?php if($__sfLegacy): ?>TikTok <span style="color:var(--red)">Shop</span><?php else: ?> <?php echo e($__sfNameWords[0] ?? $__sfName); ?> <?php if(count($__sfNameWords) > 1): ?><span style="color:var(--red)"><?php echo e(implode(' ', array_slice($__sfNameWords, 1))); ?></span><?php endif; ?> <?php endif; ?></div>
      </div>
      <p><?php if($__sfLegacy): ?>The platform's fully managed operation model — providing quality goods and services to global consumers.<?php else: ?> <?php echo e($__sfName); ?> provides marketplace software and seller onboarding. Products and fulfillment are offered by individual sellers, not by TikTok or ByteDance.<?php endif; ?></p>
    </div>
    <div class="footer-links">
      <h5>Company</h5>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Press</a></li>
        <li><a href="#">Blog</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h5>Sellers</h5>
      <ul>
        <li><a href="#">Seller Center</a></li>
        <li><a href="#">Creator Marketplace</a></li>
        <li><a href="#">Partner Program</a></li>
        <li><a href="#">Ads Manager</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h5>Support</h5>
      <ul>
        <li><a href="#">Help Center</a></li>
        <li><a href="#">Safety Center</a></li>
        <li><a href="#">Community Guidelines</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© <?php echo e(date('Y')); ?> <?php if($__sfLegacy): ?>TikTok Shop <?php else: ?> <?php echo e(config('app.name')); ?> <?php endif; ?>. All rights reserved.</p>
    <div class="social-icons">
      <div class="social-icon">🎵</div>
      <div class="social-icon">📸</div>
      <div class="social-icon">🐦</div>
      <div class="social-icon">▶️</div>
    </div>
  </div>
  <p class="footer-powered">
    <a href="<?php echo e(url('/')); ?>" rel="home">Powered by TikTok Shop, an open-source project by TikTok.</a>
  </p>
</footer>

<?php echo $__env->make('shop::components.layouts.storefront-chat-widgets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if(isset($landingHeroMedia)): ?>
  <?php if($landingHeroMedia->isNotEmpty()): ?>
    <script>
      (function () {
        var root = document.getElementById('ttk-lp-hero-slideshow');
        if (!root) return;
        var slides = root.querySelectorAll('.ttk-lp-hero-slide');
        if (!slides.length) return;
        var i = 0;
        function go(n) {
          i = ((n % slides.length) + slides.length) % slides.length;
          slides.forEach(function (el, j) {
            var on = j === i;
            el.classList.toggle('ttk-lp-hero-slide--active', on);
            var v = el.querySelector('video');
            if (v) {
              if (on) {
                try { v.currentTime = 0; } catch (e) {}
                v.play().catch(function () {});
              } else {
                v.pause();
                try { v.currentTime = 0; } catch (e) {}
              }
            }
          });
        }
        slides.forEach(function (el) {
          var v = el.querySelector('video');
          if (v) {
            v.pause();
            try { v.currentTime = 0; } catch (e) {}
          }
        });
        go(0);
        if (slides.length < 2) return;
        setInterval(function () { go(i + 1); }, 5000);
      })();
    </script>
  <?php endif; ?>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/landing/index.blade.php ENDPATH**/ ?>