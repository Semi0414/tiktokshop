<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['isMultiRow' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['isMultiRow' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<v-datagrid-table
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @selectAll="selectAll"
    @sort="sort"
    @actionSuccess="get"
>
    <?php echo e($slot); ?>

</v-datagrid-table>

<?php if (! $__env->hasRenderedOnce('06709ee4-ad65-4b62-9449-39c04e5a7d85')): $__env->markAsRenderedOnce('06709ee4-ad65-4b62-9449-39c04e5a7d85');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-datagrid-table-template"
    >
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-x-auto rounded bg-white dark:bg-gray-900">
                <slot
                    name="header"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginale16f0709ae5bfdf747b9973bd464f6ad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.table.head','data' => ['isMultiRow' => $isMultiRow]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.table.head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['isMultiRow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isMultiRow)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad)): ?>
<?php $attributes = $__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad; ?>
<?php unset($__attributesOriginale16f0709ae5bfdf747b9973bd464f6ad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale16f0709ae5bfdf747b9973bd464f6ad)): ?>
<?php $component = $__componentOriginale16f0709ae5bfdf747b9973bd464f6ad; ?>
<?php unset($__componentOriginale16f0709ae5bfdf747b9973bd464f6ad); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div
                            class="row grid min-h-[47px] items-center gap-2.5 border-b bg-gray-50 px-4 py-2.5 font-semibold text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(150px, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label for="mass_action_select_all_records">
                                    <input
                                        type="checkbox"
                                        name="mass_action_select_all_records"
                                        id="mass_action_select_all_records"
                                        class="peer hidden"
                                        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                        @change="selectAll"
                                    >

                                    <span
                                        class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                        :class="[
                                            applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600 ' : (
                                                applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-blue-600' : ''
                                            ),
                                        ]"
                                    >
                                    </span>
                                </label>
                            </p>

                            <!-- Columns -->
                            <template v-for="column in available.columns">
                                <p
                                    class="flex items-center gap-1.5 break-words"
                                    :class="{'cursor-pointer select-none hover:text-gray-800 dark:hover:text-white': column.sortable}"
                                    @click="sort(column)"
                                    v-if="column.visibility"
                                >
                                    {{ column.label }}

                                    <i
                                        class="align-text-bottom text-base text-gray-600 dark:text-gray-300"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="column.index == applied.sort.column"
                                    ></i>
                                </p>
                            </template>

                            <!-- Actions -->
                            <p
                                class="place-self-end"
                                v-if="available.actions.length"
                            >
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.table.actions'); ?>
                            </p>
                        </div>
                    </template>
                </slot>

                <slot
                    name="body"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.table.body','data' => ['isMultiRow' => $isMultiRow]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.table.body'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['isMultiRow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isMultiRow)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $attributes = $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $component = $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <template v-if="available.records.length">
                            <div
                                class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                v-for="record in available.records"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(150px, 1fr))`"
                            >
                                <!-- Mass Actions -->
                                <p v-if="available.massActions.length">
                                    <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                        <input
                                            type="checkbox"
                                            :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            :value="record[available.meta.primary_column]"
                                            :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            class="peer hidden"
                                            v-model="applied.massActions.indices"
                                        >

                                        <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600">
                                        </span>
                                    </label>
                                </p>

                                <!-- Columns -->
                                <template v-for="column in available.columns">
                                    <p
                                        class="break-words"
                                        v-html="record[column.index]"
                                        v-if="column.visibility"
                                    >
                                    </p>
                                </template>

                                <!-- Actions -->
                                <p
                                    class="place-self-end"
                                    v-if="available.actions.length"
                                >
                                    <span
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :class="action.icon"
                                        v-text="! action.icon ? action.title : ''"
                                        v-for="action in record.actions"
                                        @click="performAction(action)"
                                    >
                                    </span>
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <div class="row grid border-b px-4 py-4 text-center text-gray-600 dark:border-gray-800 dark:text-gray-300">
                                <p>
                                    <?php echo app('translator')->get('superadmin::app.components.datagrid.table.no-records-available'); ?>
                                </p>
                            </div>
                        </template>
                    </template>
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        window.app.component('v-datagrid-table', {
            template: '#v-datagrid-table-template',

            props: ['isLoading', 'available', 'applied'],

            computed: {
                gridsCount() {
                    let count = this.available.columns.filter((column) => column.visibility).length;

                    if (this.available.actions.length) {
                        ++count;
                    }

                    if (this.available.massActions.length) {
                        ++count;
                    }

                    return count;
                },
            },

            methods: {
                /**
                 * Full document navigation row action (classic POST forms; avoids XHR/json).
                 */
                submitClassicRowAction(action) {
                    const method = String(action.method || 'POST').toLowerCase();

                    const form = document.createElement('form');

                    form.method = 'POST';
                    form.action = action.url;
                    form.style.display = 'none';

                    const csrfToken = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/)?.[1];

                    if (csrfToken) {
                        const tokenInput = document.createElement('input');

                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = decodeURIComponent(csrfToken);
                        form.appendChild(tokenInput);
                    }

                    if (['put', 'patch', 'delete'].includes(method)) {
                        const spoof = document.createElement('input');

                        spoof.type = 'hidden';
                        spoof.name = '_method';
                        spoof.value = method.toUpperCase();
                        form.appendChild(spoof);
                    }

                    document.body.appendChild(form);
                    form.submit();
                },
                /**
                 * Select all records in the datagrid.
                 *
                 * @returns {void}
                 */
                selectAll() {
                    this.$emit('selectAll');
                },

                /**
                 * Perform a sorting operation on the specified column.
                 *
                 * @param {object} column
                 * @returns {void}
                 */
                sort(column) {
                    this.$emit('sort', column);
                },

                /**
                 * Perform the specified action.
                 *
                 * @param {object} action
                 * @returns {void}
                 */
                performAction(action) {
                    const method = String(action.method || 'GET').toLowerCase();

                    switch (method) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                        case 'put':
                        case 'patch':
                        case 'delete':
                            this.$emitter.emit('open-confirm-modal', {
                                agree: () => this.submitClassicRowAction(action),
                            });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/datagrid/table.blade.php ENDPATH**/ ?>