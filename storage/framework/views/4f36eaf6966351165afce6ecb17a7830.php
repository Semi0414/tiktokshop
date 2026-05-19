<?php echo view_render_event('bagisto.admin.catalog.product.edit.form.videos.before', ['product' => $product]); ?>


<div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900">
    <!-- Panel Header -->
    <div class="mb-4 flex justify-between gap-5">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('superadmin::app.catalog.products.edit.videos.title'); ?>
            </p>

            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                <?php echo app('translator')->get('superadmin::app.catalog.products.edit.videos.info', ['size' => core()->getMaxUploadSize()]); ?>
            </p>
        </div>
    </div>

    <?php
        $uploadedVideos = $product->videos
            ->map(fn ($video) => [
                'id' => $video->id,
                'url' => $video->url,
            ])
            ->values()
            ->toArray();
    ?>

    <?php if(! empty($uploadedVideos)): ?>
        <div class="mb-3">
            <p class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300">
                Existing Videos (Server Rendered)
            </p>

            <div class="grid gap-2">
                <?php $__currentLoopData = $uploadedVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingVideo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded border border-gray-200 p-2 dark:border-gray-800">
                        <input
                            type="hidden"
                            name="videos[files][<?php echo e($existingVideo['id']); ?>]"
                            value="1"
                            id="keep-video-<?php echo e($existingVideo['id']); ?>"
                        >

                        <a
                            href="<?php echo e($existingVideo['url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-xs text-blue-600 underline dark:text-blue-300"
                        >
                            <?php echo e($existingVideo['url']); ?>

                        </a>

                        <div class="mt-2 grid gap-1">
                            <label class="text-[11px] text-gray-600 dark:text-gray-300">
                                Replace
                                <input
                                    type="file"
                                    name="videos[files][<?php echo e($existingVideo['id']); ?>]"
                                    accept="video/*"
                                    class="mt-1 block w-full text-[11px]"
                                >
                            </label>

                            <label class="inline-flex items-center gap-1 text-[11px] text-red-600">
                                <input
                                    type="checkbox"
                                    onchange="document.getElementById('keep-video-<?php echo e($existingVideo['id']); ?>').disabled = this.checked"
                                >
                                Remove
                            </label>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">
            Upload New Videos
        </label>

        <input
            type="file"
            id="manual-new-videos-input"
            name="videos[files][]"
            accept="video/*"
            multiple
            class="hidden"
        >

        <label
            for="manual-new-videos-input"
            id="manual-new-videos-dropzone"
            class="mt-1 grid h-[120px] w-[120px] cursor-pointer items-center justify-items-center rounded border border-dashed border-gray-300 text-center text-[11px] font-semibold text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300"
        >
            <span class="icon-play text-4xl"></span>
        </label>

        <p id="manual-new-videos-files" class="mt-1 text-[11px] text-gray-500 dark:text-gray-400"></p>
    </div>

    <?php if (isset($component)) { $__componentOriginal7d00c14826cd26beafba9f36875ab882 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d00c14826cd26beafba9f36875ab882 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'videos.files[0]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'videos.files[0]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $attributes = $__attributesOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__attributesOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $component = $__componentOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__componentOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
</div>

<?php echo view_render_event('bagisto.admin.catalog.product.edit.form.videos.after', ['product' => $product]); ?>


<?php if (! $__env->hasRenderedOnce('ea8dfd39-5745-46a4-9564-cbf01eb84dd6')): $__env->markAsRenderedOnce('ea8dfd39-5745-46a4-9564-cbf01eb84dd6');
$__env->startPush('scripts'); ?>
    <script type="module">
        (() => {
            const input = document.getElementById('manual-new-videos-input');
            const dropzone = document.getElementById('manual-new-videos-dropzone');
            const fileInfo = document.getElementById('manual-new-videos-files');

            if (! input || ! dropzone) {
                return;
            }

            const updateFileInfo = (files) => {
                if (! fileInfo) {
                    return;
                }

                if (! files || ! files.length) {
                    fileInfo.textContent = '';
                    return;
                }

                fileInfo.textContent = `${files.length} video(s) selected`;
            };

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.add('border-blue-500');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.remove('border-blue-500');
                });
            });

            dropzone.addEventListener('drop', (event) => {
                const dt = event.dataTransfer;

                if (! dt || ! dt.files || ! dt.files.length) {
                    return;
                }

                input.files = dt.files;
                updateFileInfo(dt.files);
            });

            input.addEventListener('change', () => updateFileInfo(input.files));
        })();
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/catalog/products/edit/videos.blade.php ENDPATH**/ ?>