<?php if (isset($component)) { $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Page Title -->
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.sales.booking.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between gap-[16px] max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            <?php echo app('translator')->get('superadmin::app.sales.booking.index.title'); ?>
        </p>

        <?php if (isset($component)) { $__componentOriginale04209d27914132457a915cd31909403 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale04209d27914132457a915cd31909403 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.export.index','data' => ['src' => ''.e(route('superadmin.sales.bookings.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.export'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => ''.e(route('superadmin.sales.bookings.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale04209d27914132457a915cd31909403)): ?>
<?php $attributes = $__attributesOriginale04209d27914132457a915cd31909403; ?>
<?php unset($__attributesOriginale04209d27914132457a915cd31909403); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale04209d27914132457a915cd31909403)): ?>
<?php $component = $__componentOriginale04209d27914132457a915cd31909403; ?>
<?php unset($__componentOriginale04209d27914132457a915cd31909403); ?>
<?php endif; ?>
    </div>

    <?php echo $__env->make('superadmin::sales.bookings.calendar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="mt-6">
        <?php if (isset($component)) { $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.ssr','data' => ['datagridPayload' => $datagridPayload,'src' => route('superadmin.sales.bookings.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.ssr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['datagrid-payload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datagridPayload),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.sales.bookings.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $attributes = $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $component = $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/bookings/index.blade.php ENDPATH**/ ?>