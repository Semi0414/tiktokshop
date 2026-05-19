<?php
    $cartPageEnabled = (bool) core()->getConfigData('sales.checkout.shopping_cart.cart_page');
?>
<script>
(function () {
    var toast = document.getElementById('tik-store-cart-toast');

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'tik-store-cart-toast';
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');
        toast.style.cssText = 'position:fixed;left:50%;bottom:1.25rem;transform:translateX(-50%) translateY(120%);z-index:100060;max-width:min(92vw,360px);padding:0.75rem 1rem;border-radius:0.75rem;background:#0f172a;color:#fff;font-size:0.875rem;font-family:inherit;box-shadow:0 12px 32px rgba(15,23,42,0.35);transition:transform 0.28s ease,opacity 0.28s ease;opacity:0;pointer-events:none;';
        document.body.appendChild(toast);
    }

    var toastTimer = null;

    window.showTikStoreCartToast = function (message, isError) {
        toast.textContent = message;
        toast.style.background = isError ? '#b91c1c' : '#0f172a';
        toast.style.transform = 'translateX(-50%) translateY(0)';
        toast.style.opacity = '1';
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () {
            toast.style.transform = 'translateX(-50%) translateY(120%)';
            toast.style.opacity = '0';
        }, 4000);
    };
})();
</script>
<?php echo $__env->make('shop::checkout.partials.wallet-auto-order-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>
(function () {
    var cartPageEnabled = <?php echo json_encode($cartPageEnabled, 15, 512) ?>;
    var loginUrl = <?php echo json_encode(route('shop.customer.session.index'), 15, 512) ?>;
    var isCustomer = <?php echo json_encode(auth()->guard('customer')->check(), 15, 512) ?>;
    var wallet = window.shopWalletCheckout;

    function buildPayload(btn) {
        var pid = parseInt(btn.getAttribute('data-product-id') || '0', 10);
        var type = btn.getAttribute('data-product-type') || 'simple';
        var variantId = parseInt(btn.getAttribute('data-variant-id') || '0', 10);
        var payload = {
            quantity: 1,
            product_id: pid,
            pid: pid,
            is_buy_now: 0,
        };

        if (type === 'configurable' && variantId > 0) {
            payload.selected_configurable_option = variantId;
        }

        return payload;
    }

    function handleAddClick(btn) {
        if (!cartPageEnabled) {
            window.showTikStoreCartToast(<?php echo json_encode(__('Cart is not available right now.'), 15, 512) ?>, true);
            return;
        }

        if (!isCustomer) {
            window.location.href = loginUrl + '?redirect_url=' + encodeURIComponent(window.location.href);
            return;
        }

        if (btn.getAttribute('data-can-quick-add') !== '1') {
            var productUrl = btn.getAttribute('data-product-url');
            if (productUrl && productUrl !== '#') {
                window.location.href = productUrl;
            }
            return;
        }

        if (!wallet || !wallet.addToCartAndPlaceOrder) {
            return;
        }

        var originalLabel = btn.textContent;
        btn.disabled = true;
        btn.textContent = '…';

        wallet.addToCartAndPlaceOrder(buildPayload(btn))
            .then(function () {
                btn.disabled = false;
                btn.textContent = originalLabel;
            })
            .catch(function () {
                btn.disabled = false;
                btn.textContent = originalLabel;
            });
    }

    function bindCartButtons() {
        document.querySelectorAll('.tik-store-add-cart-btn').forEach(function (btn) {
            if (btn.dataset.cartBound === '1') {
                return;
            }
            btn.dataset.cartBound = '1';
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                handleAddClick(btn);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindCartButtons);
    } else {
        bindCartButtons();
    }
})();
</script>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/tik-store/partials/cart-script.blade.php ENDPATH**/ ?>