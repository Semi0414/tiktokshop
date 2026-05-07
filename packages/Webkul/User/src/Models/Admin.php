<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Webkul\Admin\Mail\Admin\ResetPasswordNotification;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\User\Contracts\Admin as AdminContract;
use Webkul\User\Database\Factories\AdminFactory;

class Admin extends Authenticatable implements AdminContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Seller accounts are stored in `seller` table.
     */
    protected $table = 'seller';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'api_token',
        'role_id',
        'status',
        'wallet_balance',
        'credit_score',
        'allowed_product_ids',
        'max_visible_products',
        'seller_approval_status',
        'referral_code',
        'seller_level',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'allowed_product_ids' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * Get image url for the product image.
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the product image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['image_url'] = $this->image_url;

        return $array;
    }

    /**
     * Get the role that owns the admin.
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(RoleProxy::modelClass());
    }

    /**
     * Orders assigned to this seller (seller panel / super-admin).
     */
    public function sellerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Invoices for orders assigned to this seller.
     */
    public function sellerInvoices(): HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Order::class, 'seller_id', 'order_id', 'id', 'id');
    }

    public function sellerNotes(): HasMany
    {
        return $this->hasMany(SellerNoteProxy::modelClass(), 'seller_id');
    }

    /**
     * Products this seller added from the warehouse to their store (with commission / recommended flags).
     */
    public function sellerStoreProducts(): HasMany
    {
        return $this->hasMany(SellerStoreProduct::class, 'seller_id');
    }

    /**
     * Checks if admin has permission to perform certain action.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (
            $this->role->permission_type == 'custom'
            && ! $this->role->permissions
        ) {
            return false;
        }

        return in_array($permission, $this->role->permissions);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return AdminFactory::new();
    }

    /**
     * Seller row is enabled: Bagisto uses `status` 1 = active, 0 = inactive.
     */
    public function isSellerAccountActive(): bool
    {
        return (int) ($this->status ?? 0) === 1;
    }

    /**
     * Seller storefront preview lists only products in {@see sellerStoreProducts()} (not full catalog).
     */
    public function sellerCatalogIsRestricted(): bool
    {
        return true;
    }

    /**
     * Product IDs in this seller's store for the current channel/locale, ordered by recommended first.
     *
     * @return list<int>
     */
    public function resolveVisibleProductIds(): array
    {
        $locale = app()->getLocale();
        $channel = core()->getCurrentChannelCode();

        return DB::table('seller_store_products as ssp')
            ->join('product_flat as pf', 'ssp.product_id', '=', 'pf.product_id')
            ->where('ssp.seller_id', $this->id)
            ->where('pf.locale', $locale)
            ->where('pf.channel', $channel)
            ->where('pf.sku', 'like', 'TG-%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->orderByDesc('ssp.is_recommended')
            ->orderBy('pf.product_id')
            ->pluck('ssp.product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    public function canAccessProductId(int $productId): bool
    {
        if (! $this->sellerCatalogIsRestricted()) {
            return true;
        }

        return in_array($productId, $this->resolveVisibleProductIds(), true);
    }
}
