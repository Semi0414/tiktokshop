@php
    $tawkPropertyId = trim((string) config('services.tawk.property_id', ''));
    $tawkWidgetId = trim((string) config('services.tawk.widget_id', ''));
@endphp

@if ($tawkPropertyId !== '' && $tawkWidgetId !== '')
    {{-- Same embed pattern as storefront <x-shop::layouts.tawk-chat /> (landing / tik-store index). IDs: TAWK_PROPERTY_ID, TAWK_WIDGET_ID in .env. --}}
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        @auth('admin')
        Tawk_API.onLoad = function () {
            try {
                Tawk_API.setVisitorName(@json(auth('admin')->user()->name ?? ''));
                Tawk_API.setVisitorEmail(@json(auth('admin')->user()->email ?? ''));
            } catch (e) {}
        };
        @endauth
        (function () {
            var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = @json("https://embed.tawk.to/{$tawkPropertyId}/{$tawkWidgetId}");
            s1.charset = 'UTF-8';
            s1.crossOrigin = 'anonymous';
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif
