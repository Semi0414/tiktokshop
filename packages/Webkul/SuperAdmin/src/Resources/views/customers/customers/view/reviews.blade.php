<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Reviews Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.reviews.count', ['count' => count($customer->reviews)])
        </p>
    </div>

    <x-superadmin::datagrid.ssr
        pagination-namespace="customer_reviews"
        :datagrid-payload="$customerReviewsDatagridPayload"
        :src="route('superadmin.customers.customers.view', ['id' => $customer->id, 'dg' => 'reviews'])"
        :isMultiRow="true"
    />
</div>