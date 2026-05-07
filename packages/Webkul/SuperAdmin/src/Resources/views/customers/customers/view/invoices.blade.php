<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
        @lang('superadmin::app.customers.customers.view.invoices.count', ['count' => count($customer->invoices)])
    </p>

    <x-superadmin::datagrid.ssr
        pagination-namespace="customer_invoices"
        :datagrid-payload="$customerInvoicesDatagridPayload"
        :src="route('superadmin.customers.customers.view', ['id' => $customer->id, 'dg' => 'invoices'])"
        :isMultiRow="true"
    />
</div>