<div
    class="fixed top-[62px] bottom-0 z-[1000] flex min-h-0 w-[270px] flex-col overflow-hidden bg-white pt-4 shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)] transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] dark:bg-gray-900 max-lg:hidden"
    style="position: fixed; top: 62px; bottom: 0; left: 0; height: auto; min-height: 0; z-index: 1000;"
>
    <?php
        /**
         * Seller sidebar: TikTok-style menu (reference layout).
         */
        $allowedMenuKeys = config('seller-panel.sidebar_allowed_menu_keys', []);

        $sellerUser = auth()->guard('admin')->user();
        $shopOrderBadgeCount = 0;
        if ($sellerUser) {
            $q = \Webkul\Sales\Models\Order::query()->where('seller_id', $sellerUser->id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'seller_approval_status')) {
                $q->where('seller_approval_status', 'approved');
            }
            $shopOrderBadgeCount = $q->count();
        }

        $shopLogoUrl = null;
        if ($sellerUser && \Illuminate\Support\Facades\Schema::hasTable('seller_applications')) {
            $logoPath = \Illuminate\Support\Facades\DB::table('seller_applications')
                ->where('seller_id', $sellerUser->id)
                ->orderByDesc('id')
                ->value('shop_logo');
            if (! empty($logoPath)) {
                $shopLogoUrl = \Illuminate\Support\Facades\Storage::url($logoPath);
            }
        }
        if (empty($shopLogoUrl) && $sellerUser && $sellerUser->image_url) {
            $shopLogoUrl = $sellerUser->image_url;
        }

        $sellerLevelLabel = null;
        if ($sellerUser && \Illuminate\Support\Facades\Schema::hasColumn('seller', 'seller_level')) {
            $sellerLevelLabel = \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($sellerUser->seller_level);
        }
    ?>

    <?php if($sellerUser): ?>
        <div class="mx-4 mb-4 shrink-0 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-950">
            <div class="flex gap-3">
                <a
                    href="<?php echo e(route('admin.seller.visit-store')); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-lg font-bold text-blue-700 ring-1 ring-gray-200 transition-opacity hover:opacity-90 dark:bg-blue-900 dark:text-blue-200 dark:ring-gray-700"
                    title="<?php echo app('translator')->get('admin::app.seller-panel.sidebar.visit-store'); ?>"
                >
                    <?php if($shopLogoUrl): ?>
                        <img
                            src="<?php echo e($shopLogoUrl); ?>"
                            alt=""
                            class="h-full w-full object-cover"
                        />
                    <?php else: ?>
                        <span aria-hidden="true"><?php echo e(mb_substr($sellerUser->name, 0, 1)); ?></span>
                    <?php endif; ?>
                </a>

                <div class="min-w-0 flex-1">
                    <p class="truncate font-bold text-gray-900 dark:text-white">
                        <?php echo e($sellerUser->name); ?>

                    </p>
                    <?php if($sellerLevelLabel): ?>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <?php echo app('translator')->get('admin::app.seller-panel.sidebar.seller-level-line', ['level' => $sellerLevelLabel]); ?>
                        </p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <?php if(! empty($sellerUser->referral_code)): ?>
                            <?php echo app('translator')->get('admin::app.seller-panel.sidebar.referral-code'); ?>:
                            <span class="font-mono font-semibold text-gray-700 dark:text-gray-200"><?php echo e($sellerUser->referral_code); ?></span>
                        <?php else: ?>
                            ID <?php echo e($sellerUser->id); ?>

                        <?php endif; ?>
                    </p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                        <?php echo e($sellerUser->email); ?>

                    </p>
                </div>
            </div>

            <div class="mt-3 flex flex-col gap-2">
                <a
                    href="<?php echo e(route('admin.seller.visit-store')); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700"
                >
                    <?php echo app('translator')->get('admin::app.seller-panel.sidebar.visit-store'); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="journal-scroll min-h-0 flex-1 overflow-x-hidden overflow-y-auto pb-14">
        <nav class="grid w-full gap-2">
            <!-- Navigation Menu -->
            <?php $__currentLoopData = menu()->getItems('admin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(! in_array($menuItem->getKey(), $allowedMenuKeys, true)): ?>
                    <?php continue; ?>
                <?php endif; ?>
                <div
                    class="px-4 group/item <?php echo e($menuItem->isActive() ? 'active' : 'inactive'); ?>"
                    onmouseenter="adjustSubMenuPosition(event)"
                >
                    <a
                        href="<?php echo e($menuItem->getUrl()); ?>"
                        class="flex items-center justify-between gap-2 p-1.5 cursor-pointer hover:rounded-lg <?php echo e($menuItem->isActive() ? 'bg-blue-600 rounded-lg' : ' hover:bg-gray-100 hover:dark:bg-gray-950'); ?> peer"
                    >
                        <span class="flex min-w-0 items-center gap-2.5">
                            <span class="<?php echo e($menuItem->getIcon()); ?> text-2xl shrink-0 <?php echo e($menuItem->isActive() ? 'text-white' : ''); ?>"></span>

                            <p class="text-gray-600 dark:text-gray-300 font-semibold whitespace-nowrap group-[.sidebar-collapsed]/container:hidden <?php echo e($menuItem->isActive() ? 'text-white' : ''); ?>">
                                <?php echo e($menuItem->getName()); ?>

                            </p>
                        </span>

                        <?php if($menuItem->getKey() === 'sales' && $shopOrderBadgeCount > 0): ?>
                            <span class="shrink-0 rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white group-[.sidebar-collapsed]/container:hidden">
                                <?php echo e($shopOrderBadgeCount > 99 ? '99+' : $shopOrderBadgeCount); ?>

                            </span>
                        <?php endif; ?>
                    </a>

                    <?php if($menuItem->haveChildren()): ?>
                        <div class="<?php echo e($menuItem->isActive() ? '!grid bg-gray-100 dark:bg-gray-950' : ''); ?> hidden min-w-[180px] ltr:pl-10 rtl:pr-10 pb-2 rounded-b-lg z-[100] overflow-hidden group-[.sidebar-collapsed]/container:!hidden group-[.sidebar-collapsed]/container:fixed group-[.sidebar-collapsed]/container:ltr:!left-[70px] group-[.sidebar-collapsed]/container:rtl:!right-[70px] group-[.sidebar-collapsed]/container:p-[0] group-[.sidebar-collapsed]/container:bg-white dark:group-[.sidebar-collapsed]/container:bg-gray-900 group-[.sidebar-collapsed]/container:border group-[.sidebar-collapsed]/container:ltr:rounded-r-lg group-[.sidebar-collapsed]/container:rtl:rounded-l-lg group-[.sidebar-collapsed]/container:border-gray-300 group-[.sidebar-collapsed]/container:dark:border-gray-800 group-[.sidebar-collapsed]/container:rounded-none group-[.sidebar-collapsed]/container:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:group-hover/item:!grid group-[.inactive]/item:hidden group-[.inactive]/item:fixed group-[.inactive]/item:ltr:left-[270px] group-[.inactive]/item:rtl:right-[270px] group-[.inactive]/item:p-[0] group-[.inactive]/item:bg-white dark:group-[.inactive]/item:bg-gray-900 group-[.inactive]/item:border group-[.inactive]/item:ltr:rounded-r-lg group-[.inactive]/item:rtl:rounded-l-lg group-[.inactive]/item:border-gray-300 group-[.inactive]/item:dark:border-gray-800 group-[.inactive]/item:rounded-none group-[.inactive]/item:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.inactive]/item:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.inactive]/item:group-hover/item:!grid">
                        <?php $__currentLoopData = $menuItem->getChildren(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subMenuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(! in_array($subMenuItem->getKey(), $allowedMenuKeys, true)): ?>
                                <?php continue; ?>
                            <?php endif; ?>
                                <a
                                    href="<?php echo e($subMenuItem->getUrl()); ?>"
                                    class="text-sm text-<?php echo e($subMenuItem->isActive() ? 'blue':'gray'); ?>-600 dark:text-<?php echo e($subMenuItem->isActive() ? 'blue':'gray'); ?>-300 whitespace-nowrap py-1 group-[.sidebar-collapsed]/container:px-5 group-[.sidebar-collapsed]/container:py-2.5 group-[.inactive]/item:px-5 group-[.inactive]/item:py-2.5 hover:text-blue-600 dark:hover:bg-gray-950"
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

    <!-- Collapse menu -->
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

<?php if (! $__env->hasRenderedOnce('0143f504-ddc6-4e4e-8c3b-3e1f6e232061')): $__env->markAsRenderedOnce('0143f504-ddc6-4e4e-8c3b-3e1f6e232061');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-sidebar-collapse-template"
    >
        <div
            class="absolute bottom-0 left-0 right-0 w-full max-w-[270px] cursor-pointer border-t border-gray-200 bg-white px-4 transition-all duration-300 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
            style="position: absolute; left: 0; right: 0; bottom: 0;"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex items-center gap-2.5 p-1.5">
                <span
                    class="icon-collapse text-2xl transition-all"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: <?php echo e(request()->cookie('sidebar_collapsed') ?? 0); ?>,
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }
                    
                    return 0;
                },
            },
        });
    </script>

    <script>
        const adjustSubMenuPosition = (event) => {
            let menuContainer = event.currentTarget;

            let subMenuContainer = menuContainer.lastElementChild;

            if (subMenuContainer) {
                const menuTopOffset = menuContainer.getBoundingClientRect().top;

                const subMenuHeight = subMenuContainer.offsetHeight;

                const availableHeight = window.innerHeight - menuTopOffset;

                let subMenuTopOffset = menuTopOffset;

                if (subMenuHeight > availableHeight) {
                    subMenuTopOffset = menuTopOffset - (subMenuHeight - availableHeight);
                }

                subMenuContainer.style.top = `${subMenuTopOffset}px`;
            }
        };
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Admin\src/resources/views/components/layouts/sidebar/index.blade.php ENDPATH**/ ?>