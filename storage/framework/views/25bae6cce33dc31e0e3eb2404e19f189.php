<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'headerCategoryTree'      => null,
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
    'headerCategoryTree'      => null,
    'hideHeaderCategories'    => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before'); ?>


<div class="flex min-h-[78px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
        <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before'); ?>


        <?php
            $__sfLegacyHeader = (bool) config('storefront-branding.legacy_tiktok_branding');
            $__shopNavLogoSrc = $__sfLegacyHeader
                ? asset('storage/theme/1/TikTok_logo.svg.png')
                : (core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg'));
        ?>
        <a
            href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName())); ?>"
            aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.bagisto'); ?>"
        >
            <img
                src="<?php echo e($__shopNavLogoSrc); ?>"
                width="131"
                height="29"
                <?php if($__sfLegacyHeader): ?> style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" <?php endif; ?>
                alt="<?php echo e(config('app.name')); ?>"
            >
        </a>

        <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after'); ?>


        <?php if(! $hideHeaderCategories): ?>
            <div
                class="flex max-[1300px]:hidden items-center gap-x-6 overflow-x-auto whitespace-nowrap"
                data-category-scroller
            >
                <?php echo $__env->make('shop::components.layouts.header.partials.desktop-categories-html', [
                    'categories' => $headerCategoryTree ?? app(\Webkul\Category\Repositories\CategoryRepository::class)
                        ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">

        <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before'); ?>


        <!-- Search Bar (products + seller stores) -->
        <?php if (isset($component)) { $__componentOriginal426648d992f6146c7b258a5bd367c733 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal426648d992f6146c7b258a5bd367c733 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.search-smart','data' => ['context' => 'desktop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.search-smart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['context' => 'desktop']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal426648d992f6146c7b258a5bd367c733)): ?>
<?php $attributes = $__attributesOriginal426648d992f6146c7b258a5bd367c733; ?>
<?php unset($__attributesOriginal426648d992f6146c7b258a5bd367c733); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal426648d992f6146c7b258a5bd367c733)): ?>
<?php $component = $__componentOriginal426648d992f6146c7b258a5bd367c733; ?>
<?php unset($__componentOriginal426648d992f6146c7b258a5bd367c733); ?>
<?php endif; ?>

        <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after'); ?>


        <!-- Right Navigation Links -->
        <div class="mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8">

            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before'); ?>


            <!-- Compare -->
            <?php if(core()->getConfigData('catalog.products.settings.compare_option')): ?>
                <a
                    href="<?php echo e(route('shop.compare.index')); ?>"
                    aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.compare'); ?>"
                >
                    <span
                        class="inline-block text-2xl cursor-pointer icon-compare"
                        role="presentation"
                    ></span>
                </a>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after'); ?>


            

            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before'); ?>


            <?php
                $_profileFlyoutAlign = core()->getCurrentLocale()->direction === 'ltr'
                    ? 'right-0 left-auto'
                    : 'left-0 right-auto';
            ?>

            <!-- user profile — native <details> (Vue v-dropdown was not reliable after layout changes) -->
            <details class="relative mt-1.5">
                <summary
                    class="block cursor-pointer list-none [&::-webkit-details-marker]:hidden"
                    aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.profile'); ?>"
                >
                    <span
                        class="icon-users inline-block cursor-pointer text-2xl"
                        role="presentation"
                    ></span>
                </summary>

                <div
                    role="dialog"
                    aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.profile'); ?>"
                    class="absolute <?php echo e($_profileFlyoutAlign); ?> top-full z-[100] mt-1.5 w-max min-w-[260px] rounded-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)]"
                >
                    <?php if(auth()->guard('customer')->guest()): ?>
                        <div class="p-5">
                            <div class="grid gap-2.5">
                                <p class="font-dmserif text-xl">
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.welcome-guest'); ?>
                                </p>

                                <p class="text-sm">
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.dropdown-text'); ?>
                                </p>
                            </div>

                            <p class="mt-3 w-full border border-zinc-200"></p>

                            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before'); ?>


                            <div class="mt-6 flex gap-4">
                                <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before'); ?>


                                <a
                                    href="<?php echo e(route('shop.customer.session.index')); ?>"
                                    class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 py-3 text-center text-base ltr:ml-0 rtl:mr-0"
                                >
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.sign-in'); ?>
                                </a>

                                <a
                                    href="<?php echo e(route('shop.customers.register.index')); ?>"
                                    class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 py-3 text-center text-base max-md:py-3 ltr:ml-0 rtl:mr-0"
                                >
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.sign-up'); ?>
                                </a>

                                <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after'); ?>

                            </div>

                            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after'); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(auth()->guard('customer')->check()): ?>
                        <div class="overflow-hidden rounded-[20px]">
                            <div class="grid gap-2.5 p-5 pb-0">
                                <p class="font-dmserif text-xl">
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.welcome'); ?>
                                    <?php echo e(auth()->guard('customer')->user()->first_name); ?>

                                </p>

                                <p class="text-sm">
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.dropdown-text'); ?>
                                </p>
                            </div>

                            <p class="mt-3 w-full border border-zinc-200"></p>

                            <div class="mt-2.5 grid gap-1 pb-2.5">
                                <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before'); ?>


                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="<?php echo e(route('shop.customers.account.profile.index')); ?>"
                                >
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.profile'); ?>
                                </a>

                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="<?php echo e(route('shop.customers.account.orders.index')); ?>"
                                >
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.orders'); ?>
                                </a>

                                <?php if(core()->getConfigData('customer.settings.wishlist.wishlist_option')): ?>
                                    <a
                                        class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                        href="<?php echo e(route('shop.customers.account.wishlist.index')); ?>"
                                    >
                                        <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.wishlist'); ?>
                                    </a>
                                <?php endif; ?>

                                <form
                                    id="customerLogoutHeaderDesktop"
                                    method="POST"
                                    action="<?php echo e(route('shop.customer.session.destroy')); ?>"
                                    class="contents"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button
                                        type="submit"
                                        class="w-full cursor-pointer px-5 py-2 text-start text-base hover:bg-gray-100"
                                    >
                                        <?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.logout'); ?>
                                    </button>
                                </form>

                                <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after'); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </details>

            <?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after'); ?>

        </div>
    </div>
</div>

<?php echo view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after'); ?>

<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/components/layouts/header/desktop/bottom.blade.php ENDPATH**/ ?>