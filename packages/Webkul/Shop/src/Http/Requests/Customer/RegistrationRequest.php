<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Facades\Captcha;
use Webkul\SuperAdmin\Services\AdminReferralCodeService;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (class_exists(AdminReferralCodeService::class)) {
            app(AdminReferralCodeService::class)->ensureExists();
        }

        $this->merge([
            'referral_code' => strtoupper(trim((string) $this->input('referral_code', ''))),
            'phone' => trim((string) $this->input('phone', '')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $channelId = core()->getCurrentChannel()->id;

        $rules = [
            'referral_code' => [
                'required',
                'string',
                'max:32',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $code = strtoupper(trim((string) $value));

                    $adminCode = strtoupper(trim((string) CoreConfig::query()
                        ->where('code', 'general.superadmin.referral_code')
                        ->value('value')));

                    if ($adminCode !== '' && $code === $adminCode) {
                        return;
                    }

                    if (! DB::table('seller')->where('referral_code', $code)->exists()) {
                        $fail(trans('shop::app.customers.signup-form.referral-invalid'));
                    }
                },
            ],
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required|unique:customers,email,NULL,id,channel_id,'.$channelId,
            'password' => 'confirmed|min:6|required',
            'privacy_accepted' => 'required|accepted',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date|before:today',
            'shipping_address1' => 'required|string|max:255',
            'shipping_address2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_country' => 'required|string|max:255',
            'shipping_postcode' => 'required|string|max:20',
        ];

        return Captcha::getValidations($rules);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return Captcha::getValidationMessages([
            'referral_code.exists' => trans('shop::app.customers.signup-form.referral-invalid'),
        ]);
    }

    /**
     * Custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'referral_code' => trans('shop::app.customers.signup-form.referral-code'),
            'shipping_address1' => trans('shop::app.customers.signup-form.shipping-address1'),
            'shipping_address2' => trans('shop::app.customers.signup-form.shipping-address2'),
            'shipping_city' => trans('shop::app.customers.signup-form.shipping-city'),
            'shipping_state' => trans('shop::app.customers.signup-form.shipping-state'),
            'shipping_country' => trans('shop::app.customers.signup-form.shipping-country'),
            'shipping_postcode' => trans('shop::app.customers.signup-form.shipping-postcode'),
        ];
    }
}
