<?php

namespace Webkul\Shop\Support;

/**
 * Seller preview from session (encrypted ?spv= token consumed by ResolveSellerPreviewToken).
 */
class SellerPreview
{
    /**
     * Valid seller admin id from session, or 0 if missing/expired.
     */
    public static function resolveSellerIdFromSession(): int
    {
        if (! session()->has('seller_preview_id')) {
            return 0;
        }

        $expiresAt = (int) (session('seller_preview_expires_at') ?? 0);

        if ($expiresAt < now()->timestamp) {
            session()->forget(['seller_preview_id', 'seller_preview_expires_at']);

            return 0;
        }

        return max(0, (int) session('seller_preview_id'));
    }

    public static function hasValidSession(): bool
    {
        return self::resolveSellerIdFromSession() > 0;
    }
}
