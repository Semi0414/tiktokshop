<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CaptchaGateController;

Route::get('captcha-gate', [CaptchaGateController::class, 'index'])
    ->name('shop.captcha-gate.index');

Route::post('captcha-gate/verify', [CaptchaGateController::class, 'verify'])
    ->name('shop.captcha-gate.verify');
