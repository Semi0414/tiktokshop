<v-datagrid-filter
    :src="src"
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @applyFilters="filter"
    @applySavedFilter="applySavedFilter"
>
    <?php echo e($slot); ?>

</v-datagrid-filter>

<?php if (! $__env->hasRenderedOnce('2e7fd560-1f75-4e81-9f35-1514f0c655e9')): $__env->markAsRenderedOnce('2e7fd560-1f75-4e81-9f35-1514f0c655e9');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-datagrid-filter-template"
    >
        <!-- Empty slot for right toolbar before -->
        <slot name="right-toolbar-left-before"></slot>

        <slot
            name="filter"
            :available="available"
            :applied="applied"
            :filters="filters"
            :apply-filters="applyFilters"
            :apply-column-values="applyColumnValues"
            :find-applied-column="findAppliedColumn"
            :has-any-applied-column-values="hasAnyAppliedColumnValues"
            :get-applied-column-values="getAppliedColumnValues"
            :remove-applied-column-value="removeAppliedColumnValue"
            :remove-applied-column-all-values="removeAppliedColumnAllValues"
        >
            <template v-if="isLoading">
                <?php if (isset($component)) { $__componentOriginal765230c1cd7e008982cbd943c3d76593 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal765230c1cd7e008982cbd943c3d76593 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.toolbar.filter','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.toolbar.filter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal765230c1cd7e008982cbd943c3d76593)): ?>
