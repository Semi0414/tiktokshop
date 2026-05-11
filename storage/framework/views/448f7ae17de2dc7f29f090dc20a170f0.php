<v-datagrid-export <?php echo e($attributes); ?>>
    <div class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
        <span class="icon-admin-export text-xl text-gray-600"></span>

        <?php echo app('translator')->get('superadmin::app.export.export'); ?>
    </div>
</v-datagrid-export>

<?php if (! $__env->hasRenderedOnce('4794915f-5cc9-4929-be0c-c0c0c53ef537')): $__env->markAsRenderedOnce('4794915f-5cc9-4929-be0c-c0c0c53ef537');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-datagrid-export-template"
    >
        <div>
            <?php if (isset($component)) { $__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.modal.index','data' => ['ref' => 'exportModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'exportModal']); ?>
                <!-- Modal Toggler -->
                 <?php $__env->slot('toggle', null, []); ?> 
                    <button class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                        <span class="icon-admin-export text-xl text-gray-600"></span>

                        <?php echo app('translator')->get('superadmin::app.export.export'); ?>
                    </button>
                 <?php $__env->endSlot(); ?>

                <!-- Modal Header -->
                 <?php $__env->slot('header', null, []); ?> 
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        <?php echo app('translator')->get('superadmin::app.export.download'); ?>
                    </p>
                 <?php $__env->endSlot(); ?>

                <!-- Modal Content -->
                 <?php $__env->slot('content', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['action' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => '']); ?>
                        <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                            <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'select','name' => 'format','vModel' => 'format']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'select','name' => 'format','v-model' => 'format']); ?>
                                <option value="csv">
                                    <?php echo app('translator')->get('superadmin::app.export.csv'); ?>
                                </option>

                                <option value="xls">
                                    <?php echo app('translator')->get('superadmin::app.export.xls'); ?>
                                </option>

                                <option value="xlsx">
                                    <?php echo app('translator')->get('superadmin::app.export.xlsx'); ?>
                                </option>
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
                 <?php $__env->endSlot(); ?>

                <!-- Modal Footer -->
                 <?php $__env->slot('footer', null, []); ?> 
                    <!-- Save Button -->
                    <?php if (isset($component)) { $__componentOriginal3b6b23477c69b0901e72ab03d3729d36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b6b23477c69b0901e72ab03d3729d36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.button.index','data' => ['buttonType' => 'button','class' => 'primary-button','title' => trans('superadmin::app.export.export'),'@click' => 'download']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['button-type' => 'button','class' => 'primary-button','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.export.export')),'@click' => 'download']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b6b23477c69b0901e72ab03d3729d36)): ?>
<?php $attributes = $__attributesOriginal3b6b23477c69b0901e72ab03d3729d36; ?>
<?php unset($__attributesOriginal3b6b23477c69b0901e72ab03d3729d36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b6b23477c69b0901e72ab03d3729d36)): ?>
<?php $component = $__componentOriginal3b6b23477c69b0901e72ab03d3729d36; ?>
<?php unset($__componentOriginal3b6b23477c69b0901e72ab03d3729d36); ?>
<?php endif; ?>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6)): ?>
<?php $attributes = $__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6; ?>
<?php unset($__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6)): ?>
<?php $component = $__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6; ?>
<?php unset($__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6); ?>
<?php endif; ?>
        </div>
    </script>

    <script type="module">
        window.app.component('v-datagrid-export', {
            template: '#v-datagrid-export-template',

            props: ['src'],

            data() {
                return {
                    format: 'xls',

                    available: null,

                    applied: null,

                    gridPaginationNamespace: null,
                };
            },

            mounted() {
                this.registerEvents();
            },

            methods: {
                /**
                 * Registers events to update properties and trigger the download process.
                 *
                 * @returns {void}
                 */
                registerEvents() {
                    this.$emitter.on('change-datagrid', this.updateProperties);
                },

                /**
                 * Updates the available and applied properties with new values.
                 *
                 * @param {object} data - Object containing available and applied properties.
                 * @returns {void}
                 */
                updateProperties({ src, available, applied, paginationNamespace }) {
                    if (this.src !== src) {
                        return;
                    }

                    this.available = available;

                    this.applied = applied;

                    if (paginationNamespace !== undefined) {
                        this.gridPaginationNamespace = paginationNamespace;
                    }
                },

                /**
                 * Initiates the download process for exporting data.
                 *
                 * @returns {void}
                 */
                download() {
                    const hasRows = (this.available?.records?.length > 0)
                        || (Number(this.available?.meta?.total) > 0);

                    if (! hasRows) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: '<?php echo app('translator')->get('superadmin::app.export.no-records'); ?>' });

                        this.$refs.exportModal.toggle();

                        return;
                    }

                    if (this.$refs.exportModal) {
                        this.$refs.exportModal.toggle();
                    }

                    window.location.assign(this.buildExportUrl());
                },

                /**
                 * Same query shape as the listing datagrid, plus export=1 and format (full navigation, no XHR).
                 *
                 * @returns {string}
                 */
                buildExportUrl() {
                    const base = this.src.split('?')[0];

                    if (this.gridPaginationNamespace) {
                        const ns = this.gridPaginationNamespace;
                        const next = new URLSearchParams();

                        new URLSearchParams(window.location.search).forEach((v, k) => {
                            if (k.startsWith(ns + '[')) {
                                return;
                            }

                            if (k.startsWith('pagination[') || k.startsWith('sort[') || k.startsWith('filters[')) {
                                return;
                            }

                            next.append(k, v);
                        });

                        next.set(`${ns}[pagination][page]`, String(this.applied.pagination.page));
                        next.set(`${ns}[pagination][per_page]`, String(this.applied.pagination.perPage));

                        next.set('export', '1');
                        next.set('format', this.format);

                        const qs = next.toString();

                        return qs ? `${base}?${qs}` : `${base}?export=1&format=${encodeURIComponent(this.format)}`;
                    }

                    let params = {
                        pagination: {
                            page: this.applied.pagination.page,
                            per_page: this.applied.pagination.perPage,
                        },

                        sort: {},

                        filters: {},

                        export: 1,

                        format: this.format,
                    };

                    if (
                        this.applied.sort.column &&
                        this.applied.sort.order
                    ) {
                        params.sort = this.applied.sort;
                    }

                    this.applied.filters.columns.forEach(column => {
                        params.filters[column.index] = column.value;
                    });

                    const urlParams = new URLSearchParams(window.location.search);

                    urlParams.forEach((param, key) => {
                        params[key] = param;
                    });

                    const parts = [];

                    if (params.pagination) {
                        parts.push(`pagination[page]=${encodeURIComponent(params.pagination.page)}`);
                        parts.push(`pagination[per_page]=${encodeURIComponent(params.pagination.perPage)}`);
                    }

                    if (params.sort && params.sort.column && params.sort.order) {
                        parts.push(`sort[column]=${encodeURIComponent(params.sort.column)}`);
                        parts.push(`sort[order]=${encodeURIComponent(params.sort.order)}`);
                    }

                    if (params.filters) {
                        Object.keys(params.filters).forEach((idx) => {
                            let vals = params.filters[idx];

                            if (! Array.isArray(vals)) {
                                if (vals === '' || vals === null || vals === undefined) {
                                    return;
                                }

                                vals = [vals];
                            }

                            vals.forEach((v) => {
                                if (v !== '' && v !== null && v !== undefined) {
                                    parts.push(`filters[${idx}][]=${encodeURIComponent(v)}`);
                                }
                            });
                        });
                    }

                    Object.keys(params).forEach((key) => {
                        if (['pagination', 'sort', 'filters'].includes(key)) {
                            return;
                        }

                        const val = params[key];

                        if (val === undefined || val === null || val === '') {
                            return;
                        }

                        parts.push(`${encodeURIComponent(key)}=${encodeURIComponent(val)}`);
                    });

                    const qs = parts.join('&');

                    return qs ? `${base}?${qs}` : base;
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/datagrid/export/index.blade.php ENDPATH**/ ?>