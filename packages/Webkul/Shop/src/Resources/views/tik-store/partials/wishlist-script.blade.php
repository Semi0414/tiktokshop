@php
    $wishlistEnabled = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp
@if ($wishlistEnabled)
<script>
(function () {
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';
    var storeUrl = @json(route('shop.api.customers.account.wishlist.store'));
    var loginUrl = @json(route('shop.customer.session.index'));
    var isCustomer = @json(auth()->guard('customer')->check());

    function syncButton(btn, active) {
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.textContent = active ? '♥' : '♡';
        btn.setAttribute('title', active ? @json(__('Remove from wishlist')) : @json(__('Add to wishlist')));
        btn.setAttribute('aria-label', active ? @json(__('Remove from wishlist')) : @json(__('Add to wishlist')));
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
@endif