<?php $attributes = $__attributesOriginal765230c1cd7e008982cbd943c3d76593; ?>
<?php unset($__attributesOriginal765230c1cd7e008982cbd943c3d76593); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal765230c1cd7e008982cbd943c3d76593)): ?>
<?php $component = $__componentOriginal765230c1cd7e008982cbd943c3d76593; ?>
<?php unset($__componentOriginal765230c1cd7e008982cbd943c3d76593); ?>
<?php endif; ?>
            </template>

            <template v-else>
                <?php if (isset($component)) { $__componentOriginal190969edc8078f487b25ed9c8ee6502d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal190969edc8078f487b25ed9c8ee6502d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.drawer.index','data' => ['width' => '350px','ref' => 'filterDrawer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '350px','ref' => 'filterDrawer']); ?>
                     <?php $__env->slot('toggle', null, []); ?> 
                        <div>
                            <div
                                class="relative inline-flex w-full max-w-max cursor-pointer select-none appearance-none items-center justify-between gap-x-1 rounded-md border bg-white px-1 py-1.5 text-center text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3"
                                :class="{'[&>*]:text-blue-600 border-blue-600 [&>*]:dark:text-white': hasAnyAppliedColumn() }"
                            >
                                <span class="icon-filter text-2xl"></span>

                                <span>
                                    <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.title'); ?>
                                </span>

                                <span
                                    class="icon-dot absolute right-2 top-1.5 text-sm font-bold"
                                    v-if="hasAnyAppliedColumn()"
                                >
                                </span>
                            </div>

                            <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow dark:bg-gray-900">
                            </div>
                        </div>
                     <?php $__env->endSlot(); ?>

                     <?php $__env->slot('header', null, []); ?> 
                        <!-- Apply Filter Title -->
                        <div
                            v-if="! isShowSavedFilters"
                            class="flex items-center justify-between px-1 py-2"
                        >
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.title'); ?>
                            </p>
                        </div>

                        <!-- Save Filter Title -->
                        <div v-else class="flex items-center gap-x-2">
                            <span
                                class="icon-arrow-right rtl:icon-arrow-left mt-0.5 cursor-pointer text-3xl hover:rounded-md hover:bg-gray-100 dark:hover:bg-gray-950"
                                @click="backToFilters"
                            >
                            </span>

                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                {{ applied.savedFilterId ? '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.update-filter'); ?>' : '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.save-filter'); ?>' }}
                            </p>
                        </div>
                     <?php $__env->endSlot(); ?>

                     <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
                        <template v-if="! isShowSavedFilters">
                            <!-- Quick Filters Accordion -->
                            <?php if (isset($component)) { $__componentOriginal6bc5caeed19288fe21d12b10619feb38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bc5caeed19288fe21d12b10619feb38 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.accordion.index','data' => ['class' => 'select-none rounded-none !border-none !shadow-none','vIf' => 'savedFilters.available.length > 0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'select-none rounded-none !border-none !shadow-none','v-if' => 'savedFilters.available.length > 0']); ?>
                                 <?php $__env->slot('header', null, ['class' => 'px-4']); ?> 
                                    <p class="w-full text-base font-semibold text-gray-800 dark:text-white">
                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.quick-filters'); ?>
                                    </p>
                                 <?php $__env->endSlot(); ?>

                                 <?php $__env->slot('content', null, ['class' => 'border-b !p-0 dark:border-gray-800']); ?> 
                                    <div class="grid !p-0">
                                        <!-- Listing of Quick Filters (Saved Filters) -->
                                        <div v-for="(filter,index) in savedFilters.available">
                                            <div
                                                class="flex cursor-pointer items-center justify-between px-4 py-1.5 text-gray-700 hover:bg-gray-50 dark:text-white dark:hover:bg-gray-950"
                                                :class="{ 'bg-gray-50 dark:bg-gray-950 font-semibold': applied.savedFilterId == filter.id }"
                                                @click="applySavedFilter(filter)"
                                            >
                                                <span class="text-xs font-medium text-gray-800 dark:text-white">{{ filter.name }}</span>

                                                <span
                                                    class="icon-cross rounded p-1.5 text-lg hover:bg-gray-200 dark:hover:bg-gray-800"
                                                    @click.stop="deleteSavedFilter(filter)"
                                                >
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                 <?php $__env->endSlot(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bc5caeed19288fe21d12b10619feb38)): ?>
<?php $attributes = $__attributesOriginal6bc5caeed19288fe21d12b10619feb38; ?>
<?php unset($__attributesOriginal6bc5caeed19288fe21d12b10619feb38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bc5caeed19288fe21d12b10619feb38)): ?>
<?php $component = $__componentOriginal6bc5caeed19288fe21d12b10619feb38; ?>
<?php unset($__componentOriginal6bc5caeed19288fe21d12b10619feb38); ?>
<?php endif; ?>

                            <!-- Filters Accordion -->
                            <?php if (isset($component)) { $__componentOriginal6bc5caeed19288fe21d12b10619feb38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bc5caeed19288fe21d12b10619feb38 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.accordion.index','data' => ['class' => 'select-none !rounded-none !border-none !shadow-none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'select-none !rounded-none !border-none !shadow-none']); ?>
                                 <?php $__env->slot('header', null, ['class' => 'px-4']); ?> 
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.custom-filters'); ?>
                                    </p>

                                    <div
                                        v-if="hasAnyAppliedColumn() || isFilterDirty"
                                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600 transition-all hover:underline ltr:ml-20 rtl:mr-20"
                                        @click="removeAllAppliedFilters()"
                                    >
                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                    </div>
                                 <?php $__env->endSlot(); ?>

                                 <?php $__env->slot('content', null, ['class' => '!p-4']); ?> 
                                    <!-- All Filters -->
                                    <div v-for="column in available.columns">
                                        <div v-if="column.filterable">
                                            <!-- Boolean -->
                                            <div v-if="column.type === 'boolean'">
                                                <!-- Dropdown -->
                                                <template v-if="column.filterable_type === 'dropdown'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 mt-1.5">
                                                        <?php if (isset($component)) { $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                             <?php $__env->slot('toggle', null, []); ?> 
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                                >
                                                                    <!-- If Allow Multiple Values -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="'<?php echo app('translator')->get('superadmin::app.components.datagrid.filters.select'); ?>'"
                                                                        v-if="column.allow_multiple_values"
                                                                    >
                                                                    </span>

                                                                    <!-- If Allow Single Value -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="column.filterable_options.find((option => option.value === getAppliedColumnValues(column.index)))?.label ?? '<?php echo app('translator')->get('superadmin::app.components.datagrid.filters.select'); ?>'"
                                                                        v-else
                                                                    >
                                                                    </span>

                                                                    <span class="icon-sort-down text-2xl"></span>
                                                                </button>
                                                             <?php $__env->endSlot(); ?>

                                                             <?php $__env->slot('menu', null, ['class' => 'max-h-[200px] overflow-auto']); ?> 
                                                                <?php if (isset($component)) { $__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.menu.item','data' => ['vFor' => 'option in column.filterable_options','vText' => 'option.label','@click' => 'addFilter(option.value, column)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-for' => 'option in column.filterable_options','v-text' => 'option.label','@click' => 'addFilter(option.value, column)']); ?>
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
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <!-- Retrieving the label from the options based on the applied column value. -->
                                                                <span v-text="column.filterable_options.find((option => option.value == appliedColumnValue)).label"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Basic (If Needed) -->
                                                <template v-else></template>
                                            </div>

                                            <!-- Date -->
                                            <div v-else-if="column.type === 'date'">
                                                <!-- Range -->
                                                <template v-if="column.filterable_type === 'date_range'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-1.5 grid grid-cols-2 gap-1.5">
                                                        <p
                                                            class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                            v-for="option in column.filterable_options"
                                                            v-text="option.label"
                                                            @click="addFilter(
                                                                $event,
                                                                column,
                                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                                            )"
                                                        >
                                                        </p>

                                                        <?php if (isset($component)) { $__componentOriginal688dd9cb124feb288582693d3317b61a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal688dd9cb124feb288582693d3317b61a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.date','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="date"
                                                                :name="`${column.index}[from]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                                )"
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.date','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="date"
                                                                :name="`${column.index}[to]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                                )"
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

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    {{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-1.5 grid">
                                                        <?php if (isset($component)) { $__componentOriginal688dd9cb124feb288582693d3317b61a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal688dd9cb124feb288582693d3317b61a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.date','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="date"
                                                                :name="column.index"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="column.index"
                                                                @change="addFilter($event, column)"
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

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    {{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Date Time -->
                                            <div v-else-if="column.type === 'datetime'">
                                                <!-- Range -->
                                                <template v-if="column.filterable_type === 'datetime_range'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="my-4 grid grid-cols-2 gap-1.5">
                                                        <p
                                                            class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                            v-for="option in column.filterable_options"
                                                            v-text="option.label"
                                                            @click="addFilter(
                                                                $event,
                                                                column,
                                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                                            )"
                                                        >
                                                        </p>

                                                        <?php if (isset($component)) { $__componentOriginal9a251111a967186b0e87bbed2482438d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a251111a967186b0e87bbed2482438d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.datetime','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.datetime'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="datetime-local"
                                                                :name="`${column.index}[from]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $attributes = $__attributesOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__attributesOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $component = $__componentOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__componentOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>

                                                        <?php if (isset($component)) { $__componentOriginal9a251111a967186b0e87bbed2482438d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a251111a967186b0e87bbed2482438d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.datetime','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.datetime'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="datetime-local"
                                                                :name="`${column.index}[to]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $attributes = $__attributesOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__attributesOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $component = $__componentOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__componentOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    {{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="my-4 grid">
                                                        <?php if (isset($component)) { $__componentOriginal9a251111a967186b0e87bbed2482438d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a251111a967186b0e87bbed2482438d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.flat-picker.datetime','data' => [':allowInput' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::flat-picker.datetime'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([':allow-input' => 'false']); ?>
                                                            <input
                                                                type="datetime-local"
                                                                :name="column.index"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="column.index"
                                                                @change="addFilter($event, column)"
                                                            />
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $attributes = $__attributesOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__attributesOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a251111a967186b0e87bbed2482438d)): ?>
<?php $component = $__componentOriginal9a251111a967186b0e87bbed2482438d; ?>
<?php unset($__componentOriginal9a251111a967186b0e87bbed2482438d); ?>
<?php endif; ?>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    {{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Rest -->
                                            <div v-else>
                                                <!-- Dropdown -->
                                                <template v-if="column.filterable_type === 'dropdown'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 mt-1.5">
                                                        <?php if (isset($component)) { $__componentOriginal9bfe85cfafbe99454a265eb9c32f1250 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bfe85cfafbe99454a265eb9c32f1250 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                             <?php $__env->slot('toggle', null, []); ?> 
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                                >
                                                                    <!-- If Allow Multiple Values -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="'<?php echo app('translator')->get('superadmin::app.components.datagrid.filters.select'); ?>'"
                                                                        v-if="column.allow_multiple_values"
                                                                    >
                                                                    </span>

                                                                    <!-- If Allow Single Value -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="column.filterable_options.find((option => option.value === getAppliedColumnValues(column.index)))?.label ?? '<?php echo app('translator')->get('superadmin::app.components.datagrid.filters.select'); ?>'"
                                                                        v-else
                                                                    >
                                                                    </span>

                                                                    <span class="icon-sort-down text-2xl"></span>
                                                                </button>
                                                             <?php $__env->endSlot(); ?>

                                                             <?php $__env->slot('menu', null, ['class' => 'max-h-[200px] overflow-auto']); ?> 
                                                                <?php if (isset($component)) { $__componentOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14c042a5b36b77c37dacbe1b7e82cd2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.dropdown.menu.item','data' => ['vFor' => 'option in column.filterable_options','vText' => 'option.label','@click' => 'addFilter(option.value, column)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-for' => 'option in column.filterable_options','v-text' => 'option.label','@click' => 'addFilter(option.value, column)']); ?>
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
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <!-- Retrieving the label from the options based on the applied column value. -->
                                                                <span v-text="column.filterable_options.find((option => option.value == appliedColumnValue)).label"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                <?php echo app('translator')->get('superadmin::app.components.datagrid.filters.custom-filters.clear-all'); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Text type Input field -->
                                                    <div class="mb-2 mt-1.5 grid">
                                                        <input
                                                            type="text"
                                                            class="block w-full rounded-md border bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                            :name="column.index"
                                                            :placeholder="column.label"
                                                            @change="addFilter($event, column)"
                                                        />
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <span v-text="appliedColumnValue"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>

                                                        <!-- If Allow Single Value -->
                                                        <template v-else>
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="getAppliedColumnValues(column.index) !== ''"
                                                            >
                                                                <span v-text="getAppliedColumnValues(column.index)"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, getAppliedColumnValues(column.index))"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons Panel -->
                                    <div class="flex gap-2">
                                        <!-- Apply Filter Button -->
                                        <button
                                            type="button"
                                            class="secondary-button w-full"
                                            @click="applyFilters"
                                            :disabled="! isFilterDirty"
                                        >
                                            <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.apply-filters-btn'); ?>
                                        </button>

                                        <!-- Save Filter Button -->
                                        <button
                                            type="button"
                                            v-if="hasAnyColumn"
                                            class="secondary-button w-full"
                                            @click="isShowSavedFilters = ! isShowSavedFilters"
                                            :disabled="isFilterDirty || ! filters.columns.length > 0"
                                        >
                                            {{ applied.savedFilterId ? '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.update-filter'); ?>' : '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.save-filter'); ?>' }}
                                        </button>
                                    </div>
                                 <?php $__env->endSlot(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bc5caeed19288fe21d12b10619feb38)): ?>
<?php $attributes = $__attributesOriginal6bc5caeed19288fe21d12b10619feb38; ?>
<?php unset($__attributesOriginal6bc5caeed19288fe21d12b10619feb38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bc5caeed19288fe21d12b10619feb38)): ?>
<?php $component = $__componentOriginal6bc5caeed19288fe21d12b10619feb38; ?>
<?php unset($__componentOriginal6bc5caeed19288fe21d12b10619feb38); ?>
<?php endif; ?>
                        </template>

                        <!-- Save Filter Section -->
                        <template v-else>
                            <div class="flex items-center justify-between px-4 py-4">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    {{ applied.savedFilterId ? '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.update-filter'); ?>' : '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.create-new-filter'); ?>' }}
                                </p>
                            </div>

                            <div v-if="hasAnyColumn">
                                <!-- Save Filter Form -->
                                <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div']); ?>
                                    <form @submit="handleSubmit($event, createOrUpdateFilter)">
                                        <div class="flex flex-col gap-4">
                                            <!-- Save Filter Name Input Field -->
                                            <div class="flex flex-col gap-2 border-b px-4 dark:border-gray-800">
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
                                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.name'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'hidden','name' => 'id',':value' => 'applied.savedFilterId']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'hidden','name' => 'id',':value' => 'applied.savedFilterId']); ?>
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

                                                    <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','name' => 'name','id' => 'name',':value' => 'getAppliedSavedFilter?.name','rules' => 'required','label' => trans('superadmin::app.components.datagrid.toolbar.filter.name'),'placeholder' => trans('superadmin::app.components.datagrid.toolbar.filter.name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'name','id' => 'name',':value' => 'getAppliedSavedFilter?.name','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.components.datagrid.toolbar.filter.name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.components.datagrid.toolbar.filter.name'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'name']); ?>
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

                                                <!-- Save Filter Form Submit Button -->
                                                <div class="mb-4 flex content-end items-center justify-end">
                                                    <button
                                                        type="submit"
                                                        class="primary-button"
                                                        aria-label="<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.save-btn'); ?>"
                                                        :disabled="savedFilters.params.filters.columns.every(column => column.value.length === 0)"
                                                    >
                                                        {{ applied.savedFilterId ? '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.update-filter'); ?>' : '<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.save-filter'); ?>' }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="flex flex-col gap-4 px-4">
                                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                                    <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.selected-filters'); ?>
                                                </p>

                                                <div v-if="! savedFilters.params.filters.columns.every(column => column.value.length === 0)">
                                                    <!-- Applied filters label and value listing for saving custom filter. -->
                                                    <div v-for="column in savedFilters.params.filters.columns">
                                                        <div
                                                            class="flex flex-col gap-2"
                                                            v-if="hasAnyValue(column)"
                                                        >
                                                            <p class="text-xs font-medium text-gray-800 dark:text-white">
                                                                {{ column.label }}
                                                            </p>

                                                            <div class="mb-4 flex flex-wrap gap-2">
                                                                <!-- Date & Date Time Case -->
                                                                <template v-if="column.type === 'date' || column.type === 'datetime'">
                                                                    <p class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white">
                                                                        <span>
                                                                            {{ getFormattedDates(column) }}
                                                                        </span>

                                                                        <span
                                                                            class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                            @click="removeSavedFilterColumnValue(column, appliedColumnValue)"
                                                                        >
                                                                        </span>
                                                                    </p>
                                                                </template>

                                                                <!-- Rest Case -->
                                                                <template v-else>
                                                                    <!-- If Allow Multiple Values -->
                                                                    <template v-if="column.allow_multiple_values">
                                                                        <p
                                                                            v-for="appliedColumnValue in column.value"
                                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                        >
                                                                            <span>
                                                                                {{ appliedColumnValue }}
                                                                            </span>

                                                                            <span
                                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                                @click="removeSavedFilterColumnValue(column, appliedColumnValue)"
                                                                            >
                                                                            </span>
                                                                        </p>
                                                                    </template>

                                                                    <!-- If Allow Single Value -->
                                                                    <template v-else>
                                                                        <p class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white">
                                                                            <span>
                                                                                {{ column.value }}
                                                                            </span>

                                                                            <span
                                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                                @click="removeSavedFilterColumnValue(column, column.value)"
                                                                            >
                                                                            </span>
                                                                        </p>
                                                                    </template>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Save Filter Empty Value Placeholder -->
                                                <div v-else>
                                                    <div class="mb-4 flex content-end items-center justify-end">
                                                        <div class="grid">
                                                            <div class="flex items-center gap-5 py-2.5">
                                                                <img
                                                                    src="<?php echo e(bagisto_asset('images/icon-add-product.svg')); ?>"
                                                                    class="h-20 w-20 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                                                                >

                                                                <div class="flex flex-col gap-1.5">
                                                                    <p class="text-base font-semibold text-gray-400">
                                                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.empty-title'); ?>
                                                                    </p>

                                                                    <p class="text-gray-400">
                                                                        <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.filter.empty-description'); ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                        </template>
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
            </template>
        </slot>
    </script>

    <script type="module">
        window.app.component('v-datagrid-filter', {
            template: '#v-datagrid-filter-template',

            props: ['isLoading', 'available', 'applied', 'src'],

            emits: ['applyFilters', 'applySavedFilter'],

            data() {
                return {
                    savedFilters: {
                        available: [],

                        applied: null,

                        params: {
                            filters: {
                                columns: [],
                            },
                        },
                    },

                    filters: {
                        columns: [],
                    },

                    isShowSavedFilters: false,

                    isFilterDirty: false,
                };
            },

            mounted() {
                this.filters.columns = this.getAppliedColumns();

                this.savedFilters.params.filters.columns = JSON.parse(JSON.stringify(this.filters.columns));

                this.getSavedFilters();
            },

            computed: {
                getAppliedSavedFilter() {
                    return this.savedFilters.available.find((filter) => filter.id == this.applied.savedFilterId);
                },
            },

            methods: {
                xsrfToken() {
                    const m = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);

                    return m ? decodeURIComponent(m[1]) : '';
                },

                /**
                 * Has any column.
                 *
                 * @returns {boolean}
                 */
                hasAnyColumn() {
                    return filters.columns.length;
                },

                /**
                 * Get applied columns.
                 *
                 * @returns {object}
                 */
                getAppliedColumns() {
                    return this.applied.filters.columns.filter((column) => column.index !== 'all');
                },

                /**
                 * Has any applied column.
                 *
                 * @returns {boolean}
                 */
                hasAnyAppliedColumn() {
                    return this.getAppliedColumns().length > 0;
                },

                /**
                 * Go back to filters.
                 *
                 * @returns {void}
                 */
                backToFilters() {
                    this.savedFilters.params.filters.columns = JSON.parse(JSON.stringify(this.filters.columns));

                    this.isShowSavedFilters = ! this.isShowSavedFilters;
                },

                /**
                 * Applies the saved filter.
                 *
                 * @param {Object} filter - The filter to be applied.
                 */
                applySavedFilter(filter) {
                    this.$emit('applySavedFilter', filter);
                },

                /**
                 * Remove all applied filters.
                 *
                 * @returns {void}
                 */
                removeAllAppliedFilters() {
                    this.filters = {
                        columns: [],
                    };

                    this.isFilterDirty = true;
                },

                /**
                 * Remove filter option from save filters screen.
                 *
                 * @returns {void}
                 */
                removeSavedFilterColumnValue(column, value) {
                    if (column.allow_multiple_values) {
                        column.value = column.value.filter((columnValue) => columnValue !== value);
                    } else {
                        column.value = '';
                    }
                },

                /**
                 * Save filters to the database.
                 *
                 * @returns {void}
                 */
                async createOrUpdateFilter(params, { setErrors }) {
                    let applied = JSON.parse(JSON.stringify(this.applied));

                    applied.filters.columns = this.savedFilters.params.filters.columns.filter((column) => this.hasAnyValue(column));

                    if (params.id) {
                        params._method = 'PUT';
                    }

                    const url = params.id ? `<?php echo e(route('superadmin.datagrid.saved_filters.update', '')); ?>/${params.id}` : "<?php echo e(route('superadmin.datagrid.saved_filters.store')); ?>";

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-XSRF-TOKEN': this.xsrfToken(),
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                src: this.src,
                                applied,
                                ...params,
                            }),
                        });

                        const payload = await response.json().catch(() => ({}));

                        if (! response.ok) {
                            if (response.status === 422 && payload.errors) {
                                setErrors(payload.errors);

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: payload.message || 'Something went wrong.' });

                            return;
                        }

                        const row = payload.data;

                        if (! params.id) {
                            this.savedFilters.available.push(row);
                        } else {
                            this.savedFilters.available = this.savedFilters.available.map((filter) => {
                                if (filter.id == row.id) {
                                    return row;
                                }

                                return filter;
                            });
                        }

                        this.savedFilters.name = '';

                        this.$emitter.emit('add-flash', { type: 'success', message: payload.message });

                        this.isShowSavedFilters = false;
                    } catch (error) {
                        this.$emitter.emit('add-flash', { type: 'error', message: 'Something went wrong.' });
                    }
                },

                /**
                 * Retrieves the saved filters.
                 *
                 * @returns {void}
                 */
                async getSavedFilters() {
                    try {
                        const url = new URL('<?php echo e(route('superadmin.datagrid.saved_filters.index')); ?>', window.location.origin);

                        url.searchParams.set('src', this.src);

                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-XSRF-TOKEN': this.xsrfToken(),
                            },
                            credentials: 'same-origin',
                        });

                        const payload = await response.json().catch(() => ({}));

                        if (response.ok && Array.isArray(payload.data)) {
                            this.savedFilters.available = payload.data;
                        }
                    } catch (error) {}
                },

                /**
                 * Delete the saved filter.
                 *
                 * @returns {void}
                 */
                deleteSavedFilter(filter) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: async () => {
                            try {
                                const response = await fetch(`<?php echo e(route('superadmin.datagrid.saved_filters.destroy', '')); ?>/${filter.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-XSRF-TOKEN': this.xsrfToken(),
                                    },
                                    credentials: 'same-origin',
                                });

                                const payload = await response.json().catch(() => ({}));

                                if (! response.ok) {
                                    this.$emitter.emit('add-flash', { type: 'error', message: payload.message || 'Something went wrong.' });

                                    return;
                                }

                                this.applySavedFilter(null);

                                this.savedFilters.available = this.savedFilters.available.filter((savedFilter) => savedFilter.id !== filter.id);

                                this.$emitter.emit('add-flash', { type: 'success', message: payload.message });
                            } catch (error) {
                                this.$emitter.emit('add-flash', { type: 'error', message: 'Something went wrong.' });
                            }
                        },
                    });
                },

                /**
                 * Apply all added filters.
                 *
                 * @returns {void}
                 */
                applyFilters() {
                    this.$emit('applyFilters', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Add filter.
                 *
                 * @param {Event} $event
                 * @param {object} column
                 * @param {object} additional
                 * @returns {void}
                 */
                addFilter($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isActive) {
                        let options = quickFilter.selectedFilter;

                        switch (column.type) {
                            case 'date':
                            case 'datetime':
                                this.applyColumnValues(column, options.name);

                                break;

                            default:
                                break;
                        }
                    } else {
                        /**
                         * Here, either a real event will come or a string value. If a string value is present, then
                         * we create a similar event-like structure to avoid any breakage and make it easy to use.
                         */
                        if ($event?.target?.value === undefined) {
                            $event = {
                                target: {
                                    value: $event,
                                }
                            };
                        }

                        this.applyColumnValues(column, $event.target.value, additional);

                        if (column) {
                            $event.target.value = '';
                        }
                    }
                },

                /**
                 * Apply column values.
                 *
                 * @param {object} column
                 * @param {string} requestedValue
                 * @param {object} additional
                 * @returns {void}
                 */
                applyColumnValues(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        requestedValue === undefined ||
                        requestedValue === '' ||
                        (appliedColumn?.allow_multiple_values && appliedColumn?.value.includes(requestedValue)) ||
                        (! appliedColumn?.allow_multiple_values && appliedColumn?.value === requestedValue)
                    ) {
                        return;
                    }

                    switch (column.type) {
                        case 'date':
                        case 'datetime':
                            let { range } = additional;

                            if (appliedColumn) {
                                if (range) {
                                    let appliedRanges = ['', ''];

                                    if (typeof appliedColumn.value !== 'string') {
                                        appliedRanges = appliedColumn.value[0];
                                    }

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    appliedColumn.value = [appliedRanges];
                                } else {
                                    appliedColumn.value = requestedValue;
                                }
                            } else {
                                if (range) {
                                    let appliedRanges = ['', ''];

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    this.filters.columns.push({
                                        index: column.index,
                                        label: column.label,
                                        type: column.type,
                                        value: [appliedRanges]
                                    });
                                } else {
                                    this.filters.columns.push({
                                        index: column.index,
                                        label: column.label,
                                        type: column.type,
                                        value: requestedValue
                                    });
                                }
                            }

                            break;

                        default:
                            if (appliedColumn) {
                                if (appliedColumn.allow_multiple_values) {
                                    appliedColumn.value.push(requestedValue);
                                } else {
                                    appliedColumn.value = requestedValue;
                                }
                            } else {
                                this.filters.columns.push({
                                    index: column.index,
                                    label: column.label,
                                    type: column.type,
                                    value: column.allow_multiple_values ? [requestedValue] : requestedValue,
                                    allow_multiple_values: column.allow_multiple_values,
                                });
                            }

                            break;
                    }

                    this.isFilterDirty = true;
                },

                /**
                 * Get formatted dates.
                 *
                 * @param {object} appliedColumn
                 * @returns {string}
                 */
                getFormattedDates(appliedColumn)
                {
                    if (! appliedColumn) {
                        return '';
                    }

                    if (typeof appliedColumn.value === 'string') {
                        const availableColumn = this.available.columns.find(column => column.index === appliedColumn.index);

                        if (availableColumn.filterable_type === 'date_range' || availableColumn.filterable_type === 'datetime_range') {
                            const option = availableColumn.filterable_options.find(option => option.name === appliedColumn.value);

                            return option.label;
                        }

                        return appliedColumn.value;
                    }

                    if (! appliedColumn.value.length) {
                        return '';
                    }

                    return appliedColumn.value[0].join(' to ');
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {object} column
                 * @returns {boolean}
                 */
                hasAnyValue(column) {
                    if (column.allow_multiple_values) {
                        return column.value.length > 0;
                    }

                    return column.value !== '';
                },

                /**
                 * Find applied column.
                 *
                 * @param {string} columnIndex
                 * @returns {object}
                 */
                findAppliedColumn(columnIndex) {
                    return this.filters.columns.find(column => column.index === columnIndex);
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {boolean}
                 */
                hasAnyAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    if (! appliedColumn) {
                        return false;
                    }

                    return this.hasAnyValue(appliedColumn);
                },

                /**
                 * Get applied values for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {Array}
                 */
                getAppliedColumnValues(columnIndex) {
                    const appliedColumn = this.findAppliedColumn(columnIndex);

                    if (appliedColumn?.allow_multiple_values) {
                        return appliedColumn?.value ?? [];
                    }

                    return appliedColumn?.value ?? '';
                },

                /**
                 * Remove a specific value from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @param {any} appliedColumnValue
                 * @returns {void}
                 */
                removeAppliedColumnValue(columnIndex, appliedColumnValue) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    if (appliedColumn?.type === 'date' || appliedColumn?.type === 'datetime') {
                        appliedColumn.value = [];
                    } else {
                        if (appliedColumn.allow_multiple_values) {
                            appliedColumn.value = appliedColumn?.value.filter(value => value !== appliedColumnValue);
                        } else {
                            appliedColumn.value = '';
                        }
                    }

                    /**
                     * Clean up is done here. If there are no applied values present, there is no point in including the applied column as well.
                     */
                    if (! appliedColumn.value.length) {
                        this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);
                    }

                    this.isFilterDirty = true;
                },

                /**
                 * Remove all values from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {void}
                 */
                removeAppliedColumnAllValues(columnIndex) {
                    this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);

                    this.isFilterDirty = true;
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/datagrid/toolbar/filter.blade.php ENDPATH**/ ?>