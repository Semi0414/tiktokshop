<?php
    $wishlistEnabled = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
?>
<?php if($wishlistEnabled): ?>
<script>
(function () {
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';
    var storeUrl = <?php echo json_encode(route('shop.api.customers.account.wishlist.store'), 15, 512) ?>;
    var loginUrl = <?php echo json_encode(route('shop.customer.session.index'), 15, 512) ?>;
    var isCustomer = <?php echo json_encode(auth()->guard('customer')->check(), 15, 512) ?>;

    function syncButton(btn, active) {
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.textContent = active ? '♥' : '♡';
        btn.setAttribute('title', active ? <?php echo json_encode(__('Remove from wishlist'), 15, 512) ?> : <?php echo json_encode(__('Add to wishlist'), 15, 512) ?>);
        btn.setAttribute('aria-label', active ? <?php echo json_encode(__('Remove from wishlist'), 15, 512) ?> : <?php echo json_encode(__('Add to wishlist'), 15, 512) ?>);
    }

    document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!isCustomer) {
                window.location.href = loginUrl + '?redirect_url=' + encodeURIComponent(window.location.href);
                return;
            }
            var pid = parseInt(btn.getAttribute('data-product-id'), 10);
            if (!pid) {
                return;
            }
            btn.disabled = true;
            fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'same-origin',
                body: JSON.stringify({ product_id: pid })
            })
                .then(function (r) {
                    return r.json().then(function (body) {
                        return { ok: r.ok, status: r.status, body: body };
                    });
                })
                .then(function (res) {
                    btn.disabled = false;
                    if (!res.ok) {
                        return;
                    }
                    syncButton(btn, !btn.classList.contains('is-active'));
                })
                .catch(function () {
                    btn.disabled = false;
                });
        });
    });
})();
</script>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/tik-store/partials/wishlist-script.blade.php ENDPATH**/ ?>