<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Order Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.orders.count', ['count' => $seller->sellerOrders->count()])
        </p>

        <!-- Total Order Revenue -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.orders.total-revenue', ['revenue' => core()->formatPrice($seller->sellerOrders->whereNotIn('status', ['canceled', 'closed'])->sum('base_grand_total_invoiced'))])
        </p>
    </div>

    <x-superadmin::datagrid.ssr
        pagination-namespace="seller_orders"
        :datagrid-payload="$sellerOrdersDatagridPayload"
        :src="route('superadmin.sellers.view.orders_data', ['id' => $seller->id, 'seller_id' => $seller->id])"
        :isMultiRow="true"
    />
</div>
