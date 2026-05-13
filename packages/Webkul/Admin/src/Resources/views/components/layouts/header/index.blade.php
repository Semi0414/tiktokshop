@php
    $admin = auth()->guard('admin')->user();
@endphp

<header class="sticky top-0 z-[10001] flex items-center justify-between border-b bg-white px-2 py-2 dark:border-gray-800 dark:bg-gray-900 sm:px-4 sm:py-2.5">
    <div class="flex items-center gap-1 sm:gap-1.5">
        <!-- Hamburger Menu -->
        <button
            type="button"
            class="icon-menu inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-xl text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950 lg:hidden sm:text-2xl"
            onclick="openAdminMobileSidebar()"
            aria-label="Menu"
        >
        </button>

        <!-- Logo -->
        <a href="{{ route('admin.dashboard.index') }}" class="flex-shrink-0">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    src="{{ Storage::url($logo) }}"
                    id="logo-image"
                    alt="{{ config('app.name') }}"
                    class="h-8 w-auto sm:h-10"
                />
            @else
                <img
                    src="{{ request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg') }}"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image"
                    data-theme-swap="1"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </a>

        <!-- Mega Search Bar Vue Component -->
        <v-mega-search class="hidden sm:block">
            <div class="relative flex w-[200px] items-center sm:w-[300px] md:w-[400px] lg:w-[525px] xl:max-w-[525px] ltr:ml-2 rtl:mr-2 sm:ltr:ml-2.5 sm:rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-xl ltr:left-2 rtl:right-2 sm:text-2xl sm:ltr:left-3 sm:rtl:right-3"></i>

                <input 
                    type="text" 
                    class="block w-full rounded-lg border bg-white px-8 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 sm:px-10 sm:text-base"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')" 
                >
            </div>
        </v-mega-search>
    </div>

    <div class="flex shrink-0 items-center gap-1 sm:gap-2.5">
        {{-- Native controls (no Vue): theme, notifications, profile always visible --}}
        <button
            type="button"
            id="admin-header-theme-toggle"
            class="inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-xl text-gray-600 transition-all hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950 sm:text-2xl"
            title="@lang('admin::app.components.layouts.header.toggle-theme')"
            aria-label="@lang('admin::app.components.layouts.header.toggle-theme')"
        >
            <span
                id="admin-header-theme-toggle-icon"
                class="{{ request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark' }}"
            ></span>
        </button>

        <!-- Visit Shop (store) — visible on all breakpoints -->
        <a
            href="{{ route('shop.tiktok-store.index') }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center rounded-md p-1.5 text-xl text-gray-600 transition-all hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950 sm:text-2xl"
            title="@lang('admin::app.components.layouts.header.visit-shop')"
        >
            <span class="icon-store" aria-hidden="true"></span>
            <span class="sr-only">@lang('admin::app.components.layouts.header.visit-shop')</span>
        </a>

        <a
            href="{{ route('admin.notification.index') }}"
            class="inline-flex items-center justify-center rounded-md p-1.5 text-xl text-gray-600 transition-all hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950 sm:text-2xl"
            title="@lang('admin::app.components.layouts.header.notifications')"
        >
            <span class="icon-notification" aria-hidden="true"></span>
            <span class="sr-only">@lang('admin::app.components.layouts.header.notifications')</span>
        </a>

        <!-- Admin profile (native <details>, no Vue dropdown) -->
        <details class="admin-header-profile relative z-[10002]">
            <summary
                class="flex cursor-pointer list-none items-center justify-center rounded-full outline-none ring-offset-2 hover:opacity-90 focus-visible:ring-2 focus-visible:ring-blue-500"
            >
                @if ($admin->image)
                    <span class="flex h-8 w-8 overflow-hidden rounded-full border border-gray-200 bg-white sm:h-9 sm:w-9 dark:border-gray-700">
                        <img
                            src="{{ $admin->image_url }}"
                            class="h-full w-full object-cover"
                            alt=""
                        />
                    </span>
                @else
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-400 text-xs font-semibold text-white sm:h-9 sm:w-9 sm:text-sm">
                        {{ mb_substr($admin->name, 0, 1) }}
                    </span>
                @endif
            </summary>

            <div
                class="absolute mt-2 min-w-[200px] rounded-md border border-gray-200 bg-white py-1 shadow-lg ltr:right-0 rtl:left-0 dark:border-gray-700 dark:bg-gray-900"
                role="menu"
            >
                <a
                    class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 sm:px-5 sm:text-base"
                    href="{{ route('admin.account.edit') }}"
                >
                    @lang('admin::app.components.layouts.header.my-account')
                </a>

                <form
                    method="POST"
                    action="{{ route('admin.session.destroy') }}"
                    class="m-0 border-t border-gray-100 dark:border-gray-800"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full cursor-pointer px-4 py-2 text-left text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 sm:px-5 sm:text-base"
                    >
                        @lang('admin::app.components.layouts.header.logout')
                    </button>
                </form>
            </div>
        </details>
    </div>
