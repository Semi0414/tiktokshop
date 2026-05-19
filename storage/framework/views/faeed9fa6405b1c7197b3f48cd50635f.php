<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'isActive' => true,
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
    'isActive' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'box-shadow rounded bg-white dark:bg-gray-900'])); ?>>
    <?php if(isset($header)): ?>
        <div <?php echo e($header->attributes->merge(['class' => 'flex items-center justify-between p-1.5'])); ?>>
            <?php echo e($header); ?>

        </div>
    <?php endif; ?>

    <?php if(isset($content)): ?>
        <div <?php echo e($content->attributes->merge(['class' => 'px-4 pb-4'])); ?>>
            <?php echo e($content); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/accordion/index.blade.php ENDPATH**/ ?>