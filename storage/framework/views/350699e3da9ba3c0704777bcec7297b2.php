
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'hasHeader'               => true,
    'hasFeature'              => true,
    'hasFooter'               => true,
    'hideHeaderCategories'    => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'hasHeader'               => true,
    'hasFeature'              => true,
    'hasFooter'               => true,
    'hideHeaderCategories'    => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!DOCTYPE html>

<html
    lang="<?php echo e(app()->getLocale()); ?>"
    dir="<?php echo e(core()->getCurrentLocale()->direction); ?>"
>
    <head>

        <?php echo view_render_event('bagisto.shop.layout.head.before'); ?>


        <title><?php echo e($title ?? ''); ?></title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="<?php echo e(app()->getLocale()); ?>"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="<?php echo e(url()->to('/')); ?>"
        >
        <meta
            name="currency"
            content="<?php echo e(core()->getCurrentCurrency()->toJson()); ?>"
        >
        <meta
            name="generator"
            content="<?php echo e(config('storefront-branding.legacy_tiktok_branding') ? 'TikTok Mall' : config('storefront-branding.marketplace_name')); ?>"
        >

        <?php echo $__env->yieldPushContent('meta'); ?>

        <link
            rel="icon"
            type="image/webp"
            sizes="16x16"
            href="<?php echo e(asset('storage/theme/1/favicon.webp')); ?>"
        />

        <?php echo themes()->setBagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])->toHtml(); ?>

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        />

        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        />

        
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
            media="print"
            onload="this.media='all'"
        />

        <noscript>
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
            />
        </noscript>

        <?php echo $__env->yieldPushContent('styles'); ?>

        <style>
            <?php echo core()->getConfigData('general.content.custom_scripts.custom_css'); ?>


            /* TikTok Shop storefront theme overrides */
            :root {
                --tiktok-primary: #ff0050;
                --tiktok-primary-hover: #e60048;
                --tiktok-bg: #ffffff;
                --tiktok-surface: #0b0b0b;
                --tiktok-border: #222222;
                --tiktok-text: #111111;
                --tiktok-muted: #a1a1aa;
            }

            body {
                background: var(--tiktok-bg);
                color: var(--tiktok-text);
            }

            /* Disable custom global background image */
            body::before {
                display: none !important;
                content: none !important;
            }

            body,
            #app,
            main,
            header,
            footer {
                background-image: none !important;
            }

            #app {
                position: relative;
                z-index: 1;
            }

            a {
                color: var(--tiktok-primary);
            }

            a:hover {
                color: var(--tiktok-primary-hover);
            }

            /* Force any remaining blue accents to TikTok red */
            .text-blue-600,
            .text-blue-700,
            .text-blue-800,
            .text-navyBlue,
            .hover\:text-blue-600:hover,
            .hover\:text-blue-700:hover,
            .hover\:text-blue-800:hover {
                color: var(--tiktok-primary) !important;
            }

            .bg-blue-600,
            .bg-blue-700 {
                background-color: var(--tiktok-primary) !important;
                border-color: var(--tiktok-primary) !important;
            }

            .primary-button,
            .bg-navyBlue {
                background-color: var(--tiktok-primary) !important;
                border-color: var(--tiktok-primary) !important;
            }

            .primary-button:hover,
            .bg-navyBlue:hover {
                background-color: var(--tiktok-primary-hover) !important;
                border-color: var(--tiktok-primary-hover) !important;
            }

            .secondary-button {
                border-color: var(--tiktok-primary) !important;
                color: var(--tiktok-primary) !important;
            }

            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(255, 0, 80, 0.35) !important;
                border-color: var(--tiktok-primary) !important;
            }

            /* Keep surfaces white */
            header,
            footer,
            .bg-white {
                background-color: #ffffff !important;
            }

            .border-zinc-200,
            .border-gray-200,
            .border-\[\#F3F3F3\] {
                border-color: var(--tiktok-border) !important;
            }

            .text-zinc-500,
            .text-gray-500 {
                color: var(--tiktok-muted) !important;
            }

            /* Auth pages (login/signup/forgot/reset) card styling */
            .auth-card {
                background: rgba(255, 255, 255, 0.88) !important;
                color: #111827 !important;
                border-color: rgba(255, 255, 255, 0.35) !important;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            }

            .auth-card .text-zinc-500,
            .auth-card .text-gray-500 {
                color: #4b5563 !important;
            }

            .auth-card a {
                color: var(--tiktok-primary) !important;
            }

            .auth-card input,
            .auth-card select,
            .auth-card textarea {
                background: rgba(255, 255, 255, 0.95) !important;
                color: #111827 !important;
                border-color: rgba(17, 24, 39, 0.15) !important;
            }

            /* User-requested readability: white text -> black; preserve non-white colors */
            .text-white {
                color: #111111 !important;
            }

            .bg-black,
            .bg-black\/90,
            .bg-black\/80,
            .bg-black\/70 {
                background-color: #ffffff !important;
            }

            /* Force any explicit white text (utility/inline styles) to black */
            [class*="text-white"],
            [style*="color: white"],
            [style*="color:white"],
            [style*="color:#fff"],
            [style*="color: #fff"],
            [style*="color:#ffffff"],
            [style*="color: #ffffff"] {
                color: #111111 !important;
            }
        </style>

        <?php if(core()->getConfigData('general.content.speculation_rules.enabled')): ?>
            <script type="speculationrules">
                <?php echo json_encode(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 512) ?>
            </script>
        <?php endif; ?>

        <?php echo view_render_event('bagisto.shop.layout.head.after'); ?>


    </head>

    <body>
        <?php echo view_render_event('bagisto.shop.layout.body.before'); ?>


        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <!-- Built With Bagisto -->
        <div id="app">
            <!-- Flash Message Blade Component -->
            <?php if (isset($component)) { $__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.flash-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::flash-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0)): ?>
