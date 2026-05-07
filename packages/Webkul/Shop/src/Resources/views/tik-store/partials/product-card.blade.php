@php
    $pay = (float) ($product->final_price ?? 0);
    $wishlistEnabled = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
    $pid = (int) ($product->product_id ?? 0);
    $ids = $wishlistedProductIds ?? [];
    $inWishlist = $wishlistEnabled && $pid && in_array($pid, $ids, true);
@endphp
<article class="product-card">
    <div class="product-image-wrap">
        <a href="{{ $product->product_url }}" class="product-image-inner" style="display:block;">
            @if(!empty($product->image_url))
                <img class="product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" />
            @else
                <div class="product-image" style="background:#1e293b;"></div>
            @endif
        </a>
        @if($pay > 0 && !empty($product->tik_badge_text))
            <div class="product-badge {{ !empty($product->tik_badge_is_percent) ? 'product-badge-sale' : '' }}">
                @if(!empty($product->tik_badge_dot))
                    <span class="badge-dot"></span>
                @endif
                {{ $product->tik_badge_text }}
            </div>
        @endif
        @if ($wishlistEnabled && $pid)
            <button
                type="button"
                class="wishlist-btn {{ $inWishlist ? 'is-active' : '' }}"
                data-product-id="{{ $pid }}"
                title="{{ $inWishlist ? __('Remove from wishlist') : __('Add to wishlist') }}"
                aria-label="{{ $inWishlist ? __('Remove from wishlist') : __('Add to wishlist') }}"
                aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
                onclick="event.preventDefault();event.stopPropagation();"
            >{{ $inWishlist ? '♥' : '♡' }}</button>
        @endif
    </div>
    <div class="product-body">
        <a href="{{ $product->product_url }}" class="product-title-link">
            <h3 class="product-title">{{ $product->name }}</h3>
        </a>
        <div class="product-meta-row">
            <div class="rating">
                <span class="star-icon" aria-hidden="true">★</span>
                <span>{{ $product->tik_rating ?? '4.8' }}</span>
                <span class="review-muted">({{ $product->tik_reviews_fmt ?? '0' }})</span>
            </div>
            <span class="sold-count">{{ $product->tik_sold_fmt ?? '0' }} {{ __('sold') }}</span>
        </div>
        @if($pay > 0)
            <div class="price-row">
                <span class="price">{{ core()->formatPrice($product->final_price) }}</span>
                <span class="price-original">{{ core()->formatPrice($product->tik_compare_at_price) }}</span>
                <span class="price-discount">-{{ $product->tik_discount_pct_display }}%</span>
            </div>
        @else
            <div class="price-row">
                <span class="price">{{ core()->formatPrice(0) }}</span>
            </div>
        @endif
        @if(!empty($product->tik_tags))
            <div class="product-tags">
                @foreach($product->tik_tags as $tag)
                    <span class="product-tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
        <div class="product-footer">
            <div class="ship-label">
                <span></span>
                <span>{{ $product->tik_ship_label ?? __('Free shipping') }}</span>
            </div>
            <a href="{{ $product->product_url }}" class="add-btn" onclick="event.stopPropagation();">+ {{ __('Add') }}</a>
        </div>
    </div>
</article>
