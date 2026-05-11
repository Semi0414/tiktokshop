{{-- Server-rendered product gallery (no Vue). --}}
@php
    $galleryImages = product_image()->getGalleryImages($product);
    $galleryVideos = product_video()->getVideos($product);
    $mainImage = product_image()->getProductBaseImage($product);
    $productName = $product->name;
@endphp

<div class="flex max-1180:flex-col max-1180:gap-4">
    {{-- Thumbnails (desktop) --}}
    @if (($galleryImages && count($galleryImages)) || ($galleryVideos && count($galleryVideos)))
        <div class="sticky top-20 hidden h-max flex-col gap-2 max-1180:hidden">
            @foreach ($galleryImages ?? [] as $img)
                <a href="{{ $img['large_image_url'] }}" target="_blank" rel="noopener noreferrer">
                    <img
                        src="{{ $img['small_image_url'] }}"
                        alt="{{ $productName }}"
                        width="100"
                        height="100"
                        class="max-h-[100px] max-w-[100px] rounded-xl border border-zinc-200 object-cover"
                        loading="lazy"
                    >
                </a>
            @endforeach
            @foreach ($galleryVideos ?? [] as $vid)
                <video
                    class="max-h-[100px] max-w-[100px] rounded-xl border border-zinc-200"
                    aria-label="{{ $productName }}"
                    controls
                    preload="metadata"
                >
                    <source src="{{ $vid['video_url'] }}" type="video/mp4">
                </video>
            @endforeach
        </div>
    @endif

    {{-- Main media --}}
    <div class="max-h-[610px] max-w-[560px] max-1180:mx-auto">
        @if (! empty($mainImage['large_image_url']))
            <img
                src="{{ $mainImage['large_image_url'] }}"
                alt="{{ $productName }}"
                width="560"
                height="610"
                class="w-full max-w-full cursor-zoom-in rounded-xl object-contain"
                fetchpriority="high"
            >
        @else
            <div class="flex min-h-[300px] items-center justify-center rounded-xl bg-zinc-100 text-zinc-500">
                @lang('shop::app.products.view.gallery.product-image')
            </div>
        @endif
    </div>
</div>

{{-- Mobile: horizontal strip --}}
@if (($galleryImages && count($galleryImages)) || ($galleryVideos && count($galleryVideos)))
    <div class="mt-4 flex gap-3 overflow-x-auto pb-2 1180:hidden">
        @foreach ($galleryImages ?? [] as $img)
            <a href="{{ $img['large_image_url'] }}" class="shrink-0" target="_blank" rel="noopener noreferrer">
                <img
                    src="{{ $img['small_image_url'] }}"
                    alt="{{ $productName }}"
                    width="88"
                    height="88"
                    class="h-[88px] w-[88px] rounded-lg border border-zinc-200 object-cover"
                    loading="lazy"
                >
            </a>
        @endforeach
        @foreach ($galleryVideos ?? [] as $vid)
            <video
                class="h-[88px] w-[88px] shrink-0 rounded-lg border border-zinc-200 object-cover"
                aria-label="{{ $productName }}"
                controls
                preload="metadata"
            >
                <source src="{{ $vid['video_url'] }}" type="video/mp4">
            </video>
        @endforeach
    </div>
@endif
