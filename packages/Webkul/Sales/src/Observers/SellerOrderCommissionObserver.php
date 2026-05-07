<?php

namespace Webkul\Sales\Observers;

use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerStoreProduct;
use Webkul\User\Models\SellerWalletTransaction;

class SellerOrderCommissionObserver
{
    /**
     * Expected commission for an order (same rules as payout on completion).
     */
    public static function calculateCommissionTotal(Order $order): float
    {
        $order->loadMissing('items');

        $commissionTotal = 0.0;
        foreach ($order->items as $item) {
            if ($item->parent_id) {
                continue;
            }
            $ssp = SellerStoreProduct::query()
                ->where('seller_id', $order->seller_id)
                ->where('product_id', $item->product_id)
                ->first();
            if ($ssp) {
                $commissionTotal += round(((float) $item->base_total) * ((float) $ssp->commission_percent / 100), 2);
            }
        }

        return $commissionTotal;
    }

    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status') || $order->status !== Order::STATUS_COMPLETED) {
            return;
        }

        if (! $order->seller_id || ! $order->seller_make_order_at || $order->seller_commission_credited) {
            return;
        }

        $seller = Admin::query()->find($order->seller_id);
        if (! $seller) {
            return;
        }

        $commissionTotal = self::calculateCommissionTotal($order);

        if ($commissionTotal <= 0) {
            $order->seller_commission_credited = true;
            $order->saveQuietly();

            return;
        }

        $balanceBefore = (float) ($seller->wallet_balance ?? 0);
        $balanceAfter = $balanceBefore + $commissionTotal;

        SellerWalletTransaction::create([
            'seller_id' => $seller->id,
            'amount' => $commissionTotal,
            'type' => 'credit',
            'status' => SellerWalletTransaction::STATUS_COMPLETED,
            'kind' => SellerWalletTransaction::KIND_ORDER_COMMISSION,
            'order_id' => $order->id,
            'description' => __('admin::app.seller.shop-order.commission-desc', ['increment' => $order->increment_id]),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        $seller->wallet_balance = $balanceAfter;
        $seller->save();

        $order->seller_commission_credited = true;
        $order->saveQuietly();
    }
}
