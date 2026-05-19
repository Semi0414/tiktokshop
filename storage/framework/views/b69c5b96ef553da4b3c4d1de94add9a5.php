<!-- Shipment Vue Components -->
<v-create-shipment>
    <div
        class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
    >
        <span class="icon-ship text-2xl"></span>

        <?php echo app('translator')->get('superadmin::app.sales.orders.view.ship'); ?>
    </div>
</v-create-shipment>

<?php if (! $__env->hasRenderedOnce('98689d43-f27e-4a64-b806-ee4ca5f3f9a0')): $__env->markAsRenderedOnce('98689d43-f27e-4a64-b806-ee4ca5f3f9a0');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-create-shipment-template"
    >
        <div>
            <div
                class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                @click="$refs.shipment.open()"
            >
                <span
                    class="icon-ship text-2xl"
                    role="button"
                    tabindex="0"
                >
                </span>

                <?php echo app('translator')->get('superadmin::app.sales.orders.view.ship'); ?>
            </div>

            <!-- Shipment Create Drawer -->
            <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['method' => 'POST','action' => route('superadmin.sales.shipments.store', $order->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'POST','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.sales.shipments.store', $order->id))]); ?>
                <?php if (isset($component)) { $__componentOriginal190969edc8078f487b25ed9c8ee6502d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal190969edc8078f487b25ed9c8ee6502d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.drawer.index','data' => ['ref' => 'shipment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'shipment']); ?>
                    <!-- Drawer Header -->
                     <?php $__env->slot('header', null, []); ?> 
                        <div class="grid gap-3 sm:h-8">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-medium dark:text-white">
                                    <?php echo app('translator')->get('superadmin::app.sales.shipments.create.title'); ?>
                                </p>

                                <?php if(bouncer()->hasPermission('sales.shipments.create')): ?>
                                    <button
                                        type="submit"
                                        class="primary-button ltr:mr-11 rtl:ml-11"
                                    >
                                        <?php echo app('translator')->get('superadmin::app.sales.shipments.create.create-btn'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                     <?php $__env->endSlot(); ?>

                    <!-- Drawer Content -->
                     <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
                        <div class="grid p-4 pt-2">
                            <div class="grid grid-cols-2 gap-x-5">
                                <!-- Carrier Name -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                        <?php echo app('translator')->get('superadmin::app.sales.shipments.create.carrier-name'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','id' => 'shipment[carrier_title]','name' => 'shipment[carrier_title]','label' => trans('superadmin::app.sales.shipments.create.carrier-name'),'placeholder' => trans('superadmin::app.sales.shipments.create.carrier-name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','id' => 'shipment[carrier_title]','name' => 'shipment[carrier_title]','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.carrier-name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.carrier-name'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'carrier_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'carrier_name']); ?>
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

                                <!-- Tracking Number -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                        <?php echo app('translator')->get('superadmin::app.sales.shipments.create.tracking-number'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','id' => 'shipment[track_number]','name' => 'shipment[track_number]','label' => trans('superadmin::app.sales.shipments.create.tracking-number'),'placeholder' => trans('superadmin::app.sales.shipments.create.tracking-number')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','id' => 'shipment[track_number]','name' => 'shipment[track_number]','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.tracking-number')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.tracking-number'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'shipment[track_number]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'shipment[track_number]']); ?>
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

                            <!-- Resource -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                    <?php echo app('translator')->get('superadmin::app.sales.shipments.create.source'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'select','id' => 'shipment[source]','name' => 'shipment[source]','rules' => 'required','vModel' => 'source','label' => trans('superadmin::app.sales.shipments.create.source'),'placeholder' => trans('superadmin::app.sales.shipments.create.source'),'@change' => 'onSourceChange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'select','id' => 'shipment[source]','name' => 'shipment[source]','rules' => 'required','v-model' => 'source','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.source')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.source')),'@change' => 'onSourceChange']); ?>
                                    <?php $__currentLoopData = $order->channel->inventory_sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventorySource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option 
                                            value="<?php echo e($inventorySource->id); ?>"
                                            v-pre
                                        >
                                            <?php echo e($inventorySource->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'shipment[source]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'shipment[source]']); ?>
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

                            <div class="grid">
                                <!-- Item Listing -->
                                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(
                                        $item->qty_to_ship > 0
                                        && $item->product
                                    ): ?>
                                        <div class="flex justify-between gap-2.5 py-4">
                                            <div class="flex gap-2.5">
                                                <?php if($item->product?->base_image_url): ?>
                                                    <img
                                                        class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                                        src="<?php echo e($item->product?->base_image_url); ?>"
                                                    >
                                                <?php else: ?>
                                                    <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                                        <img src="<?php echo e(bagisto_asset('images/product-placeholders/front.svg')); ?>">

                                                        <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                                            <?php echo app('translator')->get('superadmin::app.sales.invoices.view.product-image'); ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="grid place-content-start gap-1.5">
                                                    <!-- Item Name -->
                                                    <p 
                                                        class="text-base font-semibold text-gray-800 dark:text-white"
                                                        v-pre
                                                    >
                                                        <?php echo e($item->name); ?>

                                                    </p>

                                                    <div class="flex flex-col place-items-start gap-1.5">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            <?php echo app('translator')->get('superadmin::app.sales.shipments.create.amount-per-unit', [
                                                                'amount' => core()->formatBasePrice($item->base_price),
                                                                'qty'    => $item->qty_ordered,
                                                            ]); ?>
                                                        </p>

                                                        <!--Additional Attributes -->
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

                                                        <!-- Item SKU -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            <?php echo app('translator')->get('superadmin::app.sales.shipments.create.sku', ['sku' => $item->sku]); ?>
                                                        </p>

                                                        <!--Item Status -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            <?php echo e($item->qty_ordered ? trans('superadmin::app.sales.shipments.create.item-ordered', ['qty_ordered' => $item->qty_ordered]) : ''); ?>


                                                            <?php echo e($item->qty_invoiced ? trans('superadmin::app.sales.shipments.create.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : ''); ?>


                                                            <?php echo e($item->qty_shipped ? trans('superadmin::app.sales.shipments.create.item-shipped', ['qty_shipped' => $item->qty_shipped]) : ''); ?>


                                                            <?php echo e($item->qty_refunded ? trans('superadmin::app.sales.shipments.create.item-refunded', ['qty_refunded' => $item->qty_refunded]) : ''); ?>


                                                            <?php echo e($item->qty_canceled ? trans('superadmin::app.sales.shipments.create.item-canceled', ['qty_canceled' => $item->qty_canceled]) : ''); ?>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Information -->
                                        <?php $__currentLoopData = $order->channel->inventory_sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventorySource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="grid grid-cols-2 gap-2.5 border-b border-slate-300 py-2.5 dark:border-gray-800">
                                                <div class="grid gap-1">
                                                    <!--Inventory Source -->
                                                    <p
                                                        class="text-base font-semibold text-gray-800 dark:text-white"
                                                        v-pre
                                                    >
                                                        <?php echo e($inventorySource->name); ?>

                                                    </p>

                                                    <!-- Available Quantity -->
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        <?php echo app('translator')->get('superadmin::app.sales.shipments.create.qty-available'); ?> :

                                                        <?php
                                                            $product = $item->getTypeInstance()->getOrderedItem($item)->product;

                                                            $sourceQty = $product?->type == 'bundle' ? $item->qty_ordered : $product?->inventory_source_qty($inventorySource->id);
                                                        ?>

                                                        <?php echo e($sourceQty); ?>

                                                    </p>
                                                </div>

                                                <div class="grid ltr:text-right rtl:text-left">
                                                    <?php
                                                        $inputName = "shipment[items][$item->id][$inventorySource->id]";
                                                    ?>

                                                    <!-- Quantity To Ship -->
                                                    <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
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
                                                            <?php echo app('translator')->get('superadmin::app.sales.shipments.create.qty-to-ship'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','class' => '!w-[100px]','id' => $inputName,'name' => $inputName,'rules' => 'required|numeric|min_value:0|max_value:' . $item->qty_ordered,'value' => $item->qty_to_ship,'label' => trans('superadmin::app.sales.shipments.create.qty-to-ship'),'dataOriginalQuantity' => ''.e($item->qty_to_ship).'',':disabled' => '\''.e(empty($sourceQty)).'\' || source != \''.e($inventorySource->id).'\'','ref' => $inputName]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','class' => '!w-[100px]','id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inputName),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inputName),'rules' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('required|numeric|min_value:0|max_value:' . $item->qty_ordered),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->qty_to_ship),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.sales.shipments.create.qty-to-ship')),'data-original-quantity' => ''.e($item->qty_to_ship).'',':disabled' => '\''.e(empty($sourceQty)).'\' || source != \''.e($inventorySource->id).'\'','ref' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inputName)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => $inputName]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inputName)]); ?>
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
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
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
    window.app.component('v-create-shipment', {
        template: '#v-create-shipment-template',

        data() {
            return {
                source: "",
            };
        },

        methods: {
            onSourceChange() {
                this.setOriginalQuantityToAllShipmentInputElements();
            },

            getAllShipmentInputElements() {
                let allRefs = this.$refs;

                let allInputElements = [];

                Object.keys(allRefs).forEach((key) => {
                    if (key.startsWith('shipment')) {
                        allInputElements.push(allRefs[key]);
                    }
                });

                return allInputElements;
            },

            setOriginalQuantityToAllShipmentInputElements() {
                this.getAllShipmentInputElements().forEach((element) => {
                    let data = Object.assign({}, element.dataset);

                    element.value = data.originalQuantity;
                });
            }
        },
    });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/shipments/create.blade.php ENDPATH**/ ?>