{{-- Server handles wallet order on form POST (wallet_auto_order=1). Optional AJAX avoids full page reload when scripts stack loads. --}}
@include('shop::checkout.partials.wallet-auto-order-script')

@push('scripts')
    <script>
        (function () {
            var form = document.querySelector('form.cart-add-form');

            if (!form || !window.shopWalletCheckout) {
                return;
            }

            form.addEventListener('submit', function (event) {
                if (form.dataset.walletAjaxBound !== '1') {
                    return;
                }

                event.preventDefault();

                var submitBtn = form.querySelector('button[type="submit"]');
                var originalLabel = submitBtn ? submitBtn.textContent : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = '…';
                }

                var fd = new FormData(form);
                var payload = {
                    product_id: parseInt(fd.get('product_id') || '0', 10),
                    pid: parseInt(fd.get('pid') || fd.get('product_id') || '0', 10),
                    quantity: parseInt(fd.get('quantity') || '1', 10) || 1,
                    is_buy_now: 0,
                };

                var variant = fd.get('selected_configurable_option');

                if (variant) {
                    payload.selected_configurable_option = parseInt(variant, 10);
                }

                window.shopWalletCheckout.addToCartAndPlaceOrder(payload).catch(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalLabel;
                    }
                });
            });

            form.dataset.walletAjaxBound = '1';
        })();
    </script>
@endpush
