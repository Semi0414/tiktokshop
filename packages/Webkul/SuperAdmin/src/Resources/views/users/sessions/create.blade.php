<x-superadmin::layouts.anonymous>
    <x-slot:title>
        @lang('superadmin::app.users.sessions.title')
    </x-slot>

    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            body:has(.sa-page)::before { opacity: 0 !important; }
            body:has(.sa-page) { background: #0a0a0a !important; }
            body:has(.sa-page) #app { min-height: 100vh; }
            :root {
                --sa-bg: #0a0a0a;
                --sa-card: #161616;
                --sa-border: rgba(255,255,255,0.08);
                --sa-border-hover: rgba(255,200,50,0.35);
                --sa-accent: #f5c518;
                --sa-text: #efefef;
                --sa-muted: #666;
                --sa-input-bg: #0d0d0d;
                --sa-red: #e55;
                --sa-radius: 6px;
                --sa-transition: 0.18s ease;
            }
            .sa-scanlines {
                position: fixed;
                inset: 0;
                background: repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(0,0,0,0.07) 3px, rgba(0,0,0,0.07) 4px);
                pointer-events: none;
                z-index: 0;
                opacity: 0.6;
            }
            .sa-page-inner {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                font-family: 'Inter', sans-serif;
                color: var(--sa-text);
                background: var(--sa-bg);
                overflow-x: hidden;
            }
            .sa-page-inner::after {
                content: '';
                position: fixed;
                inset: 0;
                background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(245,197,24,0.06) 0%, transparent 65%);
                pointer-events: none;
                z-index: 0;
            }
            .sa-wrapper { position: relative; z-index: 1; width: 100%; max-width: 420px; }
            .sa-header { margin-bottom: 28px; }
            .sa-sys-label {
                font-family: 'Space Mono', monospace;
                font-size: 10px;
                color: var(--sa-accent);
                letter-spacing: 3px;
                text-transform: uppercase;
                margin-bottom: 8px;
                opacity: 0.8;
            }
            .sa-title {
                font-family: 'Space Mono', monospace;
                font-size: 24px;
                font-weight: 700;
                color: var(--sa-text);
                letter-spacing: -0.5px;
                line-height: 1.2;
            }
            .sa-title span { color: var(--sa-accent); }
            .sa-warning {
                display: flex;
                align-items: center;
                gap: 10px;
                background: rgba(229,85,85,0.08);
                border: 1px solid rgba(229,85,85,0.2);
                border-left: 3px solid var(--sa-red);
                border-radius: var(--sa-radius);
                padding: 10px 14px;
                margin-top: 14px;
                font-size: 12px;
                color: #e88;
            }
            .sa-card {
                background: var(--sa-card);
                border: 1px solid var(--sa-border);
                border-radius: var(--sa-radius);
                padding: 28px 26px;
            }
            .sa-tabs {
                display: flex;
                border-bottom: 1px solid var(--sa-border);
                margin-bottom: 24px;
            }
            .sa-tab {
                flex: 1;
                padding: 10px 0;
                font-family: 'Space Mono', monospace;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                background: transparent;
                border: none;
                color: var(--sa-muted);
                cursor: pointer;
                border-bottom: 2px solid transparent;
                margin-bottom: -1px;
                transition: var(--sa-transition);
            }
            .sa-tab:hover { color: var(--sa-text); }
            .sa-tab.active { color: var(--sa-accent); border-bottom-color: var(--sa-accent); }
            .sa-field { margin-bottom: 16px; }
            .sa-label {
                display: flex;
                align-items: center;
                gap: 6px;
                font-family: 'Space Mono', monospace;
                font-size: 10px;
                font-weight: 700;
                color: var(--sa-muted);
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-bottom: 8px;
            }
            .sa-label-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--sa-accent); }
            .sa-input-wrap { position: relative; }
            .sa-input-icon {
                position: absolute; left: 12px; top: 50%;
                transform: translateY(-50%);
                color: var(--sa-muted);
                pointer-events: none;
                display: flex;
            }
            .sa-input-icon svg { width: 15px; height: 15px; }
            .sa-input {
                width: 100%;
                padding: 11px 12px 11px 38px;
                background: var(--sa-input-bg);
                border: 1px solid var(--sa-border);
                border-radius: var(--sa-radius);
                color: var(--sa-text);
                font-family: 'Space Mono', monospace;
                font-size: 13px;
                outline: none;
                transition: var(--sa-transition);
            }
            .sa-input::placeholder { color: #333; }
            .sa-input:focus {
                border-color: var(--sa-border-hover);
                background: #0f0f0f;
                box-shadow: 0 0 0 3px rgba(245,197,24,0.06);
            }
            .sa-input.sa-input-error { border-color: var(--sa-red); }
            .sa-pw-toggle {
                position: absolute; right: 10px; top: 50%;
                transform: translateY(-50%);
                background: none; border: none;
                color: var(--sa-muted);
                cursor: pointer;
                padding: 4px;
                display: flex;
                transition: var(--sa-transition);
            }
            .sa-pw-toggle:hover { color: var(--sa-accent); }
            .sa-forgot-row { display: flex; justify-content: flex-end; margin-top: -6px; margin-bottom: 20px; }
            .sa-link { font-size: 12px; color: var(--sa-accent); text-decoration: none; font-family: 'Space Mono', monospace; }
            .sa-link:hover { text-decoration: underline; }
            .sa-btn {
                width: 100%;
                padding: 12px;
                background: var(--sa-accent);
                border: none;
                border-radius: var(--sa-radius);
                color: #000;
                font-family: 'Space Mono', monospace;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 2px;
                text-transform: uppercase;
                cursor: pointer;
                transition: var(--sa-transition);
            }
            .sa-btn:hover { background: #ffd22b; }
            .sa-btn:active { transform: scale(0.99); }
            .sa-footer {
                margin-top: 20px;
                text-align: center;
                font-family: 'Space Mono', monospace;
                font-size: 10px;
                color: #333;
                letter-spacing: 1px;
            }
            .sa-panel { display: none; }
            .sa-panel.active { display: block; }
            .sa-flash { font-size: 12px; color: #e88; margin-bottom: 14px; font-family: 'Space Mono', monospace; }
            .sa-hint { font-size: 11px; color: var(--sa-muted); margin-top: 8px; line-height: 1.5; font-family: 'Inter', sans-serif; }
        </style>
    @endpush

    <div class="sa-page">
        <div class="sa-page-inner">
            <div class="sa-scanlines"></div>
            <div class="sa-wrapper">
                <div class="sa-header">
                    <div class="sa-sys-label">// Restricted Access</div>
                    <div class="sa-title">Super<span>_</span>Admin<br>Login</div>
                    <div class="sa-warning">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#e55" aria-hidden="true"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                        {{ __('Authorized personnel only. All access is monitored.') }}
                    </div>
                </div>

                <div class="sa-card">
                    <form method="POST" action="{{ route('superadmin.session.store') }}" id="sa-login-form">
                        @csrf

                        <div class="sa-tabs" role="tablist">
                            <button type="button" class="sa-tab active" data-sa-tab="email" role="tab">Email</button>
                            <button type="button" class="sa-tab" data-sa-tab="phone" role="tab">Phone</button>
                        </div>

                        <div id="sa-panel-email" class="sa-panel active" role="tabpanel">
                            <div class="sa-field">
                                <label class="sa-label" for="sa-email"><span class="sa-label-dot"></span>@lang('superadmin::app.users.sessions.email')</label>
                                <div class="sa-input-wrap">
                                    <span class="sa-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
                                    <input
                                        class="sa-input @error('email') sa-input-error @enderror"
                                        type="email"
                                        name="email"
                                        id="sa-email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        placeholder="admin@domain.com"
                                    />
                                </div>
                                @error('email')
                                    <p class="sa-flash" style="margin-top:8px;margin-bottom:0;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="sa-panel-phone" class="sa-panel" role="tabpanel">
                            <div class="sa-field">
                                <label class="sa-label" for="sa-phone"><span class="sa-label-dot"></span>Admin Phone</label>
                                <div class="sa-input-wrap">
                                    <span class="sa-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg></span>
                                    <input class="sa-input" type="tel" id="sa-phone" autocomplete="tel" placeholder="+92 300 0000000" />
                                </div>
                                <p class="sa-hint">{{ __('Super admin sign-in uses email. Open the Email tab and use your registered admin email.') }}</p>
                            </div>
                        </div>

                        <div class="sa-field">
                            <label class="sa-label" for="sa-password"><span class="sa-label-dot"></span>@lang('superadmin::app.users.sessions.password')</label>
                            <div class="sa-input-wrap">
                                <span class="sa-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></span>
                                <input
                                    class="sa-input @error('password') sa-input-error @enderror"
                                    type="password"
                                    name="password"
                                    id="sa-password"
                                    required
                                    minlength="6"
                                    autocomplete="current-password"
                                    placeholder="••••••••••••"
                                />
                                <button class="sa-pw-toggle" type="button" id="sa-pw-toggle" aria-label="{{ __('Show password') }}">
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="sa-flash" style="margin-top:8px;margin-bottom:0;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sa-forgot-row">
                            <a href="{{ route('superadmin.forget_password.create') }}" class="sa-link">@lang('superadmin::app.users.sessions.forget-password-link')</a>
                        </div>

                        @if (core()->getConfigData('customer.captcha.credentials.status'))
                            <div class="sa-field">
                                {!! \Webkul\Customer\Facades\Captcha::render() !!}
                                @error('g-recaptcha-response')
                                    <p class="sa-flash" style="margin-top:8px;">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <button class="sa-btn" type="submit">@lang('superadmin::app.users.sessions.submit-btn')</button>
                    </form>
                </div>

                <div class="sa-footer">SYSTEM v2.0 &nbsp;|&nbsp; SECURE LOGIN &nbsp;|&nbsp; ALL ACTIVITY LOGGED</div>

                <p class="sa-footer" style="margin-top:12px;color:#444;">
                    @lang('superadmin::app.users.sessions.powered-by-description', [
                        'bagisto' => '<a class="sa-link" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                        'webkul' => '<a class="sa-link" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                    ])
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
        <script>
            (function () {
                var form = document.getElementById('sa-login-form');
                var tabs = document.querySelectorAll('[data-sa-tab]');
                var emailPanel = document.getElementById('sa-panel-email');
                var phonePanel = document.getElementById('sa-panel-phone');
                var emailInput = document.getElementById('sa-email');
                var activeTab = 'email';

                tabs.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        activeTab = btn.getAttribute('data-sa-tab');
                        tabs.forEach(function (t) { t.classList.toggle('active', t === btn); });
                        emailPanel.classList.toggle('active', activeTab === 'email');
                        phonePanel.classList.toggle('active', activeTab === 'phone');
                    });
                });

                form.addEventListener('submit', function (e) {
                    if (activeTab === 'phone') {
                        e.preventDefault();
                        tabs[0].click();
                        emailInput.focus();
                    }
                });

                var pw = document.getElementById('sa-password');
                document.getElementById('sa-pw-toggle').addEventListener('click', function () {
                    var show = pw.type === 'password';
                    pw.type = show ? 'text' : 'password';
                    this.innerHTML = show
                        ? '<svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-5 0-9.27-3.11-11-7.5a10.06 10.06 0 012.54-3.9M9.9 4.24A9.12 9.12 0 0112 4c5 0 9.27 3.11 11 7.5a10.05 10.05 0 01-3.17 4.26M1 1l22 22M8.56 8.56A5 5 0 0012 7a5 5 0 015 5 5 5 0 01-.56 2.31"/></svg>'
                        : '<svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
                });
            })();
        </script>
    @endpush
</x-superadmin::layouts.anonymous>
