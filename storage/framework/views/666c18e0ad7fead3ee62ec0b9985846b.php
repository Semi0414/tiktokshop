
<?php
    $galleryImages = product_image()->getGalleryImages($product);
    $galleryVideos = product_video()->getVideos($product);
    $mainImage = product_image()->getProductBaseImage($product);
    $productName = $product->name;
?>

<div class="flex max-1180:flex-col max-1180:gap-4">
    
    <?php if(($galleryImages && count($galleryImages)) || ($galleryVideos && count($galleryVideos))): ?>
        <div class="sticky top-20 hidden h-max flex-col gap-2 max-1180:hidden">
            <?php $__currentLoopData = $galleryImages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($img['large_image_url']); ?>" target="_blank" rel="noopener noreferrer">
                    <img
                        src="<?php echo e($img['small_image_url']); ?>"
                        alt="<?php echo e($productName); ?>"
                        width="100"
                        height="100"
                        class="max-h-[100px] max-w-[100px] rounded-xl border border-zinc-200 object-cover"
                        loading="lazy"
                    >
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $galleryVideos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <video
                    class="max-h-[100px] max-w-[100px] rounded-xl border border-zinc-200"
                    aria-label="<?php echo e($productName); ?>"
                    controls
                    preload="metadata"
                >
                    <source src="<?php echo e($vid['video_url']); ?>" type="video/mp4">
                </video>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <div class="max-h-[610px] max-w-[560px] max-1180:mx-auto">
        <?php if(! empty($mainImage['large_image_url'])): ?>
            <img
                src="<?php echo e($mainImage['large_image_url']); ?>"
                alt="<?php echo e($productName); ?>"
                width="560"
                height="610"
                class="w-full max-w-full cursor-zoom-in rounded-xl object-contain"
                fetchpriority="high"
            >
        <?php else: ?>
            <div class="flex min-h-[300px] items-center justify-center rounded-xl bg-zinc-100 text-zinc-500">
                <?php echo app('translator')->get('shop::app.products.view.gallery.product-image'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php if(($galleryImages && count($galleryImages)) || ($galleryVideos && count($galleryVideos))): ?>
    <div class="mt-4 flex gap-3 overflow-x-auto pb-2 1180:hidden">
        <?php $__currentLoopData = $galleryImages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($img['large_image_url']); ?>" class="shrink-0" target="_blank" rel="noopener noreferrer">
                <img
                    src="<?php echo e($img['small_image_url']); ?>"
                    alt="<?php echo e($productName); ?>"
                    width="88"
                    height="88"
                    class="h-[88px] w-[88px] rounded-lg border border-zinc-200 object-cover"
                    loading="lazy"
                >
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $galleryVideos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <video
                class="h-[88px] w-[88px] shrink-0 rounded-lg border border-zinc-200 object-cover"
                aria-label="<?php echo e($productName); ?>"
                controls
                preload="metadata"
            >
                <source src="<?php echo e($vid['video_url']); ?>" type="video/mp4">
            </video>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/products/view/gallery-html.blade.php ENDPATH**/ ?>