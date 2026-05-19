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
        <?php echo app('translator')->get('superadmin::app.reporting.customers.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <!-- Page Header -->
    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="pt-1.5 text-xl font-bold leading-6 text-gray-800 dark:text-white">
                <?php echo app('translator')->get('superadmin::app.reporting.customers.index.title'); ?>
            </p>
        </div>

        <!-- Actions -->
        <v-reporting-filters>
            <!-- Shimmer -->
            <div class="flex gap-1.5">
                <div class="shimmer h-[39px] w-[132px] rounded-md"></div>
                <div class="shimmer h-[39px] w-[140px] rounded-md"></div>
                <div class="shimmer h-[39px] w-[140px] rounded-md"></div>
            </div>
        </v-reporting-filters>
    </div>

    <!-- Customers Stats Vue Component -->
    <div class="flex flex-1 flex-col gap-4 max-xl:flex-auto">
        <!-- Customers Section -->
        <?php echo $__env->make('superadmin::reporting.customers.total-customers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Customers With Most Sales and Customers With Most Orders Sections Container -->
        <div class="flex flex-col justify-between gap-4 flex-1 [&>*]:flex-1 md:flex-row">
            <!-- Customers With Most Sales Section -->
            <?php echo $__env->make('superadmin::reporting.customers.most-sales', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Customers With Most Orders Section -->
            <?php echo $__env->make('superadmin::reporting.customers.most-orders', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Customers Traffic Section -->
        <?php echo $__env->make('superadmin::reporting.customers.total-traffic', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Top Customer Groups Sections Container -->
        <div class="flex flex-col justify-between gap-4 flex-1 [&>*]:flex-1 md:flex-row">
            <!-- Top Customer Groups Section -->
            <?php echo $__env->make('superadmin::reporting.customers.top-customer-groups', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Customer with Most Reviews Section -->
            <?php echo $__env->make('superadmin::reporting.customers.most-reviews', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php if (! $__env->hasRenderedOnce('3a99eec8-1b19-43de-bde3-92f6b5c148fe')): $__env->markAsRenderedOnce('3a99eec8-1b19-43de-bde3-92f6b5c148fe');
$__env->startPush('scripts'); ?>
        <script
            type="module"
            src="<?php echo e(bagisto_asset('js/chart.js')); ?>"
        >
        </script>

        <script
            type="text/x-template"
            id="v-reporting-filters-template"
        >
            <div class="flex gap-1.5">
                <template v-if="channels.length > 2">
                    <?php if (isset($component)) { $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.index','data' => ['position' => 'bottom-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-right']); ?>
                         <?php $__env->slot('toggle', null, []); ?> 
                            <button
                                type="button"
                                class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center text-sm leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                            >
                                {{ channels.find(channel => channel.code == filters.channel).name }}
                                
                                <span class="icon-sort-down text-2xl"></span>
                            </button>
                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('menu', null, ['class' => '!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800']); ?> 
                            <?php if (isset($component)) { $__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.menu.item','data' => ['vFor' => 'channel in channels',':class' => '{\'bg-gray-100 dark:bg-gray-950\': channel.code == filters.channel}','@click' => 'filters.channel = channel.code']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-for' => 'channel in channels',':class' => '{\'bg-gray-100 dark:bg-gray-950\': channel.code == filters.channel}','@click' => 'filters.channel = channel.code']); ?>
                                {{ channel.name }}
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a)): ?>
<?php $attributes = $__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a; ?>
<?php unset($__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a)): ?>
<?php $component = $__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a; ?>
<?php unset($__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $attributes = $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250)): ?>
<?php $component = $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250; ?>
<?php unset($__componentOriginal9bfe85cfafbe99454a265eb9c32f1250); ?>
<?php endif; ?>
                </template>

                <?php if (isset($component)) { $__componentOriginal688dd9cb124feb288582693d3317b61a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal688dd9cb124feb288582693d3317b61a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.date','data' => ['class' => '!w-[140px]',':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!w-[140px]',':allow-input' => 'false']); ?>
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.start"
                        placeholder="<?php echo app('translator')->get('superadmin::app.reporting.customers.index.start-date'); ?>"
                    />
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal688dd9cb124feb288582693d3317b61a)): ?>
<?php $attributes = $__attributesOriginal688dd9cb124feb288582693d3317b61a; ?>
<?php unset($__attributesOriginal688dd9cb124feb288582693d3317b61a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal688dd9cb124feb288582693d3317b61a)): ?>
<?php $component = $__componentOriginal688dd9cb124feb288582693d3317b61a; ?>
<?php unset($__componentOriginal688dd9cb124feb288582693d3317b61a); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal688dd9cb124feb288582693d3317b61a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal688dd9cb124feb288582693d3317b61a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.date','data' => ['class' => '!w-[140px]',':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!w-[140px]',':allow-input' => 'false']); ?>
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.end"
                        placeholder="<?php echo app('translator')->get('superadmin::app.reporting.customers.index.end-date'); ?>"
                    />
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal688dd9cb124feb288582693d3317b61a)): ?>
<?php $attributes = $__attributesOriginal688dd9cb124feb288582693d3317b61a; ?>
<?php unset($__attributesOriginal688dd9cb124feb288582693d3317b61a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal688dd9cb124feb288582693d3317b61a)): ?>
<?php $component = $__componentOriginal688dd9cb124feb288582693d3317b61a; ?>
<?php unset($__componentOriginal688dd9cb124feb288582693d3317b61a); ?>
<?php endif; ?>
            </div>
        </script>

        <script type="module">
            window.app.component('v-reporting-filters', {
                template: '#v-reporting-filters-template',

                data() {
                    return {
                        channels: [
                            {
                                name: "<?php echo app('translator')->get('superadmin::app.reporting.customers.index.all-channels'); ?>",
                                code: ''
                            },
                            ...<?php echo json_encode(core()->getAllChannels(), 15, 512) ?>,
                        ],
                        
                        filters: {
                            channel: '',

                            start: "<?php echo e($startDate->format('Y-m-d')); ?>",
                            
                            end: "<?php echo e($endDate->format('Y-m-d')); ?>",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>
    <?php $__env->stopPush(); endif; ?>
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
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/reporting/customers/index.blade.php ENDPATH**/ ?>