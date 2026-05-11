<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Product\Exceptions\InsufficientProductInventoryException;
use Webkul\Product\Repositories\ProductRepository;

class CartController extends Controller
{
    /**
     * Cart page.
     */
    public function index(): View
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        return view('shop::checkout.cart.index');
    }

    /**
     * Add product to cart from storefront HTML product form (standard POST + redirect).
     */
    public function addFromProduct(Request $request, ProductRepository $productRepository): RedirectResponse
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        if (! auth()->guard('customer')->check()) {
            return redirect()->guest(
                route('shop.customer.session.index', [
                    'redirect_url' => url()->previous() ?: url('/'),
                ])
            );
        }

        $pid = (int) $request->input('pid');
        $bodyProductId = (int) $request->input('product_id');

        if ($pid > 0 && $bodyProductId > 0 && $pid !== $bodyProductId) {
            return back()->with('warning', trans('shop::app.checkout.cart.product-id-mismatch'));
        }

        $effectiveProductId = $pid > 0 ? $pid : $bodyProductId;

        if ($effectiveProductId < 1) {
            return back()->with('warning', trans('validation.required', ['attribute' => 'product_id']));
        }

        $request->merge(['product_id' => $effectiveProductId]);

        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'is_buy_now' => 'integer|in:0,1',
                'quantity' => 'integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        /** @var \Webkul\Product\Models\Product $product */
        $product = $productRepository->with('parent')->findOrFail($effectiveProductId);

        try {
            if (! $product->status) {
                throw new \Exception(trans('shop::app.checkout.cart.inactive-add'));
            }

            if ($request->boolean('is_buy_now')) {
                Cart::deActivateCart();
            }

            $this->ensureInventoryForCartAdd($product);

            Cart::addProduct($product, $request->all());

            Cart::collectTotals();

            if ($request->boolean('is_buy_now')) {
                return redirect()
                    ->route('shop.checkout.onepage.index')
                    ->with('success', trans('shop::app.checkout.cart.item-add-to-cart'));
            }

            return back()->with('success', trans('shop::app.checkout.cart.item-add-to-cart'));
        } catch (InsufficientProductInventoryException $exception) {
            $this->ensureInventoryForCartAdd($product);

            try {
                Cart::addProduct($product, $request->all());

                Cart::collectTotals();

                return back()->with('success', trans('shop::app.checkout.cart.item-add-to-cart'));
            } catch (\Throwable $retryException) {
                // Fallback to original inventory message.
            }

            return back()->with('warning', $exception->getMessage());
        } catch (\Exception $exception) {
            return back()->with('warning', $exception->getMessage());
        }
    }

    /**
     * Bump sellable qty on the default inventory source (mirrors API cart store behavior).
     */
    private function ensureInventoryForCartAdd($product): void
    {
        $this->ensureImportedProductInventory((int) $product->id);

        $childId = request()->input('selected_configurable_option');
        if ($childId && (int) $childId !== (int) $product->id) {
            $this->ensureImportedProductInventory((int) $childId);
        }
    }

    private function ensureImportedProductInventory(int $productId): void
    {
        $inventorySourceId = (int) DB::table('inventory_sources')->orderBy('id')->value('id');

        if (! $inventorySourceId) {
            return;
        }

        DB::table('product_inventories')->updateOrInsert(
            [
                'product_id' => $productId,
                'inventory_source_id' => $inventorySourceId,
                'vendor_id' => 0,
            ],
            [
                'qty' => 9999,
            ]
        );
    }
}
