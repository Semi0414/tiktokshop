<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Webkul\Admin\Http\Controllers\Controller;

class SellerVisitStoreController extends Controller
{
    /**
     * Redirect to TikStore with encrypted preview token so pending sellers can preview (session resolves seller).
     */
    public function __invoke(): RedirectResponse
    {
        $seller = auth()->guard('admin')->user();

        if (! $seller) {
            abort(403);
        }

        $ttlSeconds = max(300, (int) config('seller_preview.token_ttl_seconds', 7200));

        $payload = json_encode([
            'sid' => (int) $seller->id,
            'exp' => now()->addSeconds($ttlSeconds)->timestamp,
        ], JSON_THROW_ON_ERROR);

        $token = Crypt::encryptString($payload);

        $channel = core()->getCurrentChannel();
        $base = rtrim((string) config('app.url'), '/');
        $host = trim((string) $channel->hostname);

        if ($host !== '' && preg_match('#^https?://#i', $host)) {
            $candidate = rtrim($host, '/');
            $hostname = strtolower((string) parse_url($candidate, PHP_URL_HOST));
            $isLoopback = in_array($hostname, ['localhost', '127.0.0.1', '::1'], true);

            if (! $isLoopback) {
                $base = $candidate;
            }
        }

        URL::forceRootUrl($base);

        return redirect()->route('shop.tik-store.index', ['spv' => $token]);
    }
}
