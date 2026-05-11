<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'average' => '0',
    'total' => 0,
    'showLabel' => true,
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
    'average' => '0',
    'total' => 0,
    'showLabel' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $avg = is_numeric($average) ? (float) $average : 0;
    $totalNum = (int) $total;
    $abbr = $totalNum >= 1000 ? rtrim(rtrim(number_format($totalNum / 1000, 1), '0'), '.').'k' : (string) $totalNum;
?>

<div <?php echo e($attributes->merge(['class' => 'flex w-max items-center gap-2 rounded-md border border-zinc-200 px-4 py-2'])); ?>>
    <span class="text-sm font-medium text-black max-sm:text-xs">
        <?php echo e(number_format($avg, 1, '.', '')); ?>

    </span>

    <span class="icon-star-fill -mt-1 text-xl text-amber-500 max-sm:text-lg" role="presentation"></span>

    <?php if($showLabel): ?>
        <span class="border-l border-zinc-300 text-sm font-medium text-black max-sm:border-zinc-300 max-sm:text-xs ltr:pl-2 rtl:pr-2">
            <?php echo e($abbr); ?>

            <span class="text-zinc-600"><?php echo app('translator')->get('shop::app.components.products.ratings.title'); ?></span>
        </span>
    <?php else: ?>
        <span class="border-l border-zinc-300 text-sm font-medium text-black max-sm:text-xs ltr:pl-2 rtl:pr-2">
            <?php echo e($abbr); ?>

        </span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/products/ratings-static.blade.php ENDPATH**/ ?>