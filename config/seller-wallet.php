<?php

/**
 * Seller wallet deposit: maps `seller_deposit_method_configs.code` to
 * `crypto_payout_addresses.network_type` (Super Admin → Settings → Crypto addresses).
 * Adjust keys if your DB uses different codes or you add custom network types.
 */
return [

    'deposit_method_to_crypto_network' => [
        'USDT' => 'trc20_usdt',
        'ETH' => 'eth',
        'BTC' => 'btc',
        'USDC' => 'other',
    ],

    'bank_deposit_method_codes' => [
        'BANK_CARD',
    ],
];
