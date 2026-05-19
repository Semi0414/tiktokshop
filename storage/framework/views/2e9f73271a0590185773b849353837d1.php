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
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.cms.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            <?php echo app('translator')->get('superadmin::app.cms.index.title'); ?>
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <?php if (isset($component)) { $__componentOriginale04209d27914132457a915cd31909403 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale04209d27914132457a915cd31909403 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.export.index','data' => ['src' => route('superadmin.cms.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.export'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.cms.index'))]); ?>
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

            <!-- Create New Pages Button -->
            <?php if(bouncer()->hasPermission('cms.create')): ?>
                <a
                    href="<?php echo e(route('superadmin.cms.create')); ?>"
                    class="primary-button"
                >
                    <?php echo app('translator')->get('superadmin::app.cms.index.create-btn'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php echo view_render_event('bagisto.admin.cms.pages.list.before'); ?>


    <?php if (isset($component)) { $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.ssr','data' => ['datagridPayload' => $datagridPayload,'src' => route('superadmin.cms.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.ssr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['datagrid-payload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datagridPayload),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.cms.index'))]); ?>
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
    
    <?php echo view_render_event('bagisto.admin.cms.pages.list.after'); ?>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/cms/index.blade.php ENDPATH**/ ?>