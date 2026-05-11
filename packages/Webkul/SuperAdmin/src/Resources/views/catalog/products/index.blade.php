<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.catalog.products.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.catalog.products.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- EExport Modal -->
            <x-superadmin::datagrid.export :src="route('superadmin.catalog.products.index')" />

            {!! view_render_event('bagisto.admin.catalog.products.create.before') !!}

            @if (bouncer()->hasPermission('catalog.products.create'))
                <v-create-product-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('superadmin::app.catalog.products.index.create-btn')
                    </button>
                </v-create-product-form>
            @endif

            {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

    @php
        $meta = $datagridPayload['meta'] ?? [];
        $currentPage = max((int) request('pagination.page', ($meta['current_page'] ?? 1)), 1);
        $lastPage = max((int) ($meta['last_page'] ?? 1), 1);
        $perPage = max((int) request('pagination.per_page', ($meta['per_page'] ?? 10)), 1);
        $searchValue = request('filters.all.0', request('search', ''));
        $baseQuery = request()->except('pagination');
    @endphp

    <div class="my-4 rounded border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('superadmin.catalog.products.index') }}" class="flex flex-wrap items-end gap-2.5">
            <div class="min-w-[260px] flex-1">
                <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">Search Products</label>
                <input
                    type="text"
                    name="filters[all][]"
                    value="{{ $searchValue }}"
                    placeholder="Search by name / sku"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200"
                >
            </div>

            <div class="w-[120px]">
                <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">Per Page</label>
                <select
                    name="pagination[per_page]"
                    onchange="this.form.submit()"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200"
                >
                    @foreach ([10, 25, 50, 100, 200] as $pageSize)
                        <option value="{{ $pageSize }}" {{ (string) $perPage === (string) $pageSize ? 'selected' : '' }}>
                            {{ $pageSize }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="pagination[page]" value="1">

            <div class="flex items-center gap-2">
                <button type="submit" class="secondary-button">Search</button>
                <a href="{{ route('superadmin.catalog.products.index') }}" class="transparent-button">Reset</a>
            </div>
        </form>
    </div>

    <!-- Datagrid -->
    <x-superadmin::datagrid.ssr
        :datagrid-payload="$datagridPayload"
        :src="route('superadmin.catalog.products.index')"
        :isMultiRow="true"
    />

    <div class="mt-4 flex items-center justify-between rounded border border-gray-200 bg-white px-3 py-2 dark:border-gray-800 dark:bg-gray-900">
        <a
            href="{{ $currentPage > 1 ? route('superadmin.catalog.products.index', array_merge($baseQuery, ['pagination' => ['page' => $currentPage - 1, 'per_page' => $perPage]])) : 'javascript:void(0)' }}"
            class="secondary-button {{ $currentPage <= 1 ? 'pointer-events-none opacity-50' : '' }}"
        >
            Prev
        </a>

        <div class="flex items-center gap-1.5">
            @php
                $startPage = max($currentPage - 2, 1);
                $endPage = min($startPage + 4, $lastPage);
                $startPage = max($endPage - 4, 1);
            @endphp

            @for ($p = $startPage; $p <= $endPage; $p++)
                <a
                    href="{{ route('superadmin.catalog.products.index', array_merge($baseQuery, ['pagination' => ['page' => $p, 'per_page' => $perPage]])) }}"
                    class="{{ $p === $currentPage ? 'primary-button' : 'secondary-button' }}"
                >
                    {{ $p }}
                </a>
            @endfor
        </div>

        <a
            href="{{ $currentPage < $lastPage ? route('superadmin.catalog.products.index', array_merge($baseQuery, ['pagination' => ['page' => $currentPage + 1, 'per_page' => $perPage]])) : 'javascript:void(0)' }}"
            class="secondary-button {{ $currentPage >= $lastPage ? 'pointer-events-none opacity-50' : '' }}"
        >
            Next
        </a>
    </div>

    {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-product-form-template"
        >
            <div>
                <!-- Product Create Button -->
                @if (bouncer()->hasPermission('catalog.products.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.productCreateModal.toggle()"
                    >
                        @lang('superadmin::app.catalog.products.index.create-btn')
                    </button>
                @endif

                <x-superadmin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Customer Create Modal -->
                        <x-superadmin::modal ref="productCreateModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-if="! attributes.length"
                                >
                                    @lang('superadmin::app.catalog.products.index.create.title')
                                </p>

                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-else
                                >
                                    @lang('superadmin::app.catalog.products.index.create.configurable-attributes')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <div v-show="! attributes.length">
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}

                                    <!-- Product Type -->
                                    <x-superadmin::form.control-group>
                                        <x-superadmin::form.control-group.label class="required">
                                            @lang('superadmin::app.catalog.products.index.create.type')
                                        </x-superadmin::form.control-group.label>

                                        <x-superadmin::form.control-group.control
                                            type="select"
                                            name="type"
                                            rules="required"
                                            :label="trans('superadmin::app.catalog.products.index.create.type')"
                                        >
                                            @foreach(config('product_types') as $key => $type)
                                                <option value="{{ $key }}">
                                                    @lang($type['name'])
                                                </option>
                                            @endforeach
                                        </x-superadmin::form.control-group.control>

                                        <x-superadmin::form.control-group.error control-name="type" />
                                    </x-superadmin::form.control-group>

                                    <!-- Attribute Family Id -->
                                    <x-superadmin::form.control-group>
                                        <x-superadmin::form.control-group.label class="required">
                                            @lang('superadmin::app.catalog.products.index.create.family')
                                        </x-superadmin::form.control-group.label>

                                        <x-superadmin::form.control-group.control
                                            type="select"
                                            name="attribute_family_id"
                                            rules="required"
                                            :label="trans('superadmin::app.catalog.products.index.create.family')"
                                        >
                                            @foreach($families as $family)
                                                <option 
                                                    value="{{ $family->id }}"
                                                    v-pre
                                                >
                                                    {{ $family->name }}
                                                </option>
                                            @endforeach
                                        </x-superadmin::form.control-group.control>

                                        <x-superadmin::form.control-group.error control-name="attribute_family_id" />
                                    </x-superadmin::form.control-group>

                                    <!-- SKU -->
                                    <x-superadmin::form.control-group>
                                        <x-superadmin::form.control-group.label class="required">
                                            @lang('superadmin::app.catalog.products.index.create.sku')
                                        </x-superadmin::form.control-group.label>

                                        <x-superadmin::form.control-group.control
                                            type="text"
                                            name="sku"
                                            ::rules="{ required: true, regex: /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/ }"
                                            :label="trans('superadmin::app.catalog.products.index.create.sku')"
                                        />

                                        <x-superadmin::form.control-group.error control-name="sku" />
                                    </x-superadmin::form.control-group>

                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.after') !!}
                                </div>

                                <div v-show="attributes.length">
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.before') !!}

                                    <div
                                        class="mb-2.5"
                                        v-for="attribute in attributes"
                                    >
                                        <label
                                            class="block text-xs font-medium leading-6 text-gray-800 dark:text-white"
                                            v-text="attribute.name"
                                        >
                                        </label>

                                        <div class="flex min-h-[38px] flex-wrap gap-1 rounded-md border p-1.5 dark:border-gray-800">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="option in attribute.options"
                                            >
                                                @{{ option.name }}

                                                <span
                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                    @click="removeOption(option)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.after') !!}
                                </div>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <div class="flex items-center gap-x-2.5">
                                    <!-- Back Button -->
                                    <x-superadmin::button
                                        button-type="button"
                                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                                        :title="trans('superadmin::app.catalog.products.index.create.back-btn')"
                                        v-if="attributes.length"
                                        @click="attributes = []"
                                    />

                                    <!-- Save Button -->
                                    <x-superadmin::button
                                        button-type="button"
                                        class="primary-button"
                                        :title="trans('superadmin::app.catalog.products.index.create.save-btn')"
                                        ::loading="isLoading"
                                        ::disabled="isLoading"
                                    />
                                </div>
                            </x-slot>
                        </x-superadmin::modal>
                    </form>
                </x-superadmin::form>
            </div>
        </script>

        <script type="module">
            window.app.component('v-create-product-form', {
                template: '#v-create-product-form-template',

                data() {
                    return {
                        attributes: [],

                        superAttributes: {},

                        isLoading: false,
                    };
                },

                methods: {
                    create(params, { resetForm, resetField, setErrors }) {
                        this.isLoading = true;

                        this.attributes.forEach(attribute => {
                            params.super_attributes ||= {};

                            params.super_attributes[attribute.code] = this.superAttributes[attribute.code];
                        });

                        this.$axios.post("{{ route('superadmin.catalog.products.store') }}", params)
                            .then((response) => {
                                this.isLoading = false;

                                if (response.data.data.redirect_url) {
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    this.attributes = response.data.data.attributes;

                                    this.setSuperAttributes();
                                }
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    removeOption(option) {
                        this.attributes.forEach(attribute => {
                            attribute.options = attribute.options.filter(item => item.id != option.id);
                        });

                        this.attributes = this.attributes.filter(attribute => attribute.options.length > 0);

                        this.setSuperAttributes();
                    },

                    setSuperAttributes() {
                        this.superAttributes = {};

                        this.attributes.forEach(attribute => {
                            this.superAttributes[attribute.code] = [];

                            attribute.options.forEach(option => {
                                this.superAttributes[attribute.code].push(option.id);
                            });
                        });
                    }
                }
            })
        </script>
    @endPushOnce
</x-superadmin::layouts>
