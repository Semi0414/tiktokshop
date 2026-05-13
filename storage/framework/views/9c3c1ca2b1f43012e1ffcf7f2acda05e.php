<!-- SEO Meta Content -->
<?php $__env->startPush('meta'); ?>
    <meta
        name="description"
        content="<?php echo app('translator')->get('shop::app.customers.signup-form.page-title'); ?>"
    />

    <meta
        name="keywords"
        content="<?php echo app('translator')->get('shop::app.customers.signup-form.page-title'); ?>"
    />
<?php $__env->stopPush(); ?>

<?php if (isset($component)) { $__componentOriginal2643b7d197f48caff2f606750db81304 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2643b7d197f48caff2f606750db81304 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.index','data' => ['hasHeader' => false,'hasFeature' => false,'hasFooter' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['has-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'has-feature' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'has-footer' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <!-- Page Title -->
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('shop::app.customers.signup-form.page-title'); ?>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
        
        <style>
            body:has(.bs-signup-page) main#main {
                background: #f6f5f2 !important;
                min-height: 100vh;
            }
            .bs-signup-page {
                font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                color: #1a1a1a;
                padding: 30px 20px;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                position: relative;
            }
            .bs-signup-page::before {
                content: '';
                position: fixed;
                bottom: -100px;
                left: -100px;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(99,102,241,0.07) 0%, transparent 65%);
                pointer-events: none;
            }
            .bs-wrapper { width: 100%; max-width: 520px; position: relative; z-index: 1; }
            .bs-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
            }
            .bs-logo {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 18px;
                font-weight: 700;
                color: #1a1a1a;
                text-decoration: none;
            }
            .bs-logo-mark {
                width: 36px;
                height: 36px;
                background: #10b981;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .bs-logo-mark svg { width: 20px; height: 20px; fill: white; }
            .bs-login-link { font-size: 13.5px; color: #8b8680; }
            .bs-login-link a { color: #10b981; text-decoration: none; font-weight: 500; }
            .bs-steps {
                display: flex;
                align-items: center;
                gap: 0;
                margin-bottom: 22px;
            }
            .bs-step {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 12.5px;
                font-weight: 500;
                color: #8b8680;
            }
            .bs-step-dot {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                border: 1.5px solid #e8e5df;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 600;
                background: #fff;
                color: #8b8680;
            }
            .bs-step.active .bs-step-dot {
                background: #ecfdf5;
                border-color: #10b981;
                color: #059669;
            }
            .bs-step.active { color: #059669; }
            .bs-step-line { flex: 1; height: 1.5px; background: #e8e5df; margin: 0 8px; }
            .bs-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 12px 40px rgba(0,0,0,0.08);
                padding: 30px 28px;
                border: 1px solid #e8e5df;
            }
            .bs-heading {
                font-size: 24px;
                font-weight: 700;
                color: #1a1a1a;
                margin-bottom: 4px;
                letter-spacing: -0.3px;
            }
            .bs-subheading { font-size: 14px; color: #8b8680; margin-bottom: 24px; }
            .bs-section-title {
                margin: 24px 0 16px;
                padding-top: 20px;
                border-top: 1px solid #e8e5df;
                font-size: 16px;
                font-weight: 600;
                color: #1a1a1a;
            }
            .bs-signup-page .bs-card input[type=text]:not([type=hidden]),
            .bs-signup-page .bs-card input[type=email],
            .bs-signup-page .bs-card input[type=password],
            .bs-signup-page .bs-card input[type=date],
            .bs-signup-page .bs-card input[type=number],
            .bs-signup-page .bs-card select {
                border: 1.5px solid #e8e5df !important;
                border-radius: 10px !important;
                background: #faf9f7 !important;
                color: #1a1a1a !important;
                font-family: inherit !important;
            }
            .bs-reg-invalid {
                border-color: #ef4444 !important;
                box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
            }
            .bs-signup-page .bs-card input[type=checkbox].bs-reg-invalid {
                outline: 2px solid #ef4444;
                outline-offset: 2px;
                box-shadow: none !important;
            }
            .bs-reg-highlight-target.bs-reg-invalid-wrap {
                box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.45);
                border-radius: 10px;
            }
            .bs-signup-page .bs-card input:focus,
            .bs-signup-page .bs-card select:focus {
                border-color: #10b981 !important;
                box-shadow: 0 0 0 3px rgba(16,185,129,0.1) !important;
                background: #fff !important;
            }
            .bs-signup-page .bs-card label,
            .bs-signup-page .bs-card .required::after {
                color: #1a1a1a;
            }
            .bs-pw-strength {
                margin: 6px 0 16px;
                display: flex;
                gap: 4px;
                align-items: center;
            }
            .bs-pw-bar {
                flex: 1;
                height: 3px;
                background: #e8e5df;
                border-radius: 2px;
                transition: 0.2s;
            }
            .bs-pw-bar.fill-1 { background: #ef4444; }
            .bs-pw-bar.fill-2 { background: #f59e0b; }
            .bs-pw-bar.fill-3 { background: #10b981; }
            .bs-pw-label { font-size: 11.5px; color: #8b8680; min-width: 44px; text-align: right; }
            .bs-btn-submit {
                width: 100%;
                padding: 13px;
                background: #10b981;
                border: none;
                border-radius: 10px;
                color: #fff;
                font-family: inherit;
                font-size: 15.5px;
                font-weight: 600;
                cursor: pointer;
                transition: 0.2s;
            }
            .bs-btn-submit:hover { background: #059669; }
            .bs-divider {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 18px 0;
            }
            .bs-divider-line { flex: 1; height: 1px; background: #e8e5df; }
            .bs-divider-text { font-size: 12px; color: #8b8680; }
            .bs-social { display: flex; gap: 10px; }
            .bs-social-btn {
                flex: 1;
                padding: 10px;
                background: #faf9f7;
                border: 1.5px solid #e8e5df;
                border-radius: 10px;
                cursor: not-allowed;
                opacity: 0.65;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                font-family: inherit;
                font-size: 13px;
                font-weight: 500;
                color: #1a1a1a;
            }
            .bs-social-btn svg { width: 17px; height: 17px; }
            .bs-footer-note { text-align: center; font-size: 12px; color: #8b8680; margin-top: 20px; }
            .bs-account-link { text-align: center; font-size: 13.5px; color: #8b8680; margin-top: 18px; }
            .bs-account-link a { color: #10b981; font-weight: 500; text-decoration: none; }
        </style>
    <?php $__env->stopPush(); ?>

    <div class="bs-signup-page">
        <?php echo view_render_event('bagisto.shop.customers.sign-up.logo.before'); ?>


        <div class="bs-wrapper">
            <div class="bs-top">
                <a
                    href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName())); ?>"
                    class="bs-logo"
                    aria-label="<?php echo app('translator')->get('shop::app.customers.signup-form.bagisto'); ?>"
                >
                    <div class="bs-logo-mark">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 5.9 17 7 17h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0023.5 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </div>
                    <?php echo e(config('app.name')); ?>

                </a>
                <div class="bs-login-link">
                    <?php echo app('translator')->get('shop::app.customers.signup-form.account-exists'); ?>
                    <a href="<?php echo e(route('shop.customer.session.index')); ?>"><?php echo app('translator')->get('shop::app.customers.signup-form.sign-in-button'); ?></a>
                </div>
            </div>

            <div class="bs-steps" aria-hidden="true">
                <div class="bs-step active">
                    <div class="bs-step-dot">1</div>
                    <?php echo e(__('Account')); ?>

                </div>
                <div class="bs-step-line"></div>
                <div class="bs-step">
                    <div class="bs-step-dot">2</div>
                    <?php echo e(__('Verify')); ?>

                </div>
                <div class="bs-step-line"></div>
                <div class="bs-step">
                    <div class="bs-step-dot">3</div>
                    <?php echo e(__('Done')); ?>

                </div>
            </div>

            <div class="bs-card">
                <h1 class="bs-heading"><?php echo app('translator')->get('shop::app.customers.signup-form.page-title'); ?></h1>
                <p class="bs-subheading"><?php echo app('translator')->get('shop::app.customers.signup-form.form-signup-text'); ?></p>

                <div class="mt-2 max-sm:mt-2">
                <form method="POST" action="<?php echo e(route('shop.customers.register.store')); ?>" id="bs-customer-register-form" novalidate>
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('shop::customers.partials.sign-up-native-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </form>
                </div>

                <div class="bs-divider">
                    <div class="bs-divider-line"></div>
                    <span class="bs-divider-text"><?php echo e(__('or sign up with')); ?></span>
                    <div class="bs-divider-line"></div>
                </div>

                <div class="bs-social">
                    <button type="button" class="bs-social-btn" disabled aria-disabled="true">
                        <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Google
                    </button>
                    <button type="button" class="bs-social-btn" disabled aria-disabled="true">
                        <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </button>
                </div>
            </div>

            <p class="bs-footer-note">
                <?php echo app('translator')->get('shop::app.customers.signup-form.footer', ['current_year'=> date('Y') ]); ?>
            </p>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <?php echo \Webkul\Customer\Facades\Captcha::renderJS(); ?>


        <script>
            (function () {
                function updatePwStrength(val) {
                    var b1 = document.getElementById('signup-bar1');
                    var b2 = document.getElementById('signup-bar2');
                    var b3 = document.getElementById('signup-bar3');
                    var lbl = document.getElementById('signup-pw-label');
                    if (! b1 || ! lbl) {
                        return;
                    }
                    b1.className = 'bs-pw-bar';
                    b2.className = 'bs-pw-bar';
                    b3.className = 'bs-pw-bar';
                    lbl.textContent = '';
                    if (! val) {
                        return;
                    }
                    var score = 0;
                    if (val.length >= 8) {
                        score++;
                    }
                    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) {
                        score++;
                    }
                    if (/[^A-Za-z0-9]/.test(val)) {
                        score++;
                    }
                    var fills = ['fill-1', 'fill-2', 'fill-3'];
                    var labels = ['Weak', 'Fair', 'Strong'];
                    var bars = [b1, b2, b3];
                    for (var i = 0; i < score; i++) {
                        bars[i].classList.add(fills[score - 1]);
                    }
                    lbl.textContent = labels[score - 1] || '';
                }
                function bindPwStrength(root) {
                    var pw = root.querySelector('#reg_password');
                    if (! pw || pw.dataset.bsPwBound) {
                        return;
                    }
                    pw.dataset.bsPwBound = '1';
                    pw.addEventListener('input', function () {
                        updatePwStrength(pw.value);
                    });
                }
                function bindPwToggles(root) {
                    root.querySelectorAll('[data-bs-pw-toggle]').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            var sel = btn.getAttribute('data-bs-pw-toggle');
                            var inp = sel ? root.querySelector(sel) : null;
                            if (! inp) {
                                return;
                            }
                            inp.type = inp.type === 'password' ? 'text' : 'password';
                        });
                    });
                }
                function clearAllRegHighlights(form) {
                    form.querySelectorAll('.bs-reg-invalid').forEach(function (n) {
                        n.classList.remove('bs-reg-invalid');
                    });
                    form.querySelectorAll('.bs-reg-invalid-wrap').forEach(function (n) {
                        n.classList.remove('bs-reg-invalid-wrap');
                    });
                }
                function markInvalidField(el) {
                    if (! el) {
                        return;
                    }
                    el.classList.add('bs-reg-invalid');
                    var wrap = el.closest('.bs-reg-highlight-target');
                    if (wrap) {
                        wrap.classList.add('bs-reg-invalid-wrap');
                    }
                }
                function highlightInvalid(form) {
                    clearAllRegHighlights(form);
                    var firstBad = null;
                    form.querySelectorAll('input, select, textarea').forEach(function (field) {
                        if (field.type === 'hidden' || field.type === 'submit' || field.type === 'button' || field.disabled) {
                            return;
                        }
                        if (field.name === '_token' || field.name === '_method') {
                            return;
                        }
                        if (! field.checkValidity()) {
                            markInvalidField(field);
                            if (! firstBad) {
                                firstBad = field;
                            }
                        }
                    });
                    if (firstBad) {
                        try {
                            firstBad.focus({ preventScroll: true });
                        } catch (err) {}
                        firstBad.scrollIntoView({ block: 'center', behavior: 'smooth' });
                    }
                }
                function applyServerSignupErrors(form, keys) {
                    if (! keys || ! keys.length) {
                        return;
                    }
                    clearAllRegHighlights(form);
                    var firstScroll = null;
                    keys.forEach(function (name) {
                        if (name === 'g-recaptcha-response') {
                            var cap = document.getElementById('bs-wrap-captcha');
                            if (cap) {
                                cap.classList.add('bs-reg-invalid-wrap');
                                if (! firstScroll) {
                                    firstScroll = cap;
                                }
                            }
                            return;
                        }
                        var el = form.querySelector('[name="' + String(name).replace(/\\/g, '\\\\').replace(/"/g, '\\"') + '"]');
                        if (el) {
                            markInvalidField(el);
                            if (! firstScroll) {
                                firstScroll = el;
                            }
                        }
                    });
                    if (firstScroll) {
                        try {
                            if (typeof firstScroll.focus === 'function') {
                                firstScroll.focus({ preventScroll: true });
                            }
                        } catch (e2) {}
                        firstScroll.scrollIntoView({ block: 'center', behavior: 'smooth' });
                    }
                }
                document.addEventListener('DOMContentLoaded', function () {
                    var root = document.querySelector('.bs-signup-page');
                    if (! root) {
                        return;
                    }
                    bindPwStrength(root);
                    bindPwToggles(root);
                    var form = document.getElementById('bs-customer-register-form');
                    if (form) {
                        var serverSignupErrorKeys = <?php echo json_encode($errors->keys(), 15, 512) ?>;
                        applyServerSignupErrors(form, serverSignupErrorKeys);

                        form.addEventListener('input', function (e) {
                            var t = e.target;
                            if (t && t.matches && t.matches('input, select, textarea')) {
                                t.classList.remove('bs-reg-invalid');
                                var w = t.closest('.bs-reg-highlight-target');
                                if (w) {
                                    w.classList.remove('bs-reg-invalid-wrap');
                                }
                            }
                        }, true);
                        form.addEventListener('change', function (e) {
                            var t = e.target;
                            if (t && t.matches && t.matches('input, select, textarea')) {
                                t.classList.remove('bs-reg-invalid');
                                var w2 = t.closest('.bs-reg-highlight-target');
                                if (w2) {
                                    w2.classList.remove('bs-reg-invalid-wrap');
                                }
                            }
                        }, true);

                        form.addEventListener('submit', function (e) {
                            clearAllRegHighlights(form);
                            var p = form.querySelector('#reg_password');
                            var c = form.querySelector('#reg_password_confirmation');
                            if (p && c) {
                                c.setCustomValidity('');
                                if (p.value !== c.value) {
                                    c.setCustomValidity(<?php echo json_encode(__('Passwords do not match.'), 15, 512) ?>);
                                }
                            }
                            if (! form.checkValidity()) {
                                e.preventDefault();
                                highlightInvalid(form);
                            }
                        });
                    }
                });
            })();
        </script>
    <?php $__env->stopPush(); ?>

    <?php if(core()->getConfigData('general.gdpr.settings.enabled') && core()->getConfigData('general.gdpr.agreement.enabled') && core()->getConfigData('general.gdpr.agreement.agreement_content')): ?>
        <dialog id="signup-terms-dlg" class="max-h-[90vh] w-full max-w-lg rounded-lg border border-zinc-200 p-0 shadow-xl backdrop:bg-black/40">
            <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-3">
                <p class="font-semibold"><?php echo app('translator')->get('shop::app.customers.signup-form.terms-conditions'); ?></p>
                <form method="dialog">
                    <button type="submit" class="icon-cancel cursor-pointer border-0 bg-transparent text-2xl" aria-label="<?php echo e(__('Close')); ?>"></button>
                </form>
            </div>
            <div class="max-h-[70vh] overflow-auto p-5 text-sm">
                <?php echo core()->getConfigData('general.gdpr.agreement.agreement_content'); ?>

            </div>
        </dialog>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $attributes = $__attributesOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__attributesOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2643b7d197f48caff2f606750db81304)): ?>
<?php $component = $__componentOriginal2643b7d197f48caff2f606750db81304; ?>
<?php unset($__componentOriginal2643b7d197f48caff2f606750db81304); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/customers/sign-up.blade.php ENDPATH**/ ?>