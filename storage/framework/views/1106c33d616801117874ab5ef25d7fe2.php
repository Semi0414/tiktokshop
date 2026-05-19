<!-- Customers with Most Sales Vue Component -->
<v-reporting-customers-with-most-sales>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.customers.most-sales','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.customers.most-sales'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3)): ?>
<?php $attributes = $__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3; ?>
<?php unset($__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3)): ?>
<?php $component = $__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3; ?>
<?php unset($__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3); ?>
<?php endif; ?>
</v-reporting-customers-with-most-sales>

<?php if (! $__env->hasRenderedOnce('0f706322-930f-4680-a94e-ce49544ad41e')): $__env->markAsRenderedOnce('0f706322-930f-4680-a94e-ce49544ad41e');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-reporting-customers-with-most-sales-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.customers.most-sales','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.customers.most-sales'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3)): ?>
<?php $attributes = $__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3; ?>
<?php unset($__attributesOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3)): ?>
<?php $component = $__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3; ?>
<?php unset($__componentOriginalcf0176f8bdecdea7fbc6e28e04e7fbe3); ?>
<?php endif; ?>
        </template>

        <!-- Customers with Most Sales Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.reporting.customers.index.customers-with-most-sales'); ?>
                    </p>

                    <a
                        href="<?php echo e(route('superadmin.reporting.customers.view', ['type' => 'customers-with-most-sales'])); ?>"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        <?php echo app('translator')->get('superadmin::app.reporting.customers.index.view-details'); ?>
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Customers with Most Sales -->
                    <template v-if="report.statistics.length">
                        <!-- Customers -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="customer in report.statistics"
                            >
                                <p class="dark:text-white">
                                    {{ customer.full_name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': customer.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        {{ customer.formatted_total }}
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
        window.app.component('v-reporting-customers-with-most-sales', {
            template: '#v-reporting-customers-with-most-sales-template',

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

                    filters.type = 'customers-with-most-sales';

                    this.$axios.get("<?php echo e(route('superadmin.reporting.customers.stats')); ?>", {
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
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/reporting/customers/most-sales.blade.php ENDPATH**/ ?>