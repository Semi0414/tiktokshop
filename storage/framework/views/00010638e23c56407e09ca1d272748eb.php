<?php
    $tawkPropertyId = config('services.tawk.property_id');
    $tawkWidgetId = config('services.tawk.widget_id');
?>

<?php if(filled($tawkPropertyId) && filled($tawkWidgetId)): ?>
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        <?php if(auth()->guard('customer')->check()): ?>
        Tawk_API.onLoad = function () {
            try {
                Tawk_API.setVisitorName(<?php echo json_encode(auth('customer')->user()->name, 15, 512) ?>);
                Tawk_API.setVisitorEmail(<?php echo json_encode(auth('customer')->user()->email, 15, 512) ?>);
            } catch (e) {}
        };
        <?php endif; ?>
        (function () {
            var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/<?php echo e($tawkPropertyId); ?>/<?php echo e($tawkWidgetId); ?>';
            s1.charset = 'UTF-8';
            s1.crossOrigin = 'anonymous';
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/tawk-chat.blade.php ENDPATH**/ ?>