</header>

<style>
    .admin-header-profile > summary::-webkit-details-marker {
        display: none;
    }
    .admin-header-profile > summary::marker {
        display: none;
        content: '';
    }
</style>

<script>
    (function adminHeaderNativeTheme() {
        function applyAdminTheme(isDark) {
            var expiry = new Date();
            expiry.setMonth(expiry.getMonth() + 1);
            document.cookie = 'dark_mode=' + (isDark ? 1 : 0) + '; path=/; expires=' + expiry.toUTCString();
            document.documentElement.classList.toggle('dark', !!isDark);

            var icon = document.getElementById('admin-header-theme-toggle-icon');
            if (icon) {
                icon.className = isDark ? 'icon-light' : 'icon-dark';
            }

            var logo = document.getElementById('logo-image');
            if (logo && logo.dataset.themeSwap === '1') {
                logo.src = isDark
                    ? @json(bagisto_asset('images/dark-logo.svg'))
                    : @json(bagisto_asset('images/logo.svg'));
            }

            try {
                if (window.emitter && typeof window.emitter.emit === 'function') {
                    window.emitter.emit('change-theme', isDark ? 'dark' : 'light');
                }
            } catch (e) {}
        }

        var btn = document.getElementById('admin-header-theme-toggle');
        if (!btn) {
            return;
        }

        btn.addEventListener('click', function () {
            var next = document.documentElement.classList.contains('dark') ? 0 : 1;
            applyAdminTheme(next);
        });

        document.querySelectorAll('.admin-header-profile').forEach(function (el) {
            el.addEventListener('toggle', function () {
                if (!this.open) {
                    return;
                }
                document.querySelectorAll('.admin-header-profile[open]').forEach(function (other) {
                    if (other !== el) {
                        other.removeAttribute('open');
                    }
                });
            });
        });

        document.addEventListener('click', function (e) {
            document.querySelectorAll('.admin-header-profile[open]').forEach(function (el) {
                if (!el.contains(e.target)) {
                    el.removeAttribute('open');
                }
            });
        });
    })();
</script>

<!-- HTML Mobile Sidebar Fallback -->
<div
    id="admin-mobile-sidebar-overlay"
    onclick="closeAdminMobileSidebar()"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:10050;"
></div>

<aside
    id="admin-mobile-sidebar-panel"
    style="display:none; position:fixed; top:0; left:0; width:270px; max-width:85vw; height:100vh; background:#fff; z-index:10051; overflow-y:auto; box-shadow:0 10px 30px rgba(0,0,0,0.2);"
>
    <div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #e5e7eb; padding:12px; display:flex; align-items:center; justify-content:space-between;">
        <strong style="font-size:14px;">Menu</strong>
        <button type="button" onclick="closeAdminMobileSidebar()" style="border:none; background:transparent; font-size:24px; line-height:1; cursor:pointer;">&times;</button>
    </div>

    <nav class="p-2.5">
        <x-admin::layouts.mobile-nav-menu />
    </nav>
