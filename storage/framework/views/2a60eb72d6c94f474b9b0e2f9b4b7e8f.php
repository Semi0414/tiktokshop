<?php
    $resolvedValue = $attributeValuesByCode[$attribute->code] ?? $product[$attribute->code];
?>

<?php switch($attribute->type):
    case ('text'): ?>
        <input
            type="text"
            id="<?php echo e($attribute->code); ?>"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
            name="<?php echo e($attribute->code); ?>"
            value="<?php echo e(old($attribute->code) ?: $resolvedValue); ?>"
            <?php if($attribute->code == 'url_key'): ?> v-slugify <?php endif; ?>
            <?php if($attribute->code == 'name'): ?> v-slugify-target:url_key="setValues" <?php endif; ?>
        >

        <?php break; ?>
    <?php case ('price'): ?>
        <input
            type="text"
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>"
            value="<?php echo e(old($attribute->code) ?: $resolvedValue); ?>"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        <?php break; ?>
    <?php case ('textarea'): ?>
        <textarea
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
            rows="4"
        ><?php echo e(old($attribute->code) ?: $resolvedValue); ?></textarea>

        <?php break; ?>
    <?php case ('date'): ?>
        <input
            type="date"
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>"
            value="<?php echo e(old($attribute->code) ?: $resolvedValue); ?>"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        <?php break; ?>
    <?php case ('datetime'): ?>
        <input
            type="datetime-local"
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>"
            value="<?php echo e(old($attribute->code) ?: $resolvedValue); ?>"
            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >

        <?php break; ?>
    <?php case ('select'): ?>
        <?php
            $selectedOption = old($attribute->code) ?: $resolvedValue;

            if ($attribute->code != 'tax_category_id') {
                $options = $attribute->options()->orderBy('sort_order')->get();
            } else {
                $options = app('Webkul\Tax\Repositories\TaxCategoryRepository')->all();
            }
        ?>

        <select
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>"
            class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    value="<?php echo e($option->id); ?>"
                    <?php echo e((
                            (string) $selectedOption === (string) $option->id
                            || (string) $selectedOption === (string) ($option->admin_name ?? $option->name)
                        ) ? 'selected' : ''); ?>

                    v-pre
                >
                    <?php echo e($option->admin_name ?? $option->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php if($attribute->code === 'tax_category_id'): ?>
            <?php
                $selectedCategoryIds = old('categories') ?? $product->categories->pluck('id')->toArray();
                $allCategories = app('Webkul\Category\Repositories\CategoryRepository')->all();
            ?>

            <div class="mt-3">
                <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                    Categories
                </label>

                <select
                    name="categories[]"
                    class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                >
                    <option value="">-- Select Category --</option>
                    <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($category->id); ?>"
                            <?php echo e(in_array($category->id, $selectedCategoryIds) ? 'selected' : ''); ?>

                        >
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>

        <?php break; ?>
    <?php case ('multiselect'): ?>
        <?php
            $selectedOption = old($attribute->code) ?: explode(',', (string) $resolvedValue);
        ?>

        <select
            id="<?php echo e($attribute->code); ?>"
            name="<?php echo e($attribute->code); ?>[]"
            multiple
            class="w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
        >
            <?php $__currentLoopData = $attribute->options()->orderBy('sort_order')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    value="<?php echo e($option->id); ?>"
                    <?php echo e((
                            in_array((string) $option->id, array_map('strval', $selectedOption), true)
                            || in_array((string) $option->admin_name, array_map('strval', $selectedOption), true)
                        ) ? 'selected' : ''); ?>

                    v-pre
                >
                    <?php echo e($option->admin_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php break; ?>
    <?php case ('checkbox'): ?>
        <?php
            $selectedOption = old($attribute->code) ?: explode(',', (string) $resolvedValue);
        ?>

        <?php $__currentLoopData = $attribute->options()->orderBy('sort_order')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-2 flex items-center gap-2.5 last:!mb-0">
                <input
                    type="checkbox"
                    id="<?php echo e($attribute->code . '_' . $option->id); ?>"
                    name="<?php echo e($attribute->code); ?>[]"
                    value="<?php echo e($option->id); ?>"
                    <?php echo e((
                            in_array((string) $option->id, array_map('strval', $selectedOption), true)
                            || in_array((string) $option->admin_name, array_map('strval', $selectedOption), true)
                        ) ? 'checked' : ''); ?>

                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >

                <label
                    class="cursor-pointer select-none text-xs font-medium text-gray-600 dark:text-gray-300"
                    for="<?php echo e($attribute->code . '_' . $option->id); ?>"
                    v-pre
                >
                    <?php echo e($option->admin_name); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php break; ?>
    <?php case ('boolean'): ?>
        <?php $selectedValue = old($attribute->code) ?: $resolvedValue ?>

        <input
            type="hidden"
            name="<?php echo e($attribute->code); ?>"
            value="0"
        >

        <label class="inline-flex cursor-pointer items-center gap-2">
            <input
                type="checkbox"
                id="<?php echo e($attribute->code); ?>"
                name="<?php echo e($attribute->code); ?>"
                value="1"
                <?php echo e((boolean) $selectedValue ? 'checked' : ''); ?>

                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
        </label>

        <?php break; ?>
    <?php case ('image'): ?>
    <?php case ('file'): ?>
        <div class="flex gap-2.5">
            <?php if($resolvedValue): ?>
                <a
                    href="<?php echo e(route('superadmin.catalog.products.file.download', [$product->id, $attribute->id] )); ?>"
                    class="flex"
                >
                    <?php if($attribute->type == 'image'): ?>
                        <?php if(Storage::exists($resolvedValue)): ?>
                            <img
                                src="<?php echo e(Storage::url($resolvedValue)); ?>"
                                class="h-[45px] w-[45px] overflow-hidden rounded border hover:border-gray-400 dark:border-gray-800"
                            />
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                            <i class="icon-down-stat text-2xl"></i>
                        </div>
                    <?php endif; ?>
                </a>

                <input
                    type="hidden"
                    name="<?php echo e($attribute->code); ?>"
                    value="<?php echo e($resolvedValue); ?>"
                />
            <?php endif; ?>

            <v-field
                type="file"
                class="w-full"
                name="<?php echo e($attribute->code); ?>"
                :rules="<?php echo e($attribute->validations); ?>"
                v-slot="{ handleChange, handleBlur }"
                label="<?php echo e($attribute->admin_name); ?>"
            >
                <input
                    type="file"
                    id="<?php echo e($attribute->code); ?>"
                    :class="[errors['<?php echo e($attribute->code); ?>'] ? 'border border-red-600 hover:border-red-600' : '']"
                    class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:file:bg-gray-800 dark:file:dark:text-white dark:hover:border-gray-400 dark:focus:border-gray-400"
                    name="<?php echo e($attribute->code); ?>"
                    @change="handleChange"
                    @blur="handleBlur"
                >
            </v-field>
        </div>

        <?php if($resolvedValue): ?>
            <div class="mt-2.5 flex items-center gap-2.5">
                <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'checkbox','id' => $attribute->code . '_delete','name' => $attribute->code . '[delete]','value' => '1','for' => $attribute->code . '_delete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'checkbox','id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->code . '_delete'),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->code . '[delete]'),'value' => '1','for' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->code . '_delete')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

                <label
                    for="<?php echo e($attribute->code . '_delete'); ?>"
                    class="cursor-pointer select-none text-sm text-gray-600 dark:text-gray-300"
                >
                    <?php echo app('translator')->get('superadmin::app.catalog.products.edit.remove'); ?>
                </label>
            </div>
        <?php endif; ?>

        <?php break; ?>
<?php endswitch; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/catalog/products/edit/controls.blade.php ENDPATH**/ ?>