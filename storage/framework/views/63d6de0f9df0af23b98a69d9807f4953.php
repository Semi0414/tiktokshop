<!-- Average Order Value Shimmer -->
<div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="mb-4 flex items-center justify-between">
        <div class="shimmer h-[17px] w-[150px]"></div>

        <div class="shimmer h-[21px] w-[79px]"></div>
    </div>

    <div class="grid gap-4">
        <div class="flex items-center justify-between gap-4">
            <div class="shimmer h-9 w-[120px]"></div>
            <div class="shimmer h-[17px] w-[75px]"></div>
        </div>

        <div class="shimmer h-5 w-[120px]"></div>
    
        <!-- Progress Bar Shimmer -->
        <?php if (isset($component)) { $__componentOriginal079d87d5c143264454e1286ada4580f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal079d87d5c143264454e1286ada4580f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.graph','data' => ['count' => 15]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.graph'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => 15]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal079d87d5c143264454e1286ada4580f5)): ?>
<?php $attributes = $__attributesOriginal079d87d5c143264454e1286ada4580f5; ?>
<?php unset($__attributesOriginal079d87d5c143264454e1286ada4580f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal079d87d5c143264454e1286ada4580f5)): ?>
<?php $component = $__componentOriginal079d87d5c143264454e1286ada4580f5; ?>
<?php unset($__componentOriginal079d87d5c143264454e1286ada4580f5); ?>
<?php endif; ?>

        <!-- Date Range -->
        <div class="flex justify-center gap-5">
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-[17px] w-[143px]"></div>
            </div>
            
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-[17px] w-[143px]"></div>
            </div>
        </div>
    </div>
</div><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/shimmer/reporting/sales/average-order-value.blade.php ENDPATH**/ ?>