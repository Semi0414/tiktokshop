<?php
    $pay = (float) ($product->final_price ?? 0);
    $wishlistEnabled = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
    $pid = (int) ($product->product_id ?? 0);
    $ids = $wishlistedProductIds ?? [];
    $inWishlist = $wishlistEnabled && $pid && in_array($pid, $ids, true);
?>
<article class="product-card">
    <div class="product-image-wrap">
        <a href="<?php echo e($product->product_url); ?>" class="product-image-inner" style="display:block;">
            <?php if(!empty($product->image_url)): ?>
                <img class="product-image" src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" />
            <?php else: ?>
                <div class="product-image" style="background:#1e293b;"></div>
            <?php endif; ?>
        </a>
        <?php if($pay > 0 && !empty($product->tik_badge_text)): ?>
            <div class="product-badge <?php echo e(!empty($product->tik_badge_is_percent) ? 'product-badge-sale' : ''); ?>">
                <?php if(!empty($product->tik_badge_dot)): ?>
                    <span class="badge-dot"></span>
                <?php endif; ?>
                <?php echo e($product->tik_badge_text); ?>

            </div>
        <?php endif; ?>
        <?php if($wishlistEnabled && $pid): ?>
            <button
                type="button"
                class="wishlist-btn <?php echo e($inWishlist ? 'is-active' : ''); ?>"
                data-product-id="<?php echo e($pid); ?>"
                title="<?php echo e($inWishlist ? __('Remove from wishlist') : __('Add to wishlist')); ?>"
                aria-label="<?php echo e($inWishlist ? __('Remove from wishlist') : __('Add to wishlist')); ?>"
                aria-pressed="<?php echo e($inWishlist ? 'true' : 'false'); ?>"
                onclick="event.preventDefault();event.stopPropagation();"
            ><?php echo e($inWishlist ? '♥' : '♡'); ?></button>
        <?php endif; ?>
    </div>
    <div class="product-body">
        <a href="<?php echo e($product->product_url); ?>" class="product-title-link">
            <h3 class="product-title"><?php echo e($product->name); ?></h3>
        </a>
        <div class="product-meta-row">
            <div class="rating">
                <span class="star-icon" aria-hidden="true">★</span>
                <span><?php echo e($product->tik_rating ?? '4.8'); ?></span>
                <span class="review-muted">(<?php echo e($product->tik_reviews_fmt ?? '0'); ?>)</span>
            </div>
            <span class="sold-count"><?php echo e($product->tik_sold_fmt ?? '0'); ?> <?php echo e(__('sold')); ?></span>
        </div>
        <?php if($pay > 0): ?>
            <div class="price-row">
                <span class="price"><?php echo e(core()->formatPrice($product->final_price)); ?></span>
                <span class="price-original"><?php echo e(core()->formatPrice($product->tik_compare_at_price)); ?></span>
                <span class="price-discount">-<?php echo e($product->tik_discount_pct_display); ?>%</span>
            </div>
        <?php else: ?>
            <div class="price-row">
                <span class="price"><?php echo e(core()->formatPrice(0)); ?></span>
            </div>
        <?php endif; ?>
        <?php if(!empty($product->tik_tags)): ?>
            <div class="product-tags">
                <?php $__currentLoopData = $product->tik_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="product-tag"><?php echo e($tag); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <div class="product-footer">
            <div class="ship-label">
                <span></span>
                <span><?php echo e($product->tik_ship_label ?? __('Free shipping')); ?></span>
            </div>
            <a href="<?php echo e($product->product_url); ?>" class="add-btn" onclick="event.stopPropagation();">+ <?php echo e(__('Add')); ?></a>
        </div>
    </div>
</article>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/tik-store/partials/product-card.blade.php ENDPATH**/ ?>