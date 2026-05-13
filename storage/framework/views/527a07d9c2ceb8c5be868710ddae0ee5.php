<?php
    $adminUser = auth()->guard('superadmin')->user();
    $adminReferralCode = app(\Webkul\SuperAdmin\Services\AdminReferralCodeService::class)->ensureExists();
    $tikStoreGlobalUrl = route('shop.tik-store.index', array_filter([
        'global' => '1',
        'ref' => $adminReferralCode,
    ]));
    $myShopUrl = route('shop.tiktok-store.index');
    $initial = $adminUser
        ? mb_strtoupper(mb_substr(trim($adminUser->name), 0, 1, 'UTF-8'))
        : '';
?>

<?php if($adminUser): ?>
    <div class="mx-3 mb-4 shrink-0 group-[.sidebar-collapsed]/container:mx-2">
        <div
            class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-950"
        >
            <div
                class="flex gap-3 group-[.sidebar-collapsed]/container:flex-col group-[.sidebar-collapsed]/container:items-center"
            >
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-100 text-lg font-bold text-gray-900 dark:bg-indigo-900/50 dark:text-indigo-100 group-[.sidebar-collapsed]/container:h-10 group-[.sidebar-collapsed]/container:w-10 group-[.sidebar-collapsed]/container:text-base"
                    aria-hidden="true"
                >
                    <?php echo e($initial); ?>

                </div>

                <div class="min-w-0 flex-1 text-left group-[.sidebar-collapsed]/container:hidden">
                    <p class="truncate font-bold text-gray-900 dark:text-white">
                        <?php echo e($adminUser->name); ?>

                    </p>
                    <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                        <?php echo app('translator')->get('superadmin::app.components.layouts.sidebar.referral-code'); ?>:
                        <span
                            class="inline-block rounded bg-sky-100 px-1.5 py-0.5 font-mono text-[0.7rem] font-semibold text-gray-800 dark:bg-sky-900/40 dark:text-sky-100"
                        ><?php echo e($adminReferralCode); ?></span>
                    </p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400" title="<?php echo e($adminUser->email); ?>">
                        <?php echo e($adminUser->email); ?>

                    </p>
                </div>
            </div>

            <div class="mt-3 flex flex-col gap-2">
                <br>
                <a
                    href="<?php echo e(route('shop.tiktok-store.index')); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-center text-sm font-semibold text-gray-800 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800 group-[.sidebar-collapsed]/container:px-2 group-[.sidebar-collapsed]/container:py-2"
                    title="<?php echo app('translator')->get('superadmin::app.components.layouts.sidebar.my-shop'); ?>"
                    style="
    background-color: chartreuse;
    color: white;
">
                    <span class="group-[.sidebar-collapsed]/container:hidden">
                        <?php echo app('translator')->get('superadmin::app.components.layouts.sidebar.my-shop'); ?>
                    </span>
                    <span class="icon-store hidden text-xl text-gray-700 group-[.sidebar-collapsed]/container:inline-block dark:text-gray-200"></span>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/layouts/sidebar/account-strip.blade.php ENDPATH**/ ?>