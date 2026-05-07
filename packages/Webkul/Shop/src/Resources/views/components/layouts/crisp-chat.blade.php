@php
    $crispWebsiteId = config('services.crisp.website_id');
@endphp

@if (filled($crispWebsiteId))
    {{-- Crisp Web SDK: https://docs.crisp.chat/guides/chatbox-sdks/web-sdk/ --}}
    <script type="text/javascript">
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = @json($crispWebsiteId);
        (function () {
            var d = document;
            var s = d.createElement('script');
            s.src = 'https://client.crisp.chat/l.js';
            s.async = true;
            d.getElementsByTagName('head')[0].appendChild(s);
        })();
    </script>

    @auth('customer')
        @php
            $customer = auth()->user();
        @endphp
        <script type="text/javascript">
            window.$crisp = window.$crisp || [];
            $crisp.push(['set', 'user:email', [@json($customer->email)]]);
            $crisp.push(['set', 'user:nickname', [@json($customer->name)]]);
        </script>
    @endauth
@endif
