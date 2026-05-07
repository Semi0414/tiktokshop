<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerWalletTransaction;

class SellerShopOrderController extends Controller
{
    public function makeOrder(Order $order): JsonResponse
    {
        $seller = auth()->guard('admin')->user();
        if (! $seller instanceof Admin) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $err = $this->applyMakeOrderToOrder($seller, $order);
            if ($err !== null) {
                DB::rollBack();

                return $err;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => __('admin::app.seller.shop-order.make-order-success')]);
    }

    public function bulkMakeOrder(Request $request): JsonResponse
    {
        $seller = auth()->guard('admin')->user();
        if (! $seller instanceof Admin) {
            abort(403);
        }

        $data = $request->validate([
            'indices' => 'required|array|min:1',
            'indices.*' => 'integer|min:1',
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['indices'])));

        $orders = Order::query()
            ->whereIn('id', $ids)
            ->where('seller_id', $seller->id)
            ->orderBy('id')
            ->get();

        if ($orders->count() !== count($ids)) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.bulk-invalid-selection')], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($orders as $order) {
                $seller->refresh();
                $err = $this->applyMakeOrderToOrder($seller, $order);
                if ($err !== null) {
                    DB::rollBack();

                    return $err;
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => __('admin::app.seller.shop-order.bulk-make-order-success')]);
    }

    /**
     * Apply Make Order to a single order. Mutates $seller (wallet) and reloads order state.
     *
     * @return JsonResponse|null Error response, or null on success.
     */
    protected function applyMakeOrderToOrder(Admin $seller, Order $order): ?JsonResponse
    {
        if ((int) $order->seller_id !== (int) $seller->id) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.forbidden-order')], 403);
        }

        if ($order->seller_make_order_at) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.already-made')], 422);
        }

        if (! $seller->isSellerAccountActive()) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.account-inactive')], 422);
        }

        if ((int) $seller->credit_score !== 100) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.credit-not-100-contact')], 422);
        }

        $amount = (float) $order->base_grand_total;
        $balanceBefore = (float) ($seller->wallet_balance ?? 0);

        if ($balanceBefore < $amount) {
            return new JsonResponse(['message' => __('admin::app.seller.shop-order.insufficient-balance-recharge')], 422);
        }

        $balanceAfter = $balanceBefore - $amount;

        SellerWalletTransaction::create([
            'seller_id' => $seller->id,
            'amount' => $amount,
            'type' => 'debit',
            'status' => SellerWalletTransaction::STATUS_COMPLETED,
            'kind' => SellerWalletTransaction::KIND_SELLER_PURCHASE,
            'order_id' => $order->id,
            'description' => __('admin::app.seller.shop-order.purchase-desc', ['increment' => $order->increment_id]),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        $seller->wallet_balance = $balanceAfter;
        $seller->save();

        $order->seller_make_order_at = now();
        if (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PENDING_PAYMENT], true)) {
            $order->status = Order::STATUS_PROCESSING;
        }
        $order->save();

        return null;
    }
}
