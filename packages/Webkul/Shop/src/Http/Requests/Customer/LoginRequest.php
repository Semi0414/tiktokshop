<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Customer\Facades\Captcha;

class LoginRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'email' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $v = trim((string) $value);

                    if (filter_var($v, FILTER_VALIDATE_EMAIL)) {
                        return;
                    }

                    $digits = preg_replace('/\D/', '', $v);

                    if (strlen($digits) >= 10) {
                        return;
                    }

                    $fail(trans('validation.email'));
                },
            ],
            'password' => 'required|min:6',
            'privacy_accepted' => 'required|accepted',
        ];

        return Captcha::getValidations($rules);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return Captcha::getValidationMessages();
    }
}
