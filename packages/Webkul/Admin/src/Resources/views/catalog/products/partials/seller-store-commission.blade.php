@php
    $ssp = $sellerStoreContext['sellerStoreProduct'] ?? null;
    $commissionRule = $sellerStoreContext['commissionRule'] ?? null;
    $eligibleForStore = (bool) ($product->status ?? false)
        && (bool) ($product->visible_individually ?? false);
@endphp

<div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
    <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">
        @lang('admin::app.seller-panel.tabs.product-warehouse')
    </p>

    @if ($ssp)
        @if ($commissionRule['readonly'] ?? false)
            <p class="mb-2 text-sm font-medium text-gray-800 dark:text-white">
                @lang('admin::app.seller-panel.store-products.edit-commission-readonly')
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                @lang('admin::app.seller-panel.store-products.commission-percent'):
                <span class="font-semibold">{{ number_format((float) $ssp->commission_percent, 2) }}%</span>
            </p>
        @else
            <form
                method="post"
                action="{{ route('admin.seller.store-products.update-commission', $ssp) }}"
                class="flex flex-wrap items-end gap-3"
            >
                @csrf
                @method('PUT')

                <x-admin::form.control-group class="!mb-0">
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
                        class="w-40"
                    />

                    <x-admin::form.control-group.error control-name="commission_percent" />
                </x-admin::form.control-group>

                <button type="submit" class="secondary-button">
                    @lang('superadmin::app.customers.customers.view.edit.save-btn')
                </button>
            </form>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.seller-panel.product-warehouse.commission-hint')
            </p>
        @endif
    @elseif ($eligibleForStore)
        <form method="post" action="{{ route('admin.seller.product-warehouse.attach') }}" class="inline-flex flex-wrap items-center gap-2">
            @csrf
            <input type="hidden" name="indices[]" value="{{ $product->id }}" />
            <button type="submit" class="seller-btn-primary">
                @lang('admin::app.seller-panel.product-warehouse.add-to-store')
            </button>
        </form>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            @lang('admin::app.seller-panel.product-warehouse.commission-hint')
        </p>
    @else
        <p class="text-sm text-gray-600 dark:text-gray-300">
            @lang('admin::app.seller-panel.product-warehouse.add-inactive-hint')
        </p>
    @endif
</div>
