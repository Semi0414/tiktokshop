<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCaptchaGate
{
    /**
     * Handle an incoming request.
     *
     * @return RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->get('captcha_gate_passed')) {
            return $next($request);
        }

        $redirectUrl = url()->current();

        return redirect()->route('shop.captcha-gate.index', [
            'redirect' => $redirectUrl,
        ]);
    }
}
