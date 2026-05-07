<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Reviews Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.customers.view.reviews.count', ['count' => 0])
        </p>
    </div>

    <x-superadmin::datagrid.ssr
        pagination-namespace="seller_reviews"
        :datagrid-payload="$sellerReviewsDatagridPayload"
        :src="route('superadmin.sellers.view', ['id' => $seller->id, 'dg' => 'reviews'])"
        :isMultiRow="true"
    />
</div>