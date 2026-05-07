<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Customer\Models\Customer;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\LoginRequest;

class SessionController extends Controller
{
    /**
     * Display the resource.
     *
     * @return RedirectResponse|View
     */
    public function index()
    {
        if (auth()->guard('customer')->check()) {
            $redirectUrl = request()->get('redirect_url');

            if ($redirectUrl) {
                return redirect($redirectUrl);
            }

            return redirect()->route('shop.tiktok-store.index');
        }

        return view('shop::customers.sign-in', [
            'redirectUrl' => request()->get('redirect_url'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(LoginRequest $loginRequest)
    {
        $identifier = trim((string) $loginRequest->input('email'));
        $channelId = core()->getCurrentChannel()->id;

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $email = $identifier;
        } else {
            $customer = Customer::query()
                ->where('channel_id', $channelId)
                ->where('phone', $identifier)
                ->first();

            if (! $customer) {
                session()->flash('error', trans('shop::app.customers.login-form.invalid-credentials'));

                return redirect()->back()->withInput($loginRequest->except('password'));
            }

            $email = $customer->email;
        }

        $credentials = [
            'email' => $email,
            'password' => $loginRequest->input('password'),
            'channel_id' => $channelId,
        ];

        if (! auth()->guard('customer')->attempt($credentials)) {
            session()->flash('error', trans('shop::app.customers.login-form.invalid-credentials'));

            return redirect()->back()->withInput($loginRequest->except('password'));
        }

        if (! auth()->guard('customer')->user()->status) {
            auth()->guard('customer')->logout();

            session()->flash('warning', trans('shop::app.customers.login-form.not-activated'));

            return redirect()->back()->withInput($loginRequest->except('password'));
        }

        if (! auth()->guard('customer')->user()->is_verified) {
            session()->flash('info', trans('shop::app.customers.login-form.verify-first'));

            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', $email, 1));

            auth()->guard('customer')->logout();

            return redirect()->back()->withInput($loginRequest->except('password'));
        }

        // Gate state: once per session.
        session()->put('captcha_gate_passed', 1);

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', auth()->guard()->user());

        $redirectUrl = request()->get('redirect_url');

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        if (core()->getConfigData('customer.settings.login_options.redirected_to_page') == 'account') {
            return redirect()->route('shop.customers.account.profile.index');
        }

        return redirect()->route('shop.tiktok-store.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        $id = auth()->guard('customer')->user()->id;

        auth()->guard('customer')->logout();

        Event::dispatch('customer.after.logout', $id);

        return redirect()->route('shop.tiktok-store.index');
    }
}
