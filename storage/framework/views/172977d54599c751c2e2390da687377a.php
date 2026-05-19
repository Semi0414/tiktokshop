<!-- Top Customers Vue Component -->
<v-reporting-customers-top-customer-groups>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal1850c7f0306b53626fcac54e472db028 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1850c7f0306b53626fcac54e472db028 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.customers.top-customer-groups','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.customers.top-customer-groups'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1850c7f0306b53626fcac54e472db028)): ?>
<?php $attributes = $__attributesOriginal1850c7f0306b53626fcac54e472db028; ?>
<?php unset($__attributesOriginal1850c7f0306b53626fcac54e472db028); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1850c7f0306b53626fcac54e472db028)): ?>
<?php $component = $__componentOriginal1850c7f0306b53626fcac54e472db028; ?>
<?php unset($__componentOriginal1850c7f0306b53626fcac54e472db028); ?>
<?php endif; ?>
</v-reporting-customers-top-customer-groups>

<?php if (! $__env->hasRenderedOnce('aaeb8d04-646c-4159-80fb-6ba352af815e')): $__env->markAsRenderedOnce('aaeb8d04-646c-4159-80fb-6ba352af815e');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-reporting-customers-top-customer-groups-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal1850c7f0306b53626fcac54e472db028 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1850c7f0306b53626fcac54e472db028 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.reporting.customers.top-customer-groups','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.reporting.customers.top-customer-groups'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1850c7f0306b53626fcac54e472db028)): ?>
<?php $attributes = $__attributesOriginal1850c7f0306b53626fcac54e472db028; ?>
<?php unset($__attributesOriginal1850c7f0306b53626fcac54e472db028); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1850c7f0306b53626fcac54e472db028)): ?>
<?php $component = $__componentOriginal1850c7f0306b53626fcac54e472db028; ?>
<?php unset($__componentOriginal1850c7f0306b53626fcac54e472db028); ?>
<?php endif; ?>
        </template>
        
        <!-- Top Customers Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.reporting.customers.index.top-customer-groups'); ?>
                    </p>

                    <a
                        href="<?php echo e(route('superadmin.reporting.customers.view', ['type' => 'top-customer-groups'])); ?>"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        <?php echo app('translator')->get('superadmin::app.reporting.customers.index.view-details'); ?>
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Top Customers -->
                    <template v-if="report.statistics.length">
                        <!-- Customers -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="customer in report.statistics"
                            >
                                <p class="dark:text-white">
                                    {{ customer.group_name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': customer.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        {{ customer.total }}
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
        window.app.component('v-reporting-customers-top-customer-groups', {
            template: '#v-reporting-customers-top-customer-groups-template',

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

                    filters.type = 'top-customer-groups';

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
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/reporting/customers/top-customer-groups.blade.php ENDPATH**/ ?>