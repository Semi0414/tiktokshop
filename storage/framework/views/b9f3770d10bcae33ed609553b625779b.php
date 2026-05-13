<?php if(\Webkul\Product\Helpers\ProductType::hasVariants($product->type)): ?>
    <?php
        $variants = $product->variants->filter(fn ($v) => (bool) $v->status)->values();
    ?>

    <?php echo view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]); ?>


    <?php if($variants->isNotEmpty()): ?>
        <div class="mt-5 w-full max-w-[455px] max-sm:w-full">
            <label for="selected_configurable_option" class="mb-2 block text-base font-medium text-zinc-800 max-sm:text-sm">
                <?php echo app('translator')->get('shop::app.products.view.type.configurable.select-options'); ?>
            </label>
            <select
                id="selected_configurable_option"
                name="selected_configurable_option"
                class="custom-select mb-3 block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-zinc-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
            >
                <option value="">
                    <?php echo app('translator')->get('shop::app.products.view.type.configurable.select-options'); ?>
                </option>
                <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($variant->id); ?>">
                        <?php echo e($variant->name ?: $variant->sku); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    <?php endif; ?>

    <?php echo view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]); ?>

<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/products/view/types/configurable-html.blade.php ENDPATH**/ ?>