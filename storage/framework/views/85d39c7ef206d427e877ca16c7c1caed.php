<?php if (isset($component)) { $__componentOriginal4c4dbe009fe892108b054e8b47e63427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4c4dbe009fe892108b054e8b47e63427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.account.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.account'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('shop::app.customers.account.orders.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php if((core()->getConfigData('general.general.breadcrumbs.shop'))): ?>
        <?php $__env->startSection('breadcrumbs'); ?>
            <?php if (isset($component)) { $__componentOriginaldef12fd0653509715c3bc62a609dde73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldef12fd0653509715c3bc62a609dde73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.breadcrumbs.index','data' => ['name' => 'orders']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'orders']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $attributes = $__attributesOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__attributesOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $component = $__componentOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__componentOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
        <?php $__env->stopSection(); ?>
    <?php endif; ?>

    <div class="max-md:hidden">
        <?php if (isset($component)) { $__componentOriginalf60f1298dff473a76a071049d503ffbb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf60f1298dff473a76a071049d503ffbb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.account.navigation','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.account.navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf60f1298dff473a76a071049d503ffbb)): ?>
<?php $attributes = $__attributesOriginalf60f1298dff473a76a071049d503ffbb; ?>
<?php unset($__attributesOriginalf60f1298dff473a76a071049d503ffbb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf60f1298dff473a76a071049d503ffbb)): ?>
<?php $component = $__componentOriginalf60f1298dff473a76a071049d503ffbb; ?>
<?php unset($__componentOriginalf60f1298dff473a76a071049d503ffbb); ?>
<?php endif; ?>
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-sm:mb-5">
            <a class="grid md:hidden" href="<?php echo e(route('shop.customers.account.index')); ?>">
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                <?php echo app('translator')->get('shop::app.customers.account.orders.title'); ?>
            </h2>
        </div>

        <?php echo view_render_event('bagisto.shop.customers.account.orders.list.before'); ?>


        <?php if($orders->isEmpty()): ?>
            <div class="rounded-lg border border-zinc-200 bg-white p-8 text-center text-zinc-600">
                <?php echo app('translator')->get('shop::app.customers.account.orders.empty-order'); ?>
            </div>
        <?php else: ?>
            
            <div class="max-md:hidden overflow-x-auto rounded-lg border border-zinc-200 bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase text-zinc-600">
                        <tr>
                            <th class="px-4 py-3"><?php echo app('translator')->get('shop::app.customers.account.orders.seller-store'); ?></th>
                            <th class="px-4 py-3"><?php echo app('translator')->get('shop::app.customers.account.orders.order-id'); ?></th>
                            <th class="px-4 py-3"><?php echo app('translator')->get('shop::app.customers.account.orders.order-date'); ?></th>
                            <th class="px-4 py-3"><?php echo app('translator')->get('shop::app.customers.account.orders.total'); ?></th>
                            <th class="px-4 py-3"><?php echo app('translator')->get('shop::app.customers.account.orders.status.title'); ?></th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-800">
                                    <?php if($order->seller?->name): ?>
                                        <?php echo e($order->seller->name); ?>

                                    <?php endif; ?>
                                    <?php if($order->seller_id): ?>
                                        <span class="text-xs text-zinc-500">· <?php echo app('translator')->get('shop::app.customers.account.orders.seller-id-label'); ?> #<?php echo e($order->seller_id); ?></span>
                                    <?php elseif(! $order->seller?->name): ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 font-medium text-zinc-900">#<?php echo e($order->increment_id); ?></td>
                                <td class="px-4 py-3 text-zinc-600"><?php echo e($order->created_at?->format('M d, Y H:i')); ?></td>
                                <td class="px-4 py-3 font-semibold text-zinc-900"><?php echo e(core()->formatPrice($order->grand_total, $order->order_currency_code)); ?></td>
                                <td class="px-4 py-3">
                                    <?php echo $__env->make('shop::customers.account.orders.partials.status-label', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="<?php echo e(route('shop.customers.account.orders.view', $order->id)); ?>" class="text-sm font-medium text-navyBlue underline">
                                        <?php echo app('translator')->get('shop::app.customers.account.orders.action-view'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="space-y-4 md:hidden">
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('shop.customers.account.orders.view', $order->id)); ?>" class="block rounded-lg border border-zinc-200 bg-white p-4 transition hover:bg-zinc-50">
                        <div class="flex justify-between gap-2">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    <?php echo app('translator')->get('shop::app.customers.account.orders.order-id'); ?>: #<?php echo e($order->increment_id); ?>

                                </p>
                                <p class="text-xs text-zinc-500"><?php echo e($order->created_at?->format('M d, Y')); ?></p>
                                <?php if($order->seller?->name || $order->seller_id): ?>
                                    <p class="mt-1 text-xs text-zinc-600">
                                        <?php if($order->seller?->name): ?><?php echo e($order->seller->name); ?><?php endif; ?>
                                        <?php if($order->seller_id): ?><span class="text-zinc-500"> · #<?php echo e($order->seller_id); ?></span><?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div><?php echo $__env->make('shop::customers.account.orders.partials.status-label', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500"><?php echo app('translator')->get('shop::app.customers.account.orders.subtotal'); ?></p>
                        <p class="text-xl font-semibold text-black"><?php echo e(core()->formatPrice($order->grand_total, $order->order_currency_code)); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-6">
                <?php echo e($orders->links()); ?>

            </div>
        <?php endif; ?>

        <?php echo view_render_event('bagisto.shop.customers.account.orders.list.after'); ?>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4c4dbe009fe892108b054e8b47e63427)): ?>
<?php $attributes = $__attributesOriginal4c4dbe009fe892108b054e8b47e63427; ?>
<?php unset($__attributesOriginal4c4dbe009fe892108b054e8b47e63427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4c4dbe009fe892108b054e8b47e63427)): ?>
<?php $component = $__componentOriginal4c4dbe009fe892108b054e8b47e63427; ?>
<?php unset($__componentOriginal4c4dbe009fe892108b054e8b47e63427); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/customers/account/orders/index.blade.php ENDPATH**/ ?>