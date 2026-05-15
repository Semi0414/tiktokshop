<script>
    (function registerSellerSseModalCore() {
        if (window.sellerShowSseModal) {
            return;
        }

        var sseModalCloseMs = 320;

        function mountSellerSseModalToBody(modal) {
            if (!modal || modal.parentNode === document.body) {
                return;
            }

            document.body.appendChild(modal);
        }

        function showSellerSseModal(modal) {
            if (!modal) {
                return;
            }

            mountSellerSseModalToBody(modal);
            modal.classList.remove('hidden', 'sse-modal--closing');
            modal.classList.add('flex', 'admin-seller-add-modal-layer');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('seller-sse-modal-open');

            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    modal.classList.add('sse-modal--open');
                });
            });
        }

        function hideSellerSseModal(modal) {
            if (!modal) {
                return;
            }

            if (!modal.classList.contains('sse-modal--open')) {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'admin-seller-add-modal-layer');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('seller-sse-modal-open');
                return;
            }

            modal.classList.remove('sse-modal--open');
            modal.classList.add('sse-modal--closing');

            setTimeout(function () {
                modal.classList.remove('sse-modal--closing', 'flex', 'admin-seller-add-modal-layer');
                modal.classList.add('hidden');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('seller-sse-modal-open');
            }, sseModalCloseMs);
        }

        window.sellerShowSseModal = showSellerSseModal;
        window.sellerHideSseModal = hideSellerSseModal;
        window.sellerMountSseModalToBody = mountSellerSseModalToBody;
        window.sellerShowStoreProductEditModal = showSellerSseModal;
        window.sellerHideStoreProductEditModal = hideSellerSseModal;

        document.addEventListener('click', function (event) {
            if (!event.target || !event.target.closest) {
                return;
            }

            if (
                event.target.closest('[data-sse-modal-dismiss]')
                || event.target.closest('[data-sse-remove-blocked-dismiss]')
            ) {
                document.querySelectorAll('.sse-modal.sse-modal--open').forEach(function (openModal) {
                    hideSellerSseModal(openModal);
                });
            }
        });
    })();
</script>
