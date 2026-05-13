<?php
    $tawkPropertyId = trim((string) config('services.tawk.property_id', ''));
    $tawkWidgetId = trim((string) config('services.tawk.widget_id', ''));
?>

<?php if($tawkPropertyId !== '' && $tawkWidgetId !== ''): ?>
    
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        <?php if(auth()->guard('admin')->check()): ?>
        Tawk_API.onLoad = function () {
            try {
                Tawk_API.setVisitorName(<?php echo json_encode(auth('admin')->user()->name ?? '', 15, 512) ?>);
                Tawk_API.setVisitorEmail(<?php echo json_encode(auth('admin')->user()->email ?? '', 15, 512) ?>);
            } catch (e) {}
        };
        <?php endif; ?>
        (function () {
            var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = <?php echo json_encode("https://embed.tawk.to/{$tawkPropertyId}/{$tawkWidgetId}", 15, 512) ?>;
            s1.charset = 'UTF-8';
            s1.crossOrigin = 'anonymous';
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Admin\src/resources/views/components/layouts/embed-tawk.blade.php ENDPATH**/ ?>