<?php $attributes = $__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0; ?>
<?php unset($__attributesOriginal32b426cadde54f0d7b8de12fc0e5c6a0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0)): ?>
<?php $component = $__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0; ?>
<?php unset($__componentOriginal32b426cadde54f0d7b8de12fc0e5c6a0); ?>
<?php endif; ?>

            <!-- Confirm Modal Blade Component -->
            <?php if (isset($component)) { $__componentOriginal5692e00db184034e913f6f80572595d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5692e00db184034e913f6f80572595d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.modal.confirm','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::modal.confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5692e00db184034e913f6f80572595d8)): ?>
<?php $attributes = $__attributesOriginal5692e00db184034e913f6f80572595d8; ?>
<?php unset($__attributesOriginal5692e00db184034e913f6f80572595d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5692e00db184034e913f6f80572595d8)): ?>
<?php $component = $__componentOriginal5692e00db184034e913f6f80572595d8; ?>
<?php unset($__componentOriginal5692e00db184034e913f6f80572595d8); ?>
<?php endif; ?>

            <!-- Page Header Blade Component -->
            <?php if($hasHeader): ?>
                <?php if (isset($component)) { $__componentOriginal8840dbae58ccad6ecf4eb407b99d7661 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.index','data' => ['hideHeaderCategories' => $hideHeaderCategories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hide-header-categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeaderCategories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661)): ?>
<?php $attributes = $__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661; ?>
<?php unset($__attributesOriginal8840dbae58ccad6ecf4eb407b99d7661); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8840dbae58ccad6ecf4eb407b99d7661)): ?>
<?php $component = $__componentOriginal8840dbae58ccad6ecf4eb407b99d7661; ?>
<?php unset($__componentOriginal8840dbae58ccad6ecf4eb407b99d7661); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            ): ?>
                <?php if (isset($component)) { $__componentOriginalee22104ea56ac769eac5950f83fdb6cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee22104ea56ac769eac5950f83fdb6cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.cookie.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.cookie'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee22104ea56ac769eac5950f83fdb6cf)): ?>
<?php $attributes = $__attributesOriginalee22104ea56ac769eac5950f83fdb6cf; ?>
<?php unset($__attributesOriginalee22104ea56ac769eac5950f83fdb6cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee22104ea56ac769eac5950f83fdb6cf)): ?>
<?php $component = $__componentOriginalee22104ea56ac769eac5950f83fdb6cf; ?>
<?php unset($__componentOriginalee22104ea56ac769eac5950f83fdb6cf); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.layout.content.before'); ?>


            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white text-black">
                <?php echo e($slot); ?>

            </main>

            <?php echo view_render_event('bagisto.shop.layout.content.after'); ?>



            <!-- Page Services Blade Component -->
            <?php if($hasFeature): ?>
                <?php if (isset($component)) { $__componentOriginalc7f3325cf24ae738796526b5a914125a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7f3325cf24ae738796526b5a914125a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.services','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.services'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7f3325cf24ae738796526b5a914125a)): ?>
<?php $attributes = $__attributesOriginalc7f3325cf24ae738796526b5a914125a; ?>
<?php unset($__attributesOriginalc7f3325cf24ae738796526b5a914125a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7f3325cf24ae738796526b5a914125a)): ?>
<?php $component = $__componentOriginalc7f3325cf24ae738796526b5a914125a; ?>
<?php unset($__componentOriginalc7f3325cf24ae738796526b5a914125a); ?>
<?php endif; ?>
            <?php endif; ?>

            <!-- Page Footer Blade Component -->
            <?php if($hasFooter): ?>
                <?php if (isset($component)) { $__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.footer.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b)): ?>
<?php $attributes = $__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b; ?>
<?php unset($__attributesOriginal52eadd6893b05c6c47a48f4bafc8ff6b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b)): ?>
<?php $component = $__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b; ?>
<?php unset($__componentOriginal52eadd6893b05c6c47a48f4bafc8ff6b); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>

        <?php echo view_render_event('bagisto.shop.layout.body.after'); ?>


        <?php echo $__env->make('shop::components.layouts.storefront-chat-widgets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->yieldPushContent('scripts'); ?>

        <?php echo view_render_event('bagisto.shop.layout.vue-app-mount.before'); ?>


        <?php echo view_render_event('bagisto.shop.layout.vue-app-mount.after'); ?>


        <script type="text/javascript">
            <?php echo core()->getConfigData('general.content.custom_scripts.custom_javascript'); ?>

        </script>
    </body>
</html>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/components/layouts/index.blade.php ENDPATH**/ ?>