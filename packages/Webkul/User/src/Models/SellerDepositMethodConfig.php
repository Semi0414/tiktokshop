<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDepositMethodConfig extends Model
{
    protected $table = 'seller_deposit_method_configs';

    protected $fillable = [
        'code',
        'name',
        'address_text',
        'network',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
