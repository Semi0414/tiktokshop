<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legacy TikTok-style storefront copy
    |--------------------------------------------------------------------------
    |
    | When true: restores previous "TikTok Shop" / "TikStore" wording and layout
    | cues (high risk of abuse/phishing reports — use only with legal clearance).
    |
    | When false (default): compliant branding using marketplace_name and APP_NAME.
    |
    */
    'legacy_tiktok_branding' => filter_var(
        env('STOREFRONT_LEGACY_TIKTOK_BRANDING', false),
        FILTER_VALIDATE_BOOLEAN
    ),

    /*
    |--------------------------------------------------------------------------
    | Public marketplace name (safe mode)
    |--------------------------------------------------------------------------
    |
    | Shown on landing, join, and storefront headers when legacy mode is off.
    | Falls back to APP_NAME.
    |
    */
    'marketplace_name' => env('STOREFRONT_MARKETPLACE_NAME') ?: env('APP_NAME', 'Marketplace'),

];
