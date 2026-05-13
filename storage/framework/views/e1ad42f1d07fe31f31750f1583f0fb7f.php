<!DOCTYPE html>

<html
    lang="<?php echo e(app()->getLocale()); ?>"
    dir="<?php echo e(core()->getCurrentLocale()->direction); ?>"
>

<head>
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
        name="generator" 
        content="TikTok Shop"
    >

    <?php echo $__env->yieldPushContent('meta'); ?>

    <?php echo themes()->setBagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])->toHtml(); ?>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    <?php if($favicon = core()->getConfigData('general.design.admin_logo.favicon')): ?>
        <link
            type="image/x-icon"
            href="<?php echo e(Storage::url($favicon)); ?>"
            rel="shortcut icon"
            sizes="16x16"
        />
    <?php else: ?>
        <link
            type="image/x-icon"
            href="/storage/theme/1/favicon.webp"
            rel="shortcut icon"
            sizes="16x16"
        />
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        <?php echo core()->getConfigData('general.content.custom_scripts.custom_css'); ?>


        /* TikTok Shop admin (anonymous) theme overrides */
        :root {
            --tiktok-primary: #ff0050;
            --tiktok-bg: #000000;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            /* background-image: url("/storage/theme/1/tiktok_background.jpeg"); */
            background-color: #ffffff;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 1;
            pointer-events: none;
            z-index: 0;
        }

        #app {
            position: relative;
            z-index: 1;
        }

        .primary-button,
        .bg-blue-600 {
            background-color: var(--tiktok-primary) !important;
            border-color: var(--tiktok-primary) !important;
        }

        .text-blue-600 {
            color: var(--tiktok-primary) !important;
        }

        body {
            background-color: #000000;
        }
    </style>

    <?php echo view_render_event('bagisto.admin.layout.head'); ?>

</head>

<body>
    <?php echo view_render_event('bagisto.admin.layout.body.before'); ?>


    <!-- Built With Bagisto -->
    <div id="app">
        <!-- Flash Message Blade Component -->
        <?php if (isset($component)) { $__componentOriginal701f473bf36886c6d0b4697249a816f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal701f473bf36886c6d0b4697249a816f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.flash-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::flash-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal701f473bf36886c6d0b4697249a816f6)): ?>
<?php $attributes = $__attributesOriginal701f473bf36886c6d0b4697249a816f6; ?>
<?php unset($__attributesOriginal701f473bf36886c6d0b4697249a816f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal701f473bf36886c6d0b4697249a816f6)): ?>
<?php $component = $__componentOriginal701f473bf36886c6d0b4697249a816f6; ?>
<?php unset($__componentOriginal701f473bf36886c6d0b4697249a816f6); ?>
<?php endif; ?>

        <?php echo view_render_event('bagisto.admin.layout.content.before'); ?>


        <!-- Page Content Blade Component -->
        <?php echo e($slot); ?>


        <?php echo view_render_event('bagisto.admin.layout.content.after'); ?>

    </div>

    <?php echo view_render_event('bagisto.admin.layout.body.after'); ?>


    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo view_render_event('bagisto.admin.layout.vue-app-mount.before'); ?>


    <?php echo view_render_event('bagisto.admin.layout.vue-app-mount.after'); ?>


    <?php echo $__env->make('admin::components.layouts.embed-tawk', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script type="text/javascript">
        <?php echo core()->getConfigData('general.content.custom_scripts.custom_javascript'); ?>

    </script>
</body>

</html>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/components/layouts/anonymous.blade.php ENDPATH**/ ?>