{{-- Plain HTML registration — validation: red borders only (see sign-up.blade.php scripts) --}}
@php
    $bsInput = 'mb-1.5 w-full rounded-[10px] border border-[#e8e5df] bg-[#faf9f7] px-5 py-3.5 text-base text-[#1a1a1a] transition focus:border-[#10b981] focus:bg-white focus:outline-none focus:ring-[3px] focus:ring-[rgba(16,185,129,0.1)] max-sm:px-4 max-sm:py-2';
@endphp

{!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_referral_code">@lang('shop::app.customers.signup-form.referral-code')</label>
    <p class="mb-2 text-sm text-zinc-500">@lang('shop::app.customers.signup-form.referral-code-hint')</p>
    <input id="reg_referral_code" type="text" name="referral_code" value="{{ old('referral_code') }}" required autocomplete="off" class="{{ $bsInput }} uppercase" placeholder="@lang('shop::app.customers.signup-form.referral-code')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_first_name">@lang('shop::app.customers.signup-form.first-name')</label>
    <input id="reg_first_name" type="text" name="first_name" value="{{ old('first_name') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.first-name')">
</div>

{!! view_render_event('bagisto.shop.customers.signup_form.first_name.after') !!}

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_last_name">@lang('shop::app.customers.signup-form.last-name')</label>
    <input id="reg_last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.last-name')">
</div>

{!! view_render_event('bagisto.shop.customers.signup_form.last_name.after') !!}

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_email">@lang('shop::app.customers.signup-form.email')</label>
    <input id="reg_email" type="email" name="email" value="{{ old('email') }}" required class="{{ $bsInput }}" placeholder="email@example.com" autocomplete="email">
</div>

{!! view_render_event('bagisto.shop.customers.signup_form.email.after') !!}

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_phone">@lang('shop::app.customers.signup-form.phone')</label>
    <input id="reg_phone" type="text" name="phone" value="{{ old('phone') }}" required maxlength="20" autocomplete="tel" class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.phone')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium" for="reg_gender">Gender</label>
    <select id="reg_gender" name="gender" class="{{ $bsInput }}">
        <option value="">Select</option>
        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
        <option value="Other" @selected(old('gender') === 'Other')>Other</option>
    </select>
</div>

<div class="mb-4 bs-reg-highlight-target rounded-lg p-0.5">
    <label class="mb-1 block text-sm font-medium" for="signup-dob">Date of Birth</label>
    @php
        $signupDobMax = \Illuminate\Support\Carbon::yesterday()->format('Y-m-d');
    @endphp
    <input
        id="signup-dob"
        type="date"
        name="date_of_birth"
        value="{{ old('date_of_birth') }}"
        max="{{ $signupDobMax }}"
        autocomplete="bday"
        class="{{ $bsInput }}"
    >
</div>

<h2 class="bs-section-title">@lang('shop::app.customers.signup-form.shipping-section')</h2>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_ship1">@lang('shop::app.customers.signup-form.shipping-address1')</label>
    <input id="reg_ship1" type="text" name="shipping_address1" value="{{ old('shipping_address1') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.shipping-address1')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium" for="reg_ship2">@lang('shop::app.customers.signup-form.shipping-address2')</label>
    <input id="reg_ship2" type="text" name="shipping_address2" value="{{ old('shipping_address2') }}" class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.shipping-address2')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_country">@lang('shop::app.customers.signup-form.shipping-country')</label>
    <select id="reg_country" name="shipping_country" required class="{{ $bsInput }}">
        <option value="">@lang('shop::app.customers.signup-form.select-country')</option>
        @foreach (core()->countries() as $country)
            <option value="{{ $country->code }}" @selected(old('shipping_country') === $country->code)>{{ $country->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_state">@lang('shop::app.customers.signup-form.shipping-state')</label>
    <input id="reg_state" type="text" name="shipping_state" value="{{ old('shipping_state') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.shipping-state')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_city">@lang('shop::app.customers.signup-form.shipping-city')</label>
    <input id="reg_city" type="text" name="shipping_city" value="{{ old('shipping_city') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.shipping-city')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_post">@lang('shop::app.customers.signup-form.shipping-postcode')</label>
    <input id="reg_post" type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" required class="{{ $bsInput }}" placeholder="@lang('shop::app.customers.signup-form.shipping-postcode')">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_password">@lang('shop::app.customers.signup-form.password')</label>
    <div class="relative">
        <input id="reg_password" type="password" name="password" required minlength="6" autocomplete="new-password" class="{{ $bsInput }} pr-12" placeholder="@lang('shop::app.customers.signup-form.password')">
        <button type="button" class="absolute end-2 top-1/2 z-[1] -translate-y-1/2 rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800" data-bs-pw-toggle="#reg_password" aria-label="{{ __('Show or hide password') }}">
            <span class="icon-eye text-2xl" aria-hidden="true"></span>
        </button>
    </div>
    <div class="bs-pw-strength">
        <div class="bs-pw-bar" id="signup-bar1"></div>
        <div class="bs-pw-bar" id="signup-bar2"></div>
        <div class="bs-pw-bar" id="signup-bar3"></div>
        <span class="bs-pw-label" id="signup-pw-label"></span>
    </div>
</div>

{!! view_render_event('bagisto.shop.customers.signup_form.password.after') !!}

<div class="mb-6">
    <label class="mb-1 block text-sm font-medium required" for="reg_password_confirmation">@lang('shop::app.customers.signup-form.confirm-pass')</label>
    <div class="relative">
        <input id="reg_password_confirmation" type="password" name="password_confirmation" required minlength="6" autocomplete="new-password" class="{{ $bsInput }} pr-12" placeholder="@lang('shop::app.customers.signup-form.confirm-pass')">
        <button type="button" class="absolute end-2 top-1/2 z-[1] -translate-y-1/2 rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800" data-bs-pw-toggle="#reg_password_confirmation" aria-label="{{ __('Show or hide password') }}">
            <span class="icon-eye text-2xl" aria-hidden="true"></span>
        </button>
    </div>
</div>

{!! view_render_event('bagisto.shop.customers.signup_form.password_confirmation.after') !!}

<div class="bs-reg-highlight-target mt-5 flex select-none items-center gap-2 rounded-lg p-0.5">
    <input type="checkbox" id="privacy_accepted" name="privacy_accepted" value="1" class="h-4 w-4 shrink-0 rounded border-zinc-300" {{ old('privacy_accepted') ? 'checked' : '' }} required>
    <label for="privacy_accepted" class="text-sm text-zinc-600">I accept the privacy policy.</label>
</div>

@if (core()->getConfigData('customer.captcha.credentials.status'))
    <div id="bs-wrap-captcha" class="bs-reg-highlight-target mt-5 rounded-lg p-0.5">
        {!! \Webkul\Customer\Facades\Captcha::render() !!}
    </div>
@endif

{{-- Newsletter subscription checkbox hidden per product request --}}

{!! view_render_event('bagisto.shop.customers.signup_form.newsletter_subscription.after') !!}

@if (core()->getConfigData('general.gdpr.settings.enabled') && core()->getConfigData('general.gdpr.agreement.enabled'))
    <div class="mb-2 mt-4 flex select-none items-center gap-1.5">
        <input type="checkbox" name="agreement" id="agreement" value="1" class="h-4 w-4 rounded border-zinc-300" @checked(old('agreement'))>
        <label for="agreement" class="cursor-pointer select-none text-base text-zinc-500 max-sm:text-sm">
            {{ core()->getConfigData('general.gdpr.agreement.agreement_label') }}
        </label>
        @if (core()->getConfigData('general.gdpr.agreement.agreement_content'))
            <button type="button" class="cursor-pointer border-0 bg-transparent p-0 text-base text-navyBlue underline max-sm:text-sm" onclick="document.getElementById('signup-terms-dlg')?.showModal()">
                @lang('shop::app.customers.signup-form.click-here')
            </button>
        @endif
    </div>
@endif

<div class="mt-8 flex flex-col gap-4">
    <button class="bs-btn-submit" type="submit" id="reg-submit-btn">
        @lang('shop::app.customers.signup-form.button-title')
    </button>
    {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
</div>

{!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}
