<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.store-products')
    </x-slot>

    <x-admin::seller.panel
        active="store_products"
        :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.store-products')]"
    >
        <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                @lang('admin::app.seller-panel.store-products.total-in-store', ['count' => $storeProductsTotalCount ?? 0])
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.seller-panel.product-warehouse.level', ['level' => \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null)])
            </p>
        </div>

        <form
            method="get"
            action="{{ route('admin.seller.store-products.index') }}"
            class="seller-filter-card mb-4"
            id="seller-store-product-filters"
        >
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-name')</label>
                    <input
                        type="text"
                        name="seller_product_name"
                        value="{{ request('seller_product_name') }}"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-id')</label>
                    <input
                        type="text"
                        name="seller_product_id"
                        value="{{ request('seller_product_id') }}"
                        inputmode="numeric"
                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.category')</label>
                    <select name="seller_product_category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">@lang('admin::app.seller-panel.filters.all')</option>
                        @foreach ($categoryFilterOptions ?? [] as $cat)
                            <option value="{{ $cat->category_id }}" @selected((string) request('seller_product_category', '') === (string) $cat->category_id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.product-status')</label>
                    <select name="seller_product_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" @selected(request('seller_product_status', '') === '')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="1" @selected(request('seller_product_status') === '1')>@lang('admin::app.catalog.products.index.datagrid.active')</option>
                        <option value="0" @selected(request('seller_product_status') === '0')>@lang('admin::app.catalog.products.index.datagrid.disable')</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.store-products.recommended-filter')</label>
                    <select name="seller_product_recommended" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" @selected(request('seller_product_recommended', '') === '')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="1" @selected(request('seller_product_recommended') === '1')>@lang('admin::app.seller-panel.store-products.recommended-yes')</option>
                        <option value="0" @selected(request('seller_product_recommended') === '0')>@lang('admin::app.seller-panel.store-products.recommended-no')</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                <a href="{{ route('admin.seller.store-products.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
            </div>
        </form>

        <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.seller-panel.tabs.store-products')
            </p>
        </div>

        <v-seller-store-edit-modal></v-seller-store-edit-modal>

        <div id="seller-store-product-grid">
            <x-admin::datagrid
                :src="route('admin.seller.store-products.index')"
                :isMultiRow="true"
            >
            </x-admin::datagrid>
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>

@pushOnce('scripts')
    <script type="text/x-template" id="v-seller-store-edit-modal-template">
        <teleport to="body">
            <div
                v-if="show"
                class="admin-seller-add-modal-layer fixed inset-0 z-[2000] flex items-center justify-center bg-black/50 p-4"
                role="dialog"
                aria-modal="true"
                @click.self="close"
            >
                <div
                    class="relative mx-auto max-h-[90vh] w-full max-w-md overflow-y-auto rounded-lg border border-gray-200 bg-white p-5 shadow-xl dark:border-gray-700 dark:bg-gray-900"
                    @click.stop
                >
                    <p class="mb-1 text-sm font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.seller-panel.store-products.edit-modal-title')
                    </p>
                    <p v-if="loading" class="py-6 text-sm text-gray-500">@lang('admin::app.seller-panel.store-products.edit-modal-loading')</p>
                    <template v-else>
                        <p class="mb-3 text-xs text-gray-600 dark:text-gray-400" v-text="productLabel"></p>
                        <div class="mb-3">
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                                @lang('admin::app.seller-panel.product-warehouse.commission-title') (%)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                class="w-full rounded-md border border-gray-200 px-2.5 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                :class="{ 'cursor-not-allowed bg-gray-100 dark:bg-gray-950': commissionRule.readonly }"
                                v-model="commissionPercent"
                                :readonly="commissionRule.readonly"
                                :min="commissionRule.min"
                                :max="commissionRule.max"
                            />
                            <p class="mt-1 text-[11px] text-gray-500" v-text="rangeHint"></p>
                        </div>
                        <div class="mb-4 flex items-center gap-2">
                            <input type="checkbox" id="ssp-rec" v-model="isRecommended" class="rounded border-gray-300" />
                            <label for="ssp-rec" class="text-sm text-gray-800 dark:text-gray-200">
                                @lang('admin::app.seller-panel.store-products.recommended')
                            </label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="secondary-button !px-3 !py-1.5 text-xs" @click="close">
                                @lang('admin::app.acl.cancel')
                            </button>
                            <button type="button" class="primary-button !px-3 !py-1.5 text-xs" @click="submit">
                                @lang('admin::app.account.edit.save-btn')
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </teleport>
    </script>

    <script type="module">
        app.component('v-seller-store-edit-modal', {
            template: '#v-seller-store-edit-modal-template',

            data() {
                return {
                    show: false,
                    loading: false,
                    productName: '',
                    sku: '',
                    commissionPercent: '15',
                    isRecommended: false,
                    commissionRule: { readonly: true, min: 15, max: 15, default: 15 },
                    updateUrl: '',
                };
            },

            computed: {
                productLabel() {
                    if (!this.productName && !this.sku) {
                        return '';
                    }

                    return (this.productName || '') + (this.sku ? ' · ' + this.sku : '');
                },

                rangeHint() {
                    const c = this.commissionRule || {};

                    if (c.readonly) {
                        return @json(trans('admin::app.seller-panel.product-warehouse.bulk-add-beginner-fixed'));
                    }

                    return @json(trans('admin::app.seller-panel.product-warehouse.bulk-add-range-hint'))
                        .replace(':min', String(c.min ?? 0))
                        .replace(':max', String(c.max ?? 100));
                },
            },

            mounted() {
                this._open = (payload) => {
                    this.show = true;
                    this.loading = true;
                    this.updateUrl = '';

                    this.$axios.get(payload.url)
                        .then((response) => {
                            const d = response.data;

                            this.productName = d.product_name || '';
                            this.sku = d.sku || '';
                            this.commissionPercent = String(d.commission_percent ?? d.commission_rule?.default ?? 15);
                            this.isRecommended = !!d.is_recommended;
                            this.commissionRule = d.commission_rule || this.commissionRule;
                            this.updateUrl = d.update_url || '';
                        })
                        .catch(() => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: @json(trans('admin::app.seller-panel.store-products.edit-modal-error')),
                            });

                            this.close();
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                };

                this.$emitter.on('seller-store-edit-open', this._open);
            },

            beforeUnmount() {
                this.$emitter.off('seller-store-edit-open', this._open);
            },

            methods: {
                close() {
                    this.show = false;
                },

                submit() {
                    let pct = parseFloat(this.commissionPercent);

                    if (this.commissionRule.readonly) {
                        pct = parseFloat(this.commissionRule.default ?? 15);
                    }

                    if (
                        Number.isNaN(pct)
                        || pct < (this.commissionRule.min ?? 0) - 0.0001
                        || pct > (this.commissionRule.max ?? 100) + 0.0001
                    ) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: @json(trans('admin::app.seller-panel.product-warehouse.bulk-add-invalid-range')),
                        });

                        return;
                    }

                    this.$axios.put(this.updateUrl, {
                        commission_percent: pct,
                        is_recommended: this.isRecommended,
                    })
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.close();

                            window.emitter.emit('seller-store-product-grid-refresh');
                        })
                        .catch((error) => {
                            const msg = error?.response?.data?.message ?? error?.message ?? 'Error';

                            this.$emitter.emit('add-flash', { type: 'error', message: msg });
                        });
                },
            },
        });
    </script>
@endPushOnce
