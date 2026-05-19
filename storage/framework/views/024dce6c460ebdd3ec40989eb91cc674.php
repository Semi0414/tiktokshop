<!-- Invoice Create Vue Component -->
<v-create-invoices>
    <div class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
        <span class="icon-sales text-2xl"></span>

        <?php echo app('translator')->get('superadmin::app.sales.invoices.create.invoice'); ?>
    </div>
</v-create-invoices>

<?php if (! $__env->hasRenderedOnce('47f708b6-51a0-476e-9440-718697675e00')): $__env->markAsRenderedOnce('47f708b6-51a0-476e-9440-718697675e00');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-create-invoices-template"
    >
        <div>
            <div
                class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    @click="$refs.invoice.open()"
                >
                    <span
                        class="icon-sales text-2xl"
                        role="presentation"
                        tabindex="0"
                    ></span>

                    <?php echo app('translator')->get('superadmin::app.sales.invoices.create.invoice'); ?>
            </div>

            <!-- Invoice Create drawer -->
            <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['method' => 'POST','action' => route('superadmin.sales.invoices.store', $order->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'POST','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.sales.invoices.store', $order->id))]); ?>
                <?php if (isset($component)) { $__componentOriginal190969edc8078f487b25ed9c8ee6502d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal190969edc8078f487b25ed9c8ee6502d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.drawer.index','data' => ['ref' => 'invoice']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'invoice']); ?>
                    <!-- Drawer Header -->
                     <?php $__env->slot('header', null, []); ?> 
                        <div class="grid h-8 gap-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-medium dark:text-white">
                                    <?php echo app('translator')->get('superadmin::app.sales.invoices.create.new-invoice'); ?>
                                </p>

                                <?php if(bouncer()->hasPermission('sales.invoices.create')): ?>
                                    <button
                                        type="submit"
                                        class="primary-button ltr:mr-11 rtl:ml-11"
                                    >
                                        <?php echo app('translator')->get('superadmin::app.sales.invoices.create.create-invoice'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                     <?php $__env->endSlot(); ?>

                    <!-- Drawer Content -->
                     <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
                        <div class="grid p-4 !pt-0">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->qty_to_invoice): ?>
                                    <div class="flex justify-between gap-2.5 border-b border-slate-300 py-4 dark:border-gray-800">
                                        <div class="flex gap-2.5">
                                            <?php if($item->product?->base_image_url): ?>
                                                <img
                                                    class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                                    src="<?php echo e($item->product?->base_image_url); ?>"
                                                />
                                            <?php else: ?>
                                                <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                                    <img src="<?php echo e(bagisto_asset('images/product-placeholders/front.svg')); ?>">

                                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                                        <?php echo app('translator')->get('superadmin::app.sales.invoices.create.product-image'); ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="grid place-content-start gap-1.5">
                                                <p 
                                                    class="break-all text-base font-semibold text-gray-800 dark:text-white"
                                                    v-pre
                                                >
                                                    <?php echo e($item->name); ?>

                                                </p>

                                                <div class="flex flex-col place-items-start gap-1.5">
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        <?php echo app('translator')->get('superadmin::app.sales.invoices.create.amount-per-unit', [
                                                            'amount' => core()->formatBasePrice($item->base_price),
                                                            'qty'    => $item->qty_ordered,
                                                        ]); ?>
                                                    </p>

                                                    <?php if(isset($item->additional['attributes'])): ?>
                                                        <?php $__currentLoopData = $item->additional['attributes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <p 
                                                                class="text-gray-600 dark:text-gray-300" 
                                                                v-pre
                                                            >
                                                                <?php if(
                                                                    ! isset($attribute['attribute_type'])
                                                                    || $attribute['attribute_type'] !== 'file'
                                                                ): ?>
                                                                    <?php echo e($attribute['attribute_name']); ?> : <?php echo e($attribute['option_label']); ?>

                                                                <?php else: ?>
                                                                    <?php echo e($attribute['attribute_name']); ?> :

                                                                    <a
                                                                        href="<?php echo e(Storage::url($attribute['option_label'])); ?>"
                                                                        class="text-blue-600 hover:underline"
                                                                        download="<?php echo e(File::basename($attribute['option_label'])); ?>"
                                                                    >
                                                                        <?php echo e(File::basename($attribute['option_label'])); ?>

                                                                    </a>
                                                                <?php endif; ?>
                                                            </p>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>

                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        <?php echo app('translator')->get('superadmin::app.sales.invoices.create.sku', ['sku' => $item->sku]); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid ltr:text-right rtl:text-left">
                                            <!-- Quantity Details -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['class' => 'required !block']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required !block']); ?>
                                                    <?php echo app('translator')->get('superadmin::app.sales.invoices.create.qty-to-invoiced'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','class' => '!w-[100px]','id' => 'invoice[items][' . $item->id . ']','name' => 'invoice[items][' . $item->id . ']','rules' => 'required|numeric|min:0','value' => $item->qty_to_invoice,'label' => trans('superadmin::app.sales.invoices.create.qty-to-invoiced'),'placeholder' => trans('superadmin::app.sales.invoices.create.qty-to-invoiced')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','class' => '!w-[100px]','id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('invoice[items][' . $item->id . ']'),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('invoice[items][' . $item->id . ']'),'rules' => 'required|numeric|min:0','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->qty_to_invoice),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.invoices.create.qty-to-invoiced')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.invoices.create.qty-to-invoiced'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'invoice[items][' . $item->id . ']']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('invoice[items][' . $item->id . ']')]); ?>
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
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <!-- Create Transaction Button -->
                            <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => ['class' => '!mb-0 flex w-max cursor-pointer select-none items-center gap-2.5 p-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0 flex w-max cursor-pointer select-none items-center gap-2.5 p-1.5']); ?>
                                <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'checkbox','name' => 'can_create_transaction','id' => 'can_create_transaction','value' => '1','for' => 'can_create_transaction']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'checkbox','name' => 'can_create_transaction','id' => 'can_create_transaction','value' => '1','for' => 'can_create_transaction']); ?>
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

                                <label
                                    for="can_create_transaction"
                                    class="cursor-pointer !text-sm !font-semibold !text-gray-600 dark:!text-gray-300"
                                >
                                    <?php echo app('translator')->get('superadmin::app.sales.invoices.create.create-transaction'); ?>
                                </label>
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
                        </div>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal190969edc8078f487b25ed9c8ee6502d)): ?>
<?php $attributes = $__attributesOriginal190969edc8078f487b25ed9c8ee6502d; ?>
<?php unset($__attributesOriginal190969edc8078f487b25ed9c8ee6502d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal190969edc8078f487b25ed9c8ee6502d)): ?>
<?php $component = $__componentOriginal190969edc8078f487b25ed9c8ee6502d; ?>
<?php unset($__componentOriginal190969edc8078f487b25ed9c8ee6502d); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $attributes = $__attributesOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $component = $__componentOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__componentOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>
        </div>
    </script>

    <script type="module">
        window.app.component('v-create-invoices', {
            template: '#v-create-invoices-template',
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/invoices/create.blade.php ENDPATH**/ ?>