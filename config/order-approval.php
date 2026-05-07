<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Seller Credit Score Requirement
    |--------------------------------------------------------------------------
    |
    | Minimum seller credit score required before an order can be approved.
    | Default 100 means only full score sellers can approve orders.
    |
    */
    'min_credit_score' => (int) env('ORDER_APPROVAL_MIN_CREDIT_SCORE', 100),

    /*
    |--------------------------------------------------------------------------
    | Wallet Balance Buffer Percent
    |--------------------------------------------------------------------------
    |
    | Additional wallet buffer percentage on top of order total.
    | Example: 10 means seller must have order_total + 10%.
    |
    */
    'wallet_buffer_percent' => (float) env('ORDER_APPROVAL_WALLET_BUFFER_PERCENT', 0),
];
