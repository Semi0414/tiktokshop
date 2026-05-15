<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'emptyMessage' => null,
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
    'emptyMessage' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'seller-responsive-table overflow-hidden'])); ?>>
    <div class="seller-rt-table-wrap">
        <?php echo e($table); ?>

    </div>

    <div class="seller-rt-cards">
        <?php if(isset($cards) && trim((string) $cards) !== ''): ?>
            <?php echo e($cards); ?>

        <?php elseif($emptyMessage): ?>
            <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                <?php echo e($emptyMessage); ?>

            </p>
        <?php endif; ?>
    </div>

    <?php if(isset($footer)): ?>
        <div class="seller-rt-footer border-t border-gray-100 dark:border-gray-800">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/components/seller/responsive-table.blade.php ENDPATH**/ ?>