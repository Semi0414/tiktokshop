<?php
    $globalTikStoreUrl = $globalTikStoreUrl ?? route('shop.tik-store.index', array_filter([
        'global' => '1',
        'ref' => request('ref'),
    ]));
?>

<div class="header-actions">
    <a class="icon-button" href="<?php echo e(route('shop.checkout.cart.index')); ?>" title="<?php echo e(__('Cart')); ?>">🛒</a>

    <details class="tik-account-menu">
        <summary class="icon-button tik-account-summary" title="<?php echo e(__('Account')); ?>" aria-label="<?php echo e(__('Account')); ?>">👤</summary>
        <div class="tik-account-dropdown" role="menu">
            <?php if(auth()->guard('customer')->check()): ?>
                <div class="tik-account-welcome">
                    <strong><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.welcome'); ?> <?php echo e(auth()->guard('customer')->user()->first_name); ?></strong>
                    <span class="tik-account-sub"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.dropdown-text'); ?></span>
                </div>
                <div class="tik-account-divider"></div>
                <a href="<?php echo e(route('shop.customers.account.profile.index')); ?>" role="menuitem"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.profile'); ?></a>
                <a href="<?php echo e(route('shop.customers.account.orders.index')); ?>" role="menuitem"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.orders'); ?></a>
                <?php if(core()->getConfigData('customer.settings.wishlist.wishlist_option')): ?>
                    <a href="<?php echo e(route('shop.customers.account.wishlist.index')); ?>" role="menuitem"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.wishlist'); ?></a>
                <?php endif; ?>
                <form
                    id="tikStoreCustomerLogout"
                    method="POST"
                    action="<?php echo e(route('shop.customer.session.destroy')); ?>"
                    style="display:none"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
                <a
                    href="<?php echo e(route('shop.customer.session.destroy')); ?>"
                    role="menuitem"
                    onclick="event.preventDefault(); document.getElementById('tikStoreCustomerLogout').submit();"
                ><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.logout'); ?></a>
            <?php else: ?>
                <div class="tik-account-welcome">
                    <strong><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.welcome-guest'); ?></strong>
                    <span class="tik-account-sub"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.dropdown-text'); ?></span>
                </div>
                <div class="tik-account-divider"></div>
                <a href="<?php echo e(route('shop.customer.session.index')); ?>" role="menuitem" class="tik-account-primary"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.sign-in'); ?></a>
                <a href="<?php echo e(route('shop.customers.register.index')); ?>" role="menuitem"><?php echo app('translator')->get('shop::app.components.layouts.header.desktop.bottom.sign-up'); ?></a>
            <?php endif; ?>
        </div>
    </details>

    <?php if(! empty($showBack) && ! empty($backUrl)): ?>
        <a class="pill-button" href="<?php echo e($backUrl); ?>"><strong><?php echo e(__('Back')); ?></strong></a>
    <?php else: ?>
        <a class="pill-button" href="<?php echo e($globalTikStoreUrl); ?>"><strong><?php echo e(__('Main shop')); ?></strong></a>
    <?php endif; ?>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/tik-store/partials/header-actions.blade.php ENDPATH**/ ?>