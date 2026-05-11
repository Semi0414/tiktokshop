<?php if (! $__env->hasRenderedOnce('superadmin:v-datagrid-register')): $__env->markAsRenderedOnce('superadmin:v-datagrid-register');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-datagrid-template"
    >
        <div>
            <!-- Toolbar -->
            <?php if (isset($component)) { $__componentOriginalc9f23aa1ed6662c8288179e5d4689cda = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9f23aa1ed6662c8288179e5d4689cda = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.toolbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9f23aa1ed6662c8288179e5d4689cda)): ?>
<?php $attributes = $__attributesOriginalc9f23aa1ed6662c8288179e5d4689cda; ?>
<?php unset($__attributesOriginalc9f23aa1ed6662c8288179e5d4689cda); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9f23aa1ed6662c8288179e5d4689cda)): ?>
<?php $component = $__componentOriginalc9f23aa1ed6662c8288179e5d4689cda; ?>
<?php unset($__componentOriginalc9f23aa1ed6662c8288179e5d4689cda); ?>
<?php endif; ?>

            <div class="mt-4 flex">
                <template v-if="! tableless">
                    <?php if (isset($component)) { $__componentOriginalbc31f2bbb3b9264b92c45f0da1c96b53 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc31f2bbb3b9264b92c45f0da1c96b53 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.table','data' => ['isMultiRow' => $isMultiRow]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['isMultiRow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isMultiRow)]); ?>
                        <template #header="{
                            isLoading,
                            available,
                            applied,
                            selectAll,
                            sort,
                            performAction
                        }">
                            <slot
                                name="header"
                                :is-loading="isLoading"
                                :available="available"
                                :applied="applied"
                                :select-all="selectAll"
                                :sort="sort"
                                :perform-action="performAction"
                            >
                            </slot>
                        </template>

                        <template #body="{
                            isLoading,
                            available,
                            applied,
                            selectAll,
                            sort,
                            performAction
                        }">
                            <slot
                                name="body"
                                :is-loading="isLoading"
                                :available="available"
                                :applied="applied"
                                :select-all="selectAll"
                                :sort="sort"
                                :perform-action="performAction"
                            >
                            </slot>
                        </template>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc31f2bbb3b9264b92c45f0da1c96b53)): ?>
