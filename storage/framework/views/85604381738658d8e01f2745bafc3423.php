<?php if (isset($component)) { $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.sales.orders.landing.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
            crossorigin="anonymous"
        >
    <?php $__env->stopPush(); ?>

    
    <div class="orders-landing mx-auto max-w-5xl px-1 sm:px-0">
        <header class="mb-8 text-center lg:mb-10">
            <span
                class="mb-3 inline-flex items-center rounded-full border border-pink-200 bg-pink-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-pink-700 dark:border-pink-900/50 dark:bg-pink-950/40 dark:text-pink-300"
            >
                <?php echo app('translator')->get('superadmin::app.sales.orders.landing.badge'); ?>
            </span>

            <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">
                <?php echo app('translator')->get('superadmin::app.sales.orders.landing.title'); ?>
            </h1>

            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400 md:text-base">
                <?php echo app('translator')->get('superadmin::app.sales.orders.landing.description'); ?>
            </p>
        </header>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
            
            <div class="min-w-0">
                <div
                    class="card h-full rounded-xl border border-gray-200 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
                >
                    <div class="card-body flex h-full min-h-[220px] flex-col p-0">
                        <div class="border-b border-gray-100 bg-gradient-to-br from-slate-50 to-white px-6 py-5 dark:border-gray-800 dark:from-gray-900 dark:to-gray-900">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-receipt-cutoff text-2xl"></i>
                                </div>
                                <div class="min-w-0 flex-1 text-start">
                                    <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                        <?php echo app('translator')->get('superadmin::app.sales.orders.landing.customer-orders'); ?>
                                    </h2>
                                    <p class="mb-0 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                        <?php echo app('translator')->get('superadmin::app.sales.orders.landing.customer-orders-hint'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="min-h-6 flex-1" aria-hidden="true"></div>

                        <div class="mt-auto border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-gray-800 dark:bg-gray-950/50">
                            <a
                                href="<?php echo e(route('superadmin.sales.orders.customers.index')); ?>"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                                style="background-color: var(--tiktok-primary, #ff0050); border: none;"
                            >
                                <span><?php echo app('translator')->get('superadmin::app.sales.orders.landing.customer-cta'); ?></span>
                                <i class="bi bi-arrow-right rtl:rotate-180"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="min-w-0">
                <div
                    class="card h-full rounded-xl border border-gray-200 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
                >
                    <div class="card-body flex h-full min-h-[220px] flex-col p-0">
                        <div class="border-b border-gray-100 bg-gradient-to-br from-slate-50 to-white px-6 py-5 dark:border-gray-800 dark:from-gray-900 dark:to-gray-900">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200"
                                    aria-hidden="true"
                                >
                                    <i class="bi bi-shop-window text-2xl"></i>
                                </div>
                                <div class="min-w-0 flex-1 text-start">
                                    <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                        <?php echo app('translator')->get('superadmin::app.sales.orders.landing.seller-orders'); ?>
                                    </h2>
                                    <p class="mb-0 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                        <?php echo app('translator')->get('superadmin::app.sales.orders.landing.seller-orders-hint'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="min-h-6 flex-1" aria-hidden="true"></div>

                        <div class="mt-auto border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-gray-800 dark:bg-gray-950/50">
                            <a
                                href="<?php echo e(route('superadmin.sellers.orders.dashboard')); ?>"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                                style="background-color: var(--tiktok-primary, #ff0050); border: none;"
                            >
                                <span><?php echo app('translator')->get('superadmin::app.sales.orders.landing.seller-cta'); ?></span>
                                <i class="bi bi-arrow-right rtl:rotate-180"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/orders/landing.blade.php ENDPATH**/ ?>