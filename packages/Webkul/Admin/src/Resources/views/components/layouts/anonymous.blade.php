<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>

<head>
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
        name="generator" 
        content="TikTok Shop"
    >

    @stack('meta')

    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
        <link
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}"
            rel="shortcut icon"
            sizes="16x16"
        />
    @else
        <link
            type="image/x-icon"
            href="/storage/theme/1/favicon.webp"
            rel="shortcut icon"
            sizes="16x16"
        />
    @endif

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}

        /* TikTok Shop admin (anonymous) theme overrides */
        :root {
            --tiktok-primary: #ff0050;
            --tiktok-bg: #000000;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            /* background-image: url("/storage/theme/1/tiktok_background.jpeg"); */
            background-color: #ffffff;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 1;
            pointer-events: none;
            z-index: 0;
        }

        #app {
            position: relative;
            z-index: 1;
        }

        .primary-button,
        .bg-blue-600 {
            background-color: var(--tiktok-primary) !important;
            border-color: var(--tiktok-primary) !important;
        }

        .text-blue-600 {
            color: var(--tiktok-primary) !important;
        }

        body {
            background-color: #000000;
        }
    </style>

    {!! view_render_event('bagisto.admin.layout.head') !!}
</head>

<body>
    {!! view_render_event('bagisto.admin.layout.body.before') !!}

    <!-- Built With Bagisto -->
    <div id="app">
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        {!! view_render_event('bagisto.admin.layout.content.before') !!}

        <!-- Page Content Blade Component -->
        {{ $slot }}

        {!! view_render_event('bagisto.admin.layout.content.after') !!}
    </div>

    {!! view_render_event('bagisto.admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.before') !!}

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.after') !!}

    @include('admin::components.layouts.embed-tawk')

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
</body>

</html>
