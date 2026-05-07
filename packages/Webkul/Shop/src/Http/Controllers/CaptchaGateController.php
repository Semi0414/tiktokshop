<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Customer\Facades\Captcha;
use Webkul\Shop\Support\ShopRoutes;

class CaptchaGateController extends Controller
{
    /**
     * Show the privacy accept gate + captcha step.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $redirectUrl = $request->query('redirect');

        if (! is_string($redirectUrl) || trim($redirectUrl) === '') {
            $redirectUrl = route(ShopRoutes::publicEntryRouteName());
        }

        if (session()->get('captcha_gate_passed')) {
            return redirect()->to($redirectUrl);
        }

        return view('shop::captcha-gate.index', [
            'redirectUrl' => $redirectUrl,
        ]);
    }

    /**
     * Verify privacy accept + reCAPTCHA token.
     */
    public function verify(Request $request): RedirectResponse
    {
        $redirectUrl = $request->input('redirect');

        if (! is_string($redirectUrl) || trim($redirectUrl) === '') {
            $redirectUrl = route(ShopRoutes::publicEntryRouteName());
        }

        $rules = Captcha::getValidations([
            'privacy_accepted' => 'required|accepted',
        ]);

        $messages = Captcha::getValidationMessages();

        $request->validate($rules, $messages);

        session()->put('captcha_gate_passed', 1);
        session()->put('captcha_gate_passed_at', now()->toDateTimeString());

        return redirect()->to($redirectUrl);
    }
}
