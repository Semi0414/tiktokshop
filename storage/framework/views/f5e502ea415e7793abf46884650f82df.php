<!DOCTYPE html>

<html
    class="<?php echo e(request()->cookie('dark_mode') ?? 0 ? 'dark' : ''); ?>"
    lang="<?php echo e(app()->getLocale()); ?>"
    dir="<?php echo e(core()->getCurrentLocale()->direction); ?>"
>

<head>
    <?php echo view_render_event('bagisto.admin.layout.head.before'); ?>


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
        content="<?php echo e(core()->getBaseCurrency()->toJson()); ?>"
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
        >
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


        /* TikTok Shop admin theme overrides */
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

        .text-blue-600,
        .dark\:text-darkBlue {
            color: var(--tiktok-primary) !important;
        }

        .dark .bg-gray-900 {
            background-color: var(--tiktok-bg) !important;
        }
    </style>

    <?php echo view_render_event('bagisto.admin.layout.head.after'); ?>

</head>

<body class="h-full dark:bg-gray-950">
    <?php echo view_render_event('bagisto.admin.layout.body.before'); ?>


    <!-- Built With Bagisto -->
    <div
        id="app"
        class="h-full"
    >
        <!-- Flash Message Blade Component -->
        <?php if (isset($component)) { $__componentOriginal382408a65f2f71f1ff27dca5d28daa0f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal382408a65f2f71f1ff27dca5d28daa0f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flash-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flash-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal382408a65f2f71f1ff27dca5d28daa0f)): ?>
<?php $attributes = $__attributesOriginal382408a65f2f71f1ff27dca5d28daa0f; ?>
<?php unset($__attributesOriginal382408a65f2f71f1ff27dca5d28daa0f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal382408a65f2f71f1ff27dca5d28daa0f)): ?>
<?php $component = $__componentOriginal382408a65f2f71f1ff27dca5d28daa0f; ?>
<?php unset($__componentOriginal382408a65f2f71f1ff27dca5d28daa0f); ?>
<?php endif; ?>

        <!-- Confirm Modal Blade Component -->
        <?php if (isset($component)) { $__componentOriginal1c523e6d12e04c2fb789917e9dfa6c75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1c523e6d12e04c2fb789917e9dfa6c75 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.modal.confirm','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::modal.confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1c523e6d12e04c2fb789917e9dfa6c75)): ?>
<?php $attributes = $__attributesOriginal1c523e6d12e04c2fb789917e9dfa6c75; ?>
<?php unset($__attributesOriginal1c523e6d12e04c2fb789917e9dfa6c75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1c523e6d12e04c2fb789917e9dfa6c75)): ?>
<?php $component = $__componentOriginal1c523e6d12e04c2fb789917e9dfa6c75; ?>
<?php unset($__componentOriginal1c523e6d12e04c2fb789917e9dfa6c75); ?>
<?php endif; ?>

        <?php echo view_render_event('bagisto.admin.layout.content.before'); ?>


        <!-- Page Header Blade Component -->
        <?php if (isset($component)) { $__componentOriginalf9cf9d43ce9ee6ee146304df8bab3421 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf9cf9d43ce9ee6ee146304df8bab3421 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.header.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf9cf9d43ce9ee6ee146304df8bab3421)): ?>
<?php $attributes = $__attributesOriginalf9cf9d43ce9ee6ee146304df8bab3421; ?>
<?php unset($__attributesOriginalf9cf9d43ce9ee6ee146304df8bab3421); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf9cf9d43ce9ee6ee146304df8bab3421)): ?>
<?php $component = $__componentOriginalf9cf9d43ce9ee6ee146304df8bab3421; ?>
<?php unset($__componentOriginalf9cf9d43ce9ee6ee146304df8bab3421); ?>
<?php endif; ?>

        <div
            id="superadmin-app-layout"
            class="group/container <?php echo e(request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed'); ?> flex flex-col lg:flex-row gap-0 lg:gap-4"
            ref="appLayout"
        >
            <!-- Page Sidebar Blade Component -->
            <div class="lg:fixed lg:top-[62px] lg:left-0 rtl:lg:right-0 rtl:lg:left-auto lg:z-10 w-full lg:w-auto">
                <?php if (isset($component)) { $__componentOriginal4885868732da3251c2c2c3359005563e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4885868732da3251c2c2c3359005563e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.sidebar.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4885868732da3251c2c2c3359005563e)): ?>
<?php $attributes = $__attributesOriginal4885868732da3251c2c2c3359005563e; ?>
<?php unset($__attributesOriginal4885868732da3251c2c2c3359005563e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4885868732da3251c2c2c3359005563e)): ?>
<?php $component = $__componentOriginal4885868732da3251c2c2c3359005563e; ?>
<?php unset($__componentOriginal4885868732da3251c2c2c3359005563e); ?>
<?php endif; ?>
            </div>

            <div class="flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-white transition-all duration-300 dark:bg-gray-950 pt-3 px-2 sm:px-4 lg:pt-3 lg:px-4 lg:ltr:pl-[286px] lg:group-[.sidebar-collapsed]/container:ltr:pl-[85px] lg:rtl:pr-[286px] lg:group-[.sidebar-collapsed]/container:rtl:pr-[85px]">
                <!-- Added dynamic tabs for third level menus  -->
                <div class="pb-4 lg:pb-6">
                    <!-- Todo @suraj-webkul need to optimize below statement. -->
                    <?php if(! request()->routeIs('superadmin.configuration.index')): ?>
                        <div class="overflow-x-auto">
                            <?php if (isset($component)) { $__componentOriginal9e2bb23229eee0a4296a1f96b30831bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e2bb23229eee0a4296a1f96b30831bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.tabs','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts.tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e2bb23229eee0a4296a1f96b30831bf)): ?>
<?php $attributes = $__attributesOriginal9e2bb23229eee0a4296a1f96b30831bf; ?>
<?php unset($__attributesOriginal9e2bb23229eee0a4296a1f96b30831bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e2bb23229eee0a4296a1f96b30831bf)): ?>
<?php $component = $__componentOriginal9e2bb23229eee0a4296a1f96b30831bf; ?>
<?php unset($__componentOriginal9e2bb23229eee0a4296a1f96b30831bf); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Page Content Blade Component -->
                    <div class="w-full overflow-x-hidden">
                        <?php echo e($slot); ?>

                    </div>
                </div>

                <!-- Powered By -->
                <div class="mt-auto">
                    <div class="border-t bg-white py-2 text-center text-xs sm:text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.components.layouts.powered-by.description', [
                            'bagisto' => '<a class="text-blue-600 hover:underline dark:text-darkBlue" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                            'webkul' => '<a class="text-blue-600 hover:underline dark:text-darkBlue" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php echo view_render_event('bagisto.admin.layout.content.after'); ?>

    </div>

    <?php echo view_render_event('bagisto.admin.layout.body.after'); ?>


    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo view_render_event('bagisto.admin.layout.vue-app-mount.before'); ?>


    <?php echo view_render_event('bagisto.admin.layout.vue-app-mount.after'); ?>

</body>

</html>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/layouts/index.blade.php ENDPATH**/ ?>