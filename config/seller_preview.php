<?php

return [

    'token_ttl_seconds' => (int) env('SELLER_PREVIEW_TOKEN_TTL', 7200),

    /**
     * When true, the storefront /home page only loads if a valid seller preview exists in session
     * (set by encrypted ?spv= from Super Admin "Visit store"). Without it, users are sent to landing.
     */
    'restrict_home_to_seller_preview' => filter_var(
        env('SELLER_PREVIEW_RESTRICT_HOME', false),
        FILTER_VALIDATE_BOOLEAN
    ),

];
