<?php if (isset($component)) { $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.home-page-media.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
        <?php echo $__env->make('superadmin::home-page-media.partials.ml-scoped-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->stopPush(); ?>

    <?php
        $nTotal = $items->count();
        $nImg = $items->where('media_type', 'image')->count();
        $nVid = $items->where('media_type', 'video')->count();
        $nextSort = $items->isEmpty() ? 0 : (int) $items->max('sort_order') + 1;
    ?>

    <div id="ml-app">
        <div class="ml-page">
            <div class="ml-topbar">
                <div class="ml-brand">
                    <div class="ml-brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <div>
                        <div class="ml-brand-name"><?php echo app('translator')->get('superadmin::app.home-page-media.title'); ?></div>
                        <div class="ml-brand-sub"><?php echo app('translator')->get('superadmin::app.home-page-media.description'); ?></div>
                    </div>
                </div>
                <button type="button" class="ml-upload-open" id="hpm-add-open" aria-haspopup="dialog" aria-controls="hpm-add-modal" aria-expanded="false">
                    <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 10V3M5 6l3-3 3 3"/><path d="M2 11v1.5A1.5 1.5 0 003.5 14h9A1.5 1.5 0 0014 12.5V11"/></svg>
                    <?php echo app('translator')->get('superadmin::app.home-page-media.add-title'); ?>
                </button>
            </div>

            <div class="ml-divider"></div>

            <div class="ml-stats">
                <div class="ml-stat s-all">
                    <div class="ml-stat-label"><?php echo app('translator')->get('superadmin::app.home-page-media.saved-title'); ?></div>
                    <div class="ml-stat-val"><?php echo e($nTotal); ?></div>
                    <div class="ml-stat-tag"><?php echo app('translator')->get('superadmin::app.home-page-media.title'); ?></div>
                </div>
                <div class="ml-stat s-photo">
                    <div class="ml-stat-label"><?php echo app('translator')->get('superadmin::app.home-page-media.type-image'); ?></div>
                    <div class="ml-stat-val"><?php echo e($nImg); ?></div>
                </div>
                <div class="ml-stat s-video">
                    <div class="ml-stat-label"><?php echo app('translator')->get('superadmin::app.home-page-media.type-video'); ?></div>
                    <div class="ml-stat-val"><?php echo e($nVid); ?></div>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="ml-flash-ok" role="status"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <p class="ml-card-title" style="padding:0 0.25rem 0.75rem;"><?php echo app('translator')->get('superadmin::app.home-page-media.saved-title'); ?></p>

            <?php if($items->isEmpty()): ?>
                <div class="ml-card hpm-scope">
                    <div class="ml-empty">
                        <div class="ml-empty-icon">
                            <svg viewBox="0 0 24 24"><path d="M5 8V5h14v3M5 16v3h14v-3M3 12h18"/></svg>
                        </div>
                        <div class="ml-empty-title"><?php echo app('translator')->get('superadmin::app.home-page-media.empty'); ?></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="ml-card hpm-scope">
                    <div class="ml-thead">
                        <div class="ml-th"><?php echo app('translator')->get('superadmin::app.home-page-media.preview'); ?></div>
                        <div class="ml-th"><?php echo app('translator')->get('superadmin::app.home-page-media.type'); ?></div>
                        <div class="ml-th"><?php echo app('translator')->get('superadmin::app.home-page-media.status'); ?></div>
                        <div class="ml-th"><?php echo e(__('Size')); ?></div>
                        <div class="ml-th"><?php echo e(__('Added')); ?></div>
                        <div class="ml-th" style="text-align:right"><?php echo app('translator')->get('superadmin::app.home-page-media.actions'); ?></div>
                    </div>

                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $sizeStr = '—';
                            $thumbVmime = 'video/mp4';
                            if ($row->isVideo()) {
                                $ve = strtolower(pathinfo((string) $row->path, PATHINFO_EXTENSION));
                                $thumbVmime = match ($ve) {
                                    'webm' => 'video/webm',
                                    'mov' => 'video/quicktime',
                                    'm4v' => 'video/x-m4v',
                                    'ogv', 'ogg' => 'video/ogg',
                                    default => 'video/mp4',
                                };
                            }
                            try {
                                if ($row->path && \Storage::disk('public')->exists($row->path)) {
                                    $b = \Storage::disk('public')->size($row->path);
                                    $sizeStr = $b < 1024
                                        ? $b . ' B'
                                        : ($b < 1048576
                                            ? round($b / 1024, 1) . ' KB'
                                            : round($b / 1048576, 1) . ' MB');
                                }
                            } catch (\Throwable $e) {
                                $sizeStr = '—';
                            }
                        ?>
                        <div class="ml-row">
                            <div class="ml-file-cell">
                                <div style="width:40px;height:40px;flex-shrink:0;border-radius:7px;overflow:hidden;border:0.5px solid #E2E8F4;background:#EEF2FA;">
                                    <?php if($row->isVideo()): ?>
                                        <video
                                            class="ml-thumb"
                                            style="width:100%;height:100%;object-fit:cover"
                                            muted
                                            playsinline
                                            loop
                                            autoplay
                                            preload="metadata"
                                        >
                                            <source src="<?php echo e($row->getPublicUrl()); ?>" type="<?php echo e($thumbVmime); ?>">
                                        </video>
                                    <?php else: ?>
                                        <img
                                            class="ml-thumb"
                                            style="width:100%;height:100%;object-fit:cover"
                                            src="<?php echo e($row->getPublicUrl()); ?>"
                                            alt=""
                                            loading="lazy"
                                        />
                                    <?php endif; ?>
                                </div>
                                <div style="min-width:0">
                                    <div class="ml-fname" title="<?php echo e($row->original_filename); ?>"><?php echo e($row->original_filename ?: $row->path); ?></div>
                                    <div class="ml-fext">#<?php echo e($row->id); ?></div>
                                </div>
                            </div>
                            <div>
                                <span class="ml-badge <?php echo e($row->isVideo() ? 'badge-video' : 'badge-photo'); ?>">
                                    <?php echo e($row->isVideo() ? __('superadmin::app.home-page-media.type-video') : __('superadmin::app.home-page-media.type-image')); ?>

                                </span>
                            </div>
                            <div>
                                <?php if($row->is_active): ?>
                                    <span class="ml-badge" style="background:#DCFCE7;color:#166534;"><?php echo app('translator')->get('superadmin::app.home-page-media.active'); ?></span>
                                <?php else: ?>
                                    <span class="ml-badge" style="background:var(--ml-surface2);color:var(--ml-text2);"><?php echo app('translator')->get('superadmin::app.home-page-media.inactive'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="ml-size"><?php echo e($sizeStr); ?></div>
                            <div class="ml-date"><?php echo e($row->created_at?->format('Y-m-d H:i')); ?></div>
                            <div class="ml-acts">
                                <a href="<?php echo e(route('superadmin.home_page_media.edit', $row->id)); ?>" class="ml-act"><?php echo app('translator')->get('superadmin::app.home-page-media.edit'); ?></a>
                                <form
                                    method="post"
                                    action="<?php echo e(route('superadmin.home_page_media.destroy', $row->id)); ?>"
                                    class="inline"
                                    style="display:inline"
                                    onsubmit="return confirm('<?php echo app('translator')->get('superadmin::app.home-page-media.delete-confirm'); ?>');"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="ml-act ml-act-danger" style="background:none;border:none;cursor:pointer;padding:0">
                                        <?php echo app('translator')->get('superadmin::app.home-page-media.delete'); ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div
                id="hpm-add-modal"
                class="ml-modal-layer"
                role="dialog"
                aria-modal="true"
                aria-labelledby="hpm-add-modal-title"
                aria-hidden="true"
                data-hpm-modal="add"
            >
                <div class="ml-modal-box" role="document">
                    <div class="ml-modal-hdr">
                        <h2 id="hpm-add-modal-title"><?php echo app('translator')->get('superadmin::app.home-page-media.add-title'); ?></h2>
                        <button type="button" class="ml-modal-x" id="hpm-add-close" aria-label="<?php echo e(__('Close')); ?>">×</button>
                    </div>
                    <div class="ml-modal-body">
                        <form
                            method="post"
                            action="<?php echo e(route('superadmin.home_page_media.store')); ?>"
                            enctype="multipart/form-data"
                        >
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="sort_order" value="<?php echo e(old('sort_order', $nextSort)); ?>" />

                            <label class="ml-dz">
                                <div class="ml-dz-icon">
                                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                </div>
                                <div class="ml-dz-title"><?php echo app('translator')->get('superadmin::app.home-page-media.file'); ?></div>
                                <div class="ml-dz-sub"><?php echo app('translator')->get('superadmin::app.home-page-media.file-hint'); ?></div>
                                <input
                                    id="hpm-add-media"
                                    type="file"
                                    name="media"
                                    class="ml-dz-file"
                                    required
                                    accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime"
                                />
                            </label>

                            <div class="hpm-up-preview" id="hpm-add-up-preview" hidden>
                                <div class="hpm-up-preview-inner" id="hpm-add-up-preview-inner"></div>
                                <p class="hpm-up-preview-name" id="hpm-add-up-preview-name"></p>
                            </div>

                            <div class="ml-field" style="margin-top:1rem;">
                                <label class="ml-label" for="hpm-new-active"><?php echo app('translator')->get('superadmin::app.home-page-media.status'); ?></label>
                                <select name="is_active" id="hpm-new-active">
                                    <option value="1" <?php if(old('is_active', '1') == '1'): echo 'selected'; endif; ?>><?php echo app('translator')->get('superadmin::app.home-page-media.active'); ?></option>
                                    <option value="0" <?php if(old('is_active') === '0'): echo 'selected'; endif; ?>><?php echo app('translator')->get('superadmin::app.home-page-media.inactive'); ?></option>
                                </select>
                                <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="ml-field-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <?php $__errorArgs = ['media'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="ml-field-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="ml-field-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            <div class="ml-modal-foot">
                                <button type="button" class="ml-btn-ghost" id="hpm-add-cancel"><?php echo e(__('Cancel')); ?></button>
                                <button type="submit" class="ml-btn-primary"><?php echo app('translator')->get('superadmin::app.home-page-media.save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
        (function () {
            var modal = document.getElementById('hpm-add-modal');
            var openBtn = document.getElementById('hpm-add-open');
            var closeBtn = document.getElementById('hpm-add-close');
            var cancelBtn = document.getElementById('hpm-add-cancel');
            var fileInput = document.getElementById('hpm-add-media');
            var previewBox = document.getElementById('hpm-add-up-preview');
            var previewInner = document.getElementById('hpm-add-up-preview-inner');
            var previewName = document.getElementById('hpm-add-up-preview-name');
            var previewUrl = null;
            if (!modal || !openBtn) return;

            function clearUploadPreview() {
                if (previewUrl) {
                    try { URL.revokeObjectURL(previewUrl); } catch (e) {}
                    previewUrl = null;
                }
                if (previewInner) previewInner.innerHTML = '';
                if (previewName) previewName.textContent = '';
                if (previewBox) previewBox.hidden = true;
            }

            function show() {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
            }
            function hide() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
                if (fileInput) fileInput.value = '';
                clearUploadPreview();
            }
            function onBackdrop(e) {
                if (e.target === modal) hide();
            }

            openBtn.addEventListener('click', function () { show(); });
            if (closeBtn) closeBtn.addEventListener('click', hide);
            if (cancelBtn) cancelBtn.addEventListener('click', hide);
            modal.addEventListener('click', onBackdrop);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) hide();
            });
            function hpmFileIsVideo(f) {
                if (!f) return false;
                if (f.type && f.type.indexOf('video/') === 0) return true;
                return /\.(mp4|mpe?g|webm|mov|m4v|ogv|ogg)$/i.test(f.name || '');
            }
            if (fileInput && previewInner && previewName && previewBox) {
                fileInput.addEventListener('change', function () {
                    clearUploadPreview();
                    var f = fileInput.files && fileInput.files[0];
                    if (!f) {
                        previewBox.hidden = true;
                        return;
                    }
                    var isVid = hpmFileIsVideo(f);
                    previewUrl = URL.createObjectURL(f);
                    if (isVid) {
                        var v = document.createElement('video');
                        v.setAttribute('playsinline', '');
                        v.setAttribute('webkit-playsinline', '');
                        v.playsInline = true;
                        v.muted = true;
                        v.controls = true;
                        v.preload = 'auto';
                        v.setAttribute('src', previewUrl);
                        v.addEventListener('loadeddata', function onLoaded() {
                            v.removeEventListener('loadeddata', onLoaded);
                            var p = v.play();
                            if (p && typeof p.then === 'function') {
                                p.catch(function () { /* use controls to play */ });
                            }
                        });
                        previewInner.appendChild(v);
                        v.load();
                    } else {
                        var im = document.createElement('img');
                        im.setAttribute('src', previewUrl);
                        im.setAttribute('alt', '');
                        previewInner.appendChild(im);
                    }
                    previewName.textContent = f.name;
                    previewBox.hidden = false;
                });
            }

            <?php if($errors->any()): ?>
            show();
            <?php endif; ?>
        })();
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/home-page-media/index.blade.php ENDPATH**/ ?>