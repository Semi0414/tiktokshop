
<ul class="<?php echo e(($depth ?? 0) ? 'mt-2 space-y-1 border-l border-zinc-100 pl-4' : 'space-y-1'); ?>">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <a
                href="<?php echo e(route('shop.captcha-gate.index', ['redirect' => $category->url])); ?>"
                class="inline-flex items-center gap-2 py-2 text-base font-medium text-black"
            >
                <?php if($category->logo_url): ?>
                    <img
                        src="<?php echo e($category->logo_url); ?>"
                        alt=""
                        class="h-6 w-6 rounded-full object-cover"
                    >
                <?php else: ?>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-zinc-200 text-[10px] font-semibold text-zinc-600">
                        <?php echo e(mb_strtoupper(mb_substr($category->name, 0, 1))); ?>

                    </span>
                <?php endif; ?>

                <span><?php echo e($category->name); ?></span>
            </a>

            <?php if($category->children && $category->children->count()): ?>
                <?php echo $__env->make('shop::components.layouts.header.partials.mobile-categories-html', [
                    'categories' => $category->children,
                    'depth' => ($depth ?? 0) + 1,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/partials/mobile-categories-html.blade.php ENDPATH**/ ?>