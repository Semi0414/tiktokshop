<!-- SEO Meta Content -->
<?php $__env->startPush('meta'); ?>
    <meta name="description" content="<?php echo app('translator')->get('shop::app.customers.login-form.page-title'); ?>"/>
    <meta name="keywords" content="<?php echo app('translator')->get('shop::app.customers.login-form.page-title'); ?>"/>
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
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('shop::app.customers.login-form.page-title'); ?>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body:has(.bl-auth) main#main {
                background: #f6f5f2 !important;
                min-height: 100vh;
            }
            .bl-auth {
                font-family: 'Outfit', sans-serif;
                color: #1a1a1a;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                position: relative;
            }
            .bl-auth::before {
                content: '';
                position: fixed;
                top: -100px;
                right: -100px;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 65%);
                pointer-events: none;
            }
            :root {
                --bl-bg: #f6f5f2;
                --bl-card: #ffffff;
                --bl-border: #e8e5df;
                --bl-border-focus: #10b981;
                --bl-accent: #10b981;
                --bl-accent-light: #ecfdf5;
                --bl-accent-dark: #059669;
                --bl-text: #1a1a1a;
                --bl-muted: #8b8680;
                --bl-input-bg: #faf9f7;
                --bl-radius: 16px;
                --bl-radius-sm: 10px;
                --bl-transition: 0.2s cubic-bezier(0.4,0,0.2,1);
                --bl-shadow-card: 0 2px 8px rgba(0,0,0,0.06), 0 12px 40px rgba(0,0,0,0.08);
            }
            .bl-wrapper { width: 100%; max-width: 440px; position: relative; z-index: 1; }
            .bl-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 28px;
            }
            .bl-logo {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 18px;
                font-weight: 700;
                color: var(--bl-text);
            }
            .bl-logo-mark {
                width: 36px;
                height: 36px;
                background: var(--bl-accent);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .bl-logo-mark svg { width: 20px; height: 20px; fill: white; }
            .bl-signup-link { font-size: 13.5px; color: var(--bl-muted); }
            .bl-signup-link a { color: var(--bl-accent); text-decoration: none; font-weight: 500; }
            .bl-card {
                background: var(--bl-card);
                border-radius: var(--bl-radius);
                box-shadow: var(--bl-shadow-card);
                padding: 34px 30px;
                border: 1px solid var(--bl-border);
            }
            .bl-heading {
                font-size: 26px;
                font-weight: 700;
                color: var(--bl-text);
                margin-bottom: 4px;
                letter-spacing: -0.3px;
            }
            .bl-subheading { font-size: 14.5px; color: var(--bl-muted); margin-bottom: 26px; }
            .bl-tabs { display: flex; gap: 8px; margin-bottom: 24px; }
            .bl-tab {
                flex: 1;
                padding: 10px 0;
                font-family: 'Outfit', sans-serif;
                font-size: 14px;
                font-weight: 500;
                background: var(--bl-input-bg);
                border: 1.5px solid var(--bl-border);
                border-radius: var(--bl-radius-sm);
                color: var(--bl-muted);
                cursor: pointer;
                transition: var(--bl-transition);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            .bl-tab svg { width: 16px; height: 16px; }
            .bl-tab.active {
                background: var(--bl-accent-light);
                border-color: var(--bl-accent);
                color: var(--bl-accent-dark);
            }
            .bl-field { margin-bottom: 16px; }
            .bl-label {
                display: block;
                font-size: 13.5px;
                font-weight: 500;
                color: var(--bl-text);
                margin-bottom: 7px;
            }
            .bl-input-wrap { position: relative; }
            .bl-input-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #c5c0b8;
                pointer-events: none;
                display: flex;
            }
            .bl-input-icon svg { width: 17px; height: 17px; }
            .bl-input {
                width: 100%;
                padding: 12px 14px 12px 44px;
                background: var(--bl-input-bg);
                border: 1.5px solid var(--bl-border);
                border-radius: var(--bl-radius-sm);
                color: var(--bl-text);
                font-family: 'Outfit', sans-serif;
                font-size: 15px;
                outline: none;
                transition: var(--bl-transition);
            }
            .bl-input.bl-input-pw { padding-right: 44px; }
            .bl-input::placeholder { color: #ccc9c3; }
            .bl-input:focus {
                border-color: var(--bl-border-focus);
                background: #fff;
                box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
            }
            .bl-input.bl-input-error { border-color: #ef4444; }
            .bl-pw-toggle {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #c5c0b8;
                cursor: pointer;
                padding: 4px;
                display: flex;
                transition: var(--bl-transition);
            }
            .bl-pw-toggle:hover { color: var(--bl-accent); }
            .bl-forgot-row { display: flex; justify-content: flex-end; margin-top: -6px; margin-bottom: 22px; }
            .bl-link { font-size: 13.5px; color: var(--bl-accent); text-decoration: none; font-weight: 500; }
            .bl-link:hover { text-decoration: underline; }
            .bl-btn {
                width: 100%;
                padding: 13.5px;
                background: var(--bl-accent);
                border: none;
                border-radius: var(--bl-radius-sm);
                color: #fff;
                font-family: 'Outfit', sans-serif;
                font-size: 15.5px;
                font-weight: 600;
                cursor: pointer;
                transition: var(--bl-transition);
            }
            .bl-btn:hover { background: var(--bl-accent-dark); }
            .bl-btn:active { transform: scale(0.99); }
            .bl-divider {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 20px 0;
            }
            .bl-divider-line { flex: 1; height: 1px; background: var(--bl-border); }
            .bl-divider-text { font-size: 12.5px; color: var(--bl-muted); }
            .bl-social { display: flex; gap: 10px; margin-bottom: 8px; }
            .bl-social-btn {
                flex: 1;
                padding: 10px;
                background: var(--bl-input-bg);
                border: 1.5px solid var(--bl-border);
                border-radius: var(--bl-radius-sm);
                cursor: not-allowed;
                opacity: 0.65;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                font-family: 'Outfit', sans-serif;
                font-size: 13.5px;
                font-weight: 500;
                color: var(--bl-text);
            }
            .bl-social-btn svg { width: 18px; height: 18px; }
            .bl-panel { display: none; }
            .bl-panel.active { display: block; }
            .bl-flash { font-size: 13px; color: #dc2626; margin-bottom: 14px; }
            .bl-privacy { margin-top: 16px; display: flex; align-items: flex-start; gap: 10px; }
            .bl-privacy input { width: 17px; height: 17px; accent-color: var(--bl-accent); margin-top: 2px; flex-shrink: 0; }
            .bl-privacy label { font-size: 13px; color: var(--bl-muted); line-height: 1.4; }
            .bl-footer-note { text-align: center; font-size: 12px; color: var(--bl-muted); margin-top: 20px; }
        </style>
    <?php $__env->stopPush(); ?>

    <div class="bl-auth">
        <?php echo view_render_event('bagisto.shop.customers.login.logo.before'); ?>


        <div class="bl-wrapper">
            <div class="bl-top">
                <a href="<?php echo e(route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName())); ?>" class="bl-logo" style="text-decoration:none;color:inherit;">
                    <div class="bl-logo-mark">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 5.9 17 7 17h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0023.5 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </div>
                    <?php echo e(config('app.name')); ?>

                </a>
                <div class="bl-signup-link">
                    <?php echo app('translator')->get('shop::app.customers.login-form.new-customer'); ?>
                    <a href="<?php echo e(route('shop.customers.register.index')); ?>"><?php echo app('translator')->get('shop::app.customers.login-form.create-your-account'); ?></a>
                </div>
            </div>

            <div class="bl-card">
                <div class="bl-heading"><?php echo app('translator')->get('shop::app.customers.login-form.page-title'); ?></div>
                <div class="bl-subheading"><?php echo app('translator')->get('shop::app.customers.login-form.form-login-text'); ?></div>

                <form method="POST" action="<?php echo e(route('shop.customer.session.create')); ?>" id="bl-login-form">
                    <?php echo csrf_field(); ?>
                    <?php if(! empty($redirectUrl)): ?>
                        <input type="hidden" name="redirect_url" value="<?php echo e($redirectUrl); ?>" />
                    <?php endif; ?>

                    <?php echo view_render_event('bagisto.shop.customers.login.before'); ?>


                    <div class="bl-tabs" role="tablist">
                        <button type="button" class="bl-tab active" data-bl-tab="email" role="tab" aria-selected="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <?php echo app('translator')->get('shop::app.customers.login-form.email'); ?>
                        </button>
                        <button type="button" class="bl-tab" data-bl-tab="phone" role="tab" aria-selected="false">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
                            <?php echo app('translator')->get('shop::app.customers.signup-form.phone'); ?>
                        </button>
                    </div>

                    <div id="bl-panel-email" class="bl-panel active" role="tabpanel">
                        <div class="bl-field">
                            <label class="bl-label" for="bl-email"><?php echo app('translator')->get('shop::app.customers.login-form.email'); ?></label>
                            <div class="bl-input-wrap">
                                <span class="bl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
                                <input
                                    class="bl-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> bl-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="email"
                                    name="email"
                                    id="bl-email"
                                    value="<?php echo e(old('email')); ?>"
                                    required
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                    inputmode="email"
                                />
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="bl-flash" style="margin-top:6px;margin-bottom:0;"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div id="bl-panel-phone" class="bl-panel" role="tabpanel">
                        <div class="bl-field">
                            <label class="bl-label" for="bl-phone"><?php echo app('translator')->get('shop::app.customers.signup-form.phone'); ?></label>
                            <div class="bl-input-wrap">
                                <span class="bl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg></span>
                                <input
                                    class="bl-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> bl-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="tel"
                                    id="bl-phone"
                                    autocomplete="tel"
                                    placeholder="+92 300 0000000"
                                    inputmode="tel"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="bl-field">
                        <label class="bl-label" for="bl-password"><?php echo app('translator')->get('shop::app.customers.login-form.password'); ?></label>
                        <div class="bl-input-wrap">
                            <span class="bl-input-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></span>
                            <input
                                class="bl-input bl-input-pw <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> bl-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                type="password"
                                name="password"
                                id="bl-password"
                                required
                                minlength="6"
                                autocomplete="current-password"
                                placeholder="<?php echo e(__('shop::app.customers.login-form.password')); ?>"
                            />
                            <button class="bl-pw-toggle" type="button" id="bl-pw-toggle" aria-label="<?php echo app('translator')->get('shop::app.customers.login-form.show-password'); ?>">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="bl-flash" style="margin-top:6px;margin-bottom:0;"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="bl-forgot-row">
                        <a href="<?php echo e(route('shop.customers.forgot_password.create')); ?>" class="bl-link"><?php echo app('translator')->get('shop::app.customers.login-form.forgot-pass'); ?></a>
                    </div>

                    <div class="bl-privacy">
                        <input
                            type="checkbox"
                            id="privacy_accepted"
                            name="privacy_accepted"
                            value="1"
                            <?php echo e(old('privacy_accepted') ? 'checked' : ''); ?>

                            required
                        />
                        <label for="privacy_accepted"><?php echo e(__('I accept the privacy policy.')); ?></label>
                    </div>
                    <?php $__errorArgs = ['privacy_accepted'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="bl-flash"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php if(core()->getConfigData('customer.captcha.credentials.status')): ?>
                        <div class="bl-field" style="margin-top:16px;">
                            <?php echo \Webkul\Customer\Facades\Captcha::render(); ?>

                            <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="bl-flash"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo view_render_event('bagisto.shop.customers.login_form_controls.before'); ?>


                    <button class="bl-btn" type="submit" style="margin-top:20px;"><?php echo app('translator')->get('shop::app.customers.login-form.button-title'); ?></button>

                    <?php echo view_render_event('bagisto.shop.customers.login_form_controls.after'); ?>

                </form>

                <div class="bl-divider">
                    <div class="bl-divider-line"></div>
                    <span class="bl-divider-text"><?php echo e(__('or continue with')); ?></span>
                    <div class="bl-divider-line"></div>
                </div>

                <div class="bl-social">
                    <button type="button" class="bl-social-btn" disabled aria-disabled="true">
                        <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Google
                    </button>
                    <button type="button" class="bl-social-btn" disabled aria-disabled="true">
                        <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </button>
                </div>
            </div>

            <p class="bl-footer-note">
                <?php echo app('translator')->get('shop::app.customers.login-form.footer', ['current_year'=> date('Y') ]); ?>
            </p>
        </div>
    </div>

    <?php echo view_render_event('bagisto.shop.customers.login.logo.after'); ?>

    <?php echo view_render_event('bagisto.shop.customers.login.after'); ?>


    <?php $__env->startPush('scripts'); ?>
        <?php echo \Webkul\Customer\Facades\Captcha::renderJS(); ?>

        <script>
            (function () {
                var form = document.getElementById('bl-login-form');
                var emailPanel = document.getElementById('bl-panel-email');
                var phonePanel = document.getElementById('bl-panel-phone');
                var emailInput = document.getElementById('bl-email');
                var phoneInput = document.getElementById('bl-phone');
                var tabs = document.querySelectorAll('[data-bl-tab]');
                var activeTab = 'email';

                function syncLoginName() {
                    if (activeTab === 'email') {
                        emailInput.setAttribute('name', 'email');
                        emailInput.required = true;
                        phoneInput.removeAttribute('name');
                        phoneInput.required = false;
                    } else {
                        phoneInput.setAttribute('name', 'email');
                        phoneInput.required = true;
                        emailInput.removeAttribute('name');
                        emailInput.required = false;
                    }
                }

                syncLoginName();

                tabs.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        activeTab = btn.getAttribute('data-bl-tab');
                        tabs.forEach(function (t) {
                            t.classList.toggle('active', t === btn);
                            t.setAttribute('aria-selected', t === btn ? 'true' : 'false');
                        });
                        emailPanel.classList.toggle('active', activeTab === 'email');
                        phonePanel.classList.toggle('active', activeTab === 'phone');
                        syncLoginName();
                    });
                });

                document.getElementById('bl-pw-toggle').addEventListener('click', function () {
                    var pw = document.getElementById('bl-password');
                    var show = pw.type === 'password';
                    pw.type = show ? 'text' : 'password';
                    this.innerHTML = show
                        ? '<svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-5 0-9.27-3.11-11-7.5a10.06 10.06 0 012.54-3.9M9.9 4.24A9.12 9.12 0 0112 4c5 0 9.27 3.11 11 7.5a10.05 10.05 0 01-3.17 4.26M1 1l22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>'
                        : '<svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
                });
            })();
        </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/customers/sign-in.blade.php ENDPATH**/ ?>