<!-- Most Orders Shimmer -->
<div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="mb-4 flex items-center justify-between">
        <div class="shimmer h-[17px] w-[150px]"></div>

        <div class="shimmer h-[21px] w-[79px]"></div>
    </div>
    
    <!-- Progress Bar Shimmer -->
    <?php if (isset($component)) { $__componentOriginal38a7b47766a0862deaf926a0ecdd5374 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38a7b47766a0862deaf926a0ecdd5374 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.progress-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.progress-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal38a7b47766a0862deaf926a0ecdd5374)): ?>
<?php $attributes = $__attributesOriginal38a7b47766a0862deaf926a0ecdd5374; ?>
<?php unset($__attributesOriginal38a7b47766a0862deaf926a0ecdd5374); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal38a7b47766a0862deaf926a0ecdd5374)): ?>
<?php $component = $__componentOriginal38a7b47766a0862deaf926a0ecdd5374; ?>
<?php unset($__componentOriginal38a7b47766a0862deaf926a0ecdd5374); ?>
<?php endif; ?>
</div><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/shimmer/reporting/customers/most-orders.blade.php ENDPATH**/ ?>