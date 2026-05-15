<?php if (isset($component)) { $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('admin::app.seller-panel.tabs.product-warehouse-browse'); ?>
     <?php $__env->endSlot(); ?>

    <?php if (isset($component)) { $__componentOriginal495cd32d7d07ecc671177d8a7089c957 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal495cd32d7d07ecc671177d8a7089c957 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.panel','data' => ['active' => 'product_warehouse','breadcrumb' => [__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.product-warehouse-browse')]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active' => 'product_warehouse','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.product-warehouse-browse')])]); ?>
        <div class="mb-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                        <?php echo app('translator')->get('admin::app.seller-panel.tabs.product-warehouse-browse'); ?>
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Browse eligible catalog products and add them to your store.
                    </p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Seller Level</p>
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        <?php echo e(\Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null)); ?>

                    </p>
                </div>
            </div>
        </div>

        <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50/80 p-4 dark:border-blue-900 dark:bg-blue-950/40">
            <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.commission-title'); ?>
            </p>
            <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.commission-hint'); ?>
            </p>

            <form
                id="warehouse-commission-form"
                data-save-url="<?php echo e(route('admin.seller.product-warehouse.commission')); ?>"
                class="flex flex-wrap items-end gap-3"
            >
                <?php echo csrf_field(); ?>

                <div class="min-w-[140px]">
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">%</label>
                    <input
                        id="warehouse-commission-input"
                        type="number"
                        step="0.01"
                        name="commission_percent"
                        value="<?php echo e(number_format((float) $currentCommissionPercent, 2, '.', '')); ?>"
                        min="<?php echo e(number_format((float) $commissionRule['min'], 2, '.', '')); ?>"
                        max="<?php echo e(number_format((float) $commissionRule['max'], 2, '.', '')); ?>"
                        <?php if($commissionRule['readonly']): ?> readonly <?php endif; ?>
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                <?php if(! $commissionRule['readonly']): ?>
                    <button
                        type="submit"
                        id="warehouse-commission-save-btn"
                        class="seller-btn-primary"
                    >
                        <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.save-commission'); ?>
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <form method="get" action="<?php echo e(route('admin.seller.product-warehouse.index')); ?>" class="seller-filter-card mb-4" id="seller-warehouse-product-filters">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500"><?php echo app('translator')->get('admin::app.seller-panel.filters.product-name'); ?></label>
                    <input
                        type="text"
                        name="seller_warehouse_product_name"
                        value="<?php echo e(request('seller_warehouse_product_name')); ?>"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500"><?php echo app('translator')->get('admin::app.seller-panel.filters.product-id'); ?></label>
                    <input
                        type="text"
                        name="seller_warehouse_product_id"
                        value="<?php echo e(request('seller_warehouse_product_id')); ?>"
                        inputmode="numeric"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary"><?php echo app('translator')->get('admin::app.seller-panel.filters.enquire'); ?></button>
                <a href="<?php echo e(route('admin.seller.product-warehouse.index')); ?>" class="seller-btn-secondary"><?php echo app('translator')->get('admin::app.seller-panel.filters.reset'); ?></a>
            </div>
        </form>

        <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('admin::app.seller-panel.tabs.product-warehouse'); ?>
            </p>
        </div>

        <div id="seller-warehouse-grid" class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <?php
                $warehouseRows = $warehouseDebugPayload['data'] ?? [];
                $warehouseMeta = $warehouseDebugPayload['meta'] ?? [];
            ?>

            <form
                id="warehouse-bulk-add-form"
                method="post"
                action="<?php echo e(route('admin.seller.product-warehouse.attach')); ?>"
                class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/50"
            >
                <?php echo csrf_field(); ?>
                <div class="text-xs text-gray-600 dark:text-gray-300">
                    Select products and add to seller store with current commission.
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="warehouse-bulk-open-modal-btn"
                        class="seller-btn-primary text-xs"
                    >
                        <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.add-to-store'); ?>
                    </button>
                </div>
            </form>

            <?php if (isset($component)) { $__componentOriginal087ad9d7cd8db2d1d5b6085f010fa019 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal087ad9d7cd8db2d1d5b6085f010fa019 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.responsive-table','data' => ['class' => 'overflow-hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.responsive-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden']); ?>
                 <?php $__env->slot('table', null, []); ?> 
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                <input id="warehouse-select-all" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Image</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Product ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">SKU</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Type</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Price</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">Quantity</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Attribute Family</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <?php $__empty_1 = true; $__currentLoopData = $warehouseRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-3 py-3">
                                    <input
                                        type="checkbox"
                                        class="warehouse-row-checkbox h-4 w-4 rounded border-gray-300"
                                        name="indices[]"
                                        value="<?php echo e($row->product_id); ?>"
                                        data-already-added="<?php echo e(in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0'); ?>"
                                        form="warehouse-bulk-add-form"
                                    >
                                </td>
                                <td class="px-3 py-3">
                                    <?php if(!empty($row->base_image ?? null)): ?>
                                        <img
                                            src="<?php echo e(Storage::url($row->base_image)); ?>"
                                            alt=""
                                            class="h-10 w-10 rounded border border-gray-200 object-cover dark:border-gray-700"
                                        />
                                    <?php else: ?>
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded border border-dashed border-gray-200 text-xs text-gray-400 dark:border-gray-700">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->product_id ?? '—'); ?></td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->sku ?? '—'); ?></td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->name ?? '—'); ?></td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->type ?? '—'); ?></td>
                                <td class="px-3 py-3 text-right text-sm font-semibold text-blue-700 dark:text-blue-400">
                                    <?php echo e($row->price !== null ? core()->formatPrice((float) $row->price) : '—'); ?>

                                </td>
                                <td class="px-3 py-3 text-right text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->quantity ?? 0); ?></td>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200"><?php echo e($row->attribute_family ?? '—'); ?></td>
                                <td class="px-3 py-3 text-sm">
                                    <button
                                        type="button"
                                        class="seller-btn-primary text-xs warehouse-single-open-modal-btn"
                                        data-product-id="<?php echo e($row->product_id); ?>"
                                        data-already-added="<?php echo e(in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0'); ?>"
                                    >
                                        <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.add-to-store'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo app('translator')->get('admin::app.components.datagrid.table.no-records-available'); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                        </table>
                    </div>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('cards', null, []); ?> 
                    <?php $__empty_1 = true; $__currentLoopData = $warehouseRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <article class="seller-mobile-card">
                            <div class="seller-mobile-card__header">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        class="warehouse-row-checkbox h-4 w-4 rounded border-gray-300"
                                        name="indices[]"
                                        value="<?php echo e($row->product_id); ?>"
                                        data-already-added="<?php echo e(in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0'); ?>"
                                        form="warehouse-bulk-add-form"
                                    />
                                    <span class="seller-mobile-card__title"><?php echo e($row->name ?? '—'); ?></span>
                                </label>
                            </div>
                            <div class="seller-mobile-card__rows">
                                <?php if(!empty($row->base_image ?? null)): ?>
                                    <div class="mb-1">
                                        <img src="<?php echo e(Storage::url($row->base_image)); ?>" alt="" class="h-14 w-14 rounded border border-gray-200 object-cover dark:border-gray-700" />
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'Product ID']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Product ID']); ?><?php echo e($row->product_id ?? '—'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'SKU']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'SKU']); ?><?php echo e($row->sku ?? '—'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'Type']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Type']); ?><?php echo e($row->type ?? '—'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'Price']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Price']); ?><?php echo e($row->price !== null ? core()->formatPrice((float) $row->price) : '—'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'Quantity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Quantity']); ?><?php echo e($row->quantity ?? 0); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal41dd02fafa630eb47abc420442c3e8b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.mobile-card-field','data' => ['label' => 'Attribute Family']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.mobile-card-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Attribute Family']); ?><?php echo e($row->attribute_family ?? '—'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $attributes = $__attributesOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__attributesOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6)): ?>
<?php $component = $__componentOriginal41dd02fafa630eb47abc420442c3e8b6; ?>
<?php unset($__componentOriginal41dd02fafa630eb47abc420442c3e8b6); ?>
<?php endif; ?>
                            </div>
                            <div class="seller-mobile-card__actions">
                                <button
                                    type="button"
                                    class="seller-btn-primary w-full text-xs warehouse-single-open-modal-btn"
                                    data-product-id="<?php echo e($row->product_id); ?>"
                                    data-already-added="<?php echo e(in_array((int) ($row->product_id ?? 0), $sellerExistingProductIds ?? [], true) ? '1' : '0'); ?>"
                                >
                                    <?php echo app('translator')->get('admin::app.seller-panel.product-warehouse.add-to-store'); ?>
                                </button>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                            <?php echo app('translator')->get('admin::app.components.datagrid.table.no-records-available'); ?>
                        </p>
                    <?php endif; ?>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('footer', null, []); ?> 
                    <div class="flex items-center justify-between gap-3 border-t border-gray-100 px-3 py-3 text-xs text-gray-600 dark:text-gray-300">
                        <span>Showing <?php echo e($warehouseMeta['from'] ?? 0); ?> to <?php echo e($warehouseMeta['to'] ?? 0); ?> of <?php echo e($warehouseMeta['total'] ?? 0); ?></span>
                        <div><?php echo e($warehouseDebugPaginator->links()); ?></div>
                    </div>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal087ad9d7cd8db2d1d5b6085f010fa019)): ?>
