<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Webkul\Shop\Support\SellerPreview;

class OrderResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        [$sellerId, $sellerApprovalStatus] = $this->resolveSellerAttribution();

        $shippingInformation = [];

        if (
            $this->haveStockableItems()
            && $this->selected_shipping_rate
            && $this->shipping_address
        ) {
            $shippingInformation = [
                'shipping_method' => $this->selected_shipping_rate->method,
                'shipping_title' => $this->selected_shipping_rate->carrier_title.' - '.$this->selected_shipping_rate->method_title,
                'shipping_description' => $this->selected_shipping_rate->method_description,
                'shipping_amount' => $this->selected_shipping_rate->price,
                'base_shipping_amount' => $this->selected_shipping_rate->base_price,
                'shipping_amount_incl_tax' => $this->selected_shipping_rate->price_incl_tax,
                'base_shipping_amount_incl_tax' => $this->selected_shipping_rate->base_price_incl_tax,
                'shipping_discount_amount' => $this->selected_shipping_rate->discount_amount,
                'base_shipping_discount_amount' => $this->selected_shipping_rate->base_discount_amount,
                'shipping_address' => (new OrderAddressResource($this->shipping_address))->jsonSerialize(),
            ];
        }

        return [
            'cart_id' => $this->id,
            'seller_id' => $sellerId,
            'seller_approval_status' => $sellerApprovalStatus,
            'is_guest' => $this->is_guest,
            'customer_id' => $this->customer_id,
            'customer_type' => $this->customer ? get_class($this->customer) : null,
            'customer_email' => $this->customer_email,
            'customer_first_name' => $this->customer_first_name,
            'customer_last_name' => $this->customer_last_name,
            'channel_id' => $this->channel_id,
            'channel_name' => $this->channel->name,
            'channel_type' => get_class($this->channel),
            'total_item_count' => $this->items_count,
            'total_qty_ordered' => $this->items_qty,
            'base_currency_code' => $this->base_currency_code,
            'channel_currency_code' => $this->channel_currency_code,
            'order_currency_code' => $this->cart_currency_code,
            'grand_total' => $this->grand_total,
            'base_grand_total' => $this->base_grand_total,
            'sub_total' => $this->sub_total,
            'sub_total_incl_tax' => $this->sub_total_incl_tax,
            'base_sub_total' => $this->base_sub_total,
            'base_sub_total_incl_tax' => $this->base_sub_total_incl_tax,
            'tax_amount' => $this->tax_total,
            'base_tax_amount' => $this->base_tax_total,
            'shipping_tax_amount' => $this->selected_shipping_rate?->tax_amount ?? 0,
            'base_shipping_tax_amount' => $this->selected_shipping_rate?->base_tax_amount ?? 0,
            'coupon_code' => $this->coupon_code,
            'applied_cart_rule_ids' => $this->applied_cart_rule_ids,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'billing_address' => $this->billing_address
                ? (new OrderAddressResource($this->billing_address))->jsonSerialize()
                : [],
            $this->mergeWhen(
                $this->haveStockableItems()
                && $this->selected_shipping_rate
                && $this->shipping_address,
                $shippingInformation
            ),
            'payment' => $this->payment
                ? (new OrderPaymentResource($this->payment))->jsonSerialize()
                : [
                    'method' => 'wallet',
                    'method_title' => 'Wallet',
                    'additional' => $request->input('orderData'),
                ],
            'items' => OrderItemResource::collection($this->items)->jsonSerialize(),
        ];
    }

    /**
     * Attribute the order to a seller using cart line products and {@see seller_store_products},
     * with optional alignment to the active seller preview session.
     *
     * @return array{0: ?int, 1: ?string}
     */
    protected function resolveSellerAttribution(): array
    {
        $productIds = $this->items->pluck('product_id')->unique()->filter()->values();
        if ($productIds->isEmpty()) {
            return [null, null];
        }

        $rows = DB::table('seller_store_products')
            ->whereIn('product_id', $productIds)
            ->select('seller_id', 'product_id')
            ->get();

        if ($rows->isEmpty()) {
            return [null, null];
        }

        $bySeller = $rows->groupBy('seller_id');
        $sellerId = null;

        if ($bySeller->count() === 1) {
            $sellerId = (int) $bySeller->keys()->first();
        } else {
            $preview = $this->resolvePreviewSellerIdFromSession();
            if ($preview && $bySeller->has($preview)) {
                $sellerId = (int) $preview;
            } else {
                $sellerId = (int) $bySeller->map->count()->sortDesc()->keys()->first();
            }
        }

        return [$sellerId, $sellerId ? 'approved' : null];
    }

    /**
     * Matches {@see SellerPreview::resolveSellerIdFromSession} without a Sales→Shop dependency.
     */
    protected function resolvePreviewSellerIdFromSession(): int
    {
        if (! session()->has('seller_preview_id')) {
            return 0;
        }

        $expiresAt = (int) (session('seller_preview_expires_at') ?? 0);

        if ($expiresAt < now()->timestamp) {
            session()->forget(['seller_preview_id', 'seller_preview_expires_at']);

            return 0;
        }

        return max(0, (int) session('seller_preview_id'));
    }
}
