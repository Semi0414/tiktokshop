<!-- Total Orders Vue Component -->
<v-reporting-sales-total-orders>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.sales.total-orders','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.sales.total-orders'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5)): ?>
<?php $attributes = $__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5; ?>
<?php unset($__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5)): ?>
<?php $component = $__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5; ?>
<?php unset($__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5); ?>
<?php endif; ?> 
</v-reporting-sales-total-orders>

<?php if (! $__env->hasRenderedOnce('21944c90-6caa-4a95-89bc-f31ba422b005')): $__env->markAsRenderedOnce('21944c90-6caa-4a95-89bc-f31ba422b005');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-reporting-sales-total-orders-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.sales.total-orders','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.sales.total-orders'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5)): ?>
<?php $attributes = $__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5; ?>
<?php unset($__attributesOriginal537a6ece29bdfb8a2bfb67f8120486a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5)): ?>
<?php $component = $__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5; ?>
<?php unset($__componentOriginal537a6ece29bdfb8a2bfb67f8120486a5); ?>
<?php endif; ?> 
        </template>

        <!-- Total Orders Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.reporting.sales.index.total-orders'); ?>
                    </p>

                    <a
                        href="<?php echo e(route('superadmin.reporting.sales.view', ['type' => 'total-orders'])); ?>"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        <?php echo app('translator')->get('superadmin::app.reporting.sales.index.view-details'); ?>
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex justify-between gap-4">
                        <p class="text-3xl font-bold leading-9 text-gray-600 dark:text-gray-300">
                            {{ report.statistics.orders.current }}
                        </p>
                        
                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base text-emerald-500"
                                :class="[report.statistics.orders.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-base text-emerald-500"
                                :class="[report.statistics.orders.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                {{ Math.abs(report.statistics.orders.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('superadmin::app.reporting.sales.index.orders-over-time'); ?>
                    </p>

                    <!-- Line Chart -->
                    <?php if (isset($component)) { $__componentOriginal490b31c3a1821621d191414e369830af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal490b31c3a1821621d191414e369830af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.charts.line','data' => [':labels' => 'chartLabels',':datasets' => 'chartDatasets']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::charts.line'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':labels' => 'chartLabels',':datasets' => 'chartDatasets']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal490b31c3a1821621d191414e369830af)): ?>
<?php $attributes = $__attributesOriginal490b31c3a1821621d191414e369830af; ?>
<?php unset($__attributesOriginal490b31c3a1821621d191414e369830af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal490b31c3a1821621d191414e369830af)): ?>
<?php $component = $__componentOriginal490b31c3a1821621d191414e369830af; ?>
<?php unset($__componentOriginal490b31c3a1821621d191414e369830af); ?>
<?php endif; ?>

                    <!-- Date Range -->
                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                {{ report.date_range.previous }}
                            </p>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-sky-400"></span>

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
        window.app.component('v-reporting-sales-total-orders', {
            template: '#v-reporting-sales-total-orders-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.current.map(({ label }) => label);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.current.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#0E9CFF',
                        backgroundColor: 'rgba(14, 156, 255, 0.3)',
                        fill: true,
                    }, {
                        data: this.report.statistics.over_time.previous.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#34D399',
                        backgroundColor: 'rgba(52, 211, 153, 0.3)',
                        fill: true,
                    }];
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

                    filters.type = 'total-orders';

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
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/reporting/sales/total-orders.blade.php ENDPATH**/ ?>