<?php

namespace Webkul\SuperAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPayoutAddress extends Model
{
    protected $table = 'crypto_payout_addresses';

    protected $fillable = [
        'network_type',
        'address',
        'label',
    ];
}
