<?php echo view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.before', ['product' => $product]); ?>


<v-inventories>
    <div class="mb-5 text-sm text-gray-600 dark:text-gray-300">
        <div class="relative mb-2.5 flex items-center">
            <span class="inline-block rounded-full bg-yellow-500 p-1.5 ltr:mr-1.5 rtl:ml-1.5"></span>

            <?php echo app('translator')->get('superadmin::app.catalog.products.edit.inventories.pending-ordered-qty', [
                'qty' => $product->ordered_inventories->pluck('qty')->first() ?? 0,
            ]); ?>
            
            <i class="icon-information peer rounded-full bg-gray-700 text-lg font-bold text-white transition-all hover:bg-gray-800 ltr:ml-2.5 rtl:mr-2.5"></i>

            <div class="absolute bottom-6 hidden rounded-lg bg-black p-2.5 text-sm italic text-white opacity-80 peer-hover:block">
                <?php echo app('translator')->get('superadmin::app.catalog.products.edit.inventories.pending-ordered-qty-info'); ?>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = app(\Webkul\Inventory\Repositories\InventorySourceRepository::class)->findWhere(['status' => 1]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventorySource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $qty = old('inventories[' . $inventorySource->id . ']')
                ?: ($product->inventories->where('inventory_source_id', $inventorySource->id)->pluck('qty')->first() ?? 0);
        ?>

        <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['vPre' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-pre' => true]); ?>
                <?php echo e($inventorySource->name); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $attributes = $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $component = $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','name' => 'inventories[' . $inventorySource->id . ']','rules' => 'numeric|min:0','value' => $qty,'label' => $inventorySource->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('inventories[' . $inventorySource->id . ']'),'rules' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('numeric|min:0'),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($qty),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inventorySource->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal7d00c14826cd26beafba9f36875ab882 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d00c14826cd26beafba9f36875ab882 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'inventories[' . $inventorySource->id . ']']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('inventories[' . $inventorySource->id . ']')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $attributes = $__attributesOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__attributesOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $component = $__componentOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__componentOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $attributes = $__attributesOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $component = $__componentOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__componentOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</v-inventories>

<?php echo view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.after', ['product' => $product]); ?>


<?php if (! $__env->hasRenderedOnce('239737f1-045d-49e1-961d-5162a34ddb8e')): $__env->markAsRenderedOnce('239737f1-045d-49e1-961d-5162a34ddb8e');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-inventories-template"
    >
        <div v-show="manageStock">
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component('v-inventories', {
            template: '#v-inventories-template',

            data() {
                return {
                    manageStock: "<?php echo e((boolean) $product->manage_stock); ?>",
                }
            },

            mounted: function() {
                let self = this;

                document.getElementById('manage_stock').addEventListener('change', function(e) {
                    self.manageStock = e.target.checked;
                });
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/catalog/products/edit/inventories.blade.php ENDPATH**/ ?>