<?php $attributes = $__attributesOriginal087ad9d7cd8db2d1d5b6085f010fa019; ?>
<?php unset($__attributesOriginal087ad9d7cd8db2d1d5b6085f010fa019); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal087ad9d7cd8db2d1d5b6085f010fa019)): ?>
<?php $component = $__componentOriginal087ad9d7cd8db2d1d5b6085f010fa019; ?>
<?php unset($__componentOriginal087ad9d7cd8db2d1d5b6085f010fa019); ?>
<?php endif; ?>
        </div>

        <?php echo $__env->make('admin::seller.product-warehouse.partials.add-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal495cd32d7d07ecc671177d8a7089c957)): ?>
<?php $attributes = $__attributesOriginal495cd32d7d07ecc671177d8a7089c957; ?>
<?php unset($__attributesOriginal495cd32d7d07ecc671177d8a7089c957); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal495cd32d7d07ecc671177d8a7089c957)): ?>
<?php $component = $__componentOriginal495cd32d7d07ecc671177d8a7089c957; ?>
<?php unset($__componentOriginal495cd32d7d07ecc671177d8a7089c957); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $attributes = $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $component = $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('452f241b-ab6d-444d-bc67-3d13df0cc6ba')): $__env->markAsRenderedOnce('452f241b-ab6d-444d-bc67-3d13df0cc6ba');
$__env->startPush('scripts'); ?>
    <script>
        (function initWarehousePage() {
            function notify(type, message) {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('add-flash', { type: type, message: message });
                } else {
                    alert(message);
                }
            }

            function boot() {
                var grid = document.getElementById('seller-warehouse-grid');

                if (grid && grid.dataset.singleAddDelegationBound !== '1') {
                    grid.dataset.singleAddDelegationBound = '1';
                    grid.addEventListener('click', function (event) {
                        var btn = event.target.closest('.warehouse-single-open-modal-btn');

                        if (!btn) {
                            return;
                        }

                        event.preventDefault();

                        if (String(btn.dataset.alreadyAdded) === '1') {
                            alert('This product is already added to your catalog.');

                            return;
                        }

                        var id = parseInt(btn.dataset.productId || '0', 10);

                        if (typeof window.openWarehouseAddModalFromSingle === 'function') {
                            window.openWarehouseAddModalFromSingle(id);
                        } else if (typeof window.openWarehouseAddModalWithIds === 'function') {
                            window.openWarehouseAddModalWithIds(Number.isNaN(id) ? [] : [id]);
                        }
                    });
                }

                var commissionInput = document.getElementById('warehouse-commission-input');
                var modalCommission = document.getElementById('warehouse-add-modal-commission');

                function syncCommissionTargets() {
                    if (commissionInput && modalCommission) {
                        modalCommission.value = commissionInput.value || modalCommission.value;
                    }
                }

                if (commissionInput) {
                    commissionInput.addEventListener('input', syncCommissionTargets);
                    commissionInput.addEventListener('change', syncCommissionTargets);
                    syncCommissionTargets();
                }

                var selectAll = document.getElementById('warehouse-select-all');
                var rowCheckboxes = document.querySelectorAll('.warehouse-row-checkbox');

                if (selectAll && rowCheckboxes.length) {
                    selectAll.addEventListener('change', function () {
                        rowCheckboxes.forEach(function (cb) {
                            cb.checked = selectAll.checked;
                        });
                    });
                }

                var bulkOpenBtn = document.getElementById('warehouse-bulk-open-modal-btn');

                if (bulkOpenBtn) {
                    bulkOpenBtn.addEventListener('click', function () {
                        var ids = Array.from(rowCheckboxes)
                            .filter(function (cb) { return cb.checked; })
                            .map(function (cb) { return parseInt(cb.value, 10); })
                            .filter(function (id) { return !Number.isNaN(id); });

                        if (typeof window.openWarehouseAddModalWithIds === 'function') {
                            window.openWarehouseAddModalWithIds(ids);
                        }
                    });
                }

                var form = document.getElementById('warehouse-commission-form');
                var submitBtn = document.getElementById('warehouse-commission-save-btn');

                if (!form || !submitBtn) {
                    return;
                }

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    submitBtn.disabled = true;

                    try {
                        var response = await fetch(form.dataset.saveUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new FormData(form),
                            credentials: 'same-origin',
                        });

                        var payload = await response.json().catch(function () { return {}; });

                        if (!response.ok) {
                            var message = payload.message
                                || (payload.errors && payload.errors.commission_percent ? payload.errors.commission_percent[0] : 'Error');

                            notify('error', message);

                            return;
                        }

                        notify('success', payload.message || 'Saved');
                    } catch (error) {
                        notify('error', 'Error');
                    } finally {
                        submitBtn.disabled = false;
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
            } else {
                boot();
            }
        })();
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/seller/product-warehouse/index.blade.php ENDPATH**/ ?>