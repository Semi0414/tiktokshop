@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))
    @php
        $configurableConfig = app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product);
        $configurableAttributes = $configurableConfig['attributes'] ?? [];
        $fallbackVariants = $product->variants ?? collect();
        $parentBaseImage = product_image()->getProductBaseImage($product);
        $fallbackVariantPayload = $fallbackVariants->map(function ($variant) {
            $variantImage = product_image()->getProductBaseImage($variant);

            return [
                'id' => (int) $variant->id,
                'name' => (string) ($variant->name ?: $variant->sku),
                'price_html' => (string) $variant->getTypeInstance()->getPriceHtml(),
                'image' => $variantImage,
            ];
        })->values();
        $fallbackParentPayload = [
            'id' => 0,
            'name' => (string) $product->name,
            'price_html' => (string) $product->getTypeInstance()->getPriceHtml(),
            'image' => $parentBaseImage,
        ];
    @endphp

    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    <v-product-configurable-options :errors="errors"></v-product-configurable-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @push('scripts')
        <script
            type="text/x-template"
            id="v-product-configurable-options-template"
        >
            <div class="w-[455px] max-w-full max-sm:w-full">
                <input
                    type="hidden"
                    name="selected_configurable_option"
                    id="selected_configurable_option"
                    :value="selectedOptionVariant"
                    ref="selected_configurable_option"
                >

                <div
                    class="mt-5"
                    v-for='(attribute, index) in childAttributes'
                    v-if="hasAttributeConfig"
                >
                    <!-- Dropdown Options Container -->
                    <template v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'">
                        <!-- Dropdown Label -->
                        <h2 class="mb-4 text-xl max-sm:mb-1.5 max-sm:text-base max-sm:font-medium">
                            @{{ attribute.label }}
                        </h2>
                        
                        <!-- Dropdown Options -->
                        <v-field
                            as="select"
                            :name="'super_attribute[' + attribute.id + ']'"
                            class="custom-select mb-3 block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-zinc-500 focus:border-blue-500 focus:ring-blue-500"
                            :class="[errors['super_attribute[' + attribute.id + ']'] ? 'border border-red-500' : '']"
                            :id="'attribute_' + attribute.id"
                            v-model="attribute.selectedValue"
                            rules="required"
                            :label="attribute.label"
                            :aria-label="attribute.label"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                        >
                            <option
                                v-for='(option, index) in attribute.options'
                                :value="option.id"
                            >
                                @{{ option.label }}
                            </option>
                        </v-field>
                    </template>

                    <!-- Swatch Options Container -->
                    <template v-else>
                        <!-- Option Label -->
                        <h2 class="mb-4 text-xl max-sm:mb-2 max-sm:text-base">
                            @{{ attribute.label }}
                        </h2>

                        <!-- Swatch Options -->
                        <div class="flex items-center gap-3">
                            <template v-for="(option, index) in attribute.options">
                                <template v-if="option.id">
                                    <!-- Color Swatch Options -->
                                    <label
                                        class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none"
                                        :class="{'ring-2 ring-gray-900' : option.id == attribute.selectedValue}"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'color'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span
                                            class="h-8 w-8 rounded-full border border-gray-200 max-sm:h-[25px] max-sm:w-[25px]"
                                            tabindex="0"
                                            :style="{ 'background-color': option.swatch_value }"
                                        ></span>
                                    </label>

                                    <!-- Image Swatch Options -->
                                    <label 
                                        class="group relative flex h-[60px] w-[60px] cursor-pointer items-center justify-center overflow-hidden rounded-md border bg-white font-medium uppercase text-gray-900 hover:bg-gray-50 sm:py-6"
                                        :class="{'border-navyBlue' : option.id == attribute.selectedValue }"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'image'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            v-model="attribute.selectedValue"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <img
                                            :src="option.swatch_value"
                                            :title="option.label"
                                        />
                                    </label>

                                    <!-- Text Swatch Options -->
                                    <label 
                                        class="group relative flex h-fit min-w-fit cursor-pointer items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-3 font-medium uppercase text-gray-900 hover:bg-gray-50 max-sm:h-fit max-sm:w-fit max-sm:px-3.5 max-sm:py-2"
                                        :class="{'border-transparent !bg-navyBlue text-white' : option.id == attribute.selectedValue }"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'text'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-model="attribute.selectedValue"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                class="peer sr-only"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span class="text-lg max-sm:text-sm">
                                            @{{ option.label }}
                                        </span>

                                        <span
                                            class="pointer-events-none absolute -inset-px rounded-full"
                                            role="presentation"
                                        >
                                        </span>
                                    </label>
                                </template>
                            </template>

                            <span
                                class="text-sm text-gray-600 max-sm:text-xs"
                                v-if="! attribute.options.length"
                            >
                                @lang('shop::app.products.view.type.configurable.select-above-options')
                            </span>
                        </div>
                    </template>

                    <v-error-message
                        :name="'super_attribute[' + attribute.id + ']'"
                        v-slot="{ message }"
                    >
                        <p class="mt-1 text-xs italic text-red-500">
                            @{{ message }}
                        </p>
                    </v-error-message>
                </div>

                <div
                    class="mt-6"
                    v-if="! hasAttributeConfig && fallbackVariants.length"
                >
                    <h2 class="mb-3 text-xl max-sm:text-base max-sm:font-medium">
                        Available Variations
                    </h2>

                    <p class="mb-3 text-sm text-zinc-500">
                        Select a specific variant to view its exact image and price.
                    </p>

                    <div class="flex flex-wrap gap-2.5">
                        <button
                            type="button"
                            class="rounded-full border px-4 py-2 text-sm transition-all"
                            :class="selectedOptionVariant ? 'border-zinc-300 text-zinc-700 hover:border-navyBlue hover:text-navyBlue' : 'border-navyBlue bg-navyBlue text-white'"
                            @click="selectFallbackParent"
                        >
                            Parent
                        </button>

                        <button
                            type="button"
                            v-for="variant in fallbackVariants"
                            :key="variant.id"
                            class="rounded-full border px-4 py-2 text-sm transition-all"
                            :class="selectedOptionVariant == variant.id ? 'border-navyBlue bg-navyBlue text-white' : 'border-zinc-300 text-zinc-700 hover:border-navyBlue hover:text-navyBlue'"
                            @click="selectFallbackVariant(variant)"
                        >
                            @{{ truncateLabel(variant.name) }}
                        </button>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            let galleryImages = @json(product_image()->getGalleryImages($product));

            app.component('v-product-configurable-options', {
                template: '#v-product-configurable-options-template',

                props: ['errors'],

                data() {
                    return {
                        config: @json($configurableConfig),

                        childAttributes: [],

                        fallbackParent: @json($fallbackParentPayload),

                        fallbackVariants: @json($fallbackVariantPayload),

                        possibleOptionVariant: null,

                        selectedOptionVariant: '',

                        galleryImages: [],
                    }
                },

                computed: {
                    hasAttributeConfig() {
                        return Array.isArray(this.config.attributes) && this.config.attributes.length > 0;
                    },
                },

                mounted() {
                    if (! this.hasAttributeConfig) {
                        return;
                    }

                    let attributes = JSON.parse(JSON.stringify(this.config)).attributes.slice();

                    let index = attributes.length;

                    while (index--) {
                        let attribute = attributes[index];

                        attribute.options = [];

                        if (index) {
                            attribute.disabled = true;
                        } else {
                            this.fillAttributeOptions(attribute);
                        }

                        attribute = Object.assign(attribute, {
                            childAttributes: this.childAttributes.slice(),
                            prevAttribute: attributes[index - 1],
                            nextAttribute: attributes[index + 1]
                        });

                        this.childAttributes.unshift(attribute);
                    }
                },

                methods: {
                    truncateLabel(label) {
                        if (! label) {
                            return 'Variant';
                        }

                        return label.length > 40 ? `${label.slice(0, 40)}...` : label;
                    },

                    applyFallbackPresentation(payload) {
                        if (! payload) {
                            return;
                        }

                        const titleEl = document.getElementById('product-main-title');
                        if (titleEl && payload.name) {
                            titleEl.textContent = payload.name;
                        }

                        const priceEl = document.getElementById('product-main-price');
                        if (priceEl && payload.price_html) {
                            priceEl.innerHTML = payload.price_html;
                        }

                        const galleryComponent = this.$parent?.$refs?.gallery;
                        if (galleryComponent && payload.image?.large_image_url) {
                            galleryComponent.media.images = [payload.image];
                            galleryComponent.baseFile.type = 'image';
                            galleryComponent.baseFile.path = payload.image.large_image_url;
                            galleryComponent.activeIndex = 0;
                        }
                    },

                    selectFallbackParent() {
                        this.selectedOptionVariant = '';

                        if (this.$refs.selected_configurable_option) {
                            this.$refs.selected_configurable_option.value = '';
                        }

                        this.applyFallbackPresentation(this.fallbackParent);
                    },

                    selectFallbackVariant(variant) {
                        if (! variant || ! variant.id) {
                            return;
                        }

                        this.selectedOptionVariant = variant.id;

                        if (this.$refs.selected_configurable_option) {
                            this.$refs.selected_configurable_option.value = variant.id;
                        }

                        this.applyFallbackPresentation(variant);
                    },

                    configure(attribute, optionId) {
                        this.possibleOptionVariant = this.getPossibleOptionVariant(attribute, optionId);

                        if (optionId) {
                            attribute.selectedValue = optionId;
                            
                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;

                                this.clearAttributeSelection(attribute.nextAttribute);

                                this.fillAttributeOptions(attribute.nextAttribute);

                                this.resetChildAttributes(attribute.nextAttribute);
                            } else {
                                this.selectedOptionVariant = this.possibleOptionVariant;
                            }
                        } else {
                            this.clearAttributeSelection(attribute);

                            this.clearAttributeSelection(attribute.nextAttribute);

                            this.resetChildAttributes(attribute);
                        }

                        this.reloadPrice();
                        
                        this.reloadImages();
                    },

                    getPossibleOptionVariant(attribute, optionId) {
                        let matchedOptions = attribute.options.filter(option => option.id == optionId);

                        if (matchedOptions[0]?.allowedProducts) {
                            return matchedOptions[0].allowedProducts[0];
                        }

                        return undefined;
                    },

                    fillAttributeOptions(attribute) {
                        let options = this.config.attributes.find(tempAttribute => tempAttribute.id === attribute.id)?.options;

                        attribute.options = [{
                            'id': '',
                            'label': "@lang('shop::app.products.view.type.configurable.select-options')",
                            'products': []
                        }];

                        if (! options) {
                            return;
                        }

                        let prevAttributeSelectedOption = attribute.prevAttribute?.options.find(option => option.id == attribute.prevAttribute.selectedValue);

                        let index = 1;

                        for (let i = 0; i < options.length; i++) {
                            let allowedProducts = [];

                            if (prevAttributeSelectedOption) {
                                for (let j = 0; j < options[i].products.length; j++) {
                                    if (prevAttributeSelectedOption.allowedProducts && prevAttributeSelectedOption.allowedProducts.includes(options[i].products[j])) {
                                        allowedProducts.push(options[i].products[j]);
                                    }
                                }
                            } else {
                                allowedProducts = options[i].products.slice(0);
                            }

                            if (allowedProducts.length > 0) {
                                options[i].allowedProducts = allowedProducts;

                                attribute.options[index++] = options[i];
                            }
                        }
                    },

                    resetChildAttributes(attribute) {
                        if (! attribute.childAttributes) {
                            return;
                        }

                        attribute.childAttributes.forEach(function (set) {
                            set.selectedValue = null;

                            set.disabled = true;
                        });
                    },

                    clearAttributeSelection (attribute) {
                        if (! attribute) {
                            return;
                        }

                        attribute.selectedValue = null;

                        this.selectedOptionVariant = null;
                    },

                    reloadPrice () {
                        let selectedOptionCount = this.childAttributes.filter(attribute => attribute.selectedValue).length;

                        let finalPrice = document.querySelector('.final-price');

                        let regularPrice = document.querySelector('.regular-price');

                        let configVariant = this.config.variant_prices[this.possibleOptionVariant];

                        if (this.childAttributes.length == selectedOptionCount) {
                            document.querySelector('.price-label').style.display = 'none';

                            if (parseInt(configVariant.regular.price) > parseInt(configVariant.final.price)) {
                                regularPrice.style.display = 'block';

                                finalPrice.innerHTML = configVariant.final.formatted_price;

                                regularPrice.innerHTML = configVariant.regular.formatted_price;
                            } else {
                                finalPrice.innerHTML = configVariant.regular.formatted_price;

                                regularPrice.style.display = 'none';
                            }

                            this.$emitter.emit('configurable-variant-selected-event',this.possibleOptionVariant);
                        } else {
                            document.querySelector('.price-label').style.display = 'inline-block';

                            finalPrice.innerHTML = this.config.regular.formatted_price;

                            this.$emitter.emit('configurable-variant-selected-event', 0);
                        }
                    },

                    reloadImages () {
                        galleryImages.splice(0, galleryImages.length)

                        if (this.possibleOptionVariant) {
                            this.config.variant_images[this.possibleOptionVariant].forEach(function(image) {
                                galleryImages.push(image);
                            });

                            this.config.variant_videos[this.possibleOptionVariant].forEach(function(video) {
                                galleryImages.push(video);
                            });
                        }

                        this.galleryImages.forEach(function(image) {
                            galleryImages.push(image);
                        });

                        if (galleryImages.length) {
                            this.$parent.$parent.$refs.gallery.media.images =  [...galleryImages];
                        }

                        this.$emitter.emit('configurable-variant-update-images-event', galleryImages);
                    },
                }
            });

        </script>
    @endpush

@endif