</aside>

<!-- Menu Sidebar Drawer -->
<x-admin::drawer
    position="left"
    width="270px"
    ref="sidebarMenuDrawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex items-center justify-between">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    src="{{ Storage::url($logo) }}"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image-drawer"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    src="{{ request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg') }}"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image-drawer"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </div>
    </x-slot>

    <x-slot:content class="p-3 sm:p-4">
        <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto">
            <nav class="grid w-full gap-1.5 sm:gap-2">
                <x-admin::layouts.mobile-nav-menu />
            </nav>
        </div>
    </x-slot>
</x-admin::drawer>

@pushOnce('scripts')
    <script>
        function openAdminMobileSidebar() {
            var overlay = document.getElementById('admin-mobile-sidebar-overlay');
            var panel = document.getElementById('admin-mobile-sidebar-panel');

            if (overlay && panel) {
                overlay.style.display = 'block';
                panel.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAdminMobileSidebar() {
            var overlay = document.getElementById('admin-mobile-sidebar-overlay');
            var panel = document.getElementById('admin-mobile-sidebar-panel');

            if (overlay && panel) {
                overlay.style.display = 'none';
                panel.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    </script>

    <script
        type="text/x-template"
        id="v-mega-search-template"
    >
        <div class="relative flex w-[200px] items-center sm:w-[300px] md:w-[400px] lg:w-[525px] xl:max-w-[525px] ltr:ml-2 rtl:mr-2 sm:ltr:ml-2.5 sm:rtl:mr-2.5">
            <i class="icon-search absolute top-1.5 flex items-center text-xl ltr:left-2 rtl:right-2 sm:text-2xl sm:ltr:left-3 sm:rtl:right-3"></i>

            <input 
                type="text"
                class="peer block w-full rounded-lg border bg-white px-8 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 sm:px-10 sm:text-base"
                :class="{'border-gray-400': isDropdownOpen}"
                placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-8 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] dark:border-gray-800 dark:bg-gray-900 sm:top-10"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b text-xs text-gray-600 dark:border-gray-800 dark:text-gray-300 sm:text-sm">
                    <div
                        class="cursor-pointer p-2 hover:bg-gray-100 dark:hover:bg-gray-950 sm:p-4"
                        :class="{ 'border-b-2 border-blue-600': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
                    >
                        @{{ tab.title }}
                    </div>
                </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.products />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', product.id)"
                                class="flex cursor-pointer justify-between gap-2 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950 sm:gap-2.5 sm:p-4"
                                v-for="product in searchedResults.products.data"
                            >
                                <!-- Left Information -->
                                <div class="flex gap-2 sm:gap-2.5">
                                    <!-- Image -->
                                    <div
                                        class="relative h-10 max-h-10 w-full max-w-10 overflow-hidden rounded sm:h-[60px] sm:max-h-[60px] sm:max-w-[60px]"
                                        :class="{'overflow-hidden rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert': ! product.images.length}"
                                    >
                                        <template v-if="! product.images.length">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}" class="h-full w-full object-cover">
                                        
                                            <p class="absolute bottom-0.5 w-full text-center text-[4px] font-semibold text-gray-400 sm:bottom-1.5 sm:text-[6px]">
                                                @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                            </p>
                                        </template>

                                        <template v-else>
                                            <img :src="product.images[0].url" class="h-full w-full object-cover">
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid place-content-start gap-1 sm:gap-1.5">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                            @{{ product.name }}
                                        </p>

                                        <p class="text-xs text-gray-500 sm:text-sm">
                                            @{{ "@lang('admin::app.components.layouts.header.mega-search.sku')".replace(':sku', product.sku) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Information -->
                                <div class="grid place-content-center gap-1 text-right">
                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                        @{{ product.formatted_price }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'{{ route('admin.catalog.products.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.products.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-products')".replace(':query', searchTerm).replace(':count', searchedResults.products.meta.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.products.index') }}"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-products')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'orders'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.orders />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'{{ route('admin.sales.orders.view', ':id') }}'.replace(':id', order.id)"
                                class="grid cursor-pointer place-content-start gap-1 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950 sm:gap-1.5 sm:p-4"
                                v-for="order in searchedResults.orders.data"
                            >
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 sm:text-base">
                                    #@{{ order.increment_id }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-300 sm:text-sm">
                                    @{{ order.formatted_created_at + ', ' + order.status_label + ', ' + order.customer_full_name }}
                                </p>
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'{{ route('admin.sales.orders.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.orders.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-orders')".replace(':query', searchTerm).replace(':count', searchedResults.orders.total) }}
                            </a>

                            <a
                                href="{{ route('admin.sales.orders.index') }}"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-orders')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'categories'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
                            <a
                                :href="'{{ route('admin.catalog.categories.edit', ':id') }}'.replace(':id', category.id)"
                                class="cursor-pointer border-b p-3 text-xs font-semibold text-gray-600 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950 sm:p-4 sm:text-sm"
                                v-for="category in searchedResults.categories.data"
                            >
                                @{{ category.name }}
                            </a>
                        </div>

                        <div class="flex border-t p-2 dark:border-gray-800 sm:p-3">
                            <a
                                :href="'{{ route('admin.catalog.categories.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-if="searchedResults.categories.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-categories')".replace(':query', searchTerm).replace(':count', searchedResults.categories.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.categories.index') }}"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-categories')
                            </a>
                        </div>
                    </template>
                </template>

            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'products',

                    isDropdownOpen: false,

                    tabs: {
                        products: {
                            key: 'products',
                            title: "@lang('admin::app.components.layouts.header.mega-search.products')",
                            is_active: true,
                            endpoint: "{{ route('admin.catalog.products.search') }}"
                        },
                        
                        orders: {
                            key: 'orders',
                            title: "@lang('admin::app.components.layouts.header.mega-search.orders')",
                            endpoint: "{{ route('admin.sales.orders.search') }}"
                        },
                        
                        categories: {
                            key: 'categories',
                            title: "@lang('admin::app.components.layouts.header.mega-search.categories')",
                            endpoint: "{{ route('admin.catalog.categories.search') }}"
                        }
                    },

                    isLoading: false,

                    searchTerm: '',

                    searchedResults: {
                        products: [],
                        orders: [],
                        categories: []
                    },
                }
            },

            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search()
                }
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            methods: {
                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedResults[this.activeTab] = [];

                        this.isDropdownOpen = false;

                        return;
                    }

                    this.isDropdownOpen = true;

                    let self = this;

                    this.isLoading = true;
                    
                    this.$axios.get(this.tabs[this.activeTab].endpoint, {
                            params: {query: this.searchTerm}
                        })
                        .then(function(response) {
                            self.searchedResults[self.activeTab] = response.data;

                            self.isLoading = false;
                        })
                        .catch(function (error) {
                        })
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target)) {
                        this.isDropdownOpen = false;
                    }
                },
            }
        });
    </script>

    <script
        type="text/x-template"
        id="v-notifications-template"
    >
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <!-- Notification Toggle -->
            <x-slot:toggle>
                <button
                    type="button"
                    class="relative flex cursor-pointer items-center border-0 bg-transparent p-0"
                    aria-haspopup="menu"
                >
                    <span
                        class="icon-notification text-red cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                        title="@lang('admin::app.components.layouts.header.notifications')"
                        aria-hidden="true"
                    >
                    </span>

                    <span
                        class="absolute -top-2 flex h-5 min-w-5 cursor-pointer items-center justify-center rounded-full bg-blue-600 p-1.5 text-[10px] font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5"
                        v-if="totalUnRead"
                    >
                        @{{ totalUnRead }}
                    </span>
                </button>
            </x-slot>

            <!-- Notification Content -->
            <x-slot:content class="min-w-[250px] max-w-[250px] !p-0">
                <!-- Header -->
                <div class="border-b p-3 text-base font-semibold text-gray-600 dark:border-gray-800 dark:text-gray-300">
                    @lang('admin::app.notifications.title', ['read' => 0])
                </div>

                <ul
                    class="m-0 max-h-[min(60vh,360px)] list-none overflow-y-auto p-0"
                    role="list"
                >
                    <li
                        v-for="(notification, idx) in notifications"
                        :key="notification.id != null ? String(notification.id) : 'n-' + String(idx)"
                        class="min-w-0 border-b border-gray-100 last:border-b-0 dark:border-gray-800"
                        role="listitem"
                    >
                        <a
                            v-if="notificationOpenHref(notification)"
                            class="flex items-start gap-1.5 p-3 hover:bg-gray-50 dark:hover:bg-gray-950"
                            :href="notificationOpenHref(notification)"
                        >
                            <span
                                v-if="notification.summary && !notification.order_id"
                                class="icon-information h-fit shrink-0 rounded-full bg-blue-100 text-2xl text-blue-600 dark:!text-blue-600"
                                aria-hidden="true"
                            >
                            </span>

                            <span
                                v-else-if="notification.order && notification.order.status && notification.order.status in notificationStatusIcon"
                                class="h-fit shrink-0"
                                :class="notificationStatusIcon[notification.order.status]"
                                aria-hidden="true"
                            >
                            </span>

                            <div class="grid min-w-0 flex-1">
                                <p
                                    v-if="notification.summary && !notification.order_id"
                                    class="m-0 text-gray-800 dark:text-white"
                                >
                                    @{{ notification.summary }}
                                </p>

                                <p
                                    v-else-if="notification.order"
                                    class="m-0 text-gray-800 dark:text-white"
                                >
                                    #@{{ notification.order.id }}
                                    @{{ orderTypeMessages[notification.order.status] ?? notification.order.status }}
                                </p>

                                <p
                                    v-else
                                    class="m-0 text-gray-800 dark:text-white"
                                >
                                    @lang('admin::app.notifications.title')
                                </p>

                                <p
                                    v-if="notification.order && notification.order.datetime"
                                    class="m-0 text-xs text-gray-600 dark:text-gray-300"
                                >
                                    @{{ notification.order.datetime }}
                                </p>

                                <p
                                    v-else-if="notification.created_at"
                                    class="m-0 text-xs text-gray-600 dark:text-gray-300"
                                >
                                    @{{ notification.created_at }}
                                </p>
                            </div>
                        </a>

                        <div
                            v-else
                            class="flex items-start gap-1.5 p-3"
                            role="group"
                            aria-label="@lang('admin::app.notifications.item-without-order-link')"
                        >
                            <p class="m-0 text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.notifications.title')
                            </p>
                        </div>
                    </li>
                </ul>

                <div class="flex h-[47px] items-center justify-between gap-1.5 border-t px-6 py-4 dark:border-gray-800">
                    <a
                        href="{{ route('admin.notification.index') }}"
                        class="cursor-pointer text-xs font-semibold text-blue-600 transition-all hover:underline"
                    >
                        @lang('admin::app.notifications.view-all')
                    </a>

                    <button
                        type="button"
                        class="cursor-pointer border-0 bg-transparent p-0 text-xs font-semibold text-blue-600 transition-all hover:underline disabled:cursor-not-allowed disabled:opacity-50"
                        v-if="notifications?.length"
                        @click="readAll()"
                    >
                        @lang('admin::app.notifications.read-all')
                    </button>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </script>

    <script type="module">
        window.app.component('v-notifications', {
            template: '#v-notifications-template',

                props: [
                    'getReadAllUrl',
                    'readAllTitle',
                ],

                data() {
                    return {
                        notifications: [],

                        ordertype: {
                            pending: {
                                icon: 'icon-information',
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },

                            processing: {
                                icon: 'icon-processing',
                                message: "@lang('admin::app.notifications.order-status-messages.processing')",
                            },

                            canceled: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.canceled')"
                            },

                            completed: {
                                icon: 'icon-done',
                                message: "@lang('admin::app.notifications.order-status-messages.completed')"
                            },

                            closed: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.closed')"
                            },

                            pending_payment: {
                                icon: "icon-information",
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },
                        },

                        totalUnRead: 0,

                        orderTypeMessages: {
                            {{ \Webkul\Sales\Models\Order::STATUS_PENDING }}: "@lang('admin::app.notifications.order-status-messages.pending')",
                            {{ \Webkul\Sales\Models\Order::STATUS_CANCELED }}: "@lang('admin::app.notifications.order-status-messages.canceled')",
                            {{ \Webkul\Sales\Models\Order::STATUS_CLOSED }}: "@lang('admin::app.notifications.order-status-messages.closed')",
                            {{ \Webkul\Sales\Models\Order::STATUS_COMPLETED }}: "@lang('admin::app.notifications.order-status-messages.completed')",
                            {{ \Webkul\Sales\Models\Order::STATUS_PROCESSING }}: "@lang('admin::app.notifications.order-status-messages.processing')",
                            {{ \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT }}: "@lang('admin::app.notifications.order-status-messages.pending-payment')",
                        }
                    }
                },

                computed: {
                    notificationStatusIcon() {
                        return {
                            pending: 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600 dark:!text-amber-600',
                            pending_payment: 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600 dark:!text-amber-600',
                            closed: 'icon-repeat rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
                            completed: 'icon-done rounded-full bg-blue-100 text-2xl text-blue-600 dark:!text-blue-600',
                            canceled: 'icon-cancel-1 rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
                            processing: 'icon-sort-right rounded-full bg-green-100 text-2xl text-green-600 dark:!text-green-600',
                        };
                    },
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    notificationOpenHref(notification) {
                        if (notification.open_url) {
                            return notification.open_url;
                        }

                        if (notification.order_id) {
                            return this.notificationHref(notification.order_id);
                        }

                        return '';
                    },

                    notificationHref(orderId) {
                        return '{{ route('admin.notification.viewed_notification', ':orderId') }}'.replace(':orderId', String(orderId));
                    },

                    getNotification() {
                        this.$axios.get('{{ route('admin.notification.get_notification') }}', {
                                params: {
                                    limit: 5,
                                    read: 0
                                }
                            })
                            .then((response) => {
                                const sr = response.data?.search_results;
                                this.notifications = (sr && Array.isArray(sr.data)) ? sr.data : [];
                                this.totalUnRead = response.data?.total_unread ?? 0;
                            })
                            .catch(() => {
                                this.notifications = [];
                                this.totalUnRead = 0;
                            });
                    },

                    readAll() {
                        this.$axios.post('{{ route('admin.notification.read_all') }}')
                            .then((response) => {
                                const sr = response.data?.search_results;
                                this.notifications = (sr && Array.isArray(sr.data)) ? sr.data : [];
                                this.totalUnRead = response.data?.total_unread ?? 0;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data?.success_message });
                            })
                            .catch(() => {});
                    },
                },
        });
    </script>

    <script
        type="text/x-template"
        id="v-dark-template"
    >
        <div class="flex">
            <span
                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('dark_mode') ?? 0 }},

                    logo: "{{ bagisto_asset('images/logo.svg') }}",

                    dark_logo: "{{ bagisto_asset('images/dark-logo.svg') }}",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    const logoEl = document.getElementById('logo-image');

                    if (this.isDarkMode) {
                        this.$emitter.emit('change-theme', 'dark');

                        if (logoEl && logoEl.dataset.themeSwap === '1') {
                            logoEl.src = this.dark_logo;
                        }
                    } else {
                        this.$emitter.emit('change-theme', 'light');

                        if (logoEl && logoEl.dataset.themeSwap === '1') {
                            logoEl.src = this.logo;
                        }
                    }
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
@endpushOnce