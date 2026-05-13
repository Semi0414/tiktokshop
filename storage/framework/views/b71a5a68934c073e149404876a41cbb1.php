
<?php
    $bsInput = 'mb-1.5 w-full rounded-[10px] border border-[#e8e5df] bg-[#faf9f7] px-5 py-3.5 text-base text-[#1a1a1a] transition focus:border-[#10b981] focus:bg-white focus:outline-none focus:ring-[3px] focus:ring-[rgba(16,185,129,0.1)] max-sm:px-4 max-sm:py-2';
?>

<?php echo view_render_event('bagisto.shop.customers.signup_form_controls.before'); ?>


<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_referral_code"><?php echo app('translator')->get('shop::app.customers.signup-form.referral-code'); ?></label>
    <p class="mb-2 text-sm text-zinc-500"><?php echo app('translator')->get('shop::app.customers.signup-form.referral-code-hint'); ?></p>
    <input id="reg_referral_code" type="text" name="referral_code" value="<?php echo e(old('referral_code')); ?>" required autocomplete="off" class="<?php echo e($bsInput); ?> uppercase" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.referral-code'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_first_name"><?php echo app('translator')->get('shop::app.customers.signup-form.first-name'); ?></label>
    <input id="reg_first_name" type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.first-name'); ?>">
</div>

<?php echo view_render_event('bagisto.shop.customers.signup_form.first_name.after'); ?>


<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_last_name"><?php echo app('translator')->get('shop::app.customers.signup-form.last-name'); ?></label>
    <input id="reg_last_name" type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.last-name'); ?>">
</div>

<?php echo view_render_event('bagisto.shop.customers.signup_form.last_name.after'); ?>


<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_email"><?php echo app('translator')->get('shop::app.customers.signup-form.email'); ?></label>
    <input id="reg_email" type="email" name="email" value="<?php echo e(old('email')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="email@example.com" autocomplete="email">
</div>

<?php echo view_render_event('bagisto.shop.customers.signup_form.email.after'); ?>


<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_phone"><?php echo app('translator')->get('shop::app.customers.signup-form.phone'); ?></label>
    <input id="reg_phone" type="text" name="phone" value="<?php echo e(old('phone')); ?>" required maxlength="20" autocomplete="tel" class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.phone'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium" for="reg_gender">Gender</label>
    <select id="reg_gender" name="gender" class="<?php echo e($bsInput); ?>">
        <option value="">Select</option>
        <option value="Male" <?php if(old('gender') === 'Male'): echo 'selected'; endif; ?>>Male</option>
        <option value="Female" <?php if(old('gender') === 'Female'): echo 'selected'; endif; ?>>Female</option>
        <option value="Other" <?php if(old('gender') === 'Other'): echo 'selected'; endif; ?>>Other</option>
    </select>
</div>

<div class="mb-4 bs-reg-highlight-target rounded-lg p-0.5">
    <label class="mb-1 block text-sm font-medium" for="signup-dob">Date of Birth</label>
    <?php
        $signupDobMax = \Illuminate\Support\Carbon::yesterday()->format('Y-m-d');
    ?>
    <input
        id="signup-dob"
        type="date"
        name="date_of_birth"
        value="<?php echo e(old('date_of_birth')); ?>"
        max="<?php echo e($signupDobMax); ?>"
        autocomplete="bday"
        class="<?php echo e($bsInput); ?>"
    >
</div>

