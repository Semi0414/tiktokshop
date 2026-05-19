<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['isMultiRow' => false]));

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

foreach (array_filter((['isMultiRow' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div>
    <?php if (isset($component)) { $__componentOriginal140e5fbd6fcf60445a53648a59b1e348 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal140e5fbd6fcf60445a53648a59b1e348 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.toolbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal140e5fbd6fcf60445a53648a59b1e348)): ?>
<?php $attributes = $__attributesOriginal140e5fbd6fcf60445a53648a59b1e348; ?>
<?php unset($__attributesOriginal140e5fbd6fcf60445a53648a59b1e348); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal140e5fbd6fcf60445a53648a59b1e348)): ?>
<?php $component = $__componentOriginal140e5fbd6fcf60445a53648a59b1e348; ?>
<?php unset($__componentOriginal140e5fbd6fcf60445a53648a59b1e348); ?>
<?php endif; ?>

    <div class="mt-4 flex">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
                <?php if (isset($component)) { $__componentOriginale16f0709ae5bfdf747b9973bd464f6ad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.table.head','data' => ['isMultiRow' => $isMultiRow]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.table.head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['isMultiRow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isMultiRow)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad)): ?>
<?php $attributes = $__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad; ?>
<?php unset($__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale16f0709ae5bfdf747b9973bd464f6ad)): ?>
<?php $component = $__componentOriginale16f0709ae5bfdf747b9973bd464f6ad; ?>
<?php unset($__componentOriginale16f0709ae5bfdf747b9973bd464f6ad); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.table.body','data' => ['isMultiRow' => $isMultiRow]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.table.body'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['isMultiRow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isMultiRow)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $attributes = $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $component = $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/shimmer/datagrid/index.blade.php ENDPATH**/ ?>