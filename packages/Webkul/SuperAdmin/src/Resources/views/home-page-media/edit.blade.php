<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.home-page-media.edit-title')
    </x-slot>

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
        @include('superadmin::home-page-media.partials.ml-scoped-styles')
    @endpush

    <div id="ml-app">
        <div class="ml-page">
            <div class="ml-topbar">
                <div class="ml-brand">
                    <div class="ml-brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <div>
                        <div class="ml-brand-name">@lang('superadmin::app.home-page-media.edit-title')</div>
                        <div class="ml-brand-sub">@lang('superadmin::app.home-page-media.replace-hint')</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('superadmin.home_page_media.index') }}" class="ml-back">@lang('superadmin::app.home-page-media.back')</a>

            <div class="ml-divider"></div>

            @if (session('success'))
                <div class="ml-flash-ok" role="status">{{ session('success') }}</div>
            @endif

            @php
                $hpmVideoMime = 'video/mp4';
                if ($item->isVideo()) {
                    $hpmVext = strtolower(pathinfo((string) $item->path, PATHINFO_EXTENSION));
                    $hpmVideoMime = match ($hpmVext) {
                        'webm' => 'video/webm',
                        'mov' => 'video/quicktime',
                        'm4v' => 'video/x-m4v',
                        'ogv', 'ogg' => 'video/ogg',
                        default => 'video/mp4',
                    };
                }
            @endphp
            <div class="ml-card">
                <div class="ml-card-pad" style="text-align:center; max-width: 22rem; margin: 0 auto;">
                    <p class="ml-card-title" style="margin-bottom:0.75rem;">@lang('superadmin::app.home-page-media.preview')</p>
                    <div class="hpm-edit-page-preview" style="width:7rem; height:10rem; margin:0 auto; border-radius:10px; overflow:hidden; border:0.5px solid var(--ml-border); background:var(--ml-surface2);">
                        @if ($item->isVideo())
                            <video
                                class="hpm-edit-page-preview-vid"
                                style="width:100%; height:100%; object-fit:cover; display:block;"
                                controls
                                playsinline
                                preload="auto"
                            >
                                <source src="{{ $item->getPublicUrl() }}" type="{{ $hpmVideoMime }}">
                            </video>
                        @else
                            <img
                                src="{{ $item->getPublicUrl() }}"
                                alt=""
                                style="width:100%; height:100%; object-fit:cover; display:block;"
                            />
                        @endif
                    </div>
                    <div class="ml-edit-cta">
                        <button type="button" class="ml-upload-open" id="hpm-edit-open" aria-haspopup="dialog" aria-controls="hpm-edit-modal" aria-expanded="false">
                            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 10V3M5 6l3-3 3 3"/><path d="M2 11v1.5A1.5 1.5 0 003.5 14h9A1.5 1.5 0 0014 12.5V11"/></svg>
                            @lang('superadmin::app.home-page-media.replace-file')
                        </button>
                    </div>
                </div>
            </div>

            <div
                id="hpm-edit-modal"
                class="ml-modal-layer"
                role="dialog"
                aria-modal="true"
                aria-labelledby="hpm-edit-modal-title"
                aria-hidden="true"
            >
                <div class="ml-modal-box" role="document">
                    <div class="ml-modal-hdr">
                        <h2 id="hpm-edit-modal-title">@lang('superadmin::app.home-page-media.update')</h2>
                        <button type="button" class="ml-modal-x" id="hpm-edit-close" aria-label="{{ __('Close') }}">×</button>
                    </div>
                    <div class="ml-modal-body">
                        <form
                            method="post"
                            action="{{ route('superadmin.home_page_media.update', $item->id) }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" />

                            <p class="ml-card-title" style="font-size:13px; margin-bottom:0.75rem;">@lang('superadmin::app.home-page-media.replace-file')</p>

                            <label class="ml-dz">
                                <div class="ml-dz-icon">
                                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                </div>
                                <div class="ml-dz-title">@lang('superadmin::app.home-page-media.file')</div>
                                <div class="ml-dz-sub">@lang('superadmin::app.home-page-media.replace-hint')</div>
                                <input
                                    id="hpm-edit-media"
                                    type="file"
                                    name="media"
                                    class="ml-dz-file"
                                    accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime"
                                />
                            </label>
                            <div class="hpm-up-preview" id="hpm-edit-up-preview" hidden>
                                <div class="hpm-up-preview-inner" id="hpm-edit-up-preview-inner"></div>
                                <p class="hpm-up-preview-name" id="hpm-edit-up-preview-name"></p>
                            </div>
                            @error('media')
                                <p class="ml-field-error">{{ $message }}</p>
                            @enderror
                            @error('sort_order')
                                <p class="ml-field-error">{{ $message }}</p>
                            @enderror

                            <div class="ml-field" style="margin-top:1rem;">
                                <label class="ml-label" for="hpm-edit-active">@lang('superadmin::app.home-page-media.status')</label>
                                <select name="is_active" id="hpm-edit-active">
                                    <option value="1" @selected(old('is_active', $item->is_active ? '1' : '0') == '1')>@lang('superadmin::app.home-page-media.active')</option>
                                    <option value="0" @selected(old('is_active', $item->is_active ? '1' : '0') === '0')>@lang('superadmin::app.home-page-media.inactive')</option>
                                </select>
                                @error('is_active')
                                    <p class="ml-field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="ml-modal-foot">
                                <button type="button" class="ml-btn-ghost" id="hpm-edit-cancel">{{ __('Cancel') }}</button>
                                <button type="submit" class="ml-btn-primary">@lang('superadmin::app.home-page-media.update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        (function () {
            var modal = document.getElementById('hpm-edit-modal');
            var openBtn = document.getElementById('hpm-edit-open');
            var closeBtn = document.getElementById('hpm-edit-close');
            var cancelBtn = document.getElementById('hpm-edit-cancel');
            var fileInput = document.getElementById('hpm-edit-media');
            var previewBox = document.getElementById('hpm-edit-up-preview');
            var previewInner = document.getElementById('hpm-edit-up-preview-inner');
            var previewName = document.getElementById('hpm-edit-up-preview-name');
            var previewUrl = null;
            if (!modal || !openBtn) return;

            function hpmFileIsVideo(f) {
                if (!f) return false;
                if (f.type && f.type.indexOf('video/') === 0) return true;
                return /\.(mp4|mpe?g|webm|mov|m4v|ogv|ogg)$/i.test(f.name || '');
            }
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
            function onBackdrop(e) { if (e.target === modal) hide(); }
            openBtn.addEventListener('click', function () { show(); });
            if (closeBtn) closeBtn.addEventListener('click', hide);
            if (cancelBtn) cancelBtn.addEventListener('click', hide);
            modal.addEventListener('click', onBackdrop);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) hide();
            });
            if (fileInput && previewInner && previewName && previewBox) {
                fileInput.addEventListener('change', function () {
                    clearUploadPreview();
                    var f = fileInput.files && fileInput.files[0];
                    if (!f) {
                        previewBox.hidden = true;
                        return;
                    }
                    if (hpmFileIsVideo(f)) {
                        previewUrl = URL.createObjectURL(f);
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
                                p.catch(function () { });
                            }
                        });
                        previewInner.appendChild(v);
                        v.load();
                    } else {
                        previewUrl = URL.createObjectURL(f);
                        var im = document.createElement('img');
                        im.setAttribute('src', previewUrl);
                        im.setAttribute('alt', '');
                        previewInner.appendChild(im);
                    }
                    previewName.textContent = f.name;
                    previewBox.hidden = false;
                });
            }
            @if ($errors->any())
            show();
            @endif
        })();
        </script>
    @endpush
</x-superadmin::layouts>
