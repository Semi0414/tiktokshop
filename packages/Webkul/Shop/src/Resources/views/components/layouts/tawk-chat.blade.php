@php
    $tawkPropertyId = config('services.tawk.property_id');
    $tawkWidgetId = config('services.tawk.widget_id');
@endphp

@if (filled($tawkPropertyId) && filled($tawkWidgetId))
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        @auth('customer')
        Tawk_API.onLoad = function () {
            try {
                Tawk_API.setVisitorName(@json(auth('customer')->user()->name));
                Tawk_API.setVisitorEmail(@json(auth('customer')->user()->email));
            } catch (e) {}
        };
        @endauth
        (function () {
            var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/{{ $tawkPropertyId }}/{{ $tawkWidgetId }}';
            s1.charset = 'UTF-8';
            s1.crossOrigin = 'anonymous';
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif
