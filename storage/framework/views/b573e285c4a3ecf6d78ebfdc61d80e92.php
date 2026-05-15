<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label']));

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

foreach (array_filter((['label']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="seller-mobile-card__row">
    <span class="seller-mobile-card__label"><?php echo e($label); ?></span>
    <span <?php echo e($attributes->merge(['class' => 'seller-mobile-card__value'])); ?>>
        <?php echo e($slot); ?>

    </span>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/components/seller/mobile-card-field.blade.php ENDPATH**/ ?>