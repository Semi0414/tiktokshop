<v-datagrid-mass-action
    :available="available"
    :applied="applied"
>
    {{ $slot }}
</v-datagrid-mass-action>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-mass-action-template"
    >
        <slot
            name="mass-action"
            :available="available"
            :applied="applied"
            :mass-actions="massActions"
            :validate-mass-action="validateMassAction"
            :perform-mass-action="performMassAction"
        >
            <div class="flex  items-center gap-x-1">
                <x-admin::dropdown>
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="inline-flex   cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        >
                            <span>
                                @lang('admin::app.components.datagrid.toolbar.mass-actions.select-action')
                            </span>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <x-slot:menu class="!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800">
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
                                        @{{ massAction.title }}
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
                                            @{{ option.label }}
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

                                    @{{ massAction.title }}
                                </a>
                            </li>
                        </template>
                    </x-slot>
                </x-admin::dropdown>

                <!-- Product Warehouse: bulk add to store (commission modal) -->
                <teleport to="body">
                    <div
                        v-if="sellerAddModal.show"
                        class="admin-seller-add-modal-layer bg-black/50 p-4"
                        role="dialog"
                        aria-modal="true"
                        @click.self="closeSellerAddToStoreModal"
                    >
                        <div
                            class="relative mx-auto box-border  min-w-0 shrink-0 rounded-lg border border-gray-200 bg-white p-4 shadow-xl sm:max-w-[min(92vw,_26rem)] dark:border-gray-700 dark:bg-gray-900"
                            @click.stop
                        >
                            <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-title')
                            </p>
                            <p class="mb-3 text-[11px] leading-snug text-gray-500 dark:text-gray-400">
                                @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-hint')
                            </p>
                            <div class="mb-3">
                                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    @lang('admin::app.seller-panel.product-warehouse.bulk-add-seller-profit-label')
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class=" rounded-md border border-gray-200 px-2.5 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    :class="{
                                        'cursor-not-allowed bg-gray-100 dark:bg-gray-950': sellerAddModal.readonly,
                                    }"
                                    v-model="sellerAddModal.bulkPct"
                                    :readonly="sellerAddModal.readonly"
                                    :min="sellerAddModal.min"
                                    :max="sellerAddModal.max"
                                />
                                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                    @{{ sellerAddModal.rangeHint }}
                                </p>
                            </div>
                            <div class="flex justify-end gap-1.5 pt-1">
                                <button
                                    type="button"
                                    class="secondary-button !px-3 !py-1.5 text-xs"
                                    @click="closeSellerAddToStoreModal"
                                >
                                    @lang('admin::app.acl.cancel')
                                </button>
                                <button
                                    type="button"
                                    class="primary-button !px-3 !py-1.5 text-xs"
                                    @click="submitSellerAddToStore"
                                >
                                    @lang('admin::app.seller-panel.product-warehouse.bulk-add-confirm')
                                </button>
                            </div>
                        </div>
                    </div>
                </teleport>

                <!-- Store products: bulk edit commission + recommended -->
                <teleport to="body">
                    <div
                        v-if="sellerBulkEditModal.show"
                        class="admin-seller-add-modal-layer fixed inset-0 z-[2000] flex items-center justify-center bg-black/50 p-4"
                        role="dialog"
                        aria-modal="true"
                        @click.self="closeSellerBulkEditModal"
                    >
                        <div
                            class="relative mx-auto box-border min-w-0 shrink-0 rounded-lg border border-gray-200 bg-white p-4 shadow-xl sm:max-w-[min(92vw,_26rem)] dark:border-gray-700 dark:bg-gray-900"
                            @click.stop
                        >
                            <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.seller-panel.store-products.bulk-edit-modal-title')
                            </p>
                            <p class="mb-3 text-[11px] leading-snug text-gray-500 dark:text-gray-400">
                                @lang('admin::app.seller-panel.store-products.bulk-edit-modal-hint')
                            </p>
                            <div class="mb-3">
                                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                                    @lang('admin::app.seller-panel.product-warehouse.commission-title') (%)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="rounded-md border border-gray-200 px-2.5 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    :class="{
                                        'cursor-not-allowed bg-gray-100 dark:bg-gray-950': sellerBulkEditModal.readonly,
                                    }"
                                    v-model="sellerBulkEditModal.bulkPct"
                                    :readonly="sellerBulkEditModal.readonly"
                                    :min="sellerBulkEditModal.min"
                                    :max="sellerBulkEditModal.max"
                                />
                                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                    @{{ sellerBulkEditModal.rangeHint }}
                                </p>
                            </div>
                            <div class="mb-3 flex items-center gap-2">
                                <input type="checkbox" id="bulk-ssp-rec" v-model="sellerBulkEditModal.isRecommended" class="rounded border-gray-300" />
                                <label for="bulk-ssp-rec" class="text-xs text-gray-800 dark:text-gray-200">
                                    @lang('admin::app.seller-panel.store-products.recommended')
                                </label>
                            </div>
                            <div class="flex justify-end gap-1.5 pt-1">
                                <button
                                    type="button"
                                    class="secondary-button !px-3 !py-1.5 text-xs"
                                    @click="closeSellerBulkEditModal"
                                >
                                    @lang('admin::app.acl.cancel')
                                </button>
                                <button
                                    type="button"
                                    class="primary-button !px-3 !py-1.5 text-xs"
                                    @click="submitSellerBulkEdit"
                                >
                                    @lang('admin::app.seller-panel.store-products.bulk-edit-confirm')
                                </button>
                            </div>
                        </div>
                    </div>
                </teleport>

                <div class="ltr:pl-2.5 rtl:pr-2.5">
                    <p class="text-sm font-light text-gray-800 dark:text-white">
                        @{{ "@lang('admin::app.components.datagrid.toolbar.length-of')".replace(':length', massActions.indices.length) }}

                        @{{ "@lang('admin::app.components.datagrid.toolbar.selected')".replace(':total', available.meta.total) }}
                    </p>
                </div>
            </div>
        </slot>
    </script>

    <script type="module">
        (window.app || globalThis.app)?.component?.('v-datagrid-mass-action', {
            template: '#v-datagrid-mass-action-template',

            props: ['available', 'applied'],

            data() {
                return {
                    massActions: {
                        meta: {
                            mode: 'none',

                            action: null,
                        },

                        indices: [],

                        value: null,
                    },

                    sellerAddModal: {
                        show: false,
                        url: '',
                        indices: [],
                        readonly: false,
                        min: 0,
                        max: 100,
                        defaultPct: '15',
                        bulkPct: '15',
                        rangeHint: '',
                    },

                    sellerBulkEditModal: {
                        show: false,
                        url: '',
                        indices: [],
                        readonly: false,
                        min: 0,
                        max: 100,
                        defaultPct: '15',
                        bulkPct: '15',
                        rangeHint: '',
                        isRecommended: false,
                    },
                };
            },

            mounted() {
                this.massActions = this.applied.massActions;
            },

            methods: {
                /**
                 * Walk up until the v-datagrid instance (has .get()) — $parent is not always the datagrid.
                 */
                refreshDatagrid() {
                    let p = this.$parent;

                    while (p && typeof p.get !== 'function') {
                        p = p.$parent;
                    }

                    if (p && typeof p.get === 'function') {
                        p.get();
                    }
                },

                closeSellerAddToStoreModal() {
                    this.sellerAddModal.show = false;
                },

                closeSellerBulkEditModal() {
                    this.sellerBulkEditModal.show = false;
                },

                submitSellerBulkEdit() {
                    let pct = parseFloat(this.sellerBulkEditModal.bulkPct);

                    if (this.sellerBulkEditModal.readonly) {
                        pct = parseFloat(this.sellerBulkEditModal.defaultPct);
                    }

                    if (
                        Number.isNaN(pct)
                        || pct < this.sellerBulkEditModal.min - 0.0001
                        || pct > this.sellerBulkEditModal.max + 0.0001
                    ) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.seller-panel.product-warehouse.bulk-add-invalid-range')",
                        });

                        return;
                    }

                    this.$axios.post(this.sellerBulkEditModal.url, {
                        indices: this.sellerBulkEditModal.indices,
                        commission_percent: pct,
                        is_recommended: !!this.sellerBulkEditModal.isRecommended,
                    })
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.massActions.indices = [];
                            this.closeSellerBulkEditModal();
                            this.refreshDatagrid();
                        })
                        .catch((error) => {
                            const msg = error?.response?.data?.message ?? error?.message ?? 'Error';

                            this.$emitter.emit('add-flash', { type: 'error', message: msg });

                            this.refreshDatagrid();
                        });
                },

                submitSellerAddToStore() {
                    let pct = parseFloat(this.sellerAddModal.bulkPct);

                    if (this.sellerAddModal.readonly) {
                        pct = parseFloat(this.sellerAddModal.defaultPct);
                    }

                    if (
                        Number.isNaN(pct)
                        || pct < this.sellerAddModal.min - 0.0001
                        || pct > this.sellerAddModal.max + 0.0001
                    ) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.seller-panel.product-warehouse.bulk-add-invalid-range')",
                        });

                        return;
                    }

                    this.$axios.post(this.sellerAddModal.url, {
                        indices: this.sellerAddModal.indices,
                        commission_percent: pct,
                    })
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.massActions.indices = [];
                            this.closeSellerAddToStoreModal();
                            this.refreshDatagrid();
                        })
                        .catch((error) => {
                            const msg = error?.response?.data?.message ?? error?.message ?? 'Error';

                            this.$emitter.emit('add-flash', { type: 'error', message: msg });

                            this.refreshDatagrid();
                        });
                },

                /**
                 * Validate mass action.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                validateMassAction() {
                    if (! this.massActions.indices.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.no-records-selected')" });

                        return false;
                    }

                    if (! this.massActions.meta.action) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.must-select-a-mass-action')" });

                        return false;
                    }

                    if (
                        this.massActions.meta.action?.options?.length &&
                        this.massActions.value === null
                    ) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.must-select-a-mass-action-option')" });

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
                    this.massActions.meta.action = currentAction;

                    if (currentOption) {
                        this.massActions.value = currentOption.value;
                    }

                    if (! this.validateMassAction()) {
                        return;
                    }

                    const { action } = this.massActions.meta;

                    if (action.meta && action.meta.modal === 'seller_add_to_store') {
                        const c = action.meta.commission || {};

                        const rangeHint = c.readonly
                            ? "@lang('admin::app.seller-panel.product-warehouse.bulk-add-beginner-fixed')"
                            : "@lang('admin::app.seller-panel.product-warehouse.bulk-add-range-hint')"
                                .replace(':min', String(c.min))
                                .replace(':max', String(c.max));

                        this.sellerAddModal = {
                            show: true,
                            url: action.url,
                            indices: [...this.massActions.indices],
                            readonly: !!c.readonly,
                            min: Number(c.min),
                            max: Number(c.max),
                            defaultPct: String(c.default ?? 15),
                            bulkPct: String(c.default ?? 15),
                            rangeHint,
                        };

                        return;
                    }

                    if (action.meta && action.meta.modal === 'seller_store_bulk_edit') {
                        const c = action.meta.commission || {};

                        const rangeHint = c.readonly
                            ? "@lang('admin::app.seller-panel.product-warehouse.bulk-add-beginner-fixed')"
                            : "@lang('admin::app.seller-panel.product-warehouse.bulk-add-range-hint')"
                                .replace(':min', String(c.min))
                                .replace(':max', String(c.max));

                        this.sellerBulkEditModal = {
                            show: true,
                            url: action.url,
                            indices: [...this.massActions.indices],
                            readonly: !!c.readonly,
                            min: Number(c.min),
                            max: Number(c.max),
                            defaultPct: String(c.default ?? 15),
                            bulkPct: String(c.default ?? 15),
                            rangeHint,
                            isRecommended: false,
                        };

                        return;
                    }

                    const method = action.method.toLowerCase();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            switch (method) {
                                case 'post':
                                case 'put':
                                case 'patch':
                                    this.$axios[method](action.url, {
                                            indices: this.massActions.indices,
                                            value: this.massActions.value,
                                        })
                                        .then((response) => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.refreshDatagrid();
                                        })
                                        .catch((error) => {
                                            const msg = error?.response?.data?.message ?? error?.message ?? 'Request failed';

                                            this.$emitter.emit('add-flash', { type: 'error', message: msg });

                                            this.refreshDatagrid();
                                        });

                                    break;

                                case 'delete':
                                    this.$axios[method](action.url, {
                                            indices: this.massActions.indices
                                        })
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            /**
                                             * Need to check reason why this.$emit('massActionSuccess') not emitting.
                                             */
                                            this.refreshDatagrid();
                                        })
                                        .catch((error) => {
                                            const msg = error?.response?.data?.message ?? error?.message ?? 'Request failed';

                                            this.$emitter.emit('add-flash', { type: 'error', message: msg });

                                            /**
                                             * Need to check reason why this.$emit('massActionSuccess') not emitting.
                                             */
                                            this.refreshDatagrid();
                                        });

                                    break;

                                default:
                                    console.error('Method not supported.');

                                    break;
                            }

                            this.massActions.indices  = [];
                        },
                    });
                },
            },
        });
    </script>
@endPushOnce
