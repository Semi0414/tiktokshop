<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.home-page-media.title')
    </x-slot>

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
        @include('superadmin::home-page-media.partials.ml-scoped-styles')
    @endpush

    @php
        $nTotal = $items->count();
        $nImg = $items->where('media_type', 'image')->count();
        $nVid = $items->where('media_type', 'video')->count();
        $nextSort = $items->isEmpty() ? 0 : (int) $items->max('sort_order') + 1;
    @endphp

    <div id="ml-app">
        <div class="ml-page">
            <div class="ml-topbar">
                <div class="ml-brand">
                    <div class="ml-brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <div>
                        <div class="ml-brand-name">@lang('superadmin::app.home-page-media.title')</div>
                        <div class="ml-brand-sub">@lang('superadmin::app.home-page-media.description')</div>
                    </div>
                </div>
                <button type="button" class="ml-upload-open" id="hpm-add-open" aria-haspopup="dialog" aria-controls="hpm-add-modal" aria-expanded="false">
                    <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 10V3M5 6l3-3 3 3"/><path d="M2 11v1.5A1.5 1.5 0 003.5 14h9A1.5 1.5 0 0014 12.5V11"/></svg>
                    @lang('superadmin::app.home-page-media.add-title')
                </button>
            </div>

            <div class="ml-divider"></div>

            <div class="ml-stats">
                <div class="ml-stat s-all">
                    <div class="ml-stat-label">@lang('superadmin::app.home-page-media.saved-title')</div>
                    <div class="ml-stat-val">{{ $nTotal }}</div>
                    <div class="ml-stat-tag">@lang('superadmin::app.home-page-media.title')</div>
                </div>
                <div class="ml-stat s-photo">
                    <div class="ml-stat-label">@lang('superadmin::app.home-page-media.type-image')</div>
                    <div class="ml-stat-val">{{ $nImg }}</div>
                </div>
                <div class="ml-stat s-video">
                    <div class="ml-stat-label">@lang('superadmin::app.home-page-media.type-video')</div>
                    <div class="ml-stat-val">{{ $nVid }}</div>
                </div>
            </div>

            @if (session('success'))
                <div class="ml-flash-ok" role="status">{{ session('success') }}</div>
            @endif

            <p class="ml-card-title" style="padding:0 0.25rem 0.75rem;">@lang('superadmin::app.home-page-media.saved-title')</p>

            @if ($items->isEmpty())
                <div class="ml-card hpm-scope">
                    <div class="ml-empty">
                        <div class="ml-empty-icon">
                            <svg viewBox="0 0 24 24"><path d="M5 8V5h14v3M5 16v3h14v-3M3 12h18"/></svg>
                        </div>
                        <div class="ml-empty-title">@lang('superadmin::app.home-page-media.empty')</div>
                    </div>
                </div>
            @else
                <div class="ml-card hpm-scope">
                    <div class="ml-thead">
                        <div class="ml-th">@lang('superadmin::app.home-page-media.preview')</div>
                        <div class="ml-th">@lang('superadmin::app.home-page-media.type')</div>
                        <div class="ml-th">@lang('superadmin::app.home-page-media.status')</div>
                        <div class="ml-th">{{ __('Size') }}</div>
                        <div class="ml-th">{{ __('Added') }}</div>
                        <div class="ml-th" style="text-align:right">@lang('superadmin::app.home-page-media.actions')</div>
                    </div>

                    @foreach ($items as $row)
                        @php
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
                        @endphp
                        <div class="ml-row">
                            <div class="ml-file-cell">
                                <div style="width:40px;height:40px;flex-shrink:0;border-radius:7px;overflow:hidden;border:0.5px solid #E2E8F4;background:#EEF2FA;">
                                    @if ($row->isVideo())
                                        <video
                                            class="ml-thumb"
                                            style="width:100%;height:100%;object-fit:cover"
                                            muted
                                            playsinline
                                            loop
                                            autoplay
                                            preload="metadata"
                                        >
                                            <source src="{{ $row->getPublicUrl() }}" type="{{ $thumbVmime }}">
                                        </video>
                                    @else
                                        <img
                                            class="ml-thumb"
                                            style="width:100%;height:100%;object-fit:cover"
                                            src="{{ $row->getPublicUrl() }}"
                                            alt=""
                                            loading="lazy"
                                        />
                                    @endif
                                </div>
                                <div style="min-width:0">
                                    <div class="ml-fname" title="{{ $row->original_filename }}">{{ $row->original_filename ?: $row->path }}</div>
                                    <div class="ml-fext">#{{ $row->id }}</div>
                                </div>
                            </div>
                            <div>
                                <span class="ml-badge {{ $row->isVideo() ? 'badge-video' : 'badge-photo' }}">
                                    {{ $row->isVideo() ? __('superadmin::app.home-page-media.type-video') : __('superadmin::app.home-page-media.type-image') }}
                                </span>
                            </div>
                            <div>
                                @if ($row->is_active)
                                    <span class="ml-badge" style="background:#DCFCE7;color:#166534;">@lang('superadmin::app.home-page-media.active')</span>
                                @else
                                    <span class="ml-badge" style="background:var(--ml-surface2);color:var(--ml-text2);">@lang('superadmin::app.home-page-media.inactive')</span>
                                @endif
                            </div>
                            <div class="ml-size">{{ $sizeStr }}</div>
                            <div class="ml-date">{{ $row->created_at?->format('Y-m-d H:i') }}</div>
                            <div class="ml-acts">
                                <a href="{{ route('superadmin.home_page_media.edit', $row->id) }}" class="ml-act">@lang('superadmin::app.home-page-media.edit')</a>
                                <form
                                    method="post"
                                    action="{{ route('superadmin.home_page_media.destroy', $row->id) }}"
                                    class="inline"
                                    style="display:inline"
                                    onsubmit="return confirm('@lang('superadmin::app.home-page-media.delete-confirm')');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-act ml-act-danger" style="background:none;border:none;cursor:pointer;padding:0">
                                        @lang('superadmin::app.home-page-media.delete')
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

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
                        <h2 id="hpm-add-modal-title">@lang('superadmin::app.home-page-media.add-title')</h2>
                        <button type="button" class="ml-modal-x" id="hpm-add-close" aria-label="{{ __('Close') }}">×</button>
                    </div>
                    <div class="ml-modal-body">
                        <form
                            method="post"
                            action="{{ route('superadmin.home_page_media.store') }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <input type="hidden" name="sort_order" value="{{ old('sort_order', $nextSort) }}" />

                            <label class="ml-dz">
                                <div class="ml-dz-icon">
                                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                </div>
                                <div class="ml-dz-title">@lang('superadmin::app.home-page-media.file')</div>
                                <div class="ml-dz-sub">@lang('superadmin::app.home-page-media.file-hint')</div>
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
                                <label class="ml-label" for="hpm-new-active">@lang('superadmin::app.home-page-media.status')</label>
                                <select name="is_active" id="hpm-new-active">
                                    <option value="1" @selected(old('is_active', '1') == '1')>@lang('superadmin::app.home-page-media.active')</option>
                                    <option value="0" @selected(old('is_active') === '0')>@lang('superadmin::app.home-page-media.inactive')</option>
                                </select>
                                @error('is_active')
                                    <p class="ml-field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('media')
                                <p class="ml-field-error">{{ $message }}</p>
                            @enderror
                            @error('sort_order')
                                <p class="ml-field-error">{{ $message }}</p>
                            @enderror

                            <div class="ml-modal-foot">
                                <button type="button" class="ml-btn-ghost" id="hpm-add-cancel">{{ __('Cancel') }}</button>
                                <button type="submit" class="ml-btn-primary">@lang('superadmin::app.home-page-media.save')</button>
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

            @if ($errors->any())
            show();
            @endif
        })();
        </script>
    @endpush
</x-superadmin::layouts>
