<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Webkul\User\Models\Admin;

class VisitSellerController extends Controller
{
    /**
     * Start a seller storefront preview session (same encrypted token flow as seller panel "Visit store").
     */
    public function __invoke(Admin $seller): RedirectResponse
    {
        if ((int) $seller->status !== 1) {
            abort(404);
        }

        $ttlSeconds = max(300, (int) config('seller_preview.token_ttl_seconds', 7200));

        $payload = json_encode([
            'sid' => (int) $seller->id,
            'exp' => now()->addSeconds($ttlSeconds)->timestamp,
        ], JSON_THROW_ON_ERROR);

        $token = Crypt::encryptString($payload);

        return redirect()->route('shop.tiktok-store.index', ['spv' => $token]);
    }
}
