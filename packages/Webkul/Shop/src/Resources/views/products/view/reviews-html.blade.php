{{-- Approved reviews rendered server-side (no Vue). --}}
<div id="product-reviews-panel" class="container max-1180:px-5">
    @if ($productReviews->isNotEmpty())
        <h3 class="mb-6 font-dmserif text-2xl font-medium max-md:text-xl">
            @lang('shop::app.products.view.reviews.customer-review')
            ({{ $productReviews->count() }})
        </h3>

        <ul class="grid gap-4">
            @foreach ($productReviews as $review)
                <li class="rounded-xl border border-zinc-200 p-5">
                    <div class="flex flex-wrap items-start gap-4">
                        <div class="flex flex-col">
                            <p class="font-semibold text-zinc-900">{{ $review->name }}</p>
                            <p class="text-sm text-zinc-500">{{ $review->created_at?->diffForHumans() }}</p>
                            <div class="mt-2 flex gap-0.5" aria-hidden="true">
                                @for ($s = 1; $s <= 5; $s++)
                                    <span class="icon-star-fill text-2xl {{ (int) $review->rating >= $s ? 'text-amber-500' : 'text-zinc-300' }}"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if ($review->title)
                        <p class="mt-3 text-base font-medium text-zinc-800">{{ $review->title }}</p>
                    @endif
                    @if ($review->comment)
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $review->comment }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <div class="grid place-content-center gap-4 py-16 text-center">
            <img
                class="mx-auto max-h-32 max-w-32"
                src="{{ bagisto_asset('images/review.png') }}"
                alt=""
                width="128"
                height="128"
            >
            <p class="text-lg text-zinc-600 max-md:text-sm">
                @lang('shop::app.products.view.reviews.empty-review')
            </p>
        </div>
    @endif
</div>
