<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\User\Contracts\SellerNote as SellerNoteContract;

class SellerNote extends Model implements SellerNoteContract
{
    protected $table = 'seller_notes';

    protected $fillable = [
        'seller_id',
        'note',
        'seller_notified',
    ];

    protected $casts = [
        'seller_notified' => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'seller_id');
    }
}
