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
        <?php echo app('translator')->get('superadmin::app.sellers.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php
        $sortUrl = function (string $column) use ($sort, $order) {
            if ($sort === $column) {
                $nextOrder = $order === 'asc' ? 'desc' : 'asc';
            } else {
                $nextOrder = 'asc';
            }

            return route('superadmin.sellers.index', array_merge(
                request()->except(['sort', 'order', 'page']),
                ['sort' => $column, 'order' => $nextOrder]
            ));
        };
    ?>

    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('superadmin::app.sellers.index.title'); ?>
            </p>

            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2">
                <form
                    method="get"
                    action="<?php echo e(route('superadmin.sellers.index')); ?>"
                    class="flex items-center gap-2"
                    role="search"
                >
                    <input type="hidden" name="sort" value="<?php echo e($sort); ?>" />
                    <input type="hidden" name="order" value="<?php echo e($order); ?>" />

                    <input
                        type="search"
                        name="q"
                        value="<?php echo e(request('q')); ?>"
                        placeholder="<?php echo e(__('superadmin::app.components.datagrid.toolbar.search.title')); ?>"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white min-w-[200px]"
                    />

                    <button type="submit" class="secondary-button text-sm py-2 px-3">
                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.search.title'); ?>
                    </button>
                </form>

                <?php if(bouncer()->hasPermission('sellers.all.create')): ?>
                    <?php echo $__env->make('superadmin::sellers.index.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <v-create-seller-form
                        ref="createSellerComponent"
                        @seller-created="window.location.reload()"
                    ></v-create-seller-form>

                    <button
                        class="primary-button"
                        type="button"
                        @click="$refs.createSellerComponent.openModal()"
                    >
                        <?php echo app('translator')->get('superadmin::app.sellers.index.create.create-btn'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php
            $sellerTableColspan = \Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code') ? 10 : 9;
        ?>

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-950">
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="<?php echo e($sortUrl('name')); ?>" class="hover:text-gray-900 dark:hover:text-white">
                                <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.name'); ?>
                                <?php if($sort === 'name'): ?>
                                    <span class="text-xs">(<?php echo e($order === 'asc' ? '↑' : '↓'); ?>)</span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="<?php echo e($sortUrl('email')); ?>" class="hover:text-gray-900 dark:hover:text-white">
                                <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.email'); ?>
                                <?php if($sort === 'email'): ?>
                                    <span class="text-xs">(<?php echo e($order === 'asc' ? '↑' : '↓'); ?>)</span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <?php if(\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code')): ?>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                <?php echo app('translator')->get('superadmin::app.sellers.index.datagrid.referral-code'); ?>
                            </th>
                        <?php endif; ?>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.group'); ?>
                        </th>
                        <th scope="col" class="px-4 py-3 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="<?php echo e($sortUrl('status')); ?>" class="hover:text-gray-900 dark:hover:text-white">
                                <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.status'); ?>
                                <?php if($sort === 'status'): ?>
                                    <span class="text-xs">(<?php echo e($order === 'asc' ? '↑' : '↓'); ?>)</span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <?php echo app('translator')->get('superadmin::app.sellers.index.datagrid.balance'); ?>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.order-count'); ?>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.revenue'); ?>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <a href="<?php echo e($sortUrl('id')); ?>" class="hover:text-gray-900 dark:hover:text-white">
                                <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.id'); ?>
                                <?php if($sort === 'id'): ?>
                                    <span class="text-xs">(<?php echo e($order === 'asc' ? '↑' : '↓'); ?>)</span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            <?php echo app('translator')->get('superadmin::app.components.datagrid.table.actions'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $rev = $revenueBySellerId[$seller->id] ?? 0;
                            $ordersCount = isset($seller->seller_orders_count) ? (int) $seller->seller_orders_count : null;
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                <?php echo e($seller->name); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo e($seller->email); ?>

                            </td>
                            <?php if(\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code')): ?>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 font-mono">
                                    <?php echo e($seller->referral_code ?: '—'); ?>

                                </td>
                            <?php endif; ?>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo e(optional($seller->role)->name ?? '—'); ?>

                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if((int) $seller->status === 1): ?>
                                    <span class="label-active">
                                        <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.active'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="label-canceled">
                                        <?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.inactive'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-900 dark:text-white whitespace-nowrap">
                                <?php echo e(core()->formatPrice((float) ($seller->wallet_balance ?? 0))); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-600 dark:text-gray-300">
                                <?php echo e($ordersCount !== null ? $ordersCount : '—'); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-end font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                <?php echo e(core()->formatPrice($rev)); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-end text-gray-600 dark:text-gray-300">
                                <?php echo e($seller->id); ?>

                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="inline-flex items-center gap-1">
                                    <a
                                        href="<?php echo e(route('superadmin.sellers.login_as_seller', $seller->id)); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="icon-login cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800"
                                        title="<?php echo app('translator')->get('superadmin::app.sellers.index.datagrid.login-as-seller'); ?>"
                                    ></a>
                                    <a
                                        href="<?php echo e(route('superadmin.sellers.view', $seller->id)); ?>"
                                        class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800"
                                        title="<?php echo app('translator')->get('superadmin::app.customers.customers.index.datagrid.view'); ?>"
                                    ></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td
                                colspan="<?php echo e($sellerTableColspan); ?>"
                                class="px-4 py-12 text-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.table.no-records-available'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($sellers->hasPages()): ?>
            <div class="mt-2">
                <?php echo e($sellers->links()); ?>

            </div>
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
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sellers/index.blade.php ENDPATH**/ ?>