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
        <?php echo app('translator')->get('admin::app.dashboard.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php if (isset($component)) { $__componentOriginal495cd32d7d07ecc671177d8a7089c957 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal495cd32d7d07ecc671177d8a7089c957 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.seller.panel','data' => ['active' => 'dashboard','breadcrumb' => [__('admin::app.dashboard.index.title')]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::seller.panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active' => 'dashboard','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([__('admin::app.dashboard.index.title')])]); ?>
        <div class="seller-home-dashboard">
            <p class="seller-home-dashboard__period">
                <?php echo app('translator')->get('admin::app.seller-panel.dashboard.period-range', [
                    'from' => $startDate->format('d M Y'),
                    'to' => $endDate->format('d M Y'),
                ]); ?>
            </p>

            
            <div class="seller-home-dashboard__sr-only" aria-hidden="true">
                <?php echo app('translator')->get('admin::app.dashboard.index.overall-details'); ?>
                <?php echo app('translator')->get('admin::app.dashboard.index.total-sales'); ?>
                <?php echo app('translator')->get('admin::app.dashboard.index.product-image'); ?>
                <?php echo app('translator')->get('admin::app.dashboard.index.today-sales'); ?>
            </div>

            <div class="seller-home-dashboard__grid-4">
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--rose">
                    <p class="seller-home-dashboard__stat-label"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.total-products'); ?></p>
                    <p class="seller-home-dashboard__stat-value"><?php echo e(number_format($dashboardStats['period']['total_products'] ?? 0)); ?></p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--blue">
                    <p class="seller-home-dashboard__stat-label"><?php echo app('translator')->get('admin::app.dashboard.index.total-sales'); ?></p>
                    <p class="seller-home-dashboard__stat-value"><?php echo e($dashboardStats['period']['formatted_total_sales'] ?? core()->formatBasePrice(0)); ?></p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--indigo">
                    <p class="seller-home-dashboard__stat-label"><?php echo app('translator')->get('admin::app.dashboard.index.total-orders'); ?></p>
                    <p class="seller-home-dashboard__stat-value"><?php echo e(number_format($dashboardStats['period']['total_orders'] ?? 0)); ?></p>
                </div>
                <div class="seller-home-dashboard__stat seller-home-dashboard__stat--teal">
                    <p class="seller-home-dashboard__stat-label"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.total-profit'); ?></p>
                    <p class="seller-home-dashboard__stat-value"><?php echo e($dashboardStats['period']['formatted_estimated_profit'] ?? core()->formatBasePrice(0)); ?></p>
                </div>
            </div>

            <div class="seller-home-dashboard__grid-3">
                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.shop-overview'); ?></h3>
                    <div class="seller-home-dashboard__card-row">
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                <?php if(($dashboardStats['shop']['overall_rating'] ?? null) !== null): ?>
                                    <?php echo e($dashboardStats['shop']['overall_rating']); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.overall-rating'); ?></p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                <?php if(($dashboardStats['shop']['credit_score'] ?? null) !== null && $dashboardStats['shop']['credit_score'] !== ''): ?>
                                    <?php echo e($dashboardStats['shop']['credit_score']); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.seller-credit-score'); ?></p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value">
                                <?php if(($dashboardStats['shop']['store_followers'] ?? null) !== null): ?>
                                    <?php echo e(number_format($dashboardStats['shop']['store_followers'])); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.store-follow'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.visitor-statistics'); ?></h3>
                    <?php if($isPlatformAdmin): ?>
                        <div class="seller-home-dashboard__card-row">
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format((int) ($dashboardStats['visitors']['today'] ?? 0))); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.todays-visitors'); ?></p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format((int) ($dashboardStats['visitors']['last_7_days'] ?? 0))); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.last-7-days'); ?></p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format((int) ($dashboardStats['visitors']['last_30_days'] ?? 0))); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.last-30-days'); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="seller-home-dashboard__note"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.visitors-not-tracked'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                    <h3 class="seller-home-dashboard__card-title"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.today-statistics'); ?></h3>
                    <div class="seller-home-dashboard__card-row">
                        <div>
                            <p class="seller-home-dashboard__accent-value"><?php echo e(number_format($dashboardStats['today']['orders'] ?? 0)); ?></p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.todays-order'); ?></p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value"><?php echo e($dashboardStats['today']['formatted_sales'] ?? core()->formatBasePrice(0)); ?></p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.todays-sales'); ?></p>
                        </div>
                        <div>
                            <p class="seller-home-dashboard__accent-value"><?php echo e($dashboardStats['today']['formatted_profit'] ?? core()->formatBasePrice(0)); ?></p>
                            <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.estimated-profit'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="seller-home-dashboard__split">
                <div class="seller-home-dashboard__card seller-home-dashboard__card--table">
                    <h3 class="seller-home-dashboard__table-title"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.top-best-selling'); ?></h3>
                    <div class="seller-home-dashboard__table-wrap">
                        <table class="seller-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo app('translator')->get('admin::app.seller-panel.filters.product-name'); ?></th>
                                    <th><?php echo app('translator')->get('admin::app.seller-panel.dashboard.price'); ?></th>
                                    <th class="seller-home-dashboard__th-num"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.sales-volume'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $dashboardStats['top_selling'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($idx + 1); ?></td>
                                        <td class="seller-home-dashboard__cell-name"><?php echo e($row['name'] ?? '—'); ?></td>
                                        <td><?php echo e($row['formatted_price'] ?? '—'); ?></td>
                                        <td class="seller-home-dashboard__cell-num"><?php echo e($row['formatted_sales_volume'] ?? '—'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="seller-home-dashboard__empty"><?php echo app('translator')->get('admin::app.seller-panel.empty'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="seller-home-dashboard__aside">
                    <div class="seller-home-dashboard__card seller-home-dashboard__card--accent">
                        <h3 class="seller-home-dashboard__aside-title"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.order-statistics'); ?></h3>
                        <div class="seller-home-dashboard__order-grid">
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format($dashboardStats['order_status']['total'] ?? 0)); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.dashboard.index.total-orders'); ?></p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format($dashboardStats['order_status']['in_process'] ?? 0)); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.in-process'); ?></p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format($dashboardStats['order_status']['completed'] ?? 0)); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.completed'); ?></p>
                            </div>
                            <div>
                                <p class="seller-home-dashboard__accent-value"><?php echo e(number_format($dashboardStats['order_status']['canceled'] ?? 0)); ?></p>
                                <p class="seller-home-dashboard__muted"><?php echo app('translator')->get('admin::app.seller-panel.dashboard.cancel-order'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="seller-home-dashboard__verified-card">
                        <div class="seller-home-dashboard__verified-orbit">
                            <div class="seller-home-dashboard__verified-orbit-inner" aria-hidden="true"></div>
                            <div class="seller-home-dashboard__verified-text">
                                <div class="seller-home-dashboard__verified-stars-row">
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                </div>
                                <h4 class="seller-home-dashboard__verified-heading">
                                    <?php echo app('translator')->get('admin::app.seller-panel.dashboard.verified'); ?>
                                </h4>
                                <div class="seller-home-dashboard__verified-stars-row seller-home-dashboard__verified-stars-row--bottom">
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                    <span class="seller-home-dashboard__verified-star">★</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    <?php if (! $__env->hasRenderedOnce('24e0c988-2352-4e55-90e2-94cafebf8115')): $__env->markAsRenderedOnce('24e0c988-2352-4e55-90e2-94cafebf8115');
$__env->startPush('styles'); ?>
        <style>
            /* Scoped to this page only — avoids clashing with admin bundle CSS */
            .seller-panel-scope .seller-home-dashboard {
                font-size: 14px;
                color: #111827;
            }
            .dark .seller-panel-scope .seller-home-dashboard {
                color: #f3f4f6;
            }
            .seller-home-dashboard__period {
                margin: 0 0 1rem;
                font-size: 0.8125rem;
                color: #6b7280;
            }
            .dark .seller-home-dashboard__period {
                color: #9ca3af;
            }
            .seller-home-dashboard__sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
            .seller-home-dashboard__grid-4 {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 768px) {
                .seller-home-dashboard__grid-4 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__grid-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }
            .seller-home-dashboard__stat {
                border-radius: 0.5rem;
                padding: 1.5rem;
                color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            }
            .seller-home-dashboard__stat--rose { background: #fb7185; }
            .seller-home-dashboard__stat--blue { background: #60a5fa; }
            .seller-home-dashboard__stat--indigo { background: #6366f1; }
            .seller-home-dashboard__stat--teal { background: #2dd4bf; }
            .seller-home-dashboard__stat-label {
                margin: 0 0 0.5rem;
                font-size: 0.8125rem;
                opacity: 0.95;
            }
            .seller-home-dashboard__stat-value {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 700;
                line-height: 1.2;
            }
            .seller-home-dashboard__grid-3 {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__grid-3 {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
            .seller-home-dashboard__card {
                background: #fff;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                padding: 1.25rem 1.5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            }
            .dark .seller-home-dashboard__card {
                background: #111827;
                border-color: #374151;
            }
            .seller-home-dashboard__card--accent {
                border-top: 3px solid #fb923c;
            }
            .seller-home-dashboard__card-title {
                margin: 0 0 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e5e7eb;
                font-size: 0.9375rem;
                font-weight: 600;
                color: #6b7280;
            }
            .dark .seller-home-dashboard__card-title {
                border-color: #374151;
                color: #9ca3af;
            }
            .seller-home-dashboard__card-row {
                display: flex;
                justify-content: space-between;
                gap: 0.75rem;
                text-align: center;
            }
            .seller-home-dashboard__accent-value {
                margin: 0 0 0.25rem;
                font-size: 1.25rem;
                font-weight: 700;
                color: #ea580c;
            }
            .seller-home-dashboard__muted {
                margin: 0;
                font-size: 0.6875rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #9ca3af;
            }
            .seller-home-dashboard__note {
                margin: 0;
                font-size: 0.875rem;
                color: #6b7280;
                line-height: 1.5;
            }
            .seller-home-dashboard__split {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__split {
                    flex-direction: row;
                    align-items: flex-start;
                }
            }
            .seller-home-dashboard__card--table {
                flex: 1;
                min-width: 0;
            }
            .seller-home-dashboard__table-title {
                margin: 0 0 1rem;
                font-size: 1rem;
                font-weight: 700;
                color: #374151;
            }
            .dark .seller-home-dashboard__table-title {
                color: #e5e7eb;
            }
            .seller-home-dashboard__table-wrap {
                overflow-x: auto;
            }
            .seller-home-dashboard__th-num,
            .seller-home-dashboard__cell-num {
                text-align: right;
            }
            .seller-home-dashboard__cell-name {
                color: #2563eb;
            }
            .dark .seller-home-dashboard__cell-name {
                color: #93c5fd;
            }
            .seller-home-dashboard__empty {
                text-align: center;
                color: #6b7280;
                padding: 1rem !important;
            }
            .seller-home-dashboard__aside {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            @media (min-width: 1024px) {
                .seller-home-dashboard__aside {
                    width: 20rem;
                    flex-shrink: 0;
                }
            }
            .seller-home-dashboard__aside-title {
                margin: 0 0 1.25rem;
                text-align: center;
                font-size: 1rem;
                font-weight: 700;
                color: #6b7280;
            }
            .seller-home-dashboard__order-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem 1rem;
                text-align: center;
            }
            /* Static “Verified” badge (reference layout, scoped — no Tailwind) */
            .seller-home-dashboard__verified-card {
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                border-top: 3px solid #fb923c;
                background: #fff;
                padding: 3rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            .dark .seller-home-dashboard__verified-card {
                border-color: #374151;
                background: #111827;
            }
            .seller-home-dashboard__verified-orbit {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 8rem;
                height: 8rem;
                border: 4px dashed #3b82f6;
                border-radius: 50%;
                transform: rotate(-12deg);
            }
            .seller-home-dashboard__verified-orbit-inner {
                position: absolute;
                inset: 0.25rem;
                border: 2px solid #60a5fa;
                border-radius: 50%;
                opacity: 0.5;
                pointer-events: none;
            }
            .seller-home-dashboard__verified-text {
                position: relative;
                z-index: 1;
                text-align: center;
            }
            .seller-home-dashboard__verified-stars-row {
                display: flex;
                justify-content: center;
                gap: 0.25rem;
                margin-bottom: 0.25rem;
            }
            .seller-home-dashboard__verified-stars-row--bottom {
                margin-top: 0.25rem;
                margin-bottom: 0;
            }
            .seller-home-dashboard__verified-star {
                font-size: 10px;
                line-height: 1;
                color: #3b82f6;
            }
            .seller-home-dashboard__verified-heading {
                margin: 0.25rem 0;
                font-size: 1.25rem;
                font-weight: 900;
                line-height: 1;
                text-transform: uppercase;
                letter-spacing: -0.02em;
                color: #2563eb;
            }
        </style>
    <?php $__env->stopPush(); endif; ?>
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
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/dashboard/index.blade.php ENDPATH**/ ?>