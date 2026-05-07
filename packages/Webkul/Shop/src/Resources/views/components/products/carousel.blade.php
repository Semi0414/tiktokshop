@props([
    'title' => '',
    'src' => '',
    'navigationLink' => null,
    'cardImageSize' => 'medium',
    'perPage' => 12,
])

<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
    :per-page="{{ (int) $perPage }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
</v-products-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-products-carousel-template"
    >
        <div
            class="container mt-20 max-lg:px-8 max-md:mt-8 max-sm:mt-7 max-sm:!px-4"
            v-if="! isLoading && products.length"
        >
            <div class="flex justify-between">
                <h2 class="font-dmserif text-3xl max-md:text-2xl max-sm:text-xl">
                    @{{ title }}
                </h2>

                <div class="flex items-center justify-between gap-8">
                    <a
                        :href="navigationLink"
                        class="hidden max-lg:flex"
                        v-if="navigationLink"
                    >
                        <p class="items-center text-xl max-md:text-base max-sm:text-sm">
                            @lang('shop::app.components.products.carousel.view-all')

                            <span class="icon-arrow-right text-2xl max-md:text-lg max-sm:text-sm"></span>
                        </p>
                    </a>

                    <template v-if="products.length > 3">
                        <span
                            v-if="products.length > 4 || (products.length > 3 && isScreenMax2xl)"
                            class="icon-arrow-left-stylish rtl:icon-arrow-right-stylish inline-block cursor-pointer text-2xl max-lg:hidden"
                            role="button"
                            aria-label="@lang('shop::app.components.products.carousel.previous')"
                            tabindex="0"
                            @click="swipeLeft"
                        >
                        </span>

                        <span
                            v-if="products.length > 4 || (products.length > 3 && isScreenMax2xl)"
                            class="icon-arrow-right-stylish rtl:icon-arrow-left-stylish inline-block cursor-pointer text-2xl max-lg:hidden"
                            role="button"
                            aria-label="@lang('shop::app.components.products.carousel.next')"
                            tabindex="0"
                            @click="swipeRight"
                        >
                        </span>
                    </template>
                </div>
            </div>

            <div
                ref="swiperContainer"
                class="flex gap-8 pb-2.5 [&>*]:flex-[0] mt-10 overflow-auto scroll-smooth scrollbar-hide max-md:gap-7 max-md:mt-5 max-sm:gap-4 max-md:pb-0 max-md:whitespace-nowrap"
            >
                <x-shop::products.card
                    class="min-w-[291px] max-md:h-fit max-md:min-w-56 max-sm:min-w-[192px]"
                    v-for="product in products"
                    :image-size="'{{ $cardImageSize }}'"
                />
            </div>

            <div
                class="mt-8 flex flex-wrap items-center justify-center gap-4 max-sm:mt-6"
                v-if="lastPage > 1"
            >
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800"
                    :disabled="currentPage <= 1 || isLoading"
                    @click="goToPage(currentPage - 1)"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                    @lang('shop::app.components.products.carousel.previous-page')
                </button>

                <span class="text-sm text-zinc-600 dark:text-gray-400">
                    @{{ pageLabel }}
                </span>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800"
                    :disabled="currentPage >= lastPage || isLoading"
                    @click="goToPage(currentPage + 1)"
                >
                    @lang('shop::app.components.products.carousel.next-page')
                    <span class="icon-arrow-right rtl:icon-arrow-left"></span>
                </button>
            </div>

            <a
                :href="navigationLink"
                class="secondary-button mx-auto mt-5 block w-max rounded-2xl px-11 py-3 text-center text-base max-lg:mt-0 max-lg:hidden max-lg:py-3.5 max-md:rounded-lg"
                :aria-label="title"
                v-if="navigationLink"
            >
                @lang('shop::app.components.products.carousel.view-all')
            </a>
        </div>

        <!-- Product Card Listing -->
        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: {
                src: { type: String, required: true },
                title: { type: String, default: '' },
                navigationLink: { type: String, default: '' },
                perPage: { type: [Number, String], default: 12 },
            },

            data() {
                return {
                    isLoading: true,

                    products: [],

                    offset: 323,

                    isScreenMax2xl: window.innerWidth <= 1440,

                    currentPage: 1,

                    lastPage: 1,
                };
            },

            computed: {
                pageLabel() {
                    return `${this.currentPage} / ${this.lastPage}`;
                },

                pageSize() {
                    const n = parseInt(this.perPage, 10);

                    return n > 0 ? n : 12;
                },
            },

            mounted() {
                this.getProducts();
            },

            created() {
                window.addEventListener('resize', this.updateScreenSize);
            },

            beforeDestroy() {
                window.removeEventListener('resize', this.updateScreenSize);
            },

            methods: {
                buildPageUrl(page) {
                    const base = typeof this.src === 'string' ? this.src : '';

                    const url = new URL(base, window.location.origin);

                    url.searchParams.set('page', String(page));
                    url.searchParams.set('limit', String(this.pageSize));

                    return url.pathname + url.search + url.hash;
                },

                getProducts(page = 1) {
                    this.isLoading = true;

                    const requestUrl = this.buildPageUrl(page);

                    this.$axios.get(requestUrl)
                        .then(response => {
                            this.isLoading = false;

                            const payload = response.data;

                            this.products = Array.isArray(payload.data) ? payload.data : [];

                            const meta = payload.meta || {};

                            this.currentPage = meta.current_page ?? page;
                            this.lastPage = meta.last_page ?? 1;

                            this.$nextTick(() => {
                                const el = this.$refs.swiperContainer;

                                if (el) {
                                    el.scrollLeft = 0;
                                }
                            });
                        }).catch(error => {
                            this.isLoading = false;

                            this.products = [];

                            this.lastPage = 1;

                            this.currentPage = 1;

                            console.error(error);
                        });
                },

                goToPage(page) {
                    if (page < 1 || page > this.lastPage || page === this.currentPage) {
                        return;
                    }

                    this.getProducts(page);
                },

                updateScreenSize() {
                    this.isScreenMax2xl = window.innerWidth <= 1440;
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    // Check if scroll reaches the end
                    if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
                        // Reset scroll to the beginning
                        container.scrollLeft = 0;
                    } else {
                        // Scroll to the right
                        container.scrollLeft += this.offset;
                    }
                },
            },
        });
    </script>
@endPushOnce
