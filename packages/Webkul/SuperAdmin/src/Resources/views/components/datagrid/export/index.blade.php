<v-datagrid-export {{ $attributes }}>
    <div class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
        <span class="icon-admin-export text-xl text-gray-600"></span>

        @lang('superadmin::app.export.export')
    </div>
</v-datagrid-export>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-export-template"
    >
        <div>
            <x-superadmin::modal ref="exportModal">
                <!-- Modal Toggler -->
                <x-slot:toggle>
                    <button class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                        <span class="icon-admin-export text-xl text-gray-600"></span>

                        @lang('superadmin::app.export.export')
                    </button>
                </x-slot>

                <!-- Modal Header -->
                <x-slot:header>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('superadmin::app.export.download')
                    </p>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    <x-superadmin::form action="">
                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.control
                                type="select"
                                name="format"
                                v-model="format"
                            >
                                <option value="csv">
                                    @lang('superadmin::app.export.csv')
                                </option>

                                <option value="xls">
                                    @lang('superadmin::app.export.xls')
                                </option>

                                <option value="xlsx">
                                    @lang('superadmin::app.export.xlsx')
                                </option>
                            </x-superadmin::form.control-group.control>
                        </x-superadmin::form.control-group>
                    </x-superadmin::form>
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <!-- Save Button -->
                    <x-superadmin::button
                        button-type="button"
                        class="primary-button"
                        :title="trans('superadmin::app.export.export')"
                        @click="download"
                    />
                </x-slot>
            </x-superadmin::modal>
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
                        this.$emitter.emit('add-flash', { type: 'warning', message: '@lang('superadmin::app.export.no-records')' });

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
@endPushOnce
