<?php
    $allowedMenuKeys = config('seller-panel.sidebar_allowed_menu_keys', []);
    if (! is_array($allowedMenuKeys)) {
        $allowedMenuKeys = [];
    }
?>

<?php $__currentLoopData = menu()->getItems('admin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(! in_array($menuItem->getKey(), $allowedMenuKeys, true)): ?>
        <?php continue; ?>
    <?php endif; ?>

    <?php if($menuItem->haveChildren()): ?>
        <?php
            $visibleChildren = $menuItem->getChildren()->filter(function ($sub) use ($allowedMenuKeys) {
                return in_array($sub->getKey(), $allowedMenuKeys, true);
            });
        ?>
        <?php if($visibleChildren->isEmpty()): ?>
            <a
                href="<?php echo e($menuItem->getUrl()); ?>"
                class="<?php echo e($menuItem->isActive() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950' : 'text-gray-800 dark:text-gray-100'); ?> mb-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline hover:bg-gray-50 dark:hover:bg-gray-800"
                onclick="if (typeof closeAdminMobileSidebar === 'function') { closeAdminMobileSidebar(); }"
            >
                <span class="<?php echo e($menuItem->getIcon()); ?> shrink-0 text-xl"></span>
                <span><?php echo e($menuItem->getName()); ?></span>
            </a>
            <?php continue; ?>
        <?php endif; ?>

        <details
            class="mb-2 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
            <?php if($menuItem->isActive()): ?> open <?php endif; ?>
        >
            <summary
                class="flex cursor-pointer list-none items-center gap-2 px-3 py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-100 [&::-webkit-details-marker]:hidden"
            >
                <span class="<?php echo e($menuItem->getIcon()); ?> shrink-0 text-xl"></span>
                <span class="min-w-0 flex-1"><?php echo e($menuItem->getName()); ?></span>
                <span class="shrink-0 text-xs text-gray-500" aria-hidden="true">▾</span>
            </summary>

            <div class="flex flex-col gap-1 border-t border-gray-100 px-2 py-2 dark:border-gray-800">
                <?php $__currentLoopData = $visibleChildren; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subMenuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a
                        href="<?php echo e($subMenuItem->getUrl()); ?>"
                        class="<?php echo e($subMenuItem->isActive() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300' : 'text-gray-700 dark:text-gray-200'); ?> rounded-md px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800"
                        onclick="if (typeof closeAdminMobileSidebar === 'function') { closeAdminMobileSidebar(); }"
                    >
                        <?php echo e($subMenuItem->getName()); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </details>
    <?php else: ?>
        <a
            href="<?php echo e($menuItem->getUrl()); ?>"
            class="<?php echo e($menuItem->isActive() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950' : 'text-gray-800 dark:text-gray-100'); ?> mb-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline hover:bg-gray-50 dark:hover:bg-gray-800"
            onclick="if (typeof closeAdminMobileSidebar === 'function') { closeAdminMobileSidebar(); }"
        >
            <span class="<?php echo e($menuItem->getIcon()); ?> shrink-0 text-xl"></span>
            <span><?php echo e($menuItem->getName()); ?></span>
        </a>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/components/layouts/mobile-nav-menu.blade.php ENDPATH**/ ?>