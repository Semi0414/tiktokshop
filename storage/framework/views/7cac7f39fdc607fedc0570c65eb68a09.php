
<div id="product-reviews-panel" class="container max-1180:px-5">
    <?php if($productReviews->isNotEmpty()): ?>
        <h3 class="mb-6 font-dmserif text-2xl font-medium max-md:text-xl">
            <?php echo app('translator')->get('shop::app.products.view.reviews.customer-review'); ?>
            (<?php echo e($productReviews->count()); ?>)
        </h3>

        <ul class="grid gap-4">
            <?php $__currentLoopData = $productReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="rounded-xl border border-zinc-200 p-5">
                    <div class="flex flex-wrap items-start gap-4">
                        <div class="flex flex-col">
                            <p class="font-semibold text-zinc-900"><?php echo e($review->name); ?></p>
                            <p class="text-sm text-zinc-500"><?php echo e($review->created_at?->diffForHumans()); ?></p>
                            <div class="mt-2 flex gap-0.5" aria-hidden="true">
                                <?php for($s = 1; $s <= 5; $s++): ?>
                                    <span class="icon-star-fill text-2xl <?php echo e((int) $review->rating >= $s ? 'text-amber-500' : 'text-zinc-300'); ?>"></span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($review->title): ?>
                        <p class="mt-3 text-base font-medium text-zinc-800"><?php echo e($review->title); ?></p>
                    <?php endif; ?>
                    <?php if($review->comment): ?>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600"><?php echo e($review->comment); ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <div class="grid place-content-center gap-4 py-16 text-center">
            <img
                class="mx-auto max-h-32 max-w-32"
                src="<?php echo e(bagisto_asset('images/review.png')); ?>"
                alt=""
                width="128"
                height="128"
            >
            <p class="text-lg text-zinc-600 max-md:text-sm">
                <?php echo app('translator')->get('shop::app.products.view.reviews.empty-review'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/products/view/reviews-html.blade.php ENDPATH**/ ?>