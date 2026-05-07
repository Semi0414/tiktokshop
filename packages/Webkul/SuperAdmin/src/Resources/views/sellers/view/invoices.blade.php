<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
        @lang('superadmin::app.customers.customers.view.invoices.count', ['count' => $seller->sellerInvoices->count()])
    </p>

    <x-superadmin::datagrid.ssr
        pagination-namespace="seller_invoices"
        :datagrid-payload="$sellerInvoicesDatagridPayload"
        :src="route('superadmin.sellers.view', ['id' => $seller->id, 'dg' => 'invoices', 'seller_id' => $seller->id])"
        :isMultiRow="true"
    />
</div>