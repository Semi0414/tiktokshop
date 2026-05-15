<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.product-review')
    </x-slot>

    <x-admin::seller.panel active="product_review" :breadcrumb="[__('admin::app.components.layouts.sidebar.product-management'), __('admin::app.seller-panel.tabs.product-review')]">
        <form method="get" action="{{ route('admin.customers.customers.review.index') }}" class="seller-filter-card mb-4" id="seller-review-filters">
            <div class="grid gap-3 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.member-nickname')</label>
                    <input type="text" name="seller_review_nickname" value="{{ request('seller_review_nickname') }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">@lang('admin::app.seller-panel.filters.review')</label>
                    <select name="seller_review_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="all" @selected(! request('seller_review_status') || request('seller_review_status') === 'all')>@lang('admin::app.seller-panel.filters.all')</option>
                        <option value="approved" @selected(request('seller_review_status') === 'approved')>@lang('admin::app.customers.reviews.index.datagrid.approved')</option>
                        <option value="pending" @selected(request('seller_review_status') === 'pending')>@lang('admin::app.customers.reviews.index.datagrid.pending')</option>
                        <option value="disapproved" @selected(request('seller_review_status') === 'disapproved')>@lang('admin::app.customers.reviews.index.datagrid.disapproved')</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="seller-btn-primary">@lang('admin::app.seller-panel.filters.enquire')</button>
                    <a href="{{ route('admin.customers.customers.review.index') }}" class="seller-btn-secondary">@lang('admin::app.seller-panel.filters.reset')</a>
                </div>
            </div>
        </form>

        <div class="mb-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.customers.reviews.index.title')
            </p>
        </div>

        <x-admin::seller.responsive-table class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <x-slot:table>
                <div class="overflow-x-auto p-3">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">ID</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Product</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Title</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Comment</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Rating</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Date</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse (($reviewsPaginator ?? null)?->items() ?? [] as $review)
                        <tr>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->id }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->customer_name }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->product_name }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->title }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->comment }}</td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $review->rating }}</td>
                            <td class="px-3 py-3 text-sm">
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst($review->status) }}</span>
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ \Illuminate\Support\Carbon::parse($review->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-2">
                                    <form method="post" action="{{ route('admin.customers.customers.review.update', $review->id) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="rounded-md border border-gray-200 px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                            <option value="approved" @selected($review->status === 'approved')>@lang('admin::app.customers.reviews.index.datagrid.approved')</option>
                                            <option value="pending" @selected($review->status === 'pending')>@lang('admin::app.customers.reviews.index.datagrid.pending')</option>
                                            <option value="disapproved" @selected($review->status === 'disapproved')>@lang('admin::app.customers.reviews.index.datagrid.disapproved')</option>
                                        </select>
                                        <button type="submit" class="seller-btn-primary text-xs">Save</button>
                                    </form>

                                    <form method="post" action="{{ route('admin.customers.customers.review.delete', $review->id) }}" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="seller-btn-secondary text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                @lang('admin::app.components.datagrid.table.no-records-available')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                    </table>
                </div>
            </x-slot:table>

            <x-slot:cards>
                @forelse (($reviewsPaginator ?? null)?->items() ?? [] as $review)
                    <article class="seller-mobile-card">
                        <div class="seller-mobile-card__header">
                            <p class="seller-mobile-card__title">{{ $review->product_name }}</p>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst($review->status) }}</span>
                        </div>
                        <div class="seller-mobile-card__rows">
                            <x-admin::seller.mobile-card-field label="ID">{{ $review->id }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Customer">{{ $review->customer_name }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Title">{{ $review->title }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Comment">{{ $review->comment }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Rating">{{ $review->rating }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Date">{{ \Illuminate\Support\Carbon::parse($review->created_at)->format('Y-m-d H:i') }}</x-admin::seller.mobile-card-field>
                        </div>
                        <div class="seller-mobile-card__actions">
                            <form method="post" action="{{ route('admin.customers.customers.review.update', $review->id) }}" class="flex w-full flex-col gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="w-full rounded-md border border-gray-200 px-2 py-1.5 text-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="approved" @selected($review->status === 'approved')>@lang('admin::app.customers.reviews.index.datagrid.approved')</option>
                                    <option value="pending" @selected($review->status === 'pending')>@lang('admin::app.customers.reviews.index.datagrid.pending')</option>
                                    <option value="disapproved" @selected($review->status === 'disapproved')>@lang('admin::app.customers.reviews.index.datagrid.disapproved')</option>
                                </select>
                                <button type="submit" class="seller-btn-primary text-xs">Save</button>
                            </form>
                            <form method="post" action="{{ route('admin.customers.customers.review.delete', $review->id) }}" onsubmit="return confirm('Delete this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="seller-btn-secondary w-full text-xs">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                        @lang('admin::app.components.datagrid.table.no-records-available')
                    </p>
                @endforelse
            </x-slot:cards>

            <x-slot:footer>
                <div class="flex items-center justify-between gap-3 border-t border-gray-100 px-3 py-3 text-xs text-gray-600 dark:border-gray-800 dark:text-gray-300">
                    <span>
                        Showing {{ $reviewsPaginator->firstItem() ?? 0 }} to {{ $reviewsPaginator->lastItem() ?? 0 }} of {{ $reviewsPaginator->total() ?? 0 }}
                    </span>
                    <div>
                        {{ ($reviewsPaginator ?? null)?->links() }}
                    </div>
                </div>
            </x-slot:footer>
        </x-admin::seller.responsive-table>
    </x-admin::seller.panel>
</x-admin::layouts>
