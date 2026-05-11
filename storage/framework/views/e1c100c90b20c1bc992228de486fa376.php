
<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="group relative flex h-[77px] items-center border-b-4 border-transparent hover:border-b-4 hover:border-navyBlue">
        <span>
            <a
                href="<?php echo e(route('shop.captcha-gate.index', ['redirect' => $category->url])); ?>"
                class="inline-flex items-center gap-2 px-5 uppercase"
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
        </span>

        <?php if($category->children && $category->children->count()): ?>
            <div
                class="pointer-events-none absolute top-[78px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in ltr:-left-9 rtl:-right-9"
            >
                <div class="flex justify-between gap-x-[70px]">
                    <?php $__currentLoopData = $category->children->chunk(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5">
                            <?php $__currentLoopData = $pair; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $secondLevelCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="font-medium text-navyBlue">
                                    <a
                                        href="<?php echo e(route('shop.captcha-gate.index', ['redirect' => $secondLevelCategory->url])); ?>"
                                        class="inline-flex items-center gap-2"
                                    >
                                        <?php if($secondLevelCategory->logo_url): ?>
                                            <img
                                                src="<?php echo e($secondLevelCategory->logo_url); ?>"
                                                alt=""
                                                class="h-5 w-5 rounded-full object-cover"
                                            >
                                        <?php else: ?>
                                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-zinc-200 text-[10px] font-semibold text-zinc-600">
                                                <?php echo e(mb_strtoupper(mb_substr($secondLevelCategory->name, 0, 1))); ?>

                                            </span>
                                        <?php endif; ?>

                                        <span><?php echo e($secondLevelCategory->name); ?></span>
                                    </a>
                                </p>

                                <?php if($secondLevelCategory->children && $secondLevelCategory->children->count()): ?>
                                    <ul class="grid grid-cols-[1fr] gap-3">
                                        <?php $__currentLoopData = $secondLevelCategory->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thirdLevelCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="text-sm font-medium text-zinc-500">
                                                <a
                                                    href="<?php echo e(route('shop.captcha-gate.index', ['redirect' => $thirdLevelCategory->url])); ?>"
                                                    class="inline-flex items-center gap-2"
                                                >
                                                    <?php if($thirdLevelCategory->logo_url): ?>
                                                        <img
                                                            src="<?php echo e($thirdLevelCategory->logo_url); ?>"
                                                            alt=""
                                                            class="h-5 w-5 rounded-full object-cover"
                                                        >
                                                    <?php else: ?>
                                                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-zinc-200 text-[10px] font-semibold text-zinc-600">
                                                            <?php echo e(mb_strtoupper(mb_substr($thirdLevelCategory->name, 0, 1))); ?>

                                                        </span>
                                                    <?php endif; ?>

                                                    <span><?php echo e($thirdLevelCategory->name); ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/header/partials/desktop-categories-html.blade.php ENDPATH**/ ?>