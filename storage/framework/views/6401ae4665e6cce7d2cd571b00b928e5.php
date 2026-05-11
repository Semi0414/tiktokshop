<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['hideHeaderCategories' => true]));

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

foreach (array_filter((['hideHeaderCategories' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php echo view_render_event('bagisto.shop.layout.header.before'); ?>


<?php
    if ($hideHeaderCategories) {
        $headerCategoryTree = collect();
    } else {
        $headerCategoryTree ??= app(\Webkul\Category\Repositories\CategoryRepository::class)
            ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
    }
?>

<?php echo $__env->make('shop::components.layouts.header.search-storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 ): ?>
    <div class="max-lg:hidden">
        <?php if (isset($component)) { $__componentOriginal545b25d9096572e7f54664393badf092 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal545b25d9096572e7f54664393badf092 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.desktop.top','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.desktop.top'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal545b25d9096572e7f54664393badf092)): ?>
<?php $attributes = $__attributesOriginal545b25d9096572e7f54664393badf092; ?>
<?php unset($__attributesOriginal545b25d9096572e7f54664393badf092); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal545b25d9096572e7f54664393badf092)): ?>
<?php $component = $__componentOriginal545b25d9096572e7f54664393badf092; ?>
<?php unset($__componentOriginal545b25d9096572e7f54664393badf092); ?>
<?php endif; ?>
    </div>
<?php endif; ?>

<header class="shadow-gray sticky top-0 z-10 bg-white shadow-sm max-lg:shadow-none">
    
    <div class="max-lg:hidden">
        <?php if (isset($component)) { $__componentOriginal8cc211b5dad97c0fb8f5f993653bc565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cc211b5dad97c0fb8f5f993653bc565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.desktop.index','data' => ['headerCategoryTree' => $headerCategoryTree,'hideHeaderCategories' => $hideHeaderCategories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.desktop'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headerCategoryTree' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($headerCategoryTree),'hideHeaderCategories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeaderCategories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cc211b5dad97c0fb8f5f993653bc565)): ?>
<?php $attributes = $__attributesOriginal8cc211b5dad97c0fb8f5f993653bc565; ?>
<?php unset($__attributesOriginal8cc211b5dad97c0fb8f5f993653bc565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cc211b5dad97c0fb8f5f993653bc565)): ?>
<?php $component = $__componentOriginal8cc211b5dad97c0fb8f5f993653bc565; ?>
<?php unset($__componentOriginal8cc211b5dad97c0fb8f5f993653bc565); ?>
<?php endif; ?>
    </div>

    <div class="lg:hidden">
        <?php if (isset($component)) { $__componentOriginal8908cb9c8ecd2de6855a9cd5031f1952 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8908cb9c8ecd2de6855a9cd5031f1952 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.header.mobile.index','data' => ['headerCategoryTree' => $headerCategoryTree,'hideHeaderCategories' => $hideHeaderCategories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.header.mobile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headerCategoryTree' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($headerCategoryTree),'hideHeaderCategories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeaderCategories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8908cb9c8ecd2de6855a9cd5031f1952)): ?>
<?php $attributes = $__attributesOriginal8908cb9c8ecd2de6855a9cd5031f1952; ?>
<?php unset($__attributesOriginal8908cb9c8ecd2de6855a9cd5031f1952); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8908cb9c8ecd2de6855a9cd5031f1952)): ?>
<?php $component = $__componentOriginal8908cb9c8ecd2de6855a9cd5031f1952; ?>
<?php unset($__componentOriginal8908cb9c8ecd2de6855a9cd5031f1952); ?>
<?php endif; ?>
    </div>
</header>

<?php echo view_render_event('bagisto.shop.layout.header.after'); ?>

<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/index.blade.php ENDPATH**/ ?>