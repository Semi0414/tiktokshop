<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'context' => 'desktop',
    'inputId' => 'organic-search',
    'wrapperClass' => 'relative w-full',
    'formClass' => 'flex max-w-[445px] items-center',
    'inputClass' => 'block w-full py-3 text-xs font-medium text-gray-900 transition-all border border-transparent rounded-lg bg-zinc-100 px-11 hover:border-gray-400 focus:border-gray-400',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'context' => 'desktop',
    'inputId' => 'organic-search',
    'wrapperClass' => 'relative w-full',
    'formClass' => 'flex max-w-[445px] items-center',
    'inputClass' => 'block w-full py-3 text-xs font-medium text-gray-900 transition-all border border-transparent rounded-lg bg-zinc-100 px-11 hover:border-gray-400 focus:border-gray-400',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $minLen = (int) core()->getConfigData('catalog.products.search.min_query_length') ?: 1;
    $maxLen = (int) core()->getConfigData('catalog.products.search.max_query_length') ?: 200;
    $productBase = rtrim((string) route('shop.product_or_category.index', ''), '/');

    if ($context === 'mobile') {
        $srSearch = __('shop::app.components.layouts.header.mobile.search');
        $placeholder = __('shop::app.components.layouts.header.mobile.search-text');
        $srSubmit = __('shop::app.components.layouts.header.desktop.bottom.submit');
    } else {
        $srSearch = __('shop::app.components.layouts.header.desktop.bottom.search');
        $placeholder = __('shop::app.components.layouts.header.desktop.bottom.search-text');
        $srSubmit = __('shop::app.components.layouts.header.desktop.bottom.submit');
    }
?>

<v-storefront-search
    context="<?php echo e($context); ?>"
    search-action="<?php echo e(route('shop.search.index')); ?>"
    products-url="<?php echo e(route('shop.api.products.index')); ?>"
    sellers-url="<?php echo e(route('shop.api.sellers.search')); ?>"
    product-base="<?php echo e($productBase); ?>"
    input-id="<?php echo e($inputId); ?>"
    min-len="<?php echo e($minLen); ?>"
    max-len="<?php echo e($maxLen); ?>"
    form-class="<?php echo e($formClass); ?>"
    wrapper-class="<?php echo e($wrapperClass); ?>"
    input-class="<?php echo e($inputClass); ?>"
    placeholder="<?php echo e($placeholder); ?>"
    sr-search="<?php echo e($srSearch); ?>"
    sr-submit="<?php echo e($srSubmit); ?>"
    loading-label="<?php echo e(__('shop::app.components.layouts.header.search.loading')); ?>"
    stores-label="<?php echo e(__('shop::app.components.layouts.header.search.stores')); ?>"
    visit-store-label="<?php echo e(__('shop::app.components.layouts.header.search.visit-store')); ?>"
    products-label="<?php echo e(__('shop::app.components.layouts.header.search.products')); ?>"
    empty-label="<?php echo e(__('shop::app.components.layouts.header.search.empty')); ?>"
>
    <?php if(core()->getConfigData('catalog.products.settings.image_search')): ?>
        <?php echo $__env->make('shop::search.images.index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</v-storefront-search>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/search-smart.blade.php ENDPATH**/ ?>