<?php $attributes = $__attributesOriginalbc31f2bbb3b9264b92c45f0da1c96b53; ?>
<?php unset($__attributesOriginalbc31f2bbb3b9264b92c45f0da1c96b53); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc31f2bbb3b9264b92c45f0da1c96b53)): ?>
<?php $component = $__componentOriginalbc31f2bbb3b9264b92c45f0da1c96b53; ?>
<?php unset($__componentOriginalbc31f2bbb3b9264b92c45f0da1c96b53); ?>
<?php endif; ?>
                </template>

                <template v-else>
                    <div
                        ref="dgSsrSlot"
                        class="datagrid-ssr-slot w-full overflow-x-auto"
                    >
                        <slot></slot>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        window.app.component('v-datagrid', {
            template: '#v-datagrid-template',

            props: {
                src: String,

                bootstrap: {
                    default: null,
                },

                bootstrapJsonId: {
                    type: String,
                    default: '',
                },

                paginationNamespace: {
                    type: String,
                    default: null,
                },

                tableless: {
                    type: Boolean,
                    default: false,
                },

                appliedBootstrap: {
                    type: Object,
                    default: null,
                },
            },

            computed: {
                effectiveBootstrap() {
                    const parsed = this.bootstrapFromJson;

                    if (
                        parsed !== null
                        && parsed !== undefined
                        && typeof parsed === 'object'
                        && Array.isArray(parsed.records)
                    ) {
                        return parsed;
                    }

                    const direct = this.bootstrap;

                    if (
                        direct !== null
                        && direct !== undefined
                        && typeof direct === 'object'
                        && Array.isArray(direct.records)
                    ) {
                        return direct;
                    }

                    return parsed ?? direct ?? null;
                },

                listingUsesBootstrap() {
                    const b = this.effectiveBootstrap;

                    return b !== null
                        && b !== undefined
                        && typeof b === 'object'
                        && Array.isArray(b.records);
                },
            },

            created() {
                if (! this.bootstrapJsonId) {
                    return;
                }

                const el = document.getElementById(this.bootstrapJsonId);

                if (! el?.textContent?.trim()) {
                    return;
                }

                try {
                    this.bootstrapFromJson = JSON.parse(el.textContent.trim());
                } catch (e) {
                    console.error('[SuperAdmin datagrid] Invalid bootstrap JSON', e);
                }
            },

            data() {
                return {
                    isLoading: false,

                    available: {
                        id: null,

                        columns: [],

                        actions: [],

                        massActions: [],

                        records: [],

                        meta: {},
                    },

                    applied: {
                        massActions: {
                            meta: {
                                mode: 'none',

                                action: null,
                            },

                            indices: [],

                            value: null,
                        },

                        pagination: {
                            page: 1,

                            perPage: 10,
                        },

                        sort: {
                            column: null,

                            order: null,
                        },

                        filters: {
                            columns: [
                                {
                                    index: 'all',
                                    value: [],
                                },
                            ],
                        },

                        savedFilterId: null,
                    },

                    bootstrapFromJson: null,

                    _dgSsrMassSyncT: null,
                };
            },

            watch: {
                'available.records': function (newRecords, oldRecords) {
                    this.setCurrentSelectionMode();

                    this.updateDatagrids();

                    this.updateExportComponent();
                },

                'applied.savedFilterId': function (newSavedFilterId, oldSavedFilterId) {
                    this.updateDatagrids();
                },

                'applied.massActions.indices': function (newIndices, oldIndices) {
                    this.setCurrentSelectionMode();

                    if (this.tableless) {
                        this.syncSsrDomCheckboxesFromIndices();
                    }

                    this.updateExportComponent();
                },
            },

            mounted() {
                this.boot();

                if (this.tableless && this.appliedBootstrap && typeof this.appliedBootstrap === 'object') {
                    this.mergeAppliedBootstrap(this.appliedBootstrap);
                }

                if (this.tableless) {
                    this.$nextTick(() => {
                        this.bindSsrMassCheckboxDelegation();

                        if (! this._dgSsrDelegationReady) {
                            this.$nextTick(() => this.bindSsrMassCheckboxDelegation());
                        }
                    });
                }

                this.$emitter.on('datagrid-refresh', this.onDatagridRefresh);
            },

            beforeUnmount() {
                this.teardownSsrMassCheckboxDelegation();

                this.$emitter.off('datagrid-refresh', this.onDatagridRefresh);
            },

            methods: {
                /**
                 * Refresh grid after mass actions (emitter avoids $parent after child unmounts).
                 *
                 * @param {object} payload optional; may include src to scope refresh
                 * @returns {void}
                 */
                onDatagridRefresh(payload) {
                    if (payload?.src && payload.src !== this.src) {
                        return;
                    }

                    this.get();
                },

                /**
                 * Initialization: This function checks for any previously saved filters in local storage and applies them as needed.
                 *
                 * @returns {void}
                 */
                boot() {
                    if (this.listingUsesBootstrap) {
                        this.applyServerPayload(this.effectiveBootstrap);

                        this.updateDatagrids();

                        this.updateExportComponent();

                        return;
                    }

                    let datagrids = this.getDatagrids();

                    const urlParams = new URLSearchParams(window.location.search);

                    if (urlParams.has('search')) {
                        let searchAppliedColumn = this.applied.filters.columns.find(column => column.index === 'all');

                        searchAppliedColumn.value = [urlParams.get('search')];
                    }

                    if (datagrids?.length) {
                        const currentDatagrid = datagrids.find(({ src }) => src === this.src);

                        if (currentDatagrid) {
                            this.applied.pagination = currentDatagrid.applied.pagination;

                            this.applied.sort = currentDatagrid.applied.sort;

                            this.applied.filters = currentDatagrid.applied.filters;

                            this.applied.savedFilterId = currentDatagrid.applied.savedFilterId;

                            if (urlParams.has('search')) {
                                let searchAppliedColumn = this.applied.filters.columns.find(column => column.index === 'all');

                                searchAppliedColumn.value = [urlParams.get('search')];
                            }
                        }
                    }

                    const target = this.buildListingUrl();
                    const current = window.location.pathname + window.location.search;

                    if (target !== current) {
                        window.location.assign(target);

                        return;
                    }

                    console.warn('[SuperAdmin datagrid] SSR listing payload missing; check controller passes datagridPayload.');
                },

                /**
                 * Reload listing via full-page navigation (SSR payload only; no XHR).
                 *
                 * @returns {void}
                 */
                get(extraParams = {}) {
                    window.location.assign(this.buildListingUrl(extraParams));
                },

                applyServerPayload(payload) {
                    if (! payload || typeof payload !== 'object') {
                        return;
                    }

                    const {
                        id,
                        columns = [],
                        actions = [],
                        mass_actions: massActions = [],
                        records = [],
                        meta = {},
                    } = payload;

                    this.available.id = id;

                    this.available.columns = Array.isArray(columns) ? columns : [];

                    this.available.actions = Array.isArray(actions) ? actions : [];

                    this.available.massActions = Array.isArray(massActions) ? massActions : [];

                    this.available.records = Array.isArray(records) ? records : [];

                    this.available.meta = meta && typeof meta === 'object' ? meta : {};

                    if (! this.available.meta.primary_column) {
                        this.available.meta.primary_column = 'id';
                    }

                    if (this.available.meta) {
                        this.applied.pagination.page = this.available.meta.current_page ?? this.applied.pagination.page;

                        this.applied.pagination.perPage = this.available.meta.per_page ?? this.applied.pagination.perPage;
                    }

                    this.isLoading = false;
                },

                buildListingUrl(extraParams = {}) {
                    if (this.paginationNamespace) {
                        const ns = this.paginationNamespace;
                        const url = new URL(this.src, window.location.origin);
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

                        Object.entries(extraParams).forEach(([k, v]) => {
                            if (v === undefined || v === null || v === '') {
                                return;
                            }

                            next.set(k, typeof v === 'object' ? JSON.stringify(v) : String(v));
                        });

                        const qs = next.toString();

                        return url.pathname + (qs ? `?${qs}` : '');
                    }

                    let params = {
                        pagination: {
                            page: this.applied.pagination.page,
                            per_page: this.applied.pagination.perPage,
                        },

                        sort: {},

                        filters: {},

                        ...extraParams,
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
                    const base = this.src.split('?')[0];

                    return qs ? `${base}?${qs}` : base;
                },

                /**
                 * Change page (full-page navigation with SSR payload).
                 *
                 * @param {integer} newPage
                 * @returns {void}
                 */
                changePage(newPage) {
                    this.applied.pagination.page = newPage;

                    this.get();
                },

                /**
                 * Change per page option.
                 *
                 * @param {integer} option
                 * @returns {void}
                 */
                changePerPageOption(option) {
                    this.applied.pagination.perPage = option;

                    /**
                     * When the total records are less than the number of data per page, we need to reset the page.
                     */
                    if (this.available.meta.last_page >= this.applied.pagination.page) {
                        this.applied.pagination.page = 1;
                    }

                    this.get();
                },

                /**
                 * Sort results.
                 *
                 * @param {object} column
                 * @returns {void}
                 */
                sort(column) {
                    if (column.sortable) {
                        this.applied.sort = {
                            column: column.index,
                            order: this.applied.sort.order === 'asc' ? 'desc' : 'asc',
                        };

                        /**
                         * When the sorting changes, we need to reset the page.
                         */
                        this.applied.pagination.page = 1;

                        this.get();
                    }
                },

                /**
                 * Search results.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                search(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index !== 'all')),
                        ...filters.columns,
                    ];

                    /**
                     * We need to reset the page on filtering.
                     */
                    this.applied.pagination.page = 1;

                    this.get();
                },

                /**
                 * Filter results.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                 filter(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index === 'all')),
                        ...filters.columns,
                    ];

                    /**
                     * This will check for empty column values and reset the saved filter ID to ensure the saved filter is not highlighted.
                     */
                    const isEmptyColumnValue = this.applied.filters.columns
                        .filter((column) => column.index !== 'all')
                        .every((column) => column.value.length === 0);

                    if (isEmptyColumnValue) {
                        this.applied.savedFilterId = null;
                    }

                    /**
                     * We need to reset the page on filtering.
                     */
                    this.applied.pagination.page = 1;

                    this.get();
                },

                /**
                 * Filter results by the saved filter.
                 *
                 * @param {Object} filter
                 * @returns {void}
                 */
                 applySavedFilter(filter) {
                    if (! filter) {
                        this.applied.savedFilterId = null;

                        return;
                    }

                    this.applied = filter.applied;

                    this.applied.savedFilterId = filter.id;

                    this.get();
                },

                /**
                 * This will analyze the current selection mode based on the mass action indices.
                 *
                 * @returns {void}
                 */
                setCurrentSelectionMode() {
                    this.applied.massActions.meta.mode = 'none';

                    if (! Array.isArray(this.available.records) || ! this.available.records.length) {
                        return;
                    }

                    const selected = new Set(this.applied.massActions.indices.map((v) => String(v)));

                    let selectionCount = 0;

                    this.available.records.forEach(record => {
                        const id = record[this.available.meta.primary_column];

                        if (selected.has(String(id))) {
                            this.applied.massActions.meta.mode = 'partial';

                            ++selectionCount;
                        }
                    });

                    if (this.available.records.length === selectionCount && selectionCount > 0) {
                        this.applied.massActions.meta.mode = 'all';
                    }
                },

                /**
                 * This will select all records and update the mass action indices.
                 *
                 * @returns {void}
                 */
                selectAll() {
                    if (['all', 'partial'].includes(this.applied.massActions.meta.mode)) {
                        this.available.records.forEach(record => {
                            const id = record[this.available.meta.primary_column];

                            this.applied.massActions.indices = this.applied.massActions.indices.filter(selectedId => String(selectedId) !== String(id));
                        });

                        this.applied.massActions.meta.mode = 'none';
                    } else {
                        this.available.records.forEach(record => {
                            const id = record[this.available.meta.primary_column];

                            let found = this.applied.massActions.indices.find(selectedId => String(selectedId) === String(id));

                            if (! found) {
                                this.applied.massActions.indices = [
                                    ...this.applied.massActions.indices,
                                    id,
                                ];
                            }
                        });

                        this.applied.massActions.meta.mode = 'all';
                    }

                    if (this.tableless) {
                        this.$nextTick(() => {
                            this.syncSsrDomCheckboxesFromIndices();
                        });
                    }
                },

                /**
                 * SSR table lives inside this wrapper; prefer ref over querySelector so delegation
                 * stays scoped when multiple grids or duplicate classes appear on the page.
                 *
                 * @returns {HTMLElement|null}
                 */
                getSsrSlotRoot() {
                    if (! this.tableless) {
                        return null;
                    }

                    const refRoot = this.$refs.dgSsrSlot;

                    if (refRoot && typeof refRoot.querySelector === 'function') {
                        return refRoot;
                    }

                    return this.$el?.querySelector('.datagrid-ssr-slot') ?? null;
                },

                mergeAppliedBootstrap(payload) {
                    if (! payload || typeof payload !== 'object') {
                        return;
                    }

                    if (payload.pagination && typeof payload.pagination === 'object') {
                        this.applied.pagination = {
                            ...this.applied.pagination,
                            ...payload.pagination,
                        };
                    }

                    if (payload.sort && typeof payload.sort === 'object') {
                        this.applied.sort = {
                            ...this.applied.sort,
                            ...payload.sort,
                        };
                    }

                    if (payload.filters && Array.isArray(payload.filters.columns)) {
                        this.applied.filters.columns = payload.filters.columns;
                    }

                    if (payload.savedFilterId !== undefined) {
                        this.applied.savedFilterId = payload.savedFilterId;
                    }
                },

                bindSsrMassCheckboxDelegation() {
                    if (! this.tableless || this._dgSsrDelegationReady) {
                        return;
                    }

                    if (! this.$el) {
                        return;
                    }

                    const slot = this.getSsrSlotRoot();

                    if (! slot) {
                        return;
                    }

                    this._dgSsrDelegationReady = true;

                    /**
                     * Listen on the SSR table wrapper (bubble phase). Capture on $el is unreliable for
                     * checkbox change/input when the control is toggled via a label + hidden input
                     * (some browsers do not run the capture path the same as direct input handlers).
                     */
                    this.__dgSsrMassUiCapture = (e) => this.onSsrMassUiChange(e);

                    slot.addEventListener('change', this.__dgSsrMassUiCapture, false);

                    slot.addEventListener('input', this.__dgSsrMassUiCapture, false);

                    this.__dgSsrSlotClickBubble = (e) => this.onSsrSlotClick(e);

                    slot.addEventListener('click', this.__dgSsrSlotClickBubble);

                    this._dgSsrSlotEl = slot;

                    this.syncSsrDomHeaderCheckbox();
                },

                /**
                 * Debounced: sync Vue mass indices from real checkbox DOM (covers cases where change/input is not delivered).
                 *
                 * @returns {void}
                 */
                scheduleSsrMassSelectionSyncFromDom() {
                    if (! this.tableless) {
                        return;
                    }

                    clearTimeout(this._dgSsrMassSyncT);

                    this._dgSsrMassSyncT = setTimeout(() => {
                        this._dgSsrMassSyncT = null;

                        this.syncSsrMassSelectionFromDom();
                    }, 0);
                },

                /**
                 * Read SSR checkbox states and update applied.massActions (for bulk toolbar + export).
                 *
                 * @returns {void}
                 */
                syncSsrMassSelectionFromDom() {
                    const root = this.getSsrSlotRoot();

                    if (! root || ! this.tableless) {
                        return;
                    }

                    const header = root.querySelector('input[data-dg-ssr-select-all]');

                    const primary = this.available.meta?.primary_column ?? 'id';

                    const records = Array.isArray(this.available.records) ? this.available.records : [];

                    if (header?.checked && records.length) {
                        this.applied.massActions.indices = records.map((record) => record[primary]);

                        this.applied.massActions.meta.mode = 'all';
                    } else {
                        this.applied.massActions.indices = [
                            ...root.querySelectorAll('input[data-dg-ssr-mass]:checked'),
                        ].map((input) => this.coerceMassId(input.value));

                        this.setCurrentSelectionMode();
                    }

                    this.syncSsrDomCheckboxesFromIndices();

                    this.syncSsrDomHeaderCheckbox();

                    this.updateDatagrids();

                    this.updateExportComponent();
                },

                teardownSsrMassCheckboxDelegation() {
                    if (! this._dgSsrDelegationReady) {
                        return;
                    }

                    this._dgSsrDelegationReady = false;

                    if (this._dgSsrMassSyncT) {
                        clearTimeout(this._dgSsrMassSyncT);

                        this._dgSsrMassSyncT = null;
                    }

                    if (this.__dgSsrMassUiCapture && this._dgSsrSlotEl) {
                        this._dgSsrSlotEl.removeEventListener('change', this.__dgSsrMassUiCapture, false);

                        this._dgSsrSlotEl.removeEventListener('input', this.__dgSsrMassUiCapture, false);

                        delete this.__dgSsrMassUiCapture;
                    }

                    if (this._dgSsrSlotEl && this.__dgSsrSlotClickBubble) {
                        this._dgSsrSlotEl.removeEventListener('click', this.__dgSsrSlotClickBubble);

                        delete this.__dgSsrSlotClickBubble;

                        delete this._dgSsrSlotEl;
                    }
                },

                onSsrSlotClick(event) {
                    /**
                     * Label + hidden checkbox toggles may not deliver change/input to capture listeners;
                     * sync from DOM after click so the bulk-action toolbar appears.
                     */
                    if (event.target.closest('label, input[type="checkbox"]')) {
                        this.scheduleSsrMassSelectionSyncFromDom();
                    }
                },
                onSsrMassUiChange(event) {
                    const target = event.target;

                    const slot = this.getSsrSlotRoot();

                    if (! slot || ! target || typeof target.matches !== 'function') {
                        return;
                    }

                    if (! slot.contains(target)) {
                        return;
                    }

                    const tag = typeof target.tagName === 'string' ? target.tagName.toLowerCase() : '';

                    if (tag !== 'input' || target.type !== 'checkbox') {
                        return;
                    }

                    if (target.matches('[data-dg-ssr-select-all]')) {
                        this.applySsrSelectAll(Boolean(target.checked));

                        return;
                    }

                    if (target.matches('[data-dg-ssr-mass]')) {
                        this.toggleSsrMassId(target.value, Boolean(target.checked));
                    }
                },

                coerceMassId(value) {
                    if (value === undefined || value === null || value === '') {
                        return value;
                    }

                    const numeric = Number(value);

                    if (Number.isFinite(numeric) && String(numeric) === String(value).trim()) {
                        return numeric;
                    }

                    return value;
                },

                toggleSsrMassId(rawValue, checked) {
                    const id = this.coerceMassId(rawValue);

                    if (checked) {
                        if (! this.applied.massActions.indices.some((selectedId) => String(selectedId) === String(id))) {
                            this.applied.massActions.indices = [...this.applied.massActions.indices, id];
                        }
                    } else {
                        this.applied.massActions.indices = this.applied.massActions.indices.filter((selectedId) => String(selectedId) !== String(id));
                    }

                    this.setCurrentSelectionMode();

                    this.syncSsrDomHeaderCheckbox();

                    this.updateDatagrids();

                    this.updateExportComponent();
                },

                applySsrSelectAll(checked) {
                    const primary = this.available.meta.primary_column;

                    if (checked) {
                        const ids = this.available.records.map((record) => record[primary]);

                        this.applied.massActions.indices = [...ids];

                        this.applied.massActions.meta.mode = 'all';
                    } else {
                        this.applied.massActions.indices = [];

                        this.applied.massActions.meta.mode = 'none';
                    }

                    this.syncSsrDomCheckboxesFromIndices();

                    this.syncSsrDomHeaderCheckbox();

                    this.updateDatagrids();

                    this.updateExportComponent();
                },

                syncSsrDomCheckboxesFromIndices() {
                    const root = this.getSsrSlotRoot();

                    if (! root) {
                        return;
                    }

                    const ids = new Set(this.applied.massActions.indices.map((v) => String(v)));

                    root.querySelectorAll('input[data-dg-ssr-mass]').forEach((input) => {
                        input.checked = ids.has(String(input.value));
                    });

                    this.syncSsrDomHeaderCheckbox();
                },

                syncSsrDomHeaderCheckbox() {
                    const root = this.getSsrSlotRoot();

                    if (! root) {
                        return;
                    }

                    const header = root.querySelector('input[data-dg-ssr-select-all]');

                    if (! header) {
                        return;
                    }

                    header.checked = this.applied.massActions.meta.mode === 'all';

                    header.indeterminate = this.applied.massActions.meta.mode === 'partial';
                },

                /**
                 * Updates the export component properties whenever new results appear in the datagrid.
                 *
                 * @returns {void}
                 */
                updateExportComponent() {
                    /**
                     * This event should be fired whenever new results appear. This allows the export feature to
                     * listen to it and update its properties accordingly.
                     */
                     this.$emitter.emit('change-datagrid', {
                        src: this.src,
                        available: this.available,
                        applied: this.applied,
                        paginationNamespace: this.paginationNamespace,
                    });

                    /**
                     * SSR tableless grids: plain scripts cannot rely on checkbox DOM timing (label + hidden input).
                     * Bubble a native event so pages (e.g. seller orders bulk modal) sync selection on every tick.
                     */
                    if (this.tableless) {
                        try {
                            document.dispatchEvent(new CustomEvent('superadmin-ssr-mass-selection', {
                                bubbles: true,
                                detail: {
                                    src: this.src,
                                    indices: Array.isArray(this.applied?.massActions?.indices)
                                        ? [...this.applied.massActions.indices]
                                        : [],
                                    records: Array.isArray(this.available?.records) ? this.available.records : [],
                                    meta: this.available?.meta && typeof this.available.meta === 'object'
                                        ? this.available.meta
                                        : {},
                                },
                            }));
                        } catch (e) {
                            /* ignore */
                        }
                    }
                },

                //=======================================================================================
                // Support for previous applied values in datagrid's. All code is based on local storage.
                //=======================================================================================

                /**
                 * Updates the datagrid's stored in local storage with the latest data.
                 *
                 * @returns {void}
                 */
                updateDatagrids() {
                    let datagrids = this.getDatagrids();

                    if (datagrids?.length) {
                        const currentDatagrid = datagrids.find(({ src }) => src === this.src);

                        if (currentDatagrid) {
                            datagrids = datagrids.map(datagrid => {
                                if (datagrid.src === this.src) {
                                    return {
                                        ...datagrid,
                                        requestCount: ++datagrid.requestCount,
                                        applied: this.applied,
                                    };
                                }

                                return datagrid;
                            });
                        } else {
                            datagrids.push(this.getDatagridInitialProperties());
                        }
                    } else {
                        datagrids = [this.getDatagridInitialProperties()];
                    }

                    this.setDatagrids(datagrids);
                },

                /**
                 * Returns the initial properties for a datagrid.
                 *
                 * @returns {object} Initial properties for a datagrid.
                 */
                getDatagridInitialProperties() {
                    return {
                        src: this.src,
                        requestCount: 0,
                        applied: this.applied,
                    };
                },

                /**
                 * Returns the storage key for datagrid's in local storage.
                 *
                 * @returns {string} Storage key for datagrid's.
                 */
                getDatagridsStorageKey() {
                    return 'datagrids';
                },

                /**
                 * Retrieves the datagrids stored in local storage.
                 *
                 * @returns {Array} Datagrids stored in local storage.
                 */
                getDatagrids() {
                    const raw = localStorage.getItem(this.getDatagridsStorageKey());

                    if (raw === null || raw === '') {
                        return [];
                    }

                    try {
                        const parsed = JSON.parse(raw);

                        return Array.isArray(parsed) ? parsed : [];
                    } catch (e) {
                        console.warn('[SuperAdmin datagrid] Clearing invalid datagrids localStorage', e);

                        try {
                            localStorage.removeItem(this.getDatagridsStorageKey());
                        } catch (removeErr) {}

                        return [];
                    }
                },

                /**
                 * Sets the datagrid's in local storage.
                 *
                 * @param {Array} datagrids - Datagrid's to be stored in local storage.
                 * @returns {void}
                 */
                setDatagrids(datagrids) {
                    localStorage.setItem(
                        this.getDatagridsStorageKey(),
                        JSON.stringify(datagrids)
                    );
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/datagrid/scripts-register.blade.php ENDPATH**/ ?>