<?php
    $noticeType = null;
    $noticeMessage = '';

    foreach (['success', 'warning', 'error'] as $type) {
        if (session()->has($type)) {
            $noticeType = $type;
            $noticeMessage = (string) session($type);
            break;
        }
    }

    if (! $noticeType && $errors->any()) {
        $noticeType = 'error';
        $noticeMessage = $errors->first();
    }

    $queryType = request()->query('cart_notice');
    $queryMessage = request()->query('cart_msg');

    if (in_array($queryType, ['success', 'warning', 'error'], true) && is_string($queryMessage) && $queryMessage !== '') {
        $noticeType = $queryType;
        $noticeMessage = rawurldecode($queryMessage);
    }
?>

<?php if($noticeMessage !== ''): ?>
    <div
        id="product-order-notice"
        class="product-pdp-notice product-pdp-notice--<?php echo e($noticeType); ?>"
        role="<?php echo e($noticeType === 'success' ? 'status' : 'alert'); ?>"
        aria-live="polite"
        data-notice-type="<?php echo e($noticeType); ?>"
    >
        <p class="product-pdp-notice__text"><?php echo e($noticeMessage); ?></p>
    </div>
<?php else: ?>
    <div id="product-order-notice" class="product-pdp-notice product-pdp-notice--hidden" hidden aria-hidden="true"></div>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('fc1b8ea4-d511-454a-98cc-5b62697d9666')): $__env->markAsRenderedOnce('fc1b8ea4-d511-454a-98cc-5b62697d9666');
$__env->startPush('styles'); ?>
    <style>
        .product-pdp-notice {
            display: block !important;
            box-sizing: border-box;
            width: 100%;
            margin: 0 0 1.5rem 0;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            border-width: 1px;
            border-style: solid;
            font-size: 0.875rem;
            line-height: 1.45;
            font-weight: 500;
        }

        .product-pdp-notice--hidden {
            display: none !important;
        }

        .product-pdp-notice__text {
            margin: 0;
            padding: 0;
        }

        .product-pdp-notice--success {
            color: #065f46 !important;
            background-color: #ecfdf5 !important;
            border-color: #6ee7b7 !important;
        }

        .product-pdp-notice--warning {
            color: #92400e !important;
            background-color: #fffbeb !important;
            border-color: #fcd34d !important;
        }

        .product-pdp-notice--error {
            color: #991b1b !important;
            background-color: #fef2f2 !important;
            border-color: #fca5a5 !important;
        }

        .product-view .product-pdp-notice {
            position: relative;
            z-index: 20;
        }
    </style>
<?php $__env->stopPush(); endif; ?>

<?php if (! $__env->hasRenderedOnce('aaac4972-69db-48d1-9490-b900082ca888')): $__env->markAsRenderedOnce('aaac4972-69db-48d1-9490-b900082ca888');
$__env->startPush('scripts'); ?>
    <script>
        (function () {
            var params = new URLSearchParams(window.location.search);
            var type = params.get('cart_notice');
            var msg = params.get('cart_msg');

            if (!msg || ['success', 'warning', 'error'].indexOf(type) === -1) {
                return;
            }

            var box = document.getElementById('product-order-notice');

            if (!box) {
                return;
            }

            box.hidden = false;
            box.removeAttribute('aria-hidden');
            box.className = 'product-pdp-notice product-pdp-notice--' + type;
            box.setAttribute('data-notice-type', type);
            box.setAttribute('role', type === 'success' ? 'status' : 'alert');
            box.innerHTML = '<p class="product-pdp-notice__text"></p>';
            box.querySelector('.product-pdp-notice__text').textContent = msg;
            box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            params.delete('cart_notice');
            params.delete('cart_msg');

            var qs = params.toString();
            var next = window.location.pathname + (qs ? '?' + qs : '') + window.location.hash;

            window.history.replaceState({}, document.title, next);
        })();
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Shop/src/resources/views/products/view/partials/product-order-notice.blade.php ENDPATH**/ ?>