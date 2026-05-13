<?php
    use Webkul\Sales\Models\Order;
?>
<?php switch($status):
    case (Order::STATUS_PROCESSING): ?>
        <span class="label-processing text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.processing')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_COMPLETED): ?>
        <span class="label-active text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.completed')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_CANCELED): ?>
        <span class="label-canceled text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.canceled')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_CLOSED): ?>
        <span class="label-closed text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.closed')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_PENDING): ?>
        <span class="label-pending text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.pending')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_PENDING_PAYMENT): ?>
        <span class="label-pending text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.pending-payment')); ?></span>
        <?php break; ?>
    <?php case (Order::STATUS_FRAUD): ?>
        <span class="label-canceled text-sm"><?php echo e(__('shop::app.customers.account.orders.status.options.fraud')); ?></span>
        <?php break; ?>
    <?php default: ?>
        <span class="text-sm text-zinc-600"><?php echo e($status); ?></span>
<?php endswitch; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/customers/account/orders/partials/status-label.blade.php ENDPATH**/ ?>