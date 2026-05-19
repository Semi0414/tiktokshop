<!-- SEO Meta Content -->
<?php $__env->startPush('meta'); ?>
    <meta name="description" content="<?php echo e(trim($product->meta_description) != '' ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '')); ?>"/>

    <meta name="keywords" content="<?php echo e($product->meta_keywords); ?>"/>

    <?php if(core()->getConfigData('catalog.rich_snippets.products.enable')): ?>
        <script type="application/ld+json">
            <?php echo app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product); ?>

        </script>
    <?php endif; ?>

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image"/>

    <meta name="twitter:title" content="<?php echo e($product->name); ?>"/>

    <meta name="twitter:description" content="<?php echo e(\Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 200, '')); ?>"/>

    <meta name="twitter:image:alt" content="<?php echo e($product->name); ?>"/>

    <meta name="twitter:image" content="<?php echo e($productBaseImage['medium_image_url']); ?>"/>

    <meta property="og:type" content="og:product"/>

    <meta property="og:title" content="<?php echo e($product->name); ?>"/>

    <meta property="og:image" content="<?php echo e($productBaseImage['medium_image_url']); ?>"/>

    <meta property="og:description" content="<?php echo e(\Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 200, '')); ?>"/>

    <meta property="og:url" content="<?php echo e(url()->full()); ?>"/>
<?php $__env->stopPush(); ?>

<?php if (isset($component)) { $__componentOriginal2643b7d197f48caff2f606750db81304 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2643b7d197f48caff2f606750db81304 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e(trim((string) $product->meta_title) !== '' ? $product->meta_title : $product->name); ?> <?php $__env->endSlot(); ?>

    <?php echo view_render_event('bagisto.shop.products.view.before', ['product' => $product]); ?>


    <?php if(core()->getConfigData('general.general.breadcrumbs.shop')): ?>
        <div class="flex justify-center px-7 max-lg:hidden">
            <?php if (isset($component)) { $__componentOriginaldef12fd0653509715c3bc62a609dde73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldef12fd0653509715c3bc62a609dde73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.breadcrumbs.index','data' => ['name' => 'product','entity' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'product','entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $attributes = $__attributesOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__attributesOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldef12fd0653509715c3bc62a609dde73)): ?>
<?php $component = $__componentOriginaldef12fd0653509715c3bc62a609dde73; ?>
<?php unset($__componentOriginaldef12fd0653509715c3bc62a609dde73); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    <article
        class="product-view"
        itemscope
        itemtype="https://schema.org/Product"
    >
        <meta itemprop="name" content="<?php echo e($product->name); ?>">
        <meta itemprop="sku" content="<?php echo e($product->sku); ?>">

        <?php if(core()->getConfigData('sales.checkout.shopping_cart.cart_page')): ?>
            <form
                class="cart-add-form"
                method="post"
                action="<?php echo e(route('shop.checkout.cart.add_from_product')); ?>"
                accept-charset="UTF-8"
            >
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                <input type="hidden" name="pid" value="<?php echo e($product->id); ?>">
                <input type="hidden" name="is_buy_now" value="0">
                <input type="hidden" name="wallet_auto_order" value="1">

                <?php echo $__env->make('shop::products.view.product-main-inner', ['insideCartForm' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </form>
        <?php else: ?>
            <?php echo $__env->make('shop::products.view.product-main-inner', ['insideCartForm' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    </article>

    <div class="container mt-16 max-w-full px-[60px] max-1180:px-5">
        <?php echo view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]); ?>


        <section id="product-description-section" aria-labelledby="product-description-heading" class="border-b border-zinc-100 py-10">
            <h2 id="product-description-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                <?php echo app('translator')->get('shop::app.products.view.description'); ?>
            </h2>
            <div class="product-description prose max-w-none text-lg text-zinc-600 max-md:text-sm">
                <?php echo $product->description; ?>

            </div>
        </section>

        <?php echo view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]); ?>


        <?php if($attributeData->isNotEmpty()): ?>
            <section id="product-information-section" aria-labelledby="product-information-heading" class="border-b border-zinc-100 py-10">
                <h2 id="product-information-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                    <?php echo app('translator')->get('shop::app.products.view.additional-information'); ?>
                </h2>
                <div class="grid max-w-2xl grid-cols-1 gap-4 md:grid-cols-[auto_1fr]" role="list">
                    <?php $__currentLoopData = $customAttributeValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customAttributeValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(! empty($customAttributeValue['value'])): ?>
                            <div class="grid" role="listitem">
                                <div class="text-base font-medium text-zinc-900">
                                    <?php echo $customAttributeValue['label']; ?>

                                </div>
                            </div>

                            <?php if($customAttributeValue['type'] == 'file'): ?>
                                <div role="listitem">
                                    <a href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>" download="<?php echo e(strip_tags((string) $customAttributeValue['label'])); ?>" class="text-blue-600 hover:underline">
                                        <span class="text-2xl icon-download" aria-hidden="true"></span>
                                        <?php echo e(__('Download')); ?>

                                    </a>
                                </div>
                            <?php elseif($customAttributeValue['type'] == 'image'): ?>
                                <div role="listitem">
                                    <a href="<?php echo e(Storage::url($product[$customAttributeValue['code']])); ?>" download="<?php echo e(strip_tags((string) $customAttributeValue['label'])); ?>" class="inline-block">
                                        <img
                                            class="h-12 w-12 rounded object-cover"
                                            src="<?php echo e(Storage::url($customAttributeValue['value'])); ?>"
                                            alt="<?php echo e(strip_tags((string) $customAttributeValue['label'])); ?>"
                                        >
                                    </a>
                                </div>
                            <?php else: ?>
                                <div role="listitem">
                                    <div class="text-base text-zinc-600">
                                        <?php echo $customAttributeValue['value']; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <section id="product-reviews-section" aria-labelledby="product-reviews-heading" class="py-10">
            <h2 id="product-reviews-heading" class="mb-6 text-xl font-semibold text-zinc-900 max-sm:text-lg">
                <?php echo app('translator')->get('shop::app.products.view.review'); ?>
            </h2>
            <?php echo $__env->make('shop::products.view.reviews-html', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>
    </div>

    <?php echo $__env->make('shop::products.view.related-products-html', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo view_render_event('bagisto.shop.products.view.after', ['product' => $product]); ?>


    <?php $__env->startPush('scripts'); ?>
        <script>
            (function () {
                var form = document.querySelector('form.cart-add-form');

                if (!form || form.dataset.cartSubmitBound === '1') {
                    return;
                }

                form.dataset.cartSubmitBound = '1';

                form.addEventListener('submit', function () {
                    var btn = form.querySelector('button[type="submit"]');

                    if (!btn || btn.disabled) {
                        return;
                    }

                    btn.disabled = true;
                    btn.setAttribute('aria-busy', 'true');

                    if (!btn.dataset.originalLabel) {
                        btn.dataset.originalLabel = btn.textContent.trim();
                    }

                    btn.textContent = <?php echo json_encode(__('Processing order…'), 15, 512) ?>;
                });
            })();
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $attributes = $__attributesOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__attributesOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $component = $__componentOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__componentOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/products/view.blade.php ENDPATH**/ ?>