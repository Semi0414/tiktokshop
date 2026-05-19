
<script>
window.shopWalletCheckout = (function () {
    var loginUrl = <?php echo json_encode(route('shop.customer.session.index'), 15, 512) ?>;
    var cartStoreUrl = <?php echo json_encode(route('shop.api.checkout.cart.store'), 15, 512) ?>;
    var orderStoreUrl = <?php echo json_encode(route('shop.checkout.onepage.orders.store'), 15, 512) ?>;
    var isCustomer = <?php echo json_encode(auth()->guard('customer')->check(), 15, 512) ?>;

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');

        if (meta) {
            return meta.getAttribute('content') || '';
        }

        var input = document.querySelector('input[name="_token"]');

        return input ? input.value : '';
    }

    function showFlashMessage(message, isError) {
        var box = document.getElementById('product-order-notice');

        if (box) {
            var type = isError ? 'error' : 'success';

            box.hidden = false;
            box.removeAttribute('aria-hidden');
            box.className = 'product-pdp-notice product-pdp-notice--' + type;
            box.setAttribute('data-notice-type', type);
            box.setAttribute('role', isError ? 'alert' : 'status');
            box.innerHTML = '<p class="product-pdp-notice__text"></p>';
            box.querySelector('.product-pdp-notice__text').textContent = message;
            box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            return;
        }

        if (typeof window.showTikStoreCartToast === 'function') {
            window.showTikStoreCartToast(message, isError);
        }
    }

    function parseResponse(response) {
        return response.text().then(function (text) {
            var body = {};

            if (text) {
                try {
                    body = JSON.parse(text);
                } catch (e) {
                    body = { message: text.slice(0, 300) };
                }
            }

            return { ok: response.ok, status: response.status, body: body };
        });
    }

    function unwrap(body) {
        if (!body || typeof body !== 'object') {
            return {};
        }

        if (body.data && typeof body.data === 'object' && !Array.isArray(body.data)) {
            return body.data;
        }

        return body;
    }

    function readMessage(body) {
        var inner = unwrap(body);

        return inner.message || body.message || '';
    }

    function apiPost(url, payload) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload || {}),
        }).then(parseResponse);
    }

    function placeOrder() {
        return apiPost(orderStoreUrl, {}).then(function (res) {
            var message = readMessage(res.body);

            if (res.status === 401) {
                window.location.href = loginUrl + '?redirect_url=' + encodeURIComponent(window.location.href);
                throw new Error('Login required');
            }

            if (!res.ok) {
                showFlashMessage(message || 'Order failed', true);
                throw new Error(message || 'Order failed');
            }

            showFlashMessage(message || 'Order has been placed successfully.', false);

            return { message: message };
        });
    }

    function addToCartAndPlaceOrder(cartPayload) {
        if (!isCustomer) {
            window.location.href = loginUrl + '?redirect_url=' + encodeURIComponent(window.location.href);

            return Promise.reject(new Error('Not logged in'));
        }

        return apiPost(cartStoreUrl, cartPayload).then(function (res) {
            if (res.status === 401) {
                window.location.href = loginUrl + '?redirect_url=' + encodeURIComponent(window.location.href);
                throw new Error('Login required');
            }

            if (!res.ok) {
                var inner = unwrap(res.body);
                var cartErr = readMessage(res.body) || 'Add to cart failed';
                showFlashMessage(cartErr, true);

                if (inner.redirect_uri || res.body.redirect_uri) {
                    setTimeout(function () {
                        window.location.href = inner.redirect_uri || res.body.redirect_uri;
                    }, 800);
                }

                throw new Error(cartErr);
            }

            return placeOrder();
        });
    }

    return {
        showFlashMessage: showFlashMessage,
        addToCartAndPlaceOrder: addToCartAndPlaceOrder,
        placeOrder: placeOrder,
        parseResponse: parseResponse,
        getCsrfToken: getCsrfToken,
    };
})();
</script>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/checkout/partials/wallet-auto-order-script.blade.php ENDPATH**/ ?>