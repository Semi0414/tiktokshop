<v-datagrid-mass-action
    :available="available"
    :applied="applied"
>
    <?php echo e($slot); ?>

</v-datagrid-mass-action>

<?php if (! $__env->hasRenderedOnce('e05fef01-6c5c-46a1-992d-7b3c3fd857ba')): $__env->markAsRenderedOnce('e05fef01-6c5c-46a1-992d-7b3c3fd857ba');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-datagrid-mass-action-template"
    >
        <slot
            name="mass-action"
            :available="available"
            :applied="applied"
            :mass-actions="applied.massActions"
            :validate-mass-action="validateMassAction"
            :perform-mass-action="performMassAction"
        >
            <div class="flex w-full items-center gap-x-1">
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
                            class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        >
                            <span>
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.mass-actions.select-action'); ?>
                            </span>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                     <?php $__env->endSlot(); ?>

                     <?php $__env->slot('menu', null, ['class' => '!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800 max-h-[min(70vh,24rem)] overflow-y-auto']); ?> 
                        <template v-for="massAction in available.massActions">
                            <li
                                class="group/item relative overflow-visible"
                                v-if="massAction?.options?.length"
                            >
                                <a
                                    class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-1.5 rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"
                                    href="javascript:void(0);"
                                >
                                    <i
                                        class="text-2xl"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    <span>
                                        {{ massAction.title }}
                                    </span>

                                    <i class="icon-arrow-left rtl:icon-arrow-right -mt-px text-xl"></i>
                                </a>

                                <ul class="absolute top-0 z-10 hidden w-max min-w-[150px] rounded border bg-white shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block dark:border-gray-800 dark:bg-gray-900 ltr:left-full rtl:right-full">
                                    <li v-for="option in massAction.options">
                                        <a
                                            class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"
                                            href="javascript:void(0);"
                                            @click="performMassAction(massAction, option)"
                                        >
                                            {{ option.label }}
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li v-else>
                                <a
                                    class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"
                                    href="javascript:void(0);"
                                    @click="performMassAction(massAction)"
                                >
                                    <i
                                        class="text-2xl"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    {{ massAction.title }}
                                </a>
                            </li>
                        </template>
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

                <div class="ltr:pl-2.5 rtl:pr-2.5">
                    <p class="text-sm font-light text-gray-800 dark:text-white">
                        {{ "<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.length-of'); ?>".replace(':length', applied.massActions.indices.length) }}

                        {{ "<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.selected'); ?>".replace(':total', available.meta.total) }}
                    </p>
                </div>
            </div>
        </slot>
    </script>

    <script type="module">
        window.app.component('v-datagrid-mass-action', {
            template: '#v-datagrid-mass-action-template',

            props: ['available', 'applied'],

            methods: {
                xsrfToken() {
                    const m = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);

                    return m ? decodeURIComponent(m[1]) : '';
                },

                /**
                 * Human-readable message for failed mass-action requests.
                 *
                 * @param {Error} error
                 * @returns {string}
                 */
                fetchErrorMessage(error) {
                    const data = error?.response?.data;

                    if (! data) {
                        return error?.message || 'Something went wrong.';
                    }

                    if (typeof data.message === 'string' && data.message) {
                        return data.message;
                    }

                    if (data.errors && typeof data.errors === 'object') {
                        for (const msgs of Object.values(data.errors)) {
                            if (Array.isArray(msgs) && msgs.length) {
                                return msgs[0];
                            }
                            if (typeof msgs === 'string') {
                                return msgs;
                            }
                        }
                    }

                    return error?.message || 'Something went wrong.';
                },

                /**
                 * Mass action JSON API via fetch (no axios).
                 *
                 * @param {string} method
                 * @param {string} url
                 * @param {array} indices
                 * @param {*} payloadValue
                 * @returns {Promise<object>}
                 */
                async requestMassAction(method, url, indices, payloadValue) {
                    const m = method.toLowerCase();

                    let bodyObj;

                    if (m === 'delete') {
                        bodyObj = { indices };
                    } else if (['post', 'put', 'patch'].includes(m)) {
                        bodyObj = { indices, value: payloadValue };
                    } else {
                        throw new Error('Method not supported.');
                    }

                    const response = await fetch(url, {
                        method: m.toUpperCase(),
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-XSRF-TOKEN': this.xsrfToken(),
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(bodyObj),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        const err = new Error(data.message || response.statusText);

                        err.response = { data };

                        throw err;
                    }

                    return data;
                },

                /**
                 * Validate mass action.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                validateMassAction() {
                    if (! this.applied.massActions.indices.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "<?php echo app('translator')->get('superadmin::app.components.datagrid.index.no-records-selected'); ?>" });

                        return false;
                    }

                    if (! this.applied.massActions.meta.action) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "<?php echo app('translator')->get('superadmin::app.components.datagrid.index.must-select-a-mass-action'); ?>" });

                        return false;
                    }

                    if (
                        this.applied.massActions.meta.action?.options?.length &&
                        this.applied.massActions.value === null
                    ) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "<?php echo app('translator')->get('superadmin::app.components.datagrid.index.must-select-a-mass-action-option'); ?>" });

                        return false;
                    }

                    return true;
                },

                /**
                 * Perform mass action.
                 *
                 * @param {object} currentAction
                 * @param {object} currentOption
                 * @returns {void}
                 */
                performMassAction(currentAction, currentOption = null) {
                    this.applied.massActions.meta.action = currentAction;

                    if (currentOption) {
                        this.applied.massActions.value = currentOption.value;
                    }

                    if (! this.validateMassAction()) {
                        this.applied.massActions.meta.action = null;

                        return;
                    }

                    const selected = this.applied.massActions.meta.action;

                    const method = selected.method.toLowerCase();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: async () => {
                            const indices = [...this.applied.massActions.indices];
                            const payloadValue = this.applied.massActions.value;

                            try {
                                const data = await this.requestMassAction(method, selected.url, indices, payloadValue);

                                if (data?.message) {
                                    this.$emitter.emit('add-flash', { type: 'success', message: data.message });
                                }

                                setTimeout(() => window.location.reload(), 250);
                            } catch (error) {
                                this.$emitter.emit('add-flash', { type: 'error', message: this.fetchErrorMessage(error) });

                                setTimeout(() => window.location.reload(), 250);
                            }

                            this.applied.massActions.indices = [];
                        },
                    });
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/datagrid/toolbar/mass-action.blade.php ENDPATH**/ ?>