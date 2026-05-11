{{-- Categories in header hidden by default; use :hide-header-categories="false" on <x-shop::layouts> to show them again --}}
@props([
    'hasHeader'               => true,
    'hasFeature'              => true,
    'hasFooter'               => true,
    'hideHeaderCategories'    => true,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >
        <meta
            name="generator"
            content="{{ config('storefront-branding.legacy_tiktok_branding') ? 'TikTok Mall' : config('storefront-branding.marketplace_name') }}"
        >

        @stack('meta')

        <link
            rel="icon"
            type="image/webp"
            sizes="16x16"
            href="{{ asset('storage/theme/1/favicon.webp') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        />

        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        />

        {{-- Non-blocking fonts: avoid render-blocking on slow networks (load as print, then apply) --}}
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
            media="print"
            onload="this.media='all'"
        />

        <noscript>
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
            />
        </noscript>

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}

            /* TikTok Shop storefront theme overrides */
            :root {
                --tiktok-primary: #ff0050;
                --tiktok-primary-hover: #e60048;
                --tiktok-bg: #ffffff;
                --tiktok-surface: #0b0b0b;
                --tiktok-border: #222222;
                --tiktok-text: #111111;
                --tiktok-muted: #a1a1aa;
            }

            body {
                background: var(--tiktok-bg);
                color: var(--tiktok-text);
            }

            /* Disable custom global background image */
            body::before {
                display: none !important;
                content: none !important;
            }

            body,
            #app,
            main,
            header,
            footer {
                background-image: none !important;
            }

            #app {
                position: relative;
                z-index: 1;
            }

            a {
                color: var(--tiktok-primary);
            }

            a:hover {
                color: var(--tiktok-primary-hover);
            }

            /* Force any remaining blue accents to TikTok red */
            .text-blue-600,
            .text-blue-700,
            .text-blue-800,
            .text-navyBlue,
            .hover\:text-blue-600:hover,
            .hover\:text-blue-700:hover,
            .hover\:text-blue-800:hover {
                color: var(--tiktok-primary) !important;
            }

            .bg-blue-600,
            .bg-blue-700 {
                background-color: var(--tiktok-primary) !important;
                border-color: var(--tiktok-primary) !important;
            }

            .primary-button,
            .bg-navyBlue {
                background-color: var(--tiktok-primary) !important;
                border-color: var(--tiktok-primary) !important;
            }

            .primary-button:hover,
            .bg-navyBlue:hover {
                background-color: var(--tiktok-primary-hover) !important;
                border-color: var(--tiktok-primary-hover) !important;
            }

            .secondary-button {
                border-color: var(--tiktok-primary) !important;
                color: var(--tiktok-primary) !important;
            }

            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(255, 0, 80, 0.35) !important;
                border-color: var(--tiktok-primary) !important;
            }

            /* Keep surfaces white */
            header,
            footer,
            .bg-white {
                background-color: #ffffff !important;
            }

            .border-zinc-200,
            .border-gray-200,
            .border-\[\#F3F3F3\] {
                border-color: var(--tiktok-border) !important;
            }

            .text-zinc-500,
            .text-gray-500 {
                color: var(--tiktok-muted) !important;
            }

            /* Auth pages (login/signup/forgot/reset) card styling */
            .auth-card {
                background: rgba(255, 255, 255, 0.88) !important;
                color: #111827 !important;
                border-color: rgba(255, 255, 255, 0.35) !important;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            }

            .auth-card .text-zinc-500,
            .auth-card .text-gray-500 {
                color: #4b5563 !important;
            }

            .auth-card a {
                color: var(--tiktok-primary) !important;
            }

            .auth-card input,
            .auth-card select,
            .auth-card textarea {
                background: rgba(255, 255, 255, 0.95) !important;
                color: #111827 !important;
                border-color: rgba(17, 24, 39, 0.15) !important;
            }

            /* User-requested readability: white text -> black; preserve non-white colors */
            .text-white {
                color: #111111 !important;
            }

            .bg-black,
            .bg-black\/90,
            .bg-black\/80,
            .bg-black\/70 {
                background-color: #ffffff !important;
            }

            /* Force any explicit white text (utility/inline styles) to black */
            [class*="text-white"],
            [style*="color: white"],
            [style*="color:white"],
            [style*="color:#fff"],
            [style*="color: #fff"],
            [style*="color:#ffffff"],
            [style*="color: #ffffff"] {
                color: #111111 !important;
            }
        </style>

        @if(core()->getConfigData('general.content.speculation_rules.enabled'))
            <script type="speculationrules">
                @json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endif

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <!-- Built With Bagisto -->
        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop::layouts.header :hide-header-categories="$hideHeaderCategories" />
            @endif

            @if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            )
                <x-shop::layouts.cookie />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white text-black">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.layout.content.after') !!}


            <!-- Page Services Blade Component -->
            @if ($hasFeature)
                <x-shop::layouts.services />
            @endif

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @include('shop::components.layouts.storefront-chat-widgets')

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
    </body>
</html>
