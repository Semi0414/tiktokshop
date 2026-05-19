<?php
    $insideCartForm = (bool) ($insideCartForm ?? false);
    $cartPageEnabled = (bool) core()->getConfigData('sales.checkout.shopping_cart.cart_page');
    $showAddToCart = $insideCartForm && $cartPageEnabled;
    $tikRatingTotal = isset($tikStoreDisplay) ? (string) ($tikStoreDisplay->tik_reviews_total ?? '') : '';
    $tikRatingAvg = isset($tikStoreDisplay) ? (string) ($tikStoreDisplay->tik_rating ?? '0') : '0';
?>

<div class="container px-[60px] max-1180:px-0">
    <div class="flex mt-12 gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
        <?php echo $__env->make('shop::products.view.gallery-html', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4">
            <?php echo $__env->make('shop::products.view.partials.product-order-notice', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo view_render_event('bagisto.shop.products.name.before', ['product' => $product]); ?>


            <header class="flex justify-between gap-4">
                <h1 id="product-main-title" class="text-3xl font-medium leading-tight text-zinc-950 break-words max-sm:text-xl" itemprop="name">
                    <?php echo e($product->name); ?>

                </h1>

                <?php if(core()->getConfigData('customer.settings.wishlist.wishlist_option')): ?>
                    <p class="text-sm">
                        <?php if(auth()->guard('customer')->check()): ?>
                            <a class="underline text-zinc-600 hover:text-zinc-900" href="<?php echo e(route('shop.customers.account.wishlist.index')); ?>">
                                <?php echo app('translator')->get('shop::app.products.view.add-to-wishlist'); ?>
                            </a>
                        <?php else: ?>
                            <a class="underline text-zinc-600 hover:text-zinc-900" href="<?php echo e(route('shop.customer.session.index', ['redirect_url' => url()->current()])); ?>">
                                <?php echo app('translator')->get('shop::app.products.view.add-to-wishlist'); ?>
                            </a>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </header>

            <?php echo view_render_event('bagisto.shop.products.name.after', ['product' => $product]); ?>


            <?php echo view_render_event('bagisto.shop.products.rating.before', ['product' => $product]); ?>


            <?php if(! empty($tikStoreDisplay)): ?>
                <div class="mt-1 flex flex-col gap-2 max-sm:mt-1.5">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                        <a href="#product-reviews-section" class="inline-flex w-max rounded-md border border-zinc-200 px-4 py-2 transition-colors hover:border-zinc-400 max-sm:px-3 max-sm:py-1">
                            <?php if (isset($component)) { $__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.products.ratings-static','data' => ['class' => 'transition-all hover:border-transparent max-sm:px-3 max-sm:py-1','average' => $tikRatingAvg,'total' => (int) $tikRatingTotal]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::products.ratings-static'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'transition-all hover:border-transparent max-sm:px-3 max-sm:py-1','average' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tikRatingAvg),'total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((int) $tikRatingTotal)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99)): ?>
<?php $attributes = $__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99; ?>
<?php unset($__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99)): ?>
<?php $component = $__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99; ?>
<?php unset($__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99); ?>
<?php endif; ?>
                        </a>
                        <span class="text-sm font-medium text-zinc-600">
                            <?php echo e($tikStoreDisplay->tik_sold_fmt); ?> <?php echo e(__('sold')); ?>

                        </span>
                        <?php if(! empty($tikStoreDisplay->tik_badge_text)): ?>
                            <?php if(! empty($tikStoreDisplay->tik_badge_is_percent)): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-semibold text-rose-600">
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-800 px-2.5 py-0.5 text-xs font-semibold text-white">
                            <?php endif; ?>
                                <?php if(! empty($tikStoreDisplay->tik_badge_dot)): ?>
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                <?php endif; ?>
                                <?php echo e($tikStoreDisplay->tik_badge_text); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if(! empty($tikStoreDisplay->tik_tags)): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $tikStoreDisplay->tik_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700"><?php echo e($tag); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <p class="text-xs text-zinc-500">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            <?php echo e($tikStoreDisplay->tik_ship_label); ?>

                        </span>
                    </p>
                </div>
            <?php elseif($reviewTotalFeedback): ?>
                <div class="mt-1">
                    <a href="#product-reviews-section" class="inline-flex w-max rounded-md border border-zinc-200 px-4 py-2 max-sm:px-3 max-sm:py-1">
                        <?php if (isset($component)) { $__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.products.ratings-static','data' => ['class' => 'transition-all max-sm:text-xs','average' => (string) $avgRatings,'total' => (int) $reviewTotalFeedback]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::products.ratings-static'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'transition-all max-sm:text-xs','average' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((string) $avgRatings),'total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((int) $reviewTotalFeedback)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99)): ?>
<?php $attributes = $__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99; ?>
<?php unset($__attributesOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99)): ?>
<?php $component = $__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99; ?>
<?php unset($__componentOriginalb28fcd0d4d0cda8dbb0c3a2504df3b99); ?>
<?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.products.rating.after', ['product' => $product]); ?>


            <?php echo view_render_event('bagisto.shop.products.price.before', ['product' => $product]); ?>


            <div id="product-main-price">
                <div class="mt-[22px] flex flex-wrap items-center gap-2.5 text-2xl font-medium max-sm:mt-2 max-sm:text-lg">
                    <?php echo $product->getTypeInstance()->getPriceHtml(); ?>

                </div>
            </div>

            <?php if(\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices()): ?>
                <p class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                    (<?php echo app('translator')->get('shop::app.products.view.tax-inclusive'); ?>)
                </p>
            <?php endif; ?>

            <?php if(count($product->getTypeInstance()->getCustomerGroupPricingOffers())): ?>
                <div class="mt-2.5 grid gap-1.5">
                    <?php $__currentLoopData = $product->getTypeInstance()->getCustomerGroupPricingOffers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-zinc-500 [&_*]:text-inherit [&_strong]:text-black">
                            <?php echo $offer; ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.products.price.after', ['product' => $product]); ?>


            <?php echo view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]); ?>


            <?php if($product->short_description): ?>
                <div class="product-short-description mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm" itemprop="description">
                    <?php echo $product->short_description; ?>

                </div>
            <?php endif; ?>

            <?php echo view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]); ?>


            <?php if($product->type === 'configurable'): ?>
                <?php echo $__env->make('shop::products.view.types.configurable-html', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif($product->type === 'simple' || $product->type === 'virtual'): ?>
                <?php echo $__env->make('shop::products.view.types.simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif($product->type === 'grouped'): ?>
                <?php echo $__env->make('shop::products.view.types.grouped', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($showAddToCart): ?>
                <div class="mt-8 flex max-w-[470px] flex-wrap items-center gap-4 max-sm:mt-4">
                    <?php echo view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]); ?>


                    <?php if($product->getTypeInstance()->showQuantityBox()): ?>
                        <label for="product_quantity" class="sr-only"><?php echo app('translator')->get('shop::app.checkout.cart.index.quantity'); ?></label>
                        <input
                            id="product_quantity"
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            step="1"
                            class="w-28 rounded-xl border border-navyBlue px-4 py-3 text-center max-sm:py-1.5"
                            required
                        >
                    <?php endif; ?>

                    <?php echo view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]); ?>


                    <?php echo view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]); ?>


                    <button
                        type="submit"
                        name="checkout_action"
                        value="cart"
                        class="primary-button inline-flex justify-center rounded-lg px-10 py-3 text-center text-base font-semibold text-white max-sm:rounded-lg max-sm:py-1.5"
                    >
                        <?php echo app('translator')->get('shop::app.products.view.add-to-cart'); ?>
                    </button>

                    <?php if($product->type === 'configurable' || $product->type === 'grouped'): ?>
                        
                    <?php endif; ?>

                    <?php echo view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]); ?>

                </div>
            <?php endif; ?>

            <?php if(! empty(core()->getConfigData('catalog.products.settings.compare_option'))): ?>
                <nav class="mt-10 flex flex-wrap gap-x-9 gap-y-3 text-base max-md:text-sm" aria-label="Compare">
                    <a href="<?php echo e(route('shop.compare.index')); ?>" class="inline-flex items-center gap-2.5 underline text-zinc-700 hover:text-zinc-900">
                        <span class="icon-compare text-2xl" aria-hidden="true"></span>
                        <?php echo app('translator')->get('shop::app.products.view.compare'); ?>
                    </a>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/products/view/product-main-inner.blade.php ENDPATH**/ ?>