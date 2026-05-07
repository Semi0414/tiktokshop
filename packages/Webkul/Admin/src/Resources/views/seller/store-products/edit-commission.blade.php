<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.store-products.edit-commission-title')
    </x-slot>

    <x-admin::seller.panel
        active="store_products"
        :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.store-products'), __('admin::app.seller-panel.store-products.edit-commission-title')]"
    >
        <div class="mx-auto max-w-lg rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-2 text-sm text-gray-600 dark:text-gray-300">
                {{ $ssp->product?->name ?? __('admin::app.catalog.products.index.datagrid.name') }} (ID: {{ $ssp->product_id }})
            </p>

            @if ($commissionRule['readonly'])
                <p class="mb-4 text-sm font-medium text-gray-800 dark:text-white">
                    @lang('admin::app.seller-panel.store-products.edit-commission-readonly')
                </p>
            @else
                <form
                    method="post"
                    action="{{ route('admin.seller.store-products.update-commission', $ssp) }}"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.seller-panel.product-warehouse.commission-title') (%)
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="commission_percent"
                            step="0.01"
                            :value="old('commission_percent', $ssp->commission_percent)"
                            :min="$commissionRule['min']"
                            :max="$commissionRule['max']"
                            rules="required"
                        />

                        <x-admin::form.control-group.error control-name="commission_percent" />

                        <p class="mt-1 text-xs text-gray-500">
                            @lang('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                                'min' => $commissionRule['min'],
                                'max' => $commissionRule['max'],
                            ])
                        </p>
                    </x-admin::form.control-group>

                    <div class="flex gap-2">
                        <button type="submit" class="seller-btn-primary">
                            @lang('superadmin::app.customers.customers.view.edit.save-btn')
                        </button>
                        <a href="{{ route('admin.seller.store-products.index') }}" class="seller-btn-secondary">
                            @lang('admin::app.catalog.products.index.create.back-btn')
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </x-admin::seller.panel>
</x-admin::layouts>
