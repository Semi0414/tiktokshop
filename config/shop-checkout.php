<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Skip inventory and catalog purchase limits (customer storefront)
    |--------------------------------------------------------------------------
    |
    | When true, stock/quantity checks and add-to-cart inventory validation are
    | bypassed so customers can place orders regardless of on-hand inventory
    | or multiple-buy rules. Shipping address, credit score, and wallet
    | balance checks in the shop checkout API remain enforced.
    |
    */
    'skip_inventory_and_purchase_limits' => env('SHOP_SKIP_INVENTORY_CHECKS', true),

];
