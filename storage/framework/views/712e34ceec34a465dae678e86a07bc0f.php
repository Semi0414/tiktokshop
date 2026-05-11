<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'headerCategoryTree'     => null,
    'hideHeaderCategories'    => true,
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
    'headerCategoryTree'     => null,
    'hideHeaderCategories'    => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="flex w-full flex-wrap">
    <?php if (isset($component)) { $__componentOriginald037dc316bb847c92e2fa747ccab56ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald037dc316bb847c92e2fa747ccab56ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.desktop.bottom','data' => ['headerCategoryTree' => $headerCategoryTree,'hideHeaderCategories' => $hideHeaderCategories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.desktop.bottom'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headerCategoryTree' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($headerCategoryTree),'hideHeaderCategories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeaderCategories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald037dc316bb847c92e2fa747ccab56ee)): ?>
<?php $attributes = $__attributesOriginald037dc316bb847c92e2fa747ccab56ee; ?>
<?php unset($__attributesOriginald037dc316bb847c92e2fa747ccab56ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald037dc316bb847c92e2fa747ccab56ee)): ?>
<?php $component = $__componentOriginald037dc316bb847c92e2fa747ccab56ee; ?>
<?php unset($__componentOriginald037dc316bb847c92e2fa747ccab56ee); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/desktop/index.blade.php ENDPATH**/ ?>