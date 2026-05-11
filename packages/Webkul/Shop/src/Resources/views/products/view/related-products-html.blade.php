@if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <section class="container mt-16 border-t border-zinc-200 pt-10 max-1180:px-5" aria-labelledby="related-products-heading">
        <h2 id="related-products-heading" class="mb-6 text-2xl font-medium text-zinc-900 max-sm:text-xl">
            @lang('shop::app.products.view.related-product-title')
        </h2>
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($relatedProducts as $rel)
                @if ($rel->isSaleable() && $rel->visible_individually && $rel->status)
                    <li class="rounded-xl border border-zinc-200 p-3 transition-shadow hover:shadow-md">
                        <a href="{{ url(trim($rel->url_key, '/')).'?pid='.$rel->id }}" class="block">
                            @php $img = product_image()->getProductBaseImage($rel); @endphp
                            @if (! empty($img['small_image_url']))
                                <img
                                    src="{{ $img['small_image_url'] }}"
                                    alt="{{ $rel->name }}"
                                    class="mb-3 aspect-square w-full rounded-lg object-cover"
                                    loading="lazy"
                                    width="200"
                                    height="200"
                                >
                            @endif
                            <p class="line-clamp-2 text-sm font-medium text-zinc-800">{{ $rel->name }}</p>
                            <p class="mt-1 text-sm text-zinc-600">
                                {!! $rel->getTypeInstance()->getPriceHtml() !!}
                            </p>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
@endif
