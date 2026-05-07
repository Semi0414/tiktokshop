<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Webkul\User\Models\Admin;

/**
 * Consumes one-time encrypted ?spv= token (no raw seller id in URL), stores preview in session, then redirects to a clean URL.
 */
class ResolveSellerPreviewToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->filled('spv')) {
            return $next($request);
        }

        $raw = (string) $request->query('spv');

        try {
            $plain = Crypt::decryptString(rawurldecode($raw));
            $data = json_decode($plain, true);

            if (! is_array($data) || empty($data['sid']) || empty($data['exp'])) {
                throw new \InvalidArgumentException;
            }

            $sellerId = (int) $data['sid'];
            $expiresAt = (int) $data['exp'];

            if ($sellerId < 1 || $expiresAt < now()->timestamp) {
                throw new \InvalidArgumentException;
            }

            if (! Admin::query()->whereKey($sellerId)->exists()) {
                throw new \InvalidArgumentException;
            }

            session([
                'seller_preview_id' => $sellerId,
                'seller_preview_expires_at' => $expiresAt,
            ]);

            if ($request->expectsJson()) {
                return $next($request);
            }

            return redirect()->to($request->url());
        } catch (\Throwable) {
            session()->forget(['seller_preview_id', 'seller_preview_expires_at']);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('shop::app.seller-preview.invalid-link'),
                ], 422);
            }

            return redirect()->to($request->url())
                ->with('error', trans('shop::app.seller-preview.invalid-link'));
        }
    }
}
