<?php
    $admin = auth()->guard('superadmin')->user();
?>

<header class="sticky top-0 z-[10001] flex items-center justify-between border-b bg-white px-2 py-2 dark:border-gray-800 dark:bg-gray-900 sm:px-4 sm:py-2.5">
    <div class="flex items-center gap-1 sm:gap-1.5">
        <!-- Hamburger Menu -->
        <i
            class="icon-menu cursor-pointer rounded-md p-1.5 text-xl hover:bg-gray-100 dark:hover:bg-gray-950 lg:hidden sm:text-2xl"
            onclick="openSuperAdminMobileSidebar()"
        >
        </i>

        <!-- Logo -->
        <a href="<?php echo e(route('superadmin.dashboard.index')); ?>" class="flex-shrink-0">
            <?php if($logo = core()->getConfigData('general.design.admin_logo.logo_image')): ?>
                <img
                    class="h-8 w-auto sm:h-10"
                    src="<?php echo e(Storage::url($logo)); ?>"
                    alt="<?php echo e(config('app.name')); ?>"
                />
            <?php else: ?>
                <img
                    src="<?php echo e(request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg')); ?>"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image"
                    alt="<?php echo e(config('app.name')); ?>"
                />
            <?php endif; ?>
        </a>

        <!-- Mega Search Bar Vue Component -->
        <v-mega-search class="hidden sm:block">
            <div class="relative flex w-[200px] items-center sm:w-[300px] md:w-[400px] lg:w-[525px] xl:max-w-[525px] ltr:ml-2 rtl:mr-2 sm:ltr:ml-2.5 sm:rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-xl ltr:left-2 rtl:right-2 sm:text-2xl sm:ltr:left-3 sm:rtl:right-3"></i>

                <input 
                    type="text"
                    id="superadmin-header-mega-search-fallback"
                    name="header_mega_search_fallback"
                    class="block w-full rounded-lg border bg-white px-8 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 sm:px-10 sm:text-base"
                    placeholder="<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.title'); ?>"
                    autocomplete="off"
                >
            </div>
        </v-mega-search>
    </div>

    <div class="flex items-center gap-1 sm:gap-2.5">
        <!-- Dark mode Switcher -->
        <v-dark>
            <div class="flex">
                <span
                    class="<?php echo e(request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark'); ?> cursor-pointer rounded-md p-1.5 text-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 sm:text-2xl"
                ></span>
            </div>
        </v-dark>

        <!-- Visit TikStore (same as sidebar) -->
        <?php
            $headerTikStoreUrl = route('shop.tik-store.index', array_filter([
                'global' => '1',
                'ref' => app(\Webkul\SuperAdmin\Services\AdminReferralCodeService::class)->get(),
            ]));
        ?>
        <a
            href="<?php echo e($headerTikStoreUrl); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hidden sm:flex"
        >
            <span
                class="icon-store cursor-pointer rounded-md p-1.5 text-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 sm:text-2xl"
                title="<?php echo app('translator')->get('superadmin::app.components.layouts.sidebar.visit-tik-store'); ?>"
            >
            </span>
        </a>

       <!-- Notification Component -->
        <v-notifications <?php echo e($attributes); ?>>
            <span class="relative flex">
                <span 
                    class="icon-notification cursor-pointer rounded-md p-1.5 text-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 sm:text-2xl" 
                    title="<?php echo app('translator')->get('superadmin::app.components.layouts.header.notifications'); ?>"
                >
                </span>
            </span>
        </v-notifications>

        <!-- Admin profile -->
        <?php if($admin): ?>
        <?php if (isset($component)) { $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.index','data' => ['position' => 'bottom-'.e(core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-'.e(core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left').'']); ?>
             <?php $__env->slot('toggle', null, []); ?> 
                <?php if($admin->image): ?>
                    <button class="flex h-8 w-8 cursor-pointer overflow-hidden rounded-full hover:opacity-80 focus:opacity-80 sm:h-9 sm:w-9">
                        <img
                            src="<?php echo e($admin->image_url); ?>"
                            class="h-full w-full object-cover"
                        />
                    </button>
                <?php else: ?>
                    <button class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-blue-400 text-xs font-semibold leading-6 text-white transition-all hover:bg-blue-500 focus:bg-blue-500 sm:h-9 sm:w-9 sm:text-sm">
                        <?php echo e(substr($admin->name, 0, 1)); ?>

                    </button>
                <?php endif; ?>
             <?php $__env->endSlot(); ?>

            <!-- Admin Dropdown -->
             <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
                <div class="grid gap-1 pb-2.5">
                    <a
                        class="cursor-pointer px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 sm:px-5 sm:text-base"
                        href="<?php echo e(route('superadmin.account.edit')); ?>"
                    >
                        <?php echo app('translator')->get('superadmin::app.components.layouts.header.my-account'); ?>
                    </a>

                    <!--Admin logout-->
                    <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['method' => 'DELETE','action' => ''.e(route('superadmin.session.destroy')).'','id' => 'adminLogout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'DELETE','action' => ''.e(route('superadmin.session.destroy')).'','id' => 'adminLogout']); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $attributes = $__attributesOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $component = $__componentOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__componentOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>

                    <a
                        class="cursor-pointer px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 sm:px-5 sm:text-base"
                        href="<?php echo e(route('superadmin.session.destroy')); ?>"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        <?php echo app('translator')->get('superadmin::app.components.layouts.header.logout'); ?>
                    </a>
                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $attributes = $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $component = $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</header>

<!-- HTML Mobile Sidebar Fallback -->
<div
    id="superadmin-mobile-sidebar-overlay"
    onclick="closeSuperAdminMobileSidebar()"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:10050;"
></div>

<aside
    id="superadmin-mobile-sidebar-panel"
    style="display:none; position:fixed; top:0; left:0; width:270px; max-width:85vw; height:100vh; background:#fff; z-index:10051; overflow-y:auto; box-shadow:0 10px 30px rgba(0,0,0,0.2);"
>
    <div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #e5e7eb; padding:12px; display:flex; align-items:center; justify-content:space-between;">
        <strong style="font-size:14px;">Menu</strong>
        <button type="button" onclick="closeSuperAdminMobileSidebar()" style="border:none; background:transparent; font-size:24px; line-height:1; cursor:pointer;">&times;</button>
    </div>

    <nav style="padding:10px;">
        <?php $__currentLoopData = menu()->getItems('superadmin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a
                href="<?php echo e($menuItem->getUrl()); ?>"
                style="display:flex; align-items:center; gap:8px; padding:10px; margin-bottom:6px; border-radius:8px; text-decoration:none; color:#374151; background:<?php echo e($menuItem->isActive() ? '#eff6ff' : 'transparent'); ?>;"
                onclick="closeSuperAdminMobileSidebar()"
            >
                <span class="<?php echo e($menuItem->getIcon()); ?>" style="font-size:20px;"></span>
                <span style="font-size:14px; font-weight:600;"><?php echo e($menuItem->getName()); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>
</aside>

<!-- Menu Sidebar Drawer -->
<?php if (isset($component)) { $__componentOriginal190969edc8078f487b25ed9c8ee6502d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal190969edc8078f487b25ed9c8ee6502d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.drawer.index','data' => ['position' => 'left','width' => '270px','ref' => 'sidebarMenuDrawer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'left','width' => '270px','ref' => 'sidebarMenuDrawer']); ?>
    <!-- Drawer Header -->
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <?php if($logo = core()->getConfigData('general.design.admin_logo.logo_image')): ?>
                <img
                    src="<?php echo e(Storage::url($logo)); ?>"
                    class="h-8 w-auto sm:h-10"
                    alt="<?php echo e(config('app.name')); ?>"
                />
            <?php else: ?>
                <img
                    src="<?php echo e(request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg')); ?>"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image"
                    alt="<?php echo e(config('app.name')); ?>"
                />
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <!-- Drawer Content -->
     <?php $__env->slot('content', null, ['class' => 'p-3 sm:p-4']); ?> 
        <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto">
            <?php echo $__env->make('superadmin::components.layouts.sidebar.account-strip', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <nav class="grid w-full gap-1.5 sm:gap-2">
                <!-- Navigation Menu -->
                <?php $__currentLoopData = menu()->getItems('superadmin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group/item relative">
                        <a
                            href="<?php echo e($menuItem->getUrl()); ?>"
                            class="flex items-center gap-2 p-1.5 cursor-pointer hover:rounded-lg <?php echo e($menuItem->isActive() == 'active' ? 'bg-blue-600 rounded-lg' : ' hover:bg-gray-100 hover:dark:bg-gray-950'); ?> peer sm:gap-2.5"
                        >
                            <span class="<?php echo e($menuItem->getIcon()); ?> text-xl <?php echo e($menuItem->isActive() ? 'text-white' : ''); ?> sm:text-2xl"></span>
                            
                            <p class="font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap text-sm group-[.sidebar-collapsed]/container:hidden <?php echo e($menuItem->isActive() ? 'text-white' : ''); ?> sm:text-base">
                                <?php echo e($menuItem->getName()); ?>

                            </p>
                        </a>

                        <?php if($menuItem->haveChildren()): ?>
                            <div class="<?php echo e($menuItem->isActive() ? ' !grid bg-gray-100 dark:bg-gray-950' : ''); ?> hidden min-w-[180px] ltr:pl-8 rtl:pr-8 pb-2 rounded-b-lg z-[100] sm:ltr:pl-10 sm:rtl:pr-10">
                                <?php $__currentLoopData = $menuItem->getChildren(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subMenuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a
                                        href="<?php echo e($subMenuItem->getUrl()); ?>"
                                        class="text-xs text-<?php echo e($subMenuItem->isActive() ? 'blue':'gray'); ?>-600 dark:text-<?php echo e($subMenuItem->isActive() ? 'blue':'gray'); ?>-300 whitespace-nowrap py-1 group-[.sidebar-collapsed]/container:px-4 group-[.sidebar-collapsed]/container:py-2 group-[.inactive]/item:px-4 group-[.inactive]/item:py-2 hover:text-blue-600 dark:hover:bg-gray-950 sm:text-sm sm:group-[.sidebar-collapsed]/container:px-5 sm:group-[.sidebar-collapsed]/container:py-2.5 sm:group-[.inactive]/item:px-5 sm:group-[.inactive]/item:py-2.5"
                                    >
                                        <?php echo e($subMenuItem->getName()); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </div>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal190969edc8078f487b25ed9c8ee6502d)): ?>
<?php $attributes = $__attributesOriginal190969edc8078f487b25ed9c8ee6502d; ?>
<?php unset($__attributesOriginal190969edc8078f487b25ed9c8ee6502d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal190969edc8078f487b25ed9c8ee6502d)): ?>
<?php $component = $__componentOriginal190969edc8078f487b25ed9c8ee6502d; ?>
<?php unset($__componentOriginal190969edc8078f487b25ed9c8ee6502d); ?>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('e6170cf8-372e-430b-8350-aab1b28f5e65')): $__env->markAsRenderedOnce('e6170cf8-372e-430b-8350-aab1b28f5e65');
$__env->startPush('scripts'); ?>
    <script>
        function openSuperAdminMobileSidebar() {
            var overlay = document.getElementById('superadmin-mobile-sidebar-overlay');
            var panel = document.getElementById('superadmin-mobile-sidebar-panel');

            if (overlay && panel) {
                overlay.style.display = 'block';
                panel.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSuperAdminMobileSidebar() {
            var overlay = document.getElementById('superadmin-mobile-sidebar-overlay');
            var panel = document.getElementById('superadmin-mobile-sidebar-panel');

            if (overlay && panel) {
                overlay.style.display = 'none';
                panel.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    </script>

    <script
        type="text/x-template"
        id="v-mega-search-template"
    >
        <div class="relative flex w-[200px] items-center sm:w-[300px] md:w-[400px] lg:w-[525px] xl:max-w-[525px] ltr:ml-2 rtl:mr-2 sm:ltr:ml-2.5 sm:rtl:mr-2.5">
            <i class="icon-search absolute top-1.5 flex items-center text-xl ltr:left-2 rtl:right-2 sm:text-2xl sm:ltr:left-3 sm:rtl:right-3"></i>

            <input 
                type="text"
                id="superadmin-header-mega-search"
                name="header_mega_search"
                class="peer block w-full rounded-lg border bg-white px-8 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 sm:px-10 sm:text-base"
                :class="{'border-gray-400': isDropdownOpen}"
                placeholder="<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.title'); ?>"
                autocomplete="off"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-8 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] dark:border-gray-800 dark:bg-gray-900 sm:top-10"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b text-xs text-gray-600 dark:border-gray-800 dark:text-gray-300 sm:text-sm">
                    <div
                        class="cursor-pointer p-2 hover:bg-gray-100 dark:hover:bg-gray-950 sm:p-4"
                        :class="{ 'border-b-2 border-blue-600': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
                    >
                        {{ tab.title }}
                    </div>
                </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginalaf30046854dd3ed21ec16c0c21825e15 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf30046854dd3ed21ec16c0c21825e15 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.header.mega-search.products','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.header.mega-search.products'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf30046854dd3ed21ec16c0c21825e15)): ?>
<?php $attributes = $__attributesOriginalaf30046854dd3ed21ec16c0c21825e15; ?>
<?php unset($__attributesOriginalaf30046854dd3ed21ec16c0c21825e15); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf30046854dd3ed21ec16c0c21825e15)): ?>
<?php $component = $__componentOriginalaf30046854dd3ed21ec16c0c21825e15; ?>
<?php unset($__componentOriginalaf30046854dd3ed21ec16c0c21825e15); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'<?php echo e(route('superadmin.catalog.products.edit', ':id')); ?>'.replace(':id', product.id)"
                                class="flex cursor-pointer justify-between gap-2 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950 sm:gap-2.5 sm:p-4"
                                v-for="product in searchedResults.products.data"
                            >
                                <!-- Left Information -->
                                <div class="flex gap-2 sm:gap-2.5">
                                    <!-- Image -->
                                    <div
                                        class="relative h-10 max-h-10 w-full max-w-10 overflow-hidden rounded sm:h-[60px] sm:max-h-[60px] sm:max-w-[60px]"
                                        :class="{'overflow-hidden rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert': ! product.images.length}"
                                    >
                                        <template v-if="! product.images.length">
                                            <img src="<?php echo e(bagisto_asset('images/product-placeholders/front.svg')); ?>" class="h-full w-full object-cover">
                                        
                                            <p class="absolute bottom-0.5 w-full text-center text-[4px] font-semibold text-gray-400 sm:bottom-1.5 sm:text-[6px]">
                                                <?php echo app('translator')->get('superadmin::app.catalog.products.edit.types.grouped.image-placeholder'); ?>
                                            </p>
                                        </template>

                                        <template v-else>
                                            <img :src="product.images[0].url" class="h-full w-full object-cover">
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid place-content-start gap-1 sm:gap-1.5">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                            {{ product.name }}
                                        </p>

                                        <p class="text-xs text-gray-500 sm:text-sm">
                                            {{ "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.sku'); ?>".replace(':sku', product.sku) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Information -->
                                <div class="grid place-content-center gap-1 text-right">
                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                        {{ product.formatted_price }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'<?php echo e(route('superadmin.catalog.products.index')); ?>?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.products.data.length"
                            >
                                {{ "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-matching-products'); ?>".replace(':query', searchTerm).replace(':count', searchedResults.products.meta.total) }}
                            </a>

                            <a
                                href="<?php echo e(route('superadmin.catalog.products.index')); ?>"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                <?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-products'); ?>
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'orders'">
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginal6d8ef351356077c20994ea94c8cdd98d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d8ef351356077c20994ea94c8cdd98d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.header.mega-search.orders','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.header.mega-search.orders'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d8ef351356077c20994ea94c8cdd98d)): ?>
<?php $attributes = $__attributesOriginal6d8ef351356077c20994ea94c8cdd98d; ?>
<?php unset($__attributesOriginal6d8ef351356077c20994ea94c8cdd98d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d8ef351356077c20994ea94c8cdd98d)): ?>
<?php $component = $__componentOriginal6d8ef351356077c20994ea94c8cdd98d; ?>
<?php unset($__componentOriginal6d8ef351356077c20994ea94c8cdd98d); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'<?php echo e(route('superadmin.sales.orders.view', ':id')); ?>'.replace(':id', order.id)"
                                class="grid cursor-pointer place-content-start gap-1 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950 sm:gap-1.5 sm:p-4"
                                v-for="order in searchedResults.orders.data"
                            >
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                    #{{ order.increment_id }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-300 sm:text-sm">
                                    {{ order.formatted_created_at + ', ' + order.status_label + ', ' + order.customer_full_name }}
                                </p>
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'<?php echo e(route('superadmin.sales.orders.customers.index')); ?>?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.orders.data.length"
                            >
                                {{ "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-matching-orders'); ?>".replace(':query', searchTerm).replace(':count', searchedResults.orders.total) }}
                            </a>

                            <a
                                href="<?php echo e(route('superadmin.sales.orders.customers.index')); ?>"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                <?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-orders'); ?>
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'categories'">
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginal4514e5411fa441cfb1be951bcd1ea628 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4514e5411fa441cfb1be951bcd1ea628 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.header.mega-search.categories','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.header.mega-search.categories'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4514e5411fa441cfb1be951bcd1ea628)): ?>
<?php $attributes = $__attributesOriginal4514e5411fa441cfb1be951bcd1ea628; ?>
<?php unset($__attributesOriginal4514e5411fa441cfb1be951bcd1ea628); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4514e5411fa441cfb1be951bcd1ea628)): ?>
<?php $component = $__componentOriginal4514e5411fa441cfb1be951bcd1ea628; ?>
<?php unset($__componentOriginal4514e5411fa441cfb1be951bcd1ea628); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'<?php echo e(route('superadmin.catalog.categories.edit', ':id')); ?>'.replace(':id', category.id)"
                                class="cursor-pointer border-b p-3 text-xs font-semibold text-gray-600 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950 sm:p-4 sm:text-sm"
                                v-for="category in searchedResults.categories.data"
                            >
                                {{ category.name }}
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'<?php echo e(route('superadmin.catalog.categories.index')); ?>?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.categories.data.length"
                            >
                                {{ "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-matching-categories'); ?>".replace(':query', searchTerm).replace(':count', searchedResults.categories.total) }}
                            </a>

                            <a
                                href="<?php echo e(route('superadmin.catalog.categories.index')); ?>"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                <?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-categories'); ?>
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'customers'">
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginalc9d226fb7bcdfb42f09b27b7ebd0d353 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9d226fb7bcdfb42f09b27b7ebd0d353 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.header.mega-search.customers','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.header.mega-search.customers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9d226fb7bcdfb42f09b27b7ebd0d353)): ?>
<?php $attributes = $__attributesOriginalc9d226fb7bcdfb42f09b27b7ebd0d353; ?>
<?php unset($__attributesOriginalc9d226fb7bcdfb42f09b27b7ebd0d353); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9d226fb7bcdfb42f09b27b7ebd0d353)): ?>
<?php $component = $__componentOriginalc9d226fb7bcdfb42f09b27b7ebd0d353; ?>
<?php unset($__componentOriginalc9d226fb7bcdfb42f09b27b7ebd0d353); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'<?php echo e(route('superadmin.customers.customers.view', ':id')); ?>'.replace(':id', customer.id)"
                                class="grid cursor-pointer place-content-start gap-1 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950 sm:gap-1.5 sm:p-4"
                                v-for="customer in searchedResults.customers.data"
                            >
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                    {{ customer.first_name + ' ' + customer.last_name }}
                                </p>

                                <p class="text-xs text-gray-500 sm:text-sm">
                                    {{ customer.email }}
                                </p>
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'<?php echo e(route('superadmin.customers.customers.index')); ?>?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.customers.data.length"
                            >
                                {{ "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-matching-customers'); ?>".replace(':query', searchTerm).replace(':count', searchedResults.customers.total) }}
                            </a>

                            <a
                                href="<?php echo e(route('superadmin.customers.customers.index')); ?>"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                <?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.explore-all-customers'); ?>
                            </a>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        window.app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'products',

                    isDropdownOpen: false,

                    tabs: {
                        products: {
                            key: 'products',
                            title: "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.products'); ?>",
                            is_active: true,
                            endpoint: "<?php echo e(route('superadmin.catalog.products.search')); ?>"
                        },
                        
                        orders: {
                            key: 'orders',
                            title: "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.orders'); ?>",
                            endpoint: "<?php echo e(route('superadmin.sales.orders.search')); ?>"
                        },
                        
                        categories: {
                            key: 'categories',
                            title: "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.categories'); ?>",
                            endpoint: "<?php echo e(route('superadmin.catalog.categories.search')); ?>"
                        },
                        
                        customers: {
                            key: 'customers',
                            title: "<?php echo app('translator')->get('superadmin::app.components.layouts.header.mega-search.customers'); ?>",
                            endpoint: "<?php echo e(route('superadmin.customers.customers.search')); ?>"
                        }
                    },

                    isLoading: false,

                    searchTerm: '',

                    searchedResults: {
                        products: [],
                        orders: [],
                        categories: [],
                        customers: []
                    },
                }
            },

            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search()
                }
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            methods: {
                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedResults[this.activeTab] = [];

                        this.isDropdownOpen = false;

                        return;
                    }

                    this.isDropdownOpen = true;

                    let self = this;

                    this.isLoading = true;
                    
                    this.$axios.get(this.tabs[this.activeTab].endpoint, {
                            params: {query: this.searchTerm}
                        })
                        .then(function(response) {
                            self.searchedResults[self.activeTab] = response.data;

                            self.isLoading = false;
                        })
                        .catch(function (error) {
                        })
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target)) {
                        this.isDropdownOpen = false;
                    }
                },
            }
        });
    </script>

    <script
        type="text/x-template"
        id="v-notifications-template"
    >
        <?php if (isset($component)) { $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.index','data' => ['position' => 'bottom-'.e(core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-'.e(core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left').'']); ?>
            <!-- Notification Toggle -->
             <?php $__env->slot('toggle', null, []); ?> 
                <span class="relative flex">
                    <span
                        class="icon-notification text-red cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950" 
                        title="<?php echo app('translator')->get('superadmin::app.components.layouts.header.notifications'); ?>"
                    >
                    </span>
                
                    <span
                        class="absolute -top-2 flex h-5 min-w-5 cursor-pointer items-center justify-center rounded-full bg-blue-600 p-1.5 text-[10px] font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5"
                        v-if="totalUnRead"
                    >
                        {{ totalUnRead }}
                    </span>
                </span>
             <?php $__env->endSlot(); ?>

            <!-- Notification Content -->
             <?php $__env->slot('content', null, ['class' => 'min-w-[250px] max-w-[250px] !p-0']); ?> 
                <!-- Header -->
                <div class="border-b p-3 text-base font-semibold text-gray-600 dark:border-gray-800 dark:text-gray-300">
                    <?php echo app('translator')->get('superadmin::app.notifications.title', ['read' => 0]); ?>
                </div>

                <!-- Content -->
                <div class="grid">
                    <a
                        class="flex items-start gap-1.5 border-b p-3 last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                        v-for="notification in notifications"
                        :href="'<?php echo e(route('superadmin.notification.viewed_notification', ':orderId')); ?>'.replace(':orderId', notification.order_id)"
                    >
                        <!-- Notification Icon -->
                        <span
                            v-if="notification.order.status in notificationStatusIcon"
                            class="h-fit"
                            :class="notificationStatusIcon[notification.order.status]"
                        >
                        </span>

                        <div class="grid">
                            <!-- Order Id & Status -->
                            <p class="text-gray-800 dark:text-white">
                                #{{ notification.order.id }}
                                {{ orderTypeMessages[notification.order.status] }}
                            </p>

                            <!-- Created Date In humand Readable Format -->
                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                {{ notification.order.datetime }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="flex h-[47px] justify-between gap-1.5 border-t px-6 py-4 dark:border-gray-800">
                    <a
                        href="<?php echo e(route('superadmin.notification.index')); ?>"
                        class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                    >
                        <?php echo app('translator')->get('superadmin::app.notifications.view-all'); ?>
                    </a>

                    <a
                        class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                        v-if="notifications?.length"
                        @click="readAll()"
                    >
                        <?php echo app('translator')->get('superadmin::app.notifications.read-all'); ?>
                    </a>
                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $attributes = $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $component = $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
    </script>

    <script type="module">
        window.app.component('v-notifications', {
            template: '#v-notifications-template',

                props: [
                    'getReadAllUrl',
                    'readAllTitle',
                ],

                data() {
                    return {
                        notifications: [],

                        ordertype: {
                            pending: {
                                icon: 'icon-information',
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.pending-payment'); ?>"
                            },

                            processing: {
                                icon: 'icon-processing',
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.processing'); ?>",
                            },

                            canceled: {
                                icon: 'icon-cancel-1',
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.canceled'); ?>"
                            },

                            completed: {
                                icon: 'icon-done',
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.completed'); ?>"
                            },

                            closed: {
                                icon: 'icon-cancel-1',
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.closed'); ?>"
                            },

                            pending_payment: {
                                icon: "icon-information",
                                message: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.pending-payment'); ?>"
                            },
                        },

                        totalUnRead: 0,

                        orderTypeMessages: {
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_PENDING); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.pending'); ?>",
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_CANCELED); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.canceled'); ?>",
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_CLOSED); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.closed'); ?>",
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_COMPLETED); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.completed'); ?>",
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_PROCESSING); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.processing'); ?>",
                            <?php echo e(\Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT); ?>: "<?php echo app('translator')->get('superadmin::app.notifications.order-status-messages.pending-payment'); ?>",
                        }
                    }
                },

                computed: {
                    notificationStatusIcon() {
                        return {
                            pending: 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600 dark:!text-amber-600',
                            closed: 'icon-repeat rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
                            completed: 'icon-done rounded-full bg-blue-100 text-2xl text-blue-600 dark:!text-blue-600',
                            canceled: 'icon-cancel-1 rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
                            processing: 'icon-sort-right rounded-full bg-green-100 text-2xl text-green-600 dark:!text-green-600',
                        };
                    },
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    getNotification() {
                        this.$axios.get('<?php echo e(route('superadmin.notification.get_notification')); ?>', {
                                params: {
                                    limit: 5,
                                    read: 0
                                }
                            })
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead =   response.data.total_unread;
                            })
                            .catch(error => console.log(error))
                    },

                    readAll() {
                        this.$axios.post('<?php echo e(route('superadmin.notification.read_all')); ?>')
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead = response.data.total_unread;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.success_message });
                        })
                        .catch((error) => {});
                },
            },
        });
    </script>

    <script
        type="text/x-template"
        id="v-dark-template"
    >
        <div class="flex">
            <span
                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <script type="module">
        window.app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: <?php echo e(request()->cookie('dark_mode') ?? 0); ?>,

                    logo: "<?php echo e(bagisto_asset('images/logo.svg')); ?>",

                    dark_logo: "<?php echo e(bagisto_asset('images/dark-logo.svg')); ?>",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    if (this.isDarkMode) {
                        this.$emitter.emit('change-theme', 'dark');

                        document.getElementById('logo-image').src = this.dark_logo;
                    } else {
                        this.$emitter.emit('change-theme', 'light');

                        document.getElementById('logo-image').src = this.logo;
                    }
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/layouts/header/index.blade.php ENDPATH**/ ?>