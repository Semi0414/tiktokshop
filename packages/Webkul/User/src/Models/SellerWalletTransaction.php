<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'seller_wallet_transactions';

    protected $fillable = [
        'seller_id',
        'amount',
        'type',
        'status',
        'kind',
        'payment_method',
        'meta',
        'receipt_path',
        'description',
        'order_id',
        'balance_before',
        'balance_after',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_REJECTED = 'rejected';

    public const KIND_DEPOSIT_REQUEST = 'deposit_request';

    public const KIND_WITHDRAW_REQUEST = 'withdraw_request';

    /**
     * Credit when Super Admin rejects a pending withdraw (funds returned to seller).
     */
    public const KIND_WITHDRAW_REJECTION_REFUND = 'withdraw_rejection_refund';

    /**
     * Credit when Super Admin rejects a seller order (refund {@see KIND_SELLER_PURCHASE} hold).
     */
    public const KIND_ORDER_REJECTION_REFUND = 'order_rejection_refund';

    public const KIND_SELLER_PURCHASE = 'seller_purchase';

    public const KIND_ORDER_COMMISSION = 'order_commission';

    /**
     * Super Admin approved seller visibility + credited order total + commission to seller wallet.
     */
    public const KIND_ORDER_REVENUE_APPROVAL = 'order_revenue_approval';

    /**
     * Super Admin marked order completed; credited order total + commission (same label as order revenue in seller wallet).
     */
    public const KIND_ORDER_REVENUE = 'order_revenue';

    public const KIND_LEGACY = 'legacy';

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'seller_id');
    }
}
