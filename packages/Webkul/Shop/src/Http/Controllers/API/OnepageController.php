<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Models\CustomerWalletTransaction;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Payment\Facades\Payment;
use Webkul\Product\Helpers\ShopCheckoutInventory;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Requests\CartAddressRequest;
use Webkul\Shop\Http\Resources\CartResource;

class OnepageController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CustomerRepository $customerRepository
    ) {}

    /**
     * Return cart summary.
     */
    public function summary(): JsonResource
    {
        $cart = Cart::getCart();

        return new CartResource($cart);
    }

    /**
     * Store address.
     */
    public function storeAddress(CartAddressRequest $cartAddressRequest): JsonResource
    {
        $params = $cartAddressRequest->all();

        if (
            ! auth()->guard('customer')->check()
            && ! Cart::getCart()->hasGuestCheckoutItems()
        ) {
            return new JsonResource([
                'redirect' => true,
                'data' => route('shop.customer.session.index'),
            ]);
        }

        if (Cart::hasError()) {
            return new JsonResource([
                'redirect' => true,
                'redirect_url' => route('shop.checkout.cart.index'),
            ]);
        }

        Cart::saveAddresses($params);

        $cart = Cart::getCart();

        Cart::collectTotals();

        if ($cart->haveStockableItems()) {
            if (! $rates = Shipping::collectRates()) {
                return new JsonResource([
                    'redirect' => true,
                    'redirect_url' => route('shop.checkout.cart.index'),
                ]);
            }

            return new JsonResource([
                'redirect' => false,
                'data' => $rates,
            ]);
        }

        return new JsonResource([
            'redirect' => false,
            'data' => Payment::getSupportedPaymentMethods(),
        ]);
    }

    /**
     * Store shipping method.
     *
     * @return Response
     */
    public function storeShippingMethod()
    {
        $validatedData = $this->validate(request(), [
            'shipping_method' => 'required',
        ]);

        if (
            Cart::hasError()
            || ! $validatedData['shipping_method']
            || ! Cart::saveShippingMethod($validatedData['shipping_method'])
        ) {
            return response()->json([
                'redirect_url' => route('shop.checkout.cart.index'),
            ], Response::HTTP_FORBIDDEN);
        }

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Store payment method.
     *
     * @return array
     */
    public function storePaymentMethod()
    {
        $validatedData = $this->validate(request(), [
            'payment' => 'required',
        ]);

        if (
            Cart::hasError()
            || ! $validatedData['payment']
            || ! Cart::savePaymentMethod($validatedData['payment'])
        ) {
            return response()->json([
                'redirect_url' => route('shop.checkout.cart.index'),
            ], Response::HTTP_FORBIDDEN);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return [
            'cart' => new CartResource($cart),
        ];
    }

    /**
     * Store order
     */
    public function storeOrder()
    {
        if (Cart::hasError()) {
            return new JsonResource([
                'redirect' => true,
                'redirect_url' => route('shop.checkout.cart.index'),
            ]);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty.',
            ], 400);
        }

        /**
         * Single-product direct order: keep only the most recently added cart line.
         */
        $targetItem = $cart->items->sortByDesc('id')->first();

        foreach ($cart->items as $item) {
            if ($item->id !== $targetItem->id) {
                Cart::removeItem($item->id);
            }
        }

        Cart::collectTotals();
        $cart = Cart::getCart();

        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return response()->json([
                'message' => 'Please log in to place order.',
            ], 401);
        }

        // Reload customer to ensure latest wallet/credit values (admin may have updated them).
        $customer = $this->customerRepository->find($customer->id) ?? $customer;

        if ((int) ($customer->credit_score ?? 0) !== 100) {
            return response()->json([
                'message' => 'Please maintain credit_score first',
            ], 400);
        }

        /**
         * Keep flow simple:
         * - only credit_score + wallet_balance checks are enforced here
         * - we only ensure a minimal billing address exists to avoid order creation failures
         */
        if (! $cart->billing_address) {
            Cart::saveAddresses([
                'billing' => [
                    'use_for_shipping' => true,
                    'default_address' => 0,
                    'first_name' => $customer->first_name ?? 'Customer',
                    'last_name' => $customer->last_name ?? '',
                    'email' => $customer->email ?? 'customer@example.com',
                    'address' => ['N/A'],
                    'country' => 'US',
                    'state' => 'CA',
                    'city' => 'N/A',
                    'postcode' => '00000',
                    'phone' => $customer->phone ?? '0000000000',
                ],
                'shipping' => [],
            ]);

            Cart::collectTotals();
            $cart = Cart::getCart();
        }

        if (! $cart->payment) {
            // Use a stable offline method so admin views don't break.
            $method = config('payment_methods.moneytransfer.class')
                ? 'moneytransfer'
                : array_key_first((array) config('payment_methods')) ?? 'moneytransfer';

            Cart::savePaymentMethod(['method' => $method]);
            $cart = Cart::getCart();
        }

        try {
            $this->validateOrder();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        // Same currency / amount as order detail grand_total (not base_grand_total).
        $amountDue = round((float) ($cart->grand_total ?? 0), 2);
        $walletBalance = round((float) ($customer->wallet_balance ?? 0), 2);

        if ($walletBalance + 0.00001 < $amountDue) {
            return response()->json([
                'message' => 'Not enough balance please contact our assistant for recharge',
            ], 400);
        }

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = null;

        DB::transaction(function () use ($data, $customer, &$order) {
            $order = $this->orderRepository->create($data);

            $order->refresh();

            // Exact amount persisted on the order (matches order view total).
            $amountToDeduct = round((float) $order->grand_total, 2);

            $freshCustomer = $this->customerRepository->find($customer->id);

            $balanceBefore = round((float) ($freshCustomer->wallet_balance ?? 0), 2);
            $balanceAfter = max(0, round($balanceBefore - $amountToDeduct, 2));

            $this->customerRepository->update([
                'wallet_balance' => $balanceAfter,
            ], $freshCustomer->id);

            CustomerWalletTransaction::create([
                'customer_id' => $freshCustomer->id,
                'amount' => $amountToDeduct,
                'type' => 'debit',
                'description' => 'Order placed',
                'order_id' => $order->id,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);
        });

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);
        session()->flash('success', 'Order has been placed successfully.');

        return new JsonResource([
            'message' => 'Order has been placed successfully.',
            'redirect' => true,
            'order_id' => $order->id,
            'redirect_url' => route('shop.customers.account.orders.view', $order->id),
        ]);
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0;

        if (
            auth()->guard('customer')->check()
            && auth()->guard('customer')->user()->is_suspended
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.suspended-account-message'));
        }

        if (
            auth()->guard('customer')->user()
            && ! auth()->guard('customer')->user()->status
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.inactive-account-message'));
        }

        if (! ShopCheckoutInventory::shouldSkipInventoryChecks() && ! Cart::haveMinimumOrderAmount()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }
    }
}
