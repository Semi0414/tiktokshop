<?php if (isset($component)) { $__componentOriginal3ad46025fb3e01e4c2eb9d1732f00674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3ad46025fb3e01e4c2eb9d1732f00674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.layouts.anonymous','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin::layouts.anonymous'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('admin::app.users.sessions.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
        <style>
            body:has(.sl-page)::before { opacity: 0 !important; }
            body:has(.sl-page) { background: #0d0f14 !important; }
            body:has(.sl-page) #app { min-height: 100vh; }
            :root {
                --sl-bg: #0d0f14;
                --sl-surface: #161a23;
                --sl-card: #1c2130;
                --sl-border: rgba(255,255,255,0.07);
                --sl-border-focus: rgba(99,168,255,0.5);
                --sl-accent: #4f8eff;
                --sl-accent-2: #a78bfa;
                --sl-text: #f0f2f8;
                --sl-muted: #7a8099;
                --sl-input-bg: #111520;
                --sl-danger: #f87171;
                --sl-radius: 14px;
                --sl-radius-sm: 8px;
                --sl-transition: 0.22s cubic-bezier(0.4,0,0.2,1);
            }
            .sl-grid-bg {
                position: fixed;
                inset: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
                background-size: 48px 48px;
                pointer-events: none;
                z-index: 0;
            }
            .sl-page-inner {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow-x: hidden;
                padding: 20px;
                font-family: 'DM Sans', sans-serif;
                color: var(--sl-text);
                background: var(--sl-bg);
            }
            .sl-page-inner::before {
                content: '';
                position: fixed;
                inset: 0;
                background:
                    radial-gradient(ellipse 60% 40% at 20% 10%, rgba(79,142,255,0.12) 0%, transparent 60%),
                    radial-gradient(ellipse 50% 50% at 80% 90%, rgba(167,139,250,0.1) 0%, transparent 60%);
                pointer-events: none;
                z-index: 0;
            }
            .sl-wrapper { position: relative; z-index: 1; width: 100%; max-width: 460px; }
            .sl-brand { text-align: center; margin-bottom: 32px; }
            .sl-brand-icon {
                width: 52px; height: 52px;
                background: linear-gradient(135deg, var(--sl-accent), var(--sl-accent-2));
                border-radius: 16px;
                display: inline-flex; align-items: center; justify-content: center;
                margin-bottom: 14px;
            }
            .sl-brand-icon svg { width: 28px; height: 28px; fill: #fff; }
            .sl-title {
                font-family: 'Syne', sans-serif;
                font-size: 28px; font-weight: 800;
                letter-spacing: -0.5px; color: var(--sl-text);
            }
            .sl-subtitle { font-size: 14px; color: var(--sl-muted); margin-top: 6px; }
            .sl-card {
                background: var(--sl-card);
                border: 1px solid var(--sl-border);
                border-radius: var(--sl-radius);
                padding: 32px 30px;
                backdrop-filter: blur(20px);
            }
            .sl-tabs {
                display: flex; gap: 6px;
                background: var(--sl-input-bg);
                border-radius: var(--sl-radius-sm);
                padding: 4px;
                margin-bottom: 26px;
            }
            .sl-tab {
                flex: 1;
                padding: 9px 0;
                font-family: 'DM Sans', sans-serif;
                font-size: 13.5px; font-weight: 500;
                background: transparent;
                border: none;
                border-radius: 6px;
                color: var(--sl-muted);
                cursor: pointer;
                transition: var(--sl-transition);
                display: flex; align-items: center; justify-content: center; gap: 7px;
            }
            .sl-tab svg { width: 15px; height: 15px; }
            .sl-tab.active {
                background: var(--sl-card);
                color: var(--sl-text);
                border: 1px solid var(--sl-border);
            }
            .sl-field { margin-bottom: 18px; }
            .sl-label {
                display: block;
                font-size: 13px; font-weight: 500;
                color: var(--sl-muted);
                margin-bottom: 8px;
                letter-spacing: 0.01em;
            }
            .sl-input-wrap { position: relative; }
            .sl-input-icon {
                position: absolute; left: 14px; top: 50%;
                transform: translateY(-50%);
                color: var(--sl-muted);
                pointer-events: none;
                display: flex;
            }
            .sl-input-icon svg { width: 16px; height: 16px; }
            .sl-input {
                width: 100%;
                padding: 12px 14px 12px 42px;
                background: var(--sl-input-bg);
                border: 1px solid var(--sl-border);
                border-radius: var(--sl-radius-sm);
                color: var(--sl-text);
                font-family: 'DM Sans', sans-serif;
                font-size: 14.5px;
                outline: none;
                transition: var(--sl-transition);
            }
            .sl-input::placeholder { color: #4a5068; }
            .sl-input:focus {
                border-color: var(--sl-border-focus);
                background: #131826;
                box-shadow: 0 0 0 3px rgba(79,142,255,0.1);
            }
            .sl-input.sl-input-error { border-color: var(--sl-danger); }
            .sl-pw-toggle {
                position: absolute; right: 12px; top: 50%;
                transform: translateY(-50%);
                background: none; border: none;
                color: var(--sl-muted);
                cursor: pointer;
                padding: 4px;
                display: flex;
                transition: var(--sl-transition);
            }
            .sl-pw-toggle:hover { color: var(--sl-text); }
            .sl-forgot-row { display: flex; justify-content: flex-end; margin-top: -8px; margin-bottom: 22px; }
            .sl-link {
                font-size: 13px;
                color: var(--sl-accent);
                text-decoration: none;
                transition: var(--sl-transition);
            }
            .sl-link:hover { color: #85b3ff; text-decoration: underline; }
            .sl-btn {
                width: 100%;
                padding: 13px;
                background: linear-gradient(135deg, var(--sl-accent), #6366f1);
                border: none;
                border-radius: var(--sl-radius-sm);
                color: #fff;
                font-family: 'Syne', sans-serif;
                font-size: 15px; font-weight: 700;
                letter-spacing: 0.3px;
                cursor: pointer;
                transition: var(--sl-transition);
            }
            .sl-btn:hover { filter: brightness(1.06); }
            .sl-btn:active { transform: scale(0.99); }
            .sl-divider {
                display: flex; align-items: center; gap: 12px;
                margin: 22px 0;
            }
            .sl-divider-line { flex: 1; height: 1px; background: var(--sl-border); }
            .sl-divider-text { font-size: 12px; color: var(--sl-muted); }
            .sl-register { text-align: center; font-size: 13.5px; color: var(--sl-muted); }
            .sl-badge {
                display: inline-flex; align-items: center; gap: 6px;
                background: rgba(79,142,255,0.1);
                border: 1px solid rgba(79,142,255,0.2);
                border-radius: 20px;
                padding: 4px 12px;
                font-size: 12px;
                color: #85b3ff;
                margin-bottom: 24px;
            }
            .sl-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--sl-accent); }
            .sl-panel { display: none; }
            .sl-panel.active { display: block; }
            .sl-hint { font-size: 12px; color: var(--sl-muted); margin-top: 8px; line-height: 1.4; }
            .sl-flash { font-size: 13px; color: var(--sl-danger); margin-bottom: 16px; }
        </style>
    <?php $__env->stopPush(); ?>

    <div class="sl-page">
        <div class="sl-page-inner">
            <div class="sl-grid-bg"></div>
            <div class="sl-wrapper">
                <div class="sl-brand">
                    <div class="sl-brand-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm-10-2h4v2h-4V5zm10 14H4V9h16v10z"/></svg>
                    </div>
                    <div class="sl-badge"><span class="sl-badge-dot"></span>Seller Portal</div>
                    <div class="sl-title"><?php echo app('translator')->get('admin::app.users.sessions.title'); ?></div>
                    <div class="sl-subtitle"><?php echo e(__('Access your seller dashboard')); ?></div>
                </div>

                <div class="sl-card">
                    <form method="POST" action="<?php echo e(route('admin.session.store')); ?>" id="sl-login-form">
                        <?php echo csrf_field(); ?>

                        <div class="sl-tabs" role="tablist">
                            <button type="button" class="sl-tab active" data-sl-tab="email" role="tab" aria-selected="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                Email
                            </button>
                            <button type="button" class="sl-tab" data-sl-tab="phone" role="tab" aria-selected="false">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
                                Phone
                            </button>
                        </div>

                        <div id="sl-panel-email" class="sl-panel active" role="tabpanel">
                            <div class="sl-field">
                                <label class="sl-label" for="sl-email"><?php echo app('translator')->get('admin::app.users.sessions.email'); ?></label>
                                <div class="sl-input-wrap">
                                    <span class="sl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
                                    <input
                                        class="sl-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> sl-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        type="email"
                                        name="email"
                                        id="sl-email"
                                        value="<?php echo e(old('email')); ?>"
                                        required
                                        autocomplete="username"
                                        placeholder="you@example.com"
                                    />
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="sl-flash" style="margin-top:8px;margin-bottom:0;"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div id="sl-panel-phone" class="sl-panel" role="tabpanel">
                            <div class="sl-field">
                                <label class="sl-label" for="sl-phone">Phone Number</label>
                                <div class="sl-input-wrap">
                                    <span class="sl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg></span>
                                    <input
                                        class="sl-input"
                                        type="tel"
                                        id="sl-phone"
                                        autocomplete="tel"
                                        placeholder="+92 300 0000000"
                                    />
                                </div>
                                <p class="sl-hint"><?php echo e(__('Seller sign-in uses your account email. Switch to the Email tab to log in, or enter the email linked to your seller account.')); ?></p>
                            </div>
                        </div>

                        <div class="sl-field">
                            <label class="sl-label" for="sl-password"><?php echo app('translator')->get('admin::app.users.sessions.password'); ?></label>
                            <div class="sl-input-wrap">
                                <span class="sl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></span>
                                <input
                                    class="sl-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> sl-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="password"
                                    name="password"
                                    id="sl-password"
                                    required
                                    minlength="6"
                                    autocomplete="current-password"
                                    placeholder="<?php echo e(__('admin::app.users.sessions.password')); ?>"
                                />
                                <button class="sl-pw-toggle" type="button" id="sl-pw-toggle" aria-label="<?php echo e(__('Show password')); ?>">
                                    <svg class="sl-eye-open" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="sl-flash" style="margin-top:8px;margin-bottom:0;"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="sl-forgot-row">
                            <a href="<?php echo e(route('admin.forget_password.create')); ?>" class="sl-link"><?php echo app('translator')->get('admin::app.users.sessions.forget-password-link'); ?></a>
                        </div>

                        <?php if(core()->getConfigData('customer.captcha.credentials.status')): ?>
                            <div class="sl-field">
                                <?php echo \Webkul\Customer\Facades\Captcha::render(); ?>

                                <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="sl-flash" style="margin-top:8px;"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>

                        <button class="sl-btn" type="submit"><?php echo app('translator')->get('admin::app.users.sessions.submit-btn'); ?></button>
                    </form>

                    <div class="sl-divider">
                        <div class="sl-divider-line"></div>
                        <span class="sl-divider-text"><?php echo e(__('Need help?')); ?></span>
                        <div class="sl-divider-line"></div>
                    </div>

                    <div class="sl-register">
                        <a href="<?php echo e(url('/')); ?>" class="sl-link"><?php echo e(__('← Back to store')); ?></a>
                    </div>
                </div>

                <p class="sl-register" style="margin-top:24px;">
                    <?php echo app('translator')->get('admin::app.users.sessions.powered-by-description', [
                        'bagisto' => '<a class="sl-link" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                        'webkul' => '<a class="sl-link" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                    ]); ?>
                </p>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <?php echo \Webkul\Customer\Facades\Captcha::renderJS(); ?>

        <script>
            (function () {
                var form = document.getElementById('sl-login-form');
                var tabs = document.querySelectorAll('[data-sl-tab]');
                var emailPanel = document.getElementById('sl-panel-email');
                var phonePanel = document.getElementById('sl-panel-phone');
                var emailInput = document.getElementById('sl-email');
                var activeTab = 'email';

                tabs.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        activeTab = btn.getAttribute('data-sl-tab');
                        tabs.forEach(function (t) {
                            t.classList.toggle('active', t === btn);
                            t.setAttribute('aria-selected', t === btn ? 'true' : 'false');
                        });
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

                var pw = document.getElementById('sl-password');
                var toggle = document.getElementById('sl-pw-toggle');
                toggle.addEventListener('click', function () {
                    var show = pw.type === 'password';
                    pw.type = show ? 'text' : 'password';
                    toggle.innerHTML = show
                        ? '<svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-5 0-9.27-3.11-11-7.5a10.06 10.06 0 012.54-3.9M9.9 4.24A9.12 9.12 0 0112 4c5 0 9.27 3.11 11 7.5a10.05 10.05 0 01-3.17 4.26M1 1l22 22M8.56 8.56A5 5 0 0012 7a5 5 0 015 5 5 5 0 01-.56 2.31"/></svg>'
                        : '<svg class="sl-eye-open" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
                });
            })();
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3ad46025fb3e01e4c2eb9d1732f00674)): ?>
<?php $attributes = $__attributesOriginal3ad46025fb3e01e4c2eb9d1732f00674; ?>
<?php unset($__attributesOriginal3ad46025fb3e01e4c2eb9d1732f00674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3ad46025fb3e01e4c2eb9d1732f00674)): ?>
<?php $component = $__componentOriginal3ad46025fb3e01e4c2eb9d1732f00674; ?>
<?php unset($__componentOriginal3ad46025fb3e01e4c2eb9d1732f00674); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Admin\src/resources/views/users/sessions/create.blade.php ENDPATH**/ ?>