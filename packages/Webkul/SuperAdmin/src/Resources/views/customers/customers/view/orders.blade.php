<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Order Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.orders.count', ['count' => count($customer->orders)])
        </p>

        <!-- Total Order Revenue -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.orders.total-revenue', ['revenue' => core()->formatPrice($customer->orders->whereNotIn('status', ['canceled', 'closed'])->sum('base_grand_total_invoiced'))])
        </p>
    </div>

    <x-superadmin::datagrid.ssr
        pagination-namespace="customer_orders"
        :datagrid-payload="$customerOrdersDatagridPayload"
        :src="route('superadmin.customers.customers.view', ['id' => $customer->id, 'dg' => 'orders'])"
        :isMultiRow="true"
    />
</div>
