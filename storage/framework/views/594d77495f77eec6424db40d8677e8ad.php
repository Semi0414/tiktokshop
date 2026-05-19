<!-- Top Payment Methods Vue Component -->
<v-reporting-sales-top-payment-methods>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal26728d85d1b6a7fdfe40cbec28a87989 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.sales.top-payment-methods','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.sales.top-payment-methods'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989)): ?>
<?php $attributes = $__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989; ?>
<?php unset($__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26728d85d1b6a7fdfe40cbec28a87989)): ?>
<?php $component = $__componentOriginal26728d85d1b6a7fdfe40cbec28a87989; ?>
<?php unset($__componentOriginal26728d85d1b6a7fdfe40cbec28a87989); ?>
<?php endif; ?>
</v-reporting-sales-top-payment-methods>

<?php if (! $__env->hasRenderedOnce('0efef344-7861-49d0-8356-e2f9e1bf2ad6')): $__env->markAsRenderedOnce('0efef344-7861-49d0-8356-e2f9e1bf2ad6');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-reporting-sales-top-payment-methods-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal26728d85d1b6a7fdfe40cbec28a87989 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.sales.top-payment-methods','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.sales.top-payment-methods'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989)): ?>
<?php $attributes = $__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989; ?>
<?php unset($__attributesOriginal26728d85d1b6a7fdfe40cbec28a87989); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26728d85d1b6a7fdfe40cbec28a87989)): ?>
<?php $component = $__componentOriginal26728d85d1b6a7fdfe40cbec28a87989; ?>
<?php unset($__componentOriginal26728d85d1b6a7fdfe40cbec28a87989); ?>
<?php endif; ?>
        </template>

        <!-- Top Payment Methods Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.reporting.sales.index.top-payment-methods'); ?>
                    </p>

                    <a
                        href="<?php echo e(route('superadmin.reporting.sales.view', ['type' => 'top-payment-methods'])); ?>"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        <?php echo app('translator')->get('superadmin::app.reporting.sales.index.view-details'); ?>
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Top Payment Methods -->
                    <template v-if="report.statistics.length">
                        <!-- Payment Methods -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="method in report.statistics"
                            >
                                <p class="dark:text-white">
                                    {{ method.title }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': method.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        {{ method.formatted_total }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template v-else>
                        <?php echo $__env->make('superadmin::reporting.empty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </template>

                    <!-- Date Range -->
                    <div class="flex justify-end gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                {{ report.date_range.current }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        window.app.component('v-reporting-sales-top-payment-methods', {
            template: '#v-reporting-sales-top-payment-methods-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'top-payment-methods';

                    this.$axios.get("<?php echo e(route('superadmin.reporting.sales.stats')); ?>", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/reporting/sales/top-payment-methods.blade.php ENDPATH**/ ?>