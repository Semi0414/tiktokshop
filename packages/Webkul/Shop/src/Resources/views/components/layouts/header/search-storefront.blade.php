@pushOnce('scripts', 'v-storefront-search-block')
    <script
        type="text/x-template"
        id="v-storefront-search-template"
    >
        <div
            :class="wrapperClass"
            class="relative"
        >
            <form
                :class="formClass"
                :action="searchAction"
                method="get"
                role="search"
                @submit="onSubmit"
            >
                <label
                    :for="inputId"
                    class="sr-only"
                >
                    @{{ srSearch }}
                </label>

                <div class="icon-search pointer-events-none absolute top-2.5 z-[1] flex items-center text-xl ltr:left-3 rtl:right-3"></div>

                <input
                    type="text"
                    name="query"
                    :id="inputId"
                    v-model="term"
                    autocomplete="off"
                    :class="inputClass"
                    :minlength="minLen"
                    :maxlength="maxLen"
                    :placeholder="placeholder"
                    :aria-expanded="panelOpen ? 'true' : 'false'"
                    :aria-controls="'header-search-panel-' + context"
                    aria-autocomplete="list"
                    @input="onInput"
                    @focus="onFocus"
                    @keydown.escape="closePanel"
                >

                <button
                    type="submit"
                    class="hidden"
                    :aria-label="srSubmit"
                >
                </button>

                <slot></slot>
            </form>

            <div
                v-show="panelOpen && (sellers.length || products.length || loading || (term.length >= minLen && ! loading))"
                :id="'header-search-panel-' + context"
                class="journal-scroll absolute left-0 right-0 top-full z-[60] mt-1 max-h-[min(70vh,420px)] overflow-auto rounded-lg border border-zinc-200 bg-white py-2 text-left shadow-lg"
                role="listbox"
            >
                <div
                    v-if="loading"
                    class="px-4 py-3 text-sm text-zinc-500"
                >
                    @{{ loadingLabel }}
                </div>

                <template v-else>
                    <p
                        v-if="sellers.length"
                        class="px-4 pb-1 text-xs font-semibold uppercase tracking-wide text-zinc-500"
                    >
                        @{{ storesLabel }}
                    </p>

                    <a
                        v-for="s in sellers"
                        :key="'s-' + s.id"
                        :href="s.visit_store_url"
                        class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-zinc-50"
                        role="option"
                        @mousedown.prevent
                        @click="closePanel"
                    >
                        <img
                            v-if="s.image_url"
                            :src="s.image_url"
                            alt=""
                            class="h-9 w-9 rounded-full object-cover"
                            loading="lazy"
                            decoding="async"
                            width="36"
                            height="36"
                        >

                        <span
                            v-else
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-200 text-xs font-semibold text-zinc-600"
                        >
                            @{{ (s.name || '?').charAt(0).toUpperCase() }}
                        </span>

                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-medium text-zinc-900">@{{ s.name }}</span>
                            <span class="block truncate text-xs text-zinc-500">@{{ visitStoreLabel }}</span>
                        </span>
                    </a>

                    <p
                        v-if="products.length"
                        class="mt-2 border-t border-zinc-100 px-4 pb-1 pt-3 text-xs font-semibold uppercase tracking-wide text-zinc-500"
                    >
                        @{{ productsLabel }}
                    </p>

                    <a
                        v-for="p in products"
                        :key="'p-' + p.id"
                        :href="productUrl(p)"
                        class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-zinc-50"
                        role="option"
                        @mousedown.prevent
                        @click="closePanel"
                    >
                        <img
                            v-if="p.base_image && p.base_image.small_image_url"
                            :src="p.base_image.small_image_url"
                            :alt="p.name"
                            class="h-10 w-10 rounded object-cover"
                            loading="lazy"
                            decoding="async"
                            width="40"
                            height="40"
                        >

                        <span class="min-w-0 flex-1 truncate font-medium text-zinc-900">@{{ p.name }}</span>
                    </a>

                    <p
                        v-if="! sellers.length && ! products.length && term.length >= minLen"
                        class="px-4 py-3 text-sm text-zinc-500"
                    >
                        @{{ emptyLabel }}
                    </p>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-storefront-search', {
            template: '#v-storefront-search-template',

            props: [
                'context',
                'searchAction',
                'productsUrl',
                'sellersUrl',
                'productBase',
                'inputId',
                'minLen',
                'maxLen',
                'formClass',
                'wrapperClass',
                'inputClass',
                'placeholder',
                'srSearch',
                'srSubmit',
                'loadingLabel',
                'storesLabel',
                'visitStoreLabel',
                'productsLabel',
                'emptyLabel',
            ],

            data() {
                return {
                    term: '',

                    panelOpen: false,

                    loading: false,

                    sellers: [],

                    products: [],

                    debounceId: null,

                    onDocClick: null,
                };
            },

            mounted() {
                this.onDocClick = (e) => {
                    if (! this.$el.contains(e.target)) {
                        this.closePanel();
                    }
                };

                document.addEventListener('click', this.onDocClick);
            },

            beforeDestroy() {
                document.removeEventListener('click', this.onDocClick);

                if (this.debounceId) {
                    clearTimeout(this.debounceId);
                }
            },

            methods: {
                productUrl(p) {
                    const base = (this.productBase || '').replace(/\/+$/, '');

                    return (base ? base + '/' : '/') + (p.url_key || '');
                },

                onSubmit(e) {
                    const min = parseInt(this.minLen, 10) || 1;

                    if ((this.term || '').trim().length < min) {
                        e.preventDefault();
                    }

                    this.closePanel();
                },

                onFocus() {
                    this.panelOpen = true;

                    this.fetchSuggestions();
                },

                onInput() {
                    this.panelOpen = true;

                    if (this.debounceId) {
                        clearTimeout(this.debounceId);
                    }

                    this.debounceId = setTimeout(() => {
                        this.fetchSuggestions();
                    }, 280);
                },

                closePanel() {
                    this.panelOpen = false;
                },

                fetchSuggestions() {
                    const q = (this.term || '').trim();

                    const min = parseInt(this.minLen, 10) || 1;

                    if (q.length < min) {
                        this.sellers = [];

                        this.products = [];

                        this.loading = false;

                        return;
                    }

                    this.loading = true;

                    const sellersReq = this.$axios.get(this.sellersUrl, {
                        params: { query: q },
                    });

                    const productsReq = this.$axios.get(this.productsUrl, {
                        params: {
                            query: q,
                            limit: 8,
                            suggest: 0,
                            mode: 'grid',
                        },
                    });

                    Promise.all([sellersReq, productsReq])
                        .then(([sellersRes, productsRes]) => {
                            this.sellers = sellersRes.data?.data ?? [];

                            this.products = productsRes.data?.data ?? [];
                        })
                        .catch(() => {
                            this.sellers = [];

                            this.products = [];
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
