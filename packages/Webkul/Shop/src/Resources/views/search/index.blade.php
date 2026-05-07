<?php
    $searchTitle = $suggestion ?? $query;
    $title = $searchTitle ? trans('shop::app.search.title', ['query' => $searchTitle]) : trans('shop::app.search.results');
    $searchInstead = $suggestion ? $query : null;
?>
<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="{{ $title }}"
    />

    <meta
        name="keywords"
        content="{{ $title }}"
    />
@endPush

<x-shop::layouts :has-feature="false">
    <!-- Page Title -->
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        @if (request()->has('image-search'))
            @include('shop::search.images.results')
        @endif

        <div class="mt-8 flex items-center justify-between max-md:mt-5">
            <h2 class="break-all text-2xl font-medium max-sm:text-base">
                <span v-text="'{{ preg_replace('/[,\\"\\\']+/', '', $title) }}'" ></span>
            </h2>
        </div>

        @if ($searchInstead)
            <form
                action="{{ route('shop.search.index', ['suggest' => false]) }}"
                class="flex max-w-[445px] items-center"
                role="search"
            >
                <input
                    type="text"
                    name="query"
                    class="hidden"
                    value="{{ $searchInstead }}"
                >

                <input
                    type="text"
                    name="suggest"
                    class="hidden"
                    value="0"
                >

                <p
                    class="mt-1 text-sm text-gray-600"
                    v-pre
                >
                    {{ trans('shop::app.search.suggest') }}

                    <button
                        type="submit"
                        class="text-blue-600 hover:text-blue-800 hover:underline"
                        aria-label="{{ trans('shop::app.components.layouts.header.desktop.bottom.submit') }}"
                    >
                        {{ $searchInstead }}
                    </button>
                </p>
            </form>
        @endif

        @php
            $categoryBarItems = [
                ['name' => 'Food & Beverage', 'image' => asset('themes/shop/default/category-bar/food-beverage.jpg')],
                ['name' => "Men's Clothing", 'image' => asset('themes/shop/default/category-bar/mens-clothing.jpg')],
                ['name' => "Women's Clothing", 'image' => asset('themes/shop/default/category-bar/womens-clothing.jpg')],
                ['name' => 'Snack Dessert', 'image' => asset('themes/shop/default/category-bar/snack-dessert.png')],
                ['name' => 'Recreational Fishing Gear', 'image' => asset('themes/shop/default/category-bar/recreational-fishing-gear.png')],
                ['name' => 'Phones & Accessories', 'image' => asset('themes/shop/default/category-bar/phones-accessories.jpg')],
                ['name' => 'Epidemic Prevention Supplies', 'image' => asset('themes/shop/default/category-bar/epidemic-prevention-supplies.jpg')],
                ['name' => 'Office Stationery', 'image' => asset('themes/shop/default/category-bar/office-stationery.jpg')],
                ['name' => 'Computer Peripherals', 'image' => asset('themes/shop/default/category-bar/computer-peripherals.jpg')],
                ['name' => 'Digital Products', 'image' => asset('themes/shop/default/category-bar/digital-products.jpg')],
                ['name' => 'Sports & Outdoors', 'image' => asset('themes/shop/default/category-bar/sports-outdoors.jpg')],
                ['name' => 'Home Appliances', 'image' => asset('themes/shop/default/category-bar/home-appliances.jpg')],
                ['name' => 'Health Beauty & Hair', 'image' => asset('themes/shop/default/category-bar/health-beauty-hair.jpg')],
                ['name' => 'Kids & Babies', 'image' => asset('themes/shop/default/category-bar/kids-babies.jpg')],
                ['name' => 'Jewelry & Watches', 'image' => asset('themes/shop/default/category-bar/jewelry-watches.jpg')],
                ['name' => 'Kids Toys', 'image' => asset('themes/shop/default/category-bar/kids-toys.jpg')],
                ['name' => 'Luxury', 'image' => asset('themes/shop/default/category-bar/luxury.jpg')],
                ['name' => "Men's Bag", 'image' => asset('themes/shop/default/category-bar/mens-bag.jpg')],
                ['name' => 'Ladies Bag', 'image' => asset('themes/shop/default/category-bar/ladies-bag.jpg')],
            ];

            $categoryUrlMap = \Illuminate\Support\Facades\DB::table('category_translations')
                ->where('locale', app()->getLocale())
                ->whereNotNull('name')
                ->whereNotNull('url_path')
                ->pluck('url_path', 'name')
                ->all();

            $loopedCategoryBarItems = array_merge($categoryBarItems, $categoryBarItems);
        @endphp

        <div class="mt-6 rounded-xl border-zinc-200 bg-white p-4 max-md:mt-4">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zinc-900">Shop by Category</h3>

                <a
                    href="{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}"
                    class="text-sm font-medium text-zinc-700 hover:text-zinc-900"
                >
                    View All
                </a>
            </div>

            <div class="relative">
                <button
                    type="button"
                    class="absolute -left-2 top-[38%] z-10 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-zinc-200 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 lg:flex"
                    aria-label="Previous categories"
                    onclick="window.scrollCategoryBar(-1)"
                >
                    ‹
                </button>

                <button
                    type="button"
                    class="absolute -right-2 top-[38%] z-10 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-zinc-200 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 lg:flex"
                    aria-label="Next categories"
                    onclick="window.scrollCategoryBar(1)"
                >
                    ›
                </button>

                <div
                    id="shop-category-carousel"
                    class="scrollbar-hide overflow-x-auto scroll-smooth"
                >
                <div class="flex min-w-max items-start gap-4 pb-1">
                    @foreach ($loopedCategoryBarItems as $barItem)
                        @php
                            $categoryPath = $categoryUrlMap[$barItem['name']] ?? null;
                            $categoryHref = $categoryPath
                                ? route('shop.product_or_category.index', $categoryPath)
                                : route('shop.search.index', ['query' => $barItem['name']]);
                        @endphp
                        <div class="w-[110px] flex-shrink-0 text-center">
                            <a
                                href="{{ $categoryHref }}"
                                class="mx-auto flex h-[92px] w-[92px] items-center justify-center rounded-full border border-zinc-200 bg-white p-2 transition-all hover:border-zinc-300 hover:shadow-sm"
                            >
                                <img
                                    src="{{ $barItem['image'] }}"
                                    alt="{{ $barItem['name'] }}"
                                    class="h-[64px] w-[64px] rounded-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>

                            <p class="mt-2 line-clamp-2 text-[12px] font-medium leading-4 text-zinc-700">
                                {{ $barItem['name'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Listing -->
    <v-search>
        <x-shop::shimmer.categories.view />
    </v-search>

    @pushOnce('scripts')
        <script>
            window.scrollCategoryBar = function(direction) {
                const track = document.getElementById('shop-category-carousel');

                if (! track) {
                    return;
                }

                track.scrollBy({
                    left: direction * 420,
                    behavior: 'smooth',
                });
            };

            document.addEventListener('DOMContentLoaded', function () {
                const track = document.getElementById('shop-category-carousel');

                if (! track) {
                    return;
                }

                let autoScrollTimer = null;

                const getLoopWidth = () => Math.floor(track.scrollWidth / 2);

                const startAutoScroll = () => {
                    if (autoScrollTimer) {
                        return;
                    }

                    autoScrollTimer = window.setInterval(() => {
                        const loopWidth = getLoopWidth();

                        if (track.scrollLeft >= loopWidth) {
                            track.scrollLeft = track.scrollLeft - loopWidth;
                        }

                        track.scrollBy({
                            left: 220,
                            behavior: 'smooth',
                        });
                    }, 2500);
                };

                const stopAutoScroll = () => {
                    if (! autoScrollTimer) {
                        return;
                    }

                    window.clearInterval(autoScrollTimer);
                    autoScrollTimer = null;
                };

                track.addEventListener('mouseenter', stopAutoScroll);
                track.addEventListener('mouseleave', startAutoScroll);
                track.addEventListener('touchstart', stopAutoScroll, { passive: true });
                track.addEventListener('touchend', startAutoScroll, { passive: true });

                startAutoScroll();
            });
        </script>

        <script
            type="text/x-template"
            id="v-search-template"
        >
            <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
                <div class="flex items-start gap-10 max-lg:gap-5 md:mt-10">
                    <!-- Product Listing Filters -->
                    @include('shop::categories.filters')

                    <!-- Product Listing Container -->
                    <div class="flex-1">
                        <!-- Desktop Product Listing Toolbar -->
                        <div class="max-md:hidden">
                            @include('shop::categories.toolbar')
                        </div>

                        <!-- Product List Card Container -->
                        <div
                            class="mt-8 grid grid-cols-1 gap-6"
                            v-if="(filters.toolbar.applied.mode ?? filters.toolbar.default.mode) === 'list'"
                        >
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.cards.list count="12" />
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <x-shop::products.card
                                        ::mode="'list'"
                                        v-for="product in products"
                                    />
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                                        <img
                                            class="max-sm:h-[100px] max-sm:w-[100px]"
                                            src="{{ bagisto_asset('images/thank-you.png') }}"
                                            alt="Empty result"
                                            loading="lazy"
                                            decoding="async"
                                        />

                                        <p
                                            class="text-xl max-sm:text-sm"
                                            role="heading"
                                        >
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- Product Grid Card Container -->
                        <div v-else>
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <div
                                    class="mt-8 grid gap-8 max-md:gap-x-4 max-sm:mt-5 max-sm:justify-items-center max-sm:gap-y-5"
                                    :style="{ gridTemplateColumns: productGridColumns }"
                                >
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <div
                                        class="mt-8 grid gap-8 max-md:mt-5 max-md:justify-items-center max-md:gap-x-4 max-md:gap-y-5"
                                        :style="{ gridTemplateColumns: productGridColumns }"
                                    >
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                            :navigation-link="route('shop.search.index')"
                                        />
                                    </div>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                                        <img
                                            class="max-sm:h-[100px] max-sm:w-[100px]"
                                            src="{{ bagisto_asset('images/thank-you.png') }}"
                                            alt="Empty result"
                                            loading="lazy"
                                            decoding="async"
                                        />

                                        <p
                                            class="text-xl max-sm:text-sm"
                                            role="heading"
                                        >
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="mt-10 flex items-center justify-center gap-2 max-sm:mt-7"
                            v-if="pagination.lastPage > 1"
                        >
                            <button
                                class="rounded-lg border-zinc-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                                :disabled="pagination.currentPage <= 1"
                                @click="goToPage(pagination.currentPage - 1)"
                            >
                                Prev
                            </button>

                            <template v-for="(page, index) in visiblePages" :key="`${page}-${index}`">
                                <button
                                    v-if="page !== '...'"
                                    class="rounded-lg border px-3 py-2 text-sm"
                                    :class="page === pagination.currentPage ? 'border-navyBlue bg-navyBlue text-white' : 'border-zinc-300'"
                                    @click="goToPage(page)"
                                >
                                    @{{ page }}
                                </button>

                                <span
                                    v-else
                                    class="px-1 text-sm text-zinc-500"
                                >
                                    ...
                                </span>
                            </template>

                            <button
                                class="rounded-lg border border-zinc-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                                :disabled="pagination.currentPage >= pagination.lastPage"
                                @click="goToPage(pagination.currentPage + 1)"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </script>

        <script type="module">
            app.component('v-search', {
                template: '#v-search-template',

                data() {
                    return {
                        isMobile: window.innerWidth <= 767,

                        isLoading: true,

                        isDrawerActive: {
                            toolbar: false,

                            filter: false,
                        },

                        filters: {
                            toolbar: {
                                default: {},

                                applied: {},
                            },

                            filter: {},
                        },

                        products: [],

                        links: {},

                        pagination: {
                            currentPage: 1,
                            lastPage: 1,
                        },

                        selectedCategoryId: {{ (int) request('category_id', 0) }},
                    }
                },

                computed: {
                    productGridColumns() {
                        if (window.innerWidth <= 768) {
                            return 'repeat(2, minmax(0, 1fr))';
                        }

                        if (window.innerWidth <= 1060) {
                            return 'repeat(3, minmax(0, 1fr))';
                        }

                        if (window.innerWidth <= 1280) {
                            return 'repeat(4, minmax(0, 1fr))';
                        }

                        return 'repeat(5, minmax(0, 1fr))';
                    },

                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar.applied);

                        queryParams.mode = queryParams.mode || 'grid';
                        queryParams.limit = queryParams.limit || 20;

                        if (this.pagination.currentPage > 1) {
                            queryParams.page = this.pagination.currentPage;
                        }

                        if (this.selectedCategoryId > 0) {
                            queryParams.category_id = this.selectedCategoryId;
                        }

                        return this.removeJsonEmptyValues(queryParams);
                    },

                    queryString() {
                        return this.jsonToQueryString(this.queryParams);
                    },

                    visiblePages() {
                        const pages = [];
                        const last = this.pagination.lastPage;
                        const current = this.pagination.currentPage;

                        if (last <= 9) {
                            for (let page = 1; page <= last; page++) {
                                pages.push(page);
                            }

                            return pages;
                        }

                        pages.push(1, 2);

                        const middleStart = Math.max(current - 1, 3);
                        const middleEnd = Math.min(current + 1, last - 2);

                        if (middleStart > 3) {
                            pages.push('...');
                        }

                        for (let page = middleStart; page <= middleEnd; page++) {
                            pages.push(page);
                        }

                        if (middleEnd < last - 2) {
                            pages.push('...');
                        }

                        pages.push(last - 1, last);

                        return pages;
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },

                    queryString() {
                        window.history.pushState({}, '', '?' + this.queryString);
                    },
                },

                methods: {
                    setFilters(type, filters) {
                        this.filters[type] = filters;
                        this.pagination.currentPage = 1;
                    },

                    clearFilters(type, filters) {
                        this.filters[type] = {};
                    },

                    getProducts() {
                        this.isDrawerActive = {
                            toolbar: false,

                            filter: false,
                        };

                        this.$axios.get(("{{ route('shop.api.products.index') }}"), {
                            params: this.queryParams
                        })
                            .then(response => {
                                this.isLoading = false;

                                this.products = response.data.data;

                                this.links = response.data.links;

                                this.pagination.currentPage = response.data.meta?.current_page ?? 1;
                                this.pagination.lastPage = response.data.meta?.last_page ?? 1;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    goToPage(page) {
                        if (page < 1 || page > this.pagination.lastPage || page === this.pagination.currentPage) {
                            return;
                        }

                        this.pagination.currentPage = page;
                    },

                    removeJsonEmptyValues(params) {
                        Object.keys(params).forEach(function (key) {
                            if ((! params[key] && params[key] !== undefined)) {
                                delete params[key];
                            }

                            if (Array.isArray(params[key])) {
                                params[key] = params[key].join(',');
                            }
                        });

                        return params;
                    },

                    jsonToQueryString(params) {
                        let parameters = new URLSearchParams();

                        for (const key in params) {
                            parameters.append(key, params[key]);
                        }

                        return parameters.toString();
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