<h2 class="bs-section-title"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-section'); ?></h2>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_ship1"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-address1'); ?></label>
    <input id="reg_ship1" type="text" name="shipping_address1" value="<?php echo e(old('shipping_address1')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.shipping-address1'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium" for="reg_ship2"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-address2'); ?></label>
    <input id="reg_ship2" type="text" name="shipping_address2" value="<?php echo e(old('shipping_address2')); ?>" class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.shipping-address2'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_country"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-country'); ?></label>
    <select id="reg_country" name="shipping_country" required class="<?php echo e($bsInput); ?>">
        <option value=""><?php echo app('translator')->get('shop::app.customers.signup-form.select-country'); ?></option>
        <?php $__currentLoopData = core()->countries(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($country->code); ?>" <?php if(old('shipping_country') === $country->code): echo 'selected'; endif; ?>><?php echo e($country->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_state"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-state'); ?></label>
    <input id="reg_state" type="text" name="shipping_state" value="<?php echo e(old('shipping_state')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.shipping-state'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_city"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-city'); ?></label>
    <input id="reg_city" type="text" name="shipping_city" value="<?php echo e(old('shipping_city')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.shipping-city'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_post"><?php echo app('translator')->get('shop::app.customers.signup-form.shipping-postcode'); ?></label>
    <input id="reg_post" type="text" name="shipping_postcode" value="<?php echo e(old('shipping_postcode')); ?>" required class="<?php echo e($bsInput); ?>" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.shipping-postcode'); ?>">
</div>

<div class="mb-4">
    <label class="mb-1 block text-sm font-medium required" for="reg_password"><?php echo app('translator')->get('shop::app.customers.signup-form.password'); ?></label>
    <div class="relative">
        <input id="reg_password" type="password" name="password" required minlength="6" autocomplete="new-password" class="<?php echo e($bsInput); ?> pr-12" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.password'); ?>">
        <button type="button" class="absolute end-2 top-1/2 z-[1] -translate-y-1/2 rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800" data-bs-pw-toggle="#reg_password" aria-label="<?php echo e(__('Show or hide password')); ?>">
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

<?php echo view_render_event('bagisto.shop.customers.signup_form.password.after'); ?>


<div class="mb-6">
    <label class="mb-1 block text-sm font-medium required" for="reg_password_confirmation"><?php echo app('translator')->get('shop::app.customers.signup-form.confirm-pass'); ?></label>
    <div class="relative">
        <input id="reg_password_confirmation" type="password" name="password_confirmation" required minlength="6" autocomplete="new-password" class="<?php echo e($bsInput); ?> pr-12" placeholder="<?php echo app('translator')->get('shop::app.customers.signup-form.confirm-pass'); ?>">
        <button type="button" class="absolute end-2 top-1/2 z-[1] -translate-y-1/2 rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800" data-bs-pw-toggle="#reg_password_confirmation" aria-label="<?php echo e(__('Show or hide password')); ?>">
            <span class="icon-eye text-2xl" aria-hidden="true"></span>
        </button>
    </div>
</div>

<?php echo view_render_event('bagisto.shop.customers.signup_form.password_confirmation.after'); ?>


<div class="bs-reg-highlight-target mt-5 flex select-none items-center gap-2 rounded-lg p-0.5">
    <input type="checkbox" id="privacy_accepted" name="privacy_accepted" value="1" class="h-4 w-4 shrink-0 rounded border-zinc-300" <?php echo e(old('privacy_accepted') ? 'checked' : ''); ?> required>
    <label for="privacy_accepted" class="text-sm text-zinc-600">I accept the privacy policy.</label>
</div>

<?php if(core()->getConfigData('customer.captcha.credentials.status')): ?>
    <div id="bs-wrap-captcha" class="bs-reg-highlight-target mt-5 rounded-lg p-0.5">
        <?php echo \Webkul\Customer\Facades\Captcha::render(); ?>

    </div>
<?php endif; ?>



<?php echo view_render_event('bagisto.shop.customers.signup_form.newsletter_subscription.after'); ?>


<?php if(core()->getConfigData('general.gdpr.settings.enabled') && core()->getConfigData('general.gdpr.agreement.enabled')): ?>
    <div class="mb-2 mt-4 flex select-none items-center gap-1.5">
        <input type="checkbox" name="agreement" id="agreement" value="1" class="h-4 w-4 rounded border-zinc-300" <?php if(old('agreement')): echo 'checked'; endif; ?>>
        <label for="agreement" class="cursor-pointer select-none text-base text-zinc-500 max-sm:text-sm">
            <?php echo e(core()->getConfigData('general.gdpr.agreement.agreement_label')); ?>

        </label>
        <?php if(core()->getConfigData('general.gdpr.agreement.agreement_content')): ?>
            <button type="button" class="cursor-pointer border-0 bg-transparent p-0 text-base text-navyBlue underline max-sm:text-sm" onclick="document.getElementById('signup-terms-dlg')?.showModal()">
                <?php echo app('translator')->get('shop::app.customers.signup-form.click-here'); ?>
            </button>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="mt-8 flex flex-col gap-4">
    <button class="bs-btn-submit" type="submit" id="reg-submit-btn">
        <?php echo app('translator')->get('shop::app.customers.signup-form.button-title'); ?>
    </button>
    <?php echo view_render_event('bagisto.shop.customers.login_form_controls.after'); ?>

</div>

<?php echo view_render_event('bagisto.shop.customers.signup_form_controls.after'); ?>

<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/customers/partials/sign-up-native-form.blade.php ENDPATH**/ ?>