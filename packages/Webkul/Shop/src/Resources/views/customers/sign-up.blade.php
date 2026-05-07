<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="@lang('shop::app.customers.signup-form.page-title')"
    />

    <meta
        name="keywords"
        content="@lang('shop::app.customers.signup-form.page-title')"
    />
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.signup-form.page-title')
    </x-slot>

    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body:has(.bs-signup-page) main#main {
                background: #f6f5f2 !important;
                min-height: 100vh;
            }
            .bs-signup-page {
                font-family: 'Outfit', sans-serif;
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
                font-family: 'Outfit', sans-serif !important;
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
                font-family: 'Outfit', sans-serif;
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
                font-family: 'Outfit', sans-serif;
                font-size: 13px;
                font-weight: 500;
                color: #1a1a1a;
            }
            .bs-social-btn svg { width: 17px; height: 17px; }
            .bs-footer-note { text-align: center; font-size: 12px; color: #8b8680; margin-top: 20px; }
            .bs-account-link { text-align: center; font-size: 13.5px; color: #8b8680; margin-top: 18px; }
            .bs-account-link a { color: #10b981; font-weight: 500; text-decoration: none; }
        </style>
    @endpush

    <div class="bs-signup-page">
        {!! view_render_event('bagisto.shop.customers.sign-up.logo.before') !!}

        <div class="bs-wrapper">
            <div class="bs-top">
                <a
                    href="{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}"
                    class="bs-logo"
                    aria-label="@lang('shop::app.customers.signup-form.bagisto')"
                >
                    <div class="bs-logo-mark">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 5.9 17 7 17h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0023.5 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </div>
                    {{ config('app.name') }}
                </a>
                <div class="bs-login-link">
                    @lang('shop::app.customers.signup-form.account-exists')
                    <a href="{{ route('shop.customer.session.index') }}">@lang('shop::app.customers.signup-form.sign-in-button')</a>
                </div>
            </div>

            <div class="bs-steps" aria-hidden="true">
                <div class="bs-step active">
                    <div class="bs-step-dot">1</div>
                    {{ __('Account') }}
                </div>
                <div class="bs-step-line"></div>
                <div class="bs-step">
                    <div class="bs-step-dot">2</div>
                    {{ __('Verify') }}
                </div>
                <div class="bs-step-line"></div>
                <div class="bs-step">
                    <div class="bs-step-dot">3</div>
                    {{ __('Done') }}
                </div>
            </div>

            <div class="bs-card">
                <h1 class="bs-heading">@lang('shop::app.customers.signup-form.page-title')</h1>
                <p class="bs-subheading">@lang('shop::app.customers.signup-form.form-signup-text')</p>

                <div class="mt-2 max-sm:mt-2">
                <x-shop::form :action="route('shop.customers.register.store')">
                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                    <!-- Seller referral code -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.referral-code')
                        </x-shop::form.control-group.label>

                        <p class="mb-2 text-sm text-zinc-500">
                            @lang('shop::app.customers.signup-form.referral-code-hint')
                        </p>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-4 uppercase max-md:py-3 max-sm:py-2"
                            name="referral_code"
                            rules="required"
                            :value="old('referral_code')"
                            :label="trans('shop::app.customers.signup-form.referral-code')"
                            :placeholder="trans('shop::app.customers.signup-form.referral-code')"
                            :aria-label="trans('shop::app.customers.signup-form.referral-code')"
                            aria-required="true"
                            autocomplete="off"
                        />

                        <x-shop::form.control-group.error control-name="referral_code" />
                    </x-shop::form.control-group>

                    <!-- First Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.first-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="first_name"
                            rules="required"
                            :value="old('first_name')"
                            :label="trans('shop::app.customers.signup-form.first-name')"
                            :placeholder="trans('shop::app.customers.signup-form.first-name')"
                            :aria-label="trans('shop::app.customers.signup-form.first-name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="first_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.first_name.after') !!}

                    <!-- Last Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.last-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="last_name"
                            rules="required"
                            :value="old('last_name')"
                            :label="trans('shop::app.customers.signup-form.last-name')"
                            :placeholder="trans('shop::app.customers.signup-form.last-name')"
                            :aria-label="trans('shop::app.customers.signup-form.last-name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="last_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.last_name.after') !!}

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.customers.signup-form.email')"
                            placeholder="email@example.com"
                            :aria-label="trans('shop::app.customers.signup-form.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.email.after') !!}

                    <!-- Phone -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.phone')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="phone"
                            rules="required|max:20"
                            :value="old('phone')"
                            :label="trans('shop::app.customers.signup-form.phone')"
                            :placeholder="trans('shop::app.customers.signup-form.phone')"
                            :aria-label="trans('shop::app.customers.signup-form.phone')"
                            aria-required="true"
                            autocomplete="tel"
                        />

                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    <!-- Gender -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            Gender
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="select"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="gender"
                            :value="old('gender')"
                            label="Gender"
                            aria-label="Gender"
                        >
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error control-name="gender" />
                    </x-shop::form.control-group>

                    <!-- Date of Birth -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            Date of Birth
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="date"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            id="signup-dob"
                            name="date_of_birth"
                            :value="old('date_of_birth')"
                            label="Date of Birth"
                            aria-label="Date of Birth"
                        />

                        <x-shop::form.control-group.error control-name="date_of_birth" />
                    </x-shop::form.control-group>

                    <!-- Shipping address -->
                    <div>
                        <h2 class="bs-section-title">
                            @lang('shop::app.customers.signup-form.shipping-section')
                        </h2>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.customers.signup-form.shipping-address1')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_address1"
                                rules="required"
                                :value="old('shipping_address1')"
                                :label="trans('shop::app.customers.signup-form.shipping-address1')"
                                :placeholder="trans('shop::app.customers.signup-form.shipping-address1')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-address1')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="shipping_address1" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.customers.signup-form.shipping-address2')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_address2"
                                :value="old('shipping_address2')"
                                :label="trans('shop::app.customers.signup-form.shipping-address2')"
                                :placeholder="trans('shop::app.customers.signup-form.shipping-address2')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-address2')"
                            />

                            <x-shop::form.control-group.error control-name="shipping_address2" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.customers.signup-form.shipping-country')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_country"
                                rules="required"
                                :value="old('shipping_country')"
                                :label="trans('shop::app.customers.signup-form.shipping-country')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-country')"
                                aria-required="true"
                            >
                                <option value="">
                                    @lang('shop::app.customers.signup-form.select-country')
                                </option>

                                @foreach (core()->countries() as $country)
                                    <option
                                        value="{{ $country->code }}"
                                        {{ old('shipping_country') === $country->code ? 'selected' : '' }}
                                    >
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error control-name="shipping_country" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.customers.signup-form.shipping-state')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_state"
                                rules="required"
                                :value="old('shipping_state')"
                                :label="trans('shop::app.customers.signup-form.shipping-state')"
                                :placeholder="trans('shop::app.customers.signup-form.shipping-state')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-state')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="shipping_state" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.customers.signup-form.shipping-city')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_city"
                                rules="required"
                                :value="old('shipping_city')"
                                :label="trans('shop::app.customers.signup-form.shipping-city')"
                                :placeholder="trans('shop::app.customers.signup-form.shipping-city')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-city')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="shipping_city" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.customers.signup-form.shipping-postcode')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                name="shipping_postcode"
                                rules="required"
                                :value="old('shipping_postcode')"
                                :label="trans('shop::app.customers.signup-form.shipping-postcode')"
                                :placeholder="trans('shop::app.customers.signup-form.shipping-postcode')"
                                :aria-label="trans('shop::app.customers.signup-form.shipping-postcode')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="shipping_postcode" />
                        </x-shop::form.control-group>
                    </div>

                    <!-- Password -->
                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.signup-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="password"
                            id="signup-password-field"
                            rules="required|min:6"
                            :value="old('password')"
                            :label="trans('shop::app.customers.signup-form.password')"
                            :placeholder="trans('shop::app.customers.signup-form.password')"
                            ref="password"
                            :aria-label="trans('shop::app.customers.signup-form.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />

                        <div class="bs-pw-strength">
                            <div class="bs-pw-bar" id="signup-bar1"></div>
                            <div class="bs-pw-bar" id="signup-bar2"></div>
                            <div class="bs-pw-bar" id="signup-bar3"></div>
                            <span class="bs-pw-label" id="signup-pw-label"></span>
                        </div>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.password.after') !!}

                    <!-- Confirm Password -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.confirm-pass')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('shop::app.customers.signup-form.password')"
                            :placeholder="trans('shop::app.customers.signup-form.confirm-pass')"
                            :aria-label="trans('shop::app.customers.signup-form.confirm-pass')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form.password_confirmation.after') !!}

                    <!-- Captcha -->
                    <div class="mt-5 flex select-none items-center gap-2">
                        <input
                            type="checkbox"
                            id="privacy_accepted"
                            name="privacy_accepted"
                            value="1"
                            class="h-4 w-4 rounded border-zinc-300 text-navyBlue focus:ring-navyBlue"
                        >

                        <label for="privacy_accepted" class="text-sm text-zinc-600">
                            I accept the privacy policy.
                        </label>
                    </div>

                    <x-shop::form.control-group.error control-name="privacy_accepted" />

                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <x-shop::form.control-group class="mt-5">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}

                            <x-shop::form.control-group.error control-name="g-recaptcha-response" />
                        </x-shop::form.control-group>
                    @endif

                    <!-- Subscribed Button -->
                    @if (core()->getConfigData('customer.settings.create_new_account_options.news_letter'))
                        <div class="mb-5 flex select-none items-center gap-1.5">
                            <input
                                type="checkbox"
                                name="is_subscribed"
                                id="is-subscribed"
                                class="peer hidden"
                            />

                            <label
                                class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                                for="is-subscribed"
                            ></label>

                            <label
                                class="cursor-pointer select-none text-base text-zinc-500 max-sm:text-sm ltr:pl-0 rtl:pr-0"
                                for="is-subscribed"
                            >
                                @lang('shop::app.customers.signup-form.subscribe-to-newsletter')
                            </label>
                        </div>
                    @endif

                    {!! view_render_event('bagisto.shop.customers.signup_form.newsletter_subscription.after') !!}

                    @if(
                        core()->getConfigData('general.gdpr.settings.enabled')
                        && core()->getConfigData('general.gdpr.agreement.enabled')
                    )
                        <div class="mb-2 flex select-none items-center gap-1.5">
                            <x-shop::form.control-group.control
                                type="checkbox"
                                name="agreement"
                                id="agreement"
                                value="0"
                                rules="required"
                                for="agreement"
                            />

                            <label
                                class="cursor-pointer select-none text-base text-zinc-500 max-sm:text-sm"
                                for="agreement"
                                v-pre
                            >
                                {{ core()->getConfigData('general.gdpr.agreement.agreement_label') }}
                            </label>

                            @if (core()->getConfigData('general.gdpr.agreement.agreement_content'))
                                <span
                                    class="cursor-pointer text-base text-navyBlue max-sm:text-sm"
                                    @click="$refs.termsModal.open()"
                                >
                                    @lang('shop::app.customers.signup-form.click-here')
                                </span>
                            @endif
                        </div>

                        <x-shop::form.control-group.error control-name="agreement" />
                    @endif

                    <div class="mt-8 flex flex-col gap-4">
                        <button
                            class="bs-btn-submit"
                            type="submit"
                        >
                            @lang('shop::app.customers.signup-form.button-title')
                        </button>

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                    </div>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

                </x-shop::form>

                <div class="bs-divider">
                    <div class="bs-divider-line"></div>
                    <span class="bs-divider-text">{{ __('or sign up with') }}</span>
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
                @lang('shop::app.customers.signup-form.footer', ['current_year'=> date('Y') ])
            </p>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

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
                function bind(scope) {
                    if (! scope || scope.dataset.bsPwBound) {
                        return;
                    }
                    scope.dataset.bsPwBound = '1';
                    scope.addEventListener('input', function (e) {
                        var t = e.target;
                        if (! t || (t.name !== 'password' && t.id !== 'signup-password-field')) {
                            return;
                        }
                        updatePwStrength(t.value);
                    });
                }
                document.addEventListener('DOMContentLoaded', function () {
                    bind(document.querySelector('.bs-signup-page'));
                });
            })();
        </script>
    @endpush

    <!-- Terms & Conditions Modal -->
    <x-shop::modal ref="termsModal">
        <x-slot:toggle></x-slot>

        <x-slot:header class="!p-5">
            <p>@lang('shop::app.customers.signup-form.terms-conditions')</p>
        </x-slot>

        <x-slot:content class="!p-5">
            <div class="max-h-[500px] overflow-auto">
                {!! core()->getConfigData('general.gdpr.agreement.agreement_content') !!}
            </div>
        </x-slot>
    </x-shop::modal>
</x-shop::layouts>
