<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.customers.reviews.index.edit.title')
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.customers.reviews.index.edit.title')
        </p>

        <a
            href="{{ route('superadmin.customers.customers.review.index') }}"
            class="secondary-button text-sm"
        >
            @lang('superadmin::app.customers.reviews.index.edit.back-to-list')
        </a>
    </div>

    <div class="box-shadow rounded bg-white px-6 py-5 dark:bg-gray-900">
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.customer')
                </p>

                <p class="font-semibold text-gray-800 dark:text-white">{{ $review->name !== '' ? $review->name : 'N/A' }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.product')
                </p>

                <p class="font-semibold text-gray-800 dark:text-white">{{ $review->product?->name ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.id')
                </p>

                <p class="font-semibold text-gray-800 dark:text-white">{{ $review->id }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.date')
                </p>

                <p class="font-semibold text-gray-800 dark:text-white">{{ $review->created_at?->format('Y-m-d') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('superadmin.customers.customers.review.update', $review->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-6 flex flex-col gap-2">
                <label class="required text-xs font-semibold text-gray-800 dark:text-white" for="review-status">
                    @lang('superadmin::app.customers.reviews.index.edit.status')
                </label>

                <select id="review-status" name="status" class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white" required>
                    <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>
                        @lang('superadmin::app.customers.reviews.index.edit.approved')
                    </option>

                    <option value="disapproved" {{ $review->status === 'disapproved' ? 'selected' : '' }}>
                        @lang('superadmin::app.customers.reviews.index.edit.disapproved')
                    </option>

                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>
                        @lang('superadmin::app.customers.reviews.index.edit.pending')
                    </option>
                </select>

                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <p class="font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.rating')
                </p>

                <div class="mt-1 flex">
                    <x-superadmin::star-rating
                        :value="$review->rating"
                        :disabled="true"
                    />
                </div>
            </div>

            <div class="mb-4">
                <p class="block text-xs font-medium text-gray-800 dark:text-white">
                    @lang('superadmin::app.customers.reviews.index.edit.review-title')
                </p>

                <p class="font-semibold text-gray-800 dark:text-white">{{ $review->title }}</p>
            </div>

            <div class="mb-6">
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @lang('superadmin::app.customers.reviews.index.edit.review-comment')
                </p>

                <p class="text-gray-800 dark:text-white">{{ $review->comment }}</p>
            </div>

            @if ($review->images->isNotEmpty())
                <div class="mb-6 w-full">
                    <p class="mb-2 text-xs font-semibold text-gray-600 dark:text-gray-300">
                        @lang('superadmin::app.customers.reviews.index.edit.images')
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @foreach ($review->images as $image)
                            @if ($image->type === 'image')
                                <img
                                    src="{{ $image->url }}"
                                    class="h-[60px] w-[60px] rounded"
                                    alt=""
                                />
                            @else
                                <video class="h-[60px] w-[60px] rounded" controls>
                                    <source src="{{ $image->url }}" type="video/mp4">
                                </video>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="primary-button">
                @lang('superadmin::app.customers.reviews.index.edit.save-btn')
            </button>
        </form>

        @if (bouncer()->hasPermission('customers.reviews.delete'))
            <form
                class="mt-3"
                method="POST"
                action="{{ route('superadmin.customers.customers.review.delete', $review->id) }}"
                onsubmit="return confirm(@json(__('superadmin::app.components.modal.confirm.message')))"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="secondary-button">
                    @lang('superadmin::app.customers.reviews.index.datagrid.delete')
                </button>
            </form>
        @endif
    </div>
</x-superadmin::layouts>
