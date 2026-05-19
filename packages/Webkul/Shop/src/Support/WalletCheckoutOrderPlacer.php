<?php

namespace Webkul\Shop\Support;

use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerWalletTransaction;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Helpers\ShopCheckoutInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class WalletCheckoutOrderPlacer
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CustomerRepository $customerRepository
    ) {}

    /**
     * Place a pending order from the active cart (credit 100% + wallet debit).
     *
     * @return array{order: Order, message: string, redirect_url: string}
     *
     * @throws \Exception
     */
    public function placeFromActiveCart(bool $keepLatestItemOnly = true): array
    {
        if (Cart::hasError()) {
            throw new \Exception(trans('shop::app.checkout.cart.index.cart'));
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        if (! $cart || $cart->items->isEmpty()) {
            throw new \Exception('Cart is empty.');
        }

        if ($keepLatestItemOnly) {
            $targetItem = $cart->items->sortByDesc('id')->first();

            foreach ($cart->items as $item) {
                if ($item->id !== $targetItem->id) {
                    Cart::removeItem($item->id);
                }
            }

            Cart::collectTotals();
            $cart = Cart::getCart();
        }

        $customer = auth()->guard('customer')->user();

        if (! $customer instanceof Customer) {
            throw new \Exception('Please log in to place order.');
        }

        $customer = $this->customerRepository->find($customer->id) ?? $customer;

        if ((int) ($customer->credit_score ?? 0) !== 100) {
            throw new \Exception('Please maintain credit_score first');
        }

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
            $method = config('payment_methods.moneytransfer.class')
                ? 'moneytransfer'
                : array_key_first((array) config('payment_methods')) ?? 'moneytransfer';

            Cart::savePaymentMethod(['method' => $method]);
            $cart = Cart::getCart();
        }

        $this->validateOrder();

        Cart::collectTotals();
        $cart = Cart::getCart();

        $amountDue = round((float) ($cart->grand_total ?? 0), 2);
        $walletBalance = round((float) ($customer->wallet_balance ?? 0), 2);

        if ($walletBalance + 0.00001 < $amountDue) {
            throw new \Exception('Not enough balance please contact our assistant for recharge');
        }

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = null;

        DB::transaction(function () use ($data, $customer, &$order) {
            $order = $this->orderRepository->create($data);

            $order->refresh();

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

        $message = 'Order has been placed successfully.';

        return [
            'order' => $order,
            'message' => $message,
            'redirect_url' => route('shop.customers.account.orders.view', $order->id),
        ];
    }

    /**
     * @throws \Exception
     */
    protected function validateOrder(): void
    {
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
