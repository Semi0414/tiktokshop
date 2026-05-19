<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Product\Exceptions\InsufficientProductInventoryException;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Support\ConfigurableCartSelection;
use Webkul\Shop\Support\WalletCheckoutOrderPlacer;

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
    public function addFromProduct(
        Request $request,
        ProductRepository $productRepository,
        WalletCheckoutOrderPlacer $walletCheckoutOrderPlacer
    ): RedirectResponse {
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
            return $this->redirectToProductPage($productRepository, 'warning', trans('shop::app.checkout.cart.product-id-mismatch'), $pid);
        }

        $effectiveProductId = $pid > 0 ? $pid : $bodyProductId;

        if ($effectiveProductId < 1) {
            return redirect()->back()->with('warning', trans('validation.required', ['attribute' => 'product_id']));
        }

        $request->merge(['product_id' => $effectiveProductId]);

        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'is_buy_now' => 'integer|in:0,1',
                'quantity' => 'integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->redirectToProductPage(
                $productRepository,
                $effectiveProductId,
                'error',
                collect($e->errors())->flatten()->first() ?: trans('shop::app.checkout.cart.item-add-to-cart')
            );
        }

        /** @var Product $product */
        $product = $productRepository->with(['parent', 'product_flats', 'variants'])->findOrFail($effectiveProductId);

        ConfigurableCartSelection::applyToRequest($product, $request);

        if (ConfigurableCartSelection::requiresUserSelection($product, $request)) {
            return $this->redirectToProductPage(
                $product,
                'warning',
                __('Please select a product option before adding to cart.')
            );
        }

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

            if ($this->wantsWalletAutoOrder($request)) {
                try {
                    $result = $walletCheckoutOrderPlacer->placeFromActiveCart(true);

                    return $this->redirectToProductPage($product, 'success', $result['message']);
                } catch (\Exception $orderException) {
                    return $this->redirectToProductPage($product, 'warning', $orderException->getMessage());
                }
            }

            return $this->redirectToProductPage($product, 'success', trans('shop::app.checkout.cart.item-add-to-cart'));
        } catch (InsufficientProductInventoryException $exception) {
            $this->ensureInventoryForCartAdd($product);

            try {
                Cart::addProduct($product, $request->all());

                Cart::collectTotals();

                if ($this->wantsWalletAutoOrder($request)) {
                    try {
                        $result = $walletCheckoutOrderPlacer->placeFromActiveCart(true);

                        return $this->redirectToProductPage($product, 'success', $result['message']);
                    } catch (\Exception $orderException) {
                        return $this->redirectToProductPage($product, 'warning', $orderException->getMessage());
                    }
                }

                return $this->redirectToProductPage($product, 'success', trans('shop::app.checkout.cart.item-add-to-cart'));
            } catch (\Throwable $retryException) {
                // Fallback to original inventory message.
            }

            return $this->redirectToProductPage($product, 'warning', $exception->getMessage());
        } catch (\Exception $exception) {
            return $this->redirectToProductPage($product, 'warning', $exception->getMessage());
        }
    }

    /**
     * Wallet checkout is on unless the client explicitly sends wallet_auto_order=0.
     */
    protected function wantsWalletAutoOrder(Request $request): bool
    {
        if (! $request->has('wallet_auto_order')) {
            return true;
        }

        return filter_var($request->input('wallet_auto_order'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Redirect back to the product PDP with a visible notice (works with response cache via query string).
     */
    protected function redirectToProductPage(
        Product|ProductRepository $productOrRepository,
        string $noticeType,
        string $message,
        ?int $productId = null
    ): RedirectResponse {
        if ($productOrRepository instanceof ProductRepository) {
            $product = $productOrRepository->with('product_flats')->findOrFail($productId);
        } else {
            $product = $productOrRepository;
        }

        $noticeType = in_array($noticeType, ['success', 'warning', 'error'], true) ? $noticeType : 'warning';

        $channelCode = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        $flat = $product->product_flats
            ->where('channel', $channelCode)
            ->where('locale', $locale)
            ->first();

        $urlKey = $flat?->url_key ?: $product->url_key;

        $target = url('/'.ltrim((string) $urlKey, '/'));

        $query = http_build_query([
            'pid' => $product->id,
            'cart_notice' => $noticeType,
            'cart_msg' => $message,
        ]);

        $this->forgetProductPageCache($urlKey);

        return redirect()
            ->to($target.'?'.$query)
            ->with($noticeType, $message);
    }

    protected function forgetProductPageCache(?string $urlKey): void
    {
        if (! $urlKey || ! class_exists(\Spatie\ResponseCache\Facades\ResponseCache::class)) {
            return;
        }

        try {
            \Spatie\ResponseCache\Facades\ResponseCache::forget('/'.ltrim($urlKey, '/'));
        } catch (\Throwable $e) {
            // Non-fatal if response cache is disabled.
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
