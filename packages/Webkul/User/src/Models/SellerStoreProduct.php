<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\ProductProxy as ProductModelProxy;
use Webkul\User\Contracts\SellerStoreProduct as SellerStoreProductContract;

class SellerStoreProduct extends Model implements SellerStoreProductContract
{
    protected $table = 'seller_store_products';

    protected $fillable = [
        'seller_id',
        'product_id',
        'commission_percent',
        'is_recommended',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'is_recommended' => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(AdminProxy::modelClass(), 'seller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModelProxy::modelClass(), 'product_id');
    }
}
