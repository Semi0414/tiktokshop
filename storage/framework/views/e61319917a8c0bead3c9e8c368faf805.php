<!--
    Mobile header — HTML drawer + server-rendered categories (no Vue drawer/category).
-->
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

<?php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');

    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');

    $__sfLegacyHeader = (bool) config('storefront-branding.legacy_tiktok_branding');
    $__shopNavLogoSrc = $__sfLegacyHeader
        ? asset('storage/theme/1/TikTok_logo.svg.png')
        : (core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg'));

    $__navCategories = $hideHeaderCategories
        ? collect()
        : ($headerCategoryTree ?? app(\Webkul\Category\Repositories\CategoryRepository::class)
            ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id));

    $customerUser = auth()->guard('customer')->user();

    $mobileLocales = core()->getCurrentChannel()->locales()->orderBy('name')->get();
?>

<div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
    <div class="flex w-full items-center justify-between">
        <!-- Left Navigation -->
        <div class="flex items-center gap-x-1.5">
            <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before'); ?>


            <input
                type="checkbox"
                id="shop-mobile-drawer-toggle"
                class="peer sr-only"
                autocomplete="off"
            >

            <label
                for="shop-mobile-drawer-toggle"
                class="icon-hamburger cursor-pointer text-2xl"
                role="button"
                tabindex="0"
            ></label>

            <!-- Backdrop -->
            <label
                for="shop-mobile-drawer-toggle"
                class="pointer-events-none fixed inset-0 z-[45] bg-black/40 opacity-0 transition-opacity duration-200 peer-checked:pointer-events-auto peer-checked:opacity-100"
                aria-hidden="true"
            ></label>

            <!-- Panel -->
            <aside
                class="fixed top-0 z-[50] flex h-full w-full max-w-sm -translate-x-full flex-col bg-white shadow transition-transform duration-200 peer-checked:translate-x-0 ltr:left-0 rtl:right-0 rtl:translate-x-full rtl:peer-checked:translate-x-0"
            >
                <div class="flex items-center justify-between border-b border-zinc-200 p-4">
                    <a href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName())); ?>">
                        <img
                            src="<?php echo e($__shopNavLogoSrc); ?>"
                            alt="<?php echo e(config('app.name')); ?>"
                            width="131"
                            height="29"
                            <?php if($__sfLegacyHeader): ?> style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" <?php endif; ?>
                        >
                    </a>

                    <label
                        for="shop-mobile-drawer-toggle"
                        class="icon-cancel cursor-pointer text-2xl"
                        role="button"
                        tabindex="0"
                    ></label>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto">
                    <div class="border-b border-zinc-200 p-4">
                        <div class="grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 p-2.5">
                            <div>
                                <img
                                    src="<?php echo e($customerUser?->image_url ?? bagisto_asset('images/user-placeholder.png')); ?>"
                                    alt=""
                                    class="h-[60px] w-[60px] rounded-full max-md:rounded-full"
                                >
                            </div>

                            <?php if(auth()->guard('customer')->guest()): ?>
                                <a
                                    href="<?php echo e(route('shop.customer.session.index')); ?>"
                                    class="flex text-base font-medium"
                                >
                                    <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.login'); ?>

                                    <i class="icon-double-arrow text-2xl ltr:ml-2.5 rtl:mr-2.5"></i>
                                </a>
                            <?php endif; ?>

                            <?php if(auth()->guard('customer')->check()): ?>
                                <div class="flex flex-col justify-between gap-2.5 max-md:gap-0">
                                    <p class="break-all text-2xl font-medium max-md:text-xl">
                                        Hello! <?php echo e($customerUser?->first_name); ?>

                                    </p>

                                    <p class="text-zinc-500 no-underline max-md:text-sm"><?php echo e($customerUser?->email); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before'); ?>


                    <?php if(! $hideHeaderCategories): ?>
                        <nav class="px-6 py-4" aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.categories'); ?>">
                            <?php echo $__env->make('shop::components.layouts.header.partials.mobile-categories-html', [
                                'categories' => $__navCategories,
                                'depth' => 0,
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </nav>
                    <?php endif; ?>

                    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after'); ?>

                </div>

                <?php if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1): ?>
                    <div class="grid w-full shrink-0 grid-cols-[1fr_auto_1fr] items-center justify-items-center gap-1 border-t border-zinc-200 bg-white px-3 py-2">
                        <?php if(core()->getCurrentChannel()->currencies()->count() > 1): ?>
                            <details class="relative w-full text-center">
                                <summary
                                    class="cursor-pointer list-none px-1 py-2 text-xs font-medium uppercase max-sm:text-[10px]"
                                >
                                    <?php echo e(core()->getCurrentCurrency()->symbol); ?> <?php echo e(core()->getCurrentCurrencyCode()); ?>

                                </summary>
                                <div
                                    class="absolute bottom-full left-0 right-0 z-20 mb-1 max-h-48 overflow-auto border border-zinc-200 bg-white text-left shadow"
                                >
                                    <?php $__currentLoopData = core()->getCurrentChannel()->currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a
                                            href="<?php echo e(request()->fullUrlWithQuery(['currency' => $currency->code])); ?>"
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'block px-3 py-2 text-sm hover:bg-gray-100',
                                                'bg-gray-100' => $currency->code === core()->getCurrentCurrencyCode(),
                                            ]); ?>"
                                        >
                                            <?php echo e($currency->symbol); ?> <?php echo e($currency->code); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </details>
                        <?php else: ?>
                            <span class="px-1 py-2 text-xs font-medium uppercase">
                                <?php echo e(core()->getCurrentCurrency()->symbol); ?> <?php echo e(core()->getCurrentCurrencyCode()); ?>

                            </span>
                        <?php endif; ?>

                        <span class="h-5 w-0.5 bg-zinc-200"></span>

                        <?php if($mobileLocales->count() > 1): ?>
                            <details class="relative w-full text-center">
                                <summary
                                    class="flex cursor-pointer list-none items-center justify-center gap-1 px-1 py-2 text-xs font-medium uppercase max-sm:text-[10px]"
                                >
                                    <img
                                        src="<?php echo e(! empty(core()->getCurrentLocale()->logo_url)
                                            ? core()->getCurrentLocale()->logo_url
                                            : bagisto_asset('images/default-language.svg')); ?>"
                                        class="h-full"
                                        alt=""
                                        width="24"
                                        height="16"
                                    >

                                    <?php echo e($mobileLocales->firstWhere('code', app()->getLocale())?->name ?? app()->getLocale()); ?>

                                </summary>
                                <div
                                    class="absolute bottom-full left-0 right-0 z-20 mb-1 max-h-48 overflow-auto border border-zinc-200 bg-white text-left shadow"
                                >
                                    <?php $__currentLoopData = $mobileLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a
                                            href="<?php echo e(request()->fullUrlWithQuery(['locale' => $locale->code])); ?>"
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100',
                                                'bg-gray-100' => $locale->code === app()->getLocale(),
                                            ]); ?>"
                                        >
                                            <img
                                                src="<?php echo e($locale->logo_url ?: bagisto_asset('images/default-language.svg')); ?>"
                                                width="24"
                                                height="16"
                                                alt=""
                                            >

                                            <?php echo e($locale->name); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </details>
                        <?php else: ?>
                            <span class="flex items-center justify-center gap-1 px-1 py-2 text-xs font-medium uppercase">
                                <img
                                    src="<?php echo e(! empty(core()->getCurrentLocale()->logo_url)
                                        ? core()->getCurrentLocale()->logo_url
                                        : bagisto_asset('images/default-language.svg')); ?>"
                                    class="h-full"
                                    alt=""
                                    width="24"
                                    height="16"
                                >
                                <?php echo e($mobileLocales->first()?->name ?? app()->getLocale()); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after'); ?>


            <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before'); ?>


            <a
                href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName())); ?>"
                class="max-h-[30px]"
                aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.bagisto'); ?>"
            >
                <img
                    src="<?php echo e($__shopNavLogoSrc); ?>"
                    alt="<?php echo e(config('app.name')); ?>"
                    width="131"
                    height="29"
                    <?php if($__sfLegacyHeader): ?> style="background-color: paleturquoise; padding: 10px 30px; border-radius: 50px;" <?php endif; ?>
                >
            </a>

            <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after'); ?>

        </div>

        <!-- Right Navigation -->
        <div>
            <div class="flex items-center gap-x-5 max-md:gap-x-4">
                <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before'); ?>


                <?php if($showCompare): ?>
                    <a
                        href="<?php echo e(route('shop.compare.index')); ?>"
                        aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.compare'); ?>"
                    >
                        <span class="icon-compare cursor-pointer text-2xl"></span>
                    </a>
                <?php endif; ?>

                <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after'); ?>


                

                <!-- Tablet+ row: native <details> profile menu (avoid Vue dropdown mount order) -->
                <div class="max-md:hidden">
                    <?php
                        $_mobProfileFlyoutAlign = core()->getCurrentLocale()->direction === 'ltr'
                            ? 'right-0 left-auto'
                            : 'left-0 right-auto';
                    ?>

                    <details class="relative">
                        <summary
                            class="block cursor-pointer list-none [&::-webkit-details-marker]:hidden"
                            aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.account'); ?>"
                        >
                            <span
                                class="icon-users cursor-pointer text-2xl"
                                role="presentation"
                            ></span>
                        </summary>

                        <div
                            class="absolute <?php echo e($_mobProfileFlyoutAlign); ?> top-full z-[100] mt-1.5 w-max min-w-[260px] rounded-lg bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)]"
                            role="dialog"
                            aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.account'); ?>"
                        >
                            <?php if(auth()->guard('customer')->guest()): ?>
                                <div class="p-5">
                                    <div class="grid gap-2.5">
                                        <p class="font-dmserif text-xl">
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.welcome-guest'); ?>
                                        </p>

                                        <p class="text-sm">
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.dropdown-text'); ?>
                                        </p>
                                    </div>

                                    <p class="mt-3 w-full border border-zinc-200"></p>

                                    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before'); ?>


                                    <div class="mt-6 flex gap-4">
                                        <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before'); ?>


                                        <a
                                            href="<?php echo e(route('shop.customer.session.index')); ?>"
                                            class="primary-button mx-auto m-0 block w-max cursor-pointer rounded-2xl px-7 py-4 text-center text-base font-medium text-white ltr:ml-0 rtl:mr-0"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.sign-in'); ?>
                                        </a>

                                        <a
                                            href="<?php echo e(route('shop.customers.register.index')); ?>"
                                            class="secondary-button mx-auto m-0 block w-max cursor-pointer rounded-2xl border-2 border-navyBlue bg-white px-7 py-3.5 text-center text-base font-medium text-navyBlue ltr:ml-0 rtl:mr-0"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.sign-up'); ?>
                                        </a>

                                        <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after'); ?>

                                    </div>

                                    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after'); ?>

                                </div>
                            <?php endif; ?>

                            <?php if(auth()->guard('customer')->check()): ?>
                                <div class="overflow-hidden rounded-lg">
                                    <div class="grid gap-2.5 p-5 pb-0">
                                        <p class="font-dmserif text-xl">
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.welcome'); ?>
                                            <?php echo e($customerUser?->first_name); ?>

                                        </p>

                                        <p class="text-sm">
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.dropdown-text'); ?>
                                        </p>
                                    </div>

                                    <p class="mt-3 w-full border border-zinc-200"></p>

                                    <div class="mt-2.5 grid gap-1 pb-2.5">
                                        <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before'); ?>


                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="<?php echo e(route('shop.customers.account.profile.index')); ?>"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.profile'); ?>
                                        </a>

                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="<?php echo e(route('shop.customers.account.orders.index')); ?>"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.orders'); ?>
                                        </a>

                                        <?php if($showWishlist): ?>
                                            <a
                                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                                href="<?php echo e(route('shop.customers.account.wishlist.index')); ?>"
                                            >
                                                <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.wishlist'); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (isset($component)) { $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.form.index','data' => ['method' => 'DELETE','action' => ''.e(route('shop.customer.session.destroy')).'','id' => 'customerLogoutHeaderMobile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'DELETE','action' => ''.e(route('shop.customer.session.destroy')).'','id' => 'customerLogoutHeaderMobile']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $attributes = $__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__attributesOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc)): ?>
<?php $component = $__componentOriginal4d3fcee3e355fb6c8889181b04f357cc; ?>
<?php unset($__componentOriginal4d3fcee3e355fb6c8889181b04f357cc); ?>
<?php endif; ?>

                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="<?php echo e(route('shop.customer.session.destroy')); ?>"
                                            onclick="event.preventDefault(); document.getElementById('customerLogoutHeaderMobile').submit();"
                                        >
                                            <?php echo app('translator')->get('shop::app.components.layouts.header.mobile.logout'); ?>
                                        </a>

                                        <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after'); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </details>
                </div>

                <!-- For Medium and small screen -->
                <div class="md:hidden">
                    <?php if(auth()->guard('customer')->guest()): ?>
                        <a
                            href="<?php echo e(route('shop.customer.session.index')); ?>"
                            aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.account'); ?>"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    <?php endif; ?>

                    <?php if(auth()->guard('customer')->check()): ?>
                        <a
                            href="<?php echo e(route('shop.customers.account.index')); ?>"
                            aria-label="<?php echo app('translator')->get('shop::app.components.layouts.header.mobile.account'); ?>"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.search.before'); ?>


    <?php if (isset($component)) { $__componentOriginal426648d992f6146c7b258a5bd367c733 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal426648d992f6146c7b258a5bd367c733 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.search-smart','data' => ['context' => 'mobile','inputId' => 'organic-search-mobile','wrapperClass' => 'relative w-full','formClass' => 'flex w-full items-center','inputClass' => 'block w-full rounded-xl border border-[\'#E3E3E3\'] px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.search-smart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['context' => 'mobile','input-id' => 'organic-search-mobile','wrapper-class' => 'relative w-full','form-class' => 'flex w-full items-center','input-class' => 'block w-full rounded-xl border border-[\'#E3E3E3\'] px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs']); ?>
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

    <?php echo view_render_event('bagisto.shop.components.layouts.header.mobile.search.after'); ?>

</div>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/mobile/index.blade.php ENDPATH**/ ?>