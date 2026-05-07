<!DOCTYPE html>

<html
    class="{{ request()->cookie('dark_mode') ?? 0 ? 'dark' : '' }}"
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>

<head>
    {!! view_render_event('bagisto.admin.layout.head.before') !!}

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
        content="{{ core()->getBaseCurrency()->toJson() }}"
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
        >
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

        /* TikTok Shop admin theme overrides */
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

        .text-blue-600,
        .dark\:text-darkBlue {
            color: var(--tiktok-primary) !important;
        }

        .dark .bg-gray-900 {
            background-color: var(--tiktok-bg) !important;
        }
    </style>

    {!! view_render_event('bagisto.admin.layout.head.after') !!}
</head>

<body class="h-full dark:bg-gray-950">
    {!! view_render_event('bagisto.admin.layout.body.before') !!}

    <!-- Built With Bagisto -->
    <div
        id="app"
        class="h-full"
    >
        <!-- Flash Message Blade Component -->
        <x-superadmin::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-superadmin::modal.confirm />

        {!! view_render_event('bagisto.admin.layout.content.before') !!}

        <!-- Page Header Blade Component -->
        <x-superadmin::layouts.header />

        <div
            id="superadmin-app-layout"
            class="group/container {{ request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }} flex flex-col lg:flex-row gap-0 lg:gap-4"
            ref="appLayout"
        >
            <!-- Page Sidebar Blade Component -->
            <div class="lg:fixed lg:top-[62px] lg:left-0 rtl:lg:right-0 rtl:lg:left-auto lg:z-10 w-full lg:w-auto">
                <x-superadmin::layouts.sidebar />
            </div>

            <div class="flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-white transition-all duration-300 dark:bg-gray-950 pt-3 px-2 sm:px-4 lg:pt-3 lg:px-4 lg:ltr:pl-[286px] lg:group-[.sidebar-collapsed]/container:ltr:pl-[85px] lg:rtl:pr-[286px] lg:group-[.sidebar-collapsed]/container:rtl:pr-[85px]">
                <!-- Added dynamic tabs for third level menus  -->
                <div class="pb-4 lg:pb-6">
                    <!-- Todo @suraj-webkul need to optimize below statement. -->
                    @if (! request()->routeIs('superadmin.configuration.index'))
                        <div class="overflow-x-auto">
                            <x-superadmin::layouts.tabs />
                        </div>
                    @endif

                    <!-- Page Content Blade Component -->
                    <div class="w-full overflow-x-hidden">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Powered By -->
                <div class="mt-auto">
                    <div class="border-t bg-white py-2 text-center text-xs sm:text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                        @lang('superadmin::app.components.layouts.powered-by.description', [
                            'bagisto' => '<a class="text-blue-600 hover:underline dark:text-darkBlue" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                            'webkul' => '<a class="text-blue-600 hover:underline dark:text-darkBlue" href="' . e(url('/')) . '">' . e(config('app.name')) . '</a>',
                        ])
                    </div>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.layout.content.after') !!}
    </div>

    {!! view_render_event('bagisto.admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.before') !!}

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.after') !!}
</body>

</html>
