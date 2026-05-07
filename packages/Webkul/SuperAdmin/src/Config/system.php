<?php

use Webkul\Sales\Models\Order;

return [
    /**
     * General.
     */
    [
        'key' => 'general',
        'name' => 'superadmin::app.configuration.index.general.title',
        'info' => 'superadmin::app.configuration.index.general.info',
        'sort' => 1,
    ], [
        'key' => 'general.general',
        'name' => 'superadmin::app.configuration.index.general.general.title',
        'info' => 'superadmin::app.configuration.index.general.general.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key' => 'general.general.locale_options',
        'name' => 'superadmin::app.configuration.index.general.general.unit-options.title',
        'info' => 'superadmin::app.configuration.index.general.general.unit-options.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'weight_unit',
                'title' => 'superadmin::app.configuration.index.general.general.unit-options.weight-unit',
                'type' => 'select',
                'default' => 'kgs',
                'options' => [
                    [
                        'title' => 'lbs',
                        'value' => 'lbs',
                    ], [
                        'title' => 'kgs',
                        'value' => 'kgs',
                    ],
                ],
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'general.general.breadcrumbs',
        'name' => 'superadmin::app.configuration.index.general.general.breadcrumbs.title',
        'info' => 'superadmin::app.configuration.index.general.general.breadcrumbs.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'shop',
                'title' => 'superadmin::app.configuration.index.general.general.breadcrumbs.shop',
                'type' => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key' => 'general.general.visitor_options',
        'name' => 'superadmin::app.configuration.index.general.general.visitor-options.title',
        'info' => 'superadmin::app.configuration.index.general.general.visitor-options.title-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.general.visitor-options.enable',
                'type' => 'boolean',
                'default' => false,
            ],
        ],
    ], [
        'key' => 'general.content',
        'name' => 'superadmin::app.configuration.index.general.content.title',
        'info' => 'superadmin::app.configuration.index.general.content.info',
        'icon' => 'settings/store.svg',
        'sort' => 2,
    ], [
        'key' => 'general.content.header_offer',
        'name' => 'superadmin::app.configuration.index.general.content.header-offer.title',
        'info' => 'superadmin::app.configuration.index.general.content.header-offer.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.general.content.header-offer.offer-title',
                'type' => 'text',
                'default' => 'Get UPTO 40% OFF on your 1st order',
                'validation' => 'max:100',
            ], [
                'name' => 'redirection_title',
                'title' => 'superadmin::app.configuration.index.general.content.header-offer.redirection-title',
                'type' => 'text',
                'default' => 'SHOP NOW',
                'validation' => 'max:25',
            ], [
                'name' => 'redirection_link',
                'title' => 'superadmin::app.configuration.index.general.content.header-offer.redirection-link',
                'type' => 'text',
            ],
        ],
    ], [
        'key' => 'general.content.footer',
        'name' => 'superadmin::app.configuration.index.general.content.copyright-content.title',
        'info' => 'superadmin::app.configuration.index.general.content.copyright-content.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'copyright_content',
                'title' => 'superadmin::app.configuration.index.general.content.copyright-content.title',
                'type' => 'text',
                'channel_based' => false,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.content.speculation_rules',
        'name' => 'superadmin::app.configuration.index.general.content.speculation-rules.title',
        'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.enable-speculation',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'prerender_enabled',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.enabled',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'prerender_ignore_urls',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.ignore-urls',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.ignore-urls-info',
                'type' => 'textarea',
                'default' => '/customer/account/*|/checkout/*',
                'depends' => 'prerender_enabled:true',
            ], [
                'name' => 'prerender_ignore_url_params',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.ignore-url-params',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.ignore-url-params-info',
                'type' => 'textarea',
                'depends' => 'prerender_enabled:true',
            ], [
                'name' => 'prerender_eagerness',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.eagerness',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.eagerness-info',
                'type' => 'select',
                'default' => 'moderate',
                'depends' => 'prerender_enabled:true',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.eager',
                        'value' => 'eager',
                    ],
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.moderate',
                        'value' => 'moderate',
                    ],
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prerender.conservative',
                        'value' => 'conservative',
                    ],
                ],
            ], [
                'name' => 'prefetch_enabled',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.enabled',
                'type' => 'boolean',
                'default' => false,
            ], [
                'name' => 'prefetch_ignore_urls',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.ignore-urls',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.ignore-urls-info',
                'type' => 'textarea',
                'default' => '/customer/account/*|/checkout/*',
                'depends' => 'prefetch_enabled:true',
            ], [
                'name' => 'prefetch_ignore_url_params',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.ignore-url-params',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.ignore-url-params-info',
                'type' => 'textarea',
                'depends' => 'prefetch_enabled:true',
            ], [
                'name' => 'prefetch_eagerness',
                'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.eagerness',
                'info' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.eagerness-info',
                'type' => 'select',
                'default' => 'moderate',
                'depends' => 'prefetch_enabled:true',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.eager',
                        'value' => 'eager',
                    ],
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.moderate',
                        'value' => 'moderate',
                    ],
                    [
                        'title' => 'superadmin::app.configuration.index.general.content.speculation-rules.prefetch.conservative',
                        'value' => 'conservative',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'general.content.custom_scripts',
        'name' => 'superadmin::app.configuration.index.general.content.custom-scripts.title',
        'info' => 'superadmin::app.configuration.index.general.content.custom-scripts.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'custom_css',
                'title' => 'superadmin::app.configuration.index.general.content.custom-scripts.custom-css',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'custom_javascript',
                'title' => 'superadmin::app.configuration.index.general.content.custom-scripts.custom-javascript',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false,
            ],
        ],
    ], [
        'key' => 'general.design',
        'name' => 'superadmin::app.configuration.index.general.design.title',
        'info' => 'superadmin::app.configuration.index.general.design.info',
        'icon' => 'settings/theme.svg',
        'sort' => 3,
    ], [
        'key' => 'general.design.admin_logo',
        'name' => 'superadmin::app.configuration.index.general.design.admin-logo.title',
        'info' => 'superadmin::app.configuration.index.general.design.admin-logo.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'logo_image',
                'title' => 'superadmin::app.configuration.index.general.design.admin-logo.logo-image',
                'type' => 'image',
                'channel_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ], [
                'name' => 'favicon',
                'title' => 'superadmin::app.configuration.index.general.design.admin-logo.favicon',
                'type' => 'image',
                'channel_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg,ico',
            ],
        ],
    ], [
        'key' => 'general.design.categories',
        'name' => 'superadmin::app.configuration.index.general.design.menu-category.title',
        'info' => 'superadmin::app.configuration.index.general.design.menu-category.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'category_view',
                'title' => 'superadmin::app.configuration.index.general.design.menu-category.title',
                'type' => 'select',
                'default' => 'default',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.design.menu-category.default',
                        'value' => 'default',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.design.menu-category.sidebar',
                        'value' => 'sidebar',
                    ],
                ],
            ], [
                'name' => 'agreement_label',
                'title' => 'superadmin::app.configuration.index.general.gdpr.agreement.checkbox-label',
                'type' => 'blade',
                'path' => 'superadmin::configuration.custom-views.category-menu',
            ],
        ],
    ], [
        'key' => 'general.magic_ai',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.info',
        'icon' => 'settings/magic-ai.svg',
        'sort' => 3,
    ], [
        'key' => 'general.magic_ai.settings',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.settings.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.settings.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.settings.enabled',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'api_key',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.settings.api-key',
                'type' => 'password',
                'channel_based' => true,
            ], [
                'name' => 'organization',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.settings.organization',
                'type' => 'text',
                'channel_based' => true,
            ], [
                'name' => 'api_domain',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.settings.llm-api-domain',
                'type' => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'general.magic_ai.content_generation',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.enabled',
                'type' => 'boolean',
            ], [
                'name' => 'product_short_description_prompt',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.product-short-description-prompt',
                'type' => 'textarea',
                'locale_based' => true,
            ], [
                'name' => 'product_description_prompt',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.product-description-prompt',
                'type' => 'textarea',
                'locale_based' => true,
            ], [
                'name' => 'category_description_prompt',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.category-description-prompt',
                'type' => 'textarea',
                'locale_based' => true,
            ], [
                'name' => 'cms_page_content_prompt',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.content-generation.cms-page-content-prompt',
                'type' => 'textarea',
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.magic_ai.image_generation',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.image-generation.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.image-generation.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.image-generation.enabled',
                'type' => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'general.magic_ai.review_translation',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.enabled',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'model',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.model',
                'type' => 'select',
                'channel_based' => true,
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.gpt-4-turbo',
                        'value' => 'gpt-4-turbo',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.gpt-4o',
                        'value' => 'gpt-4o',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.gpt-4o-mini',
                        'value' => 'gpt-4o-mini',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.gemini-2-0-flash',
                        'value' => 'gemini-2.0-flash',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.deepseek-r1-8b',
                        'value' => 'deepseek-r1:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llama-groq',
                        'value' => 'llama3-8b-8192',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llama3-2-3b',
                        'value' => 'llama3.2:3b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llama3-2-1b',
                        'value' => 'llama3.2:1b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llama3-1-8b',
                        'value' => 'llama3.1:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llama3-8b',
                        'value' => 'llama3:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.llava-7b',
                        'value' => 'llava:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.vicuna-13b',
                        'value' => 'vicuna:13b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.vicuna-7b',
                        'value' => 'vicuna:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.qwen2-5-14b',
                        'value' => 'qwen2.5:14b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.qwen2-5-7b',
                        'value' => 'qwen2.5:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.qwen2-5-3b',
                        'value' => 'qwen2.5:3b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.qwen2-5-1-5b',
                        'value' => 'qwen2.5:1.5b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.qwen2-5-0-5b',
                        'value' => 'qwen2.5:0.5b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.mistral-7b',
                        'value' => 'mistral:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.starling-lm-7b',
                        'value' => 'starling-lm:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.phi3-5',
                        'value' => 'phi3.5',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.review-translation.orca-mini',
                        'value' => 'orca-mini',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'general.magic_ai.checkout_message',
        'name' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.title',
        'info' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.enabled',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'model',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.model',
                'type' => 'select',
                'channel_based' => true,
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.gpt-4-turbo',
                        'value' => 'gpt-4-turbo',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.gpt-4o',
                        'value' => 'gpt-4o',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.gpt-4o-mini',
                        'value' => 'gpt-4o-mini',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.gemini-2-0-flash',
                        'value' => 'gemini-2.0-flash',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.deepseek-r1-8b',
                        'value' => 'deepseek-r1:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llama-groq',
                        'value' => 'llama3.3',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llama3-2-3b',
                        'value' => 'llama3.2:3b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llama3-2-1b',
                        'value' => 'llama3.2:1b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llama3-1-8b',
                        'value' => 'llama3.1:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llama3-8b',
                        'value' => 'llama3:8b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.llava-7b',
                        'value' => 'llava:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.vicuna-13b',
                        'value' => 'vicuna:13b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.vicuna-7b',
                        'value' => 'vicuna:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.qwen2-5-14b',
                        'value' => 'qwen2.5:14b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.qwen2-5-7b',
                        'value' => 'qwen2.5:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.qwen2-5-3b',
                        'value' => 'qwen2.5:3b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.qwen2-5-1-5b',
                        'value' => 'qwen2.5:1.5b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.qwen2-5-0-5b',
                        'value' => 'qwen2.5:0.5b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.mistral-7b',
                        'value' => 'mistral:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.starling-lm-7b',
                        'value' => 'starling-lm:7b',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.phi3-5',
                        'value' => 'phi3.5',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.orca-mini',
                        'value' => 'orca-mini',
                    ],
                ],
            ], [
                'name' => 'prompt',
                'title' => 'superadmin::app.configuration.index.general.magic-ai.checkout-message.prompt',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.exchange_rates',
        'name' => 'superadmin::app.configuration.index.general.exchange-rates.title',
        'info' => 'superadmin::app.configuration.index.general.exchange-rates.info',
        'icon' => 'settings/exchange-rate.svg',
        'sort' => 4,
    ], [
        'key' => 'general.exchange_rates.settings',
        'name' => 'superadmin::app.configuration.index.general.exchange-rates.settings.title',
        'info' => 'superadmin::app.configuration.index.general.exchange-rates.settings.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'default_service',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.settings.default-service',
                'type' => 'select',
                'default' => 'exchange_rates',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.exchange-rates.settings.exchange-rates-api',
                        'value' => 'exchange_rates',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.exchange-rates.settings.fixer-api',
                        'value' => 'fixer',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'general.exchange_rates.fixer',
        'name' => 'superadmin::app.configuration.index.general.exchange-rates.fixer.title',
        'info' => 'superadmin::app.configuration.index.general.exchange-rates.fixer.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'api_key',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.fixer.api-key',
                'type' => 'password',
            ],
        ],
    ], [
        'key' => 'general.exchange_rates.exchange_rates_api',
        'name' => 'superadmin::app.configuration.index.general.exchange-rates.exchange-rates-api-section.title',
        'info' => 'superadmin::app.configuration.index.general.exchange-rates.exchange-rates-api-section.title-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'api_key',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.exchange-rates-api-section.api-key',
                'type' => 'password',
            ],
        ],
    ], [
        'key' => 'general.exchange_rates.schedule',
        'name' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.title',
        'info' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.enabled',
                'type' => 'boolean',
                'default' => false,
            ], [
                'name' => 'frequency',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.frequency',
                'type' => 'select',
                'default' => 'daily',
                'depends' => 'enabled:true',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.daily',
                        'value' => 'daily',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.weekly',
                        'value' => 'weekly',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.monthly',
                        'value' => 'monthly',
                    ],
                ],
            ], [
                'name' => 'time',
                'title' => 'superadmin::app.configuration.index.general.exchange-rates.schedule.time',
                'type' => 'text',
                'default' => '00:00',
                'depends' => 'enabled:true',
                'validation' => 'date_format:H:i',
            ],
        ],
    ], [
        'key' => 'general.sitemap',
        'name' => 'superadmin::app.configuration.index.general.sitemap.title',
        'info' => 'superadmin::app.configuration.index.general.sitemap.info',
        'icon' => 'settings/sitemap.svg',
        'sort' => 5,
    ], [
        'key' => 'general.sitemap.settings',
        'name' => 'superadmin::app.configuration.index.general.sitemap.settings.title',
        'info' => 'superadmin::app.configuration.index.general.sitemap.settings.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.sitemap.settings.enabled',
                'type' => 'boolean',
                'default' => 1,
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'general.sitemap.file_limits',
        'name' => 'superadmin::app.configuration.index.general.sitemap.file-limits.title',
        'info' => 'superadmin::app.configuration.index.general.sitemap.file-limits.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'max_url_per_file',
                'title' => 'superadmin::app.configuration.index.general.sitemap.file-limits.max-url-per-file',
                'type' => 'text',
                'default' => 50000,
                'validation' => 'integer|min:1',
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'general.gdpr',
        'name' => 'superadmin::app.configuration.index.general.gdpr.title',
        'info' => 'superadmin::app.configuration.index.general.gdpr.info',
        'icon' => 'settings/gdpr.svg',
        'sort' => 6,
    ], [
        'key' => 'general.gdpr.settings',
        'name' => 'superadmin::app.configuration.index.general.gdpr.settings.title',
        'info' => 'superadmin::app.configuration.index.general.gdpr.settings.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.gdpr.settings.enabled',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.gdpr.agreement',
        'name' => 'superadmin::app.configuration.index.general.gdpr.agreement.title',
        'info' => 'superadmin::app.configuration.index.general.gdpr.agreement.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.gdpr.agreement.enable',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'agreement_label',
                'title' => 'superadmin::app.configuration.index.general.gdpr.agreement.checkbox-label',
                'type' => 'text',
                'default' => 'I agree with the terms and conditions.',
                'validation' => 'max:255',
                'depends' => 'enabled:true',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'agreement_content',
                'title' => 'superadmin::app.configuration.index.general.gdpr.agreement.content',
                'type' => 'editor',
                'depends' => 'enabled:true',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.gdpr.cookie',
        'name' => 'superadmin::app.configuration.index.general.gdpr.cookie.title',
        'info' => 'superadmin::app.configuration.index.general.gdpr.cookie.info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.enable',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'position',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.position',
                'type' => 'select',
                'default' => 'bottom-left',
                'depends' => 'enabled:true',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.bottom-left',
                        'value' => 'bottom-left',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.bottom-right',
                        'value' => 'bottom-right',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.top-left',
                        'value' => 'top-left',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.top-right',
                        'value' => 'top-right',
                    ], [
                        'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.center',
                        'value' => 'center',
                    ],
                ],
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'static_block_identifier',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.identifier',
                'type' => 'text',
                'default' => 'Cookie Block',
                'validation' => 'max:255',
                'depends' => 'enabled:true',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie.description',
                'type' => 'textarea',
                'default' => 'This website uses cookies to ensure you get the best experience on our website.',
                'validation' => 'max:500',
                'depends' => 'enabled:true',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'general.gdpr.cookie_consent',
        'name' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.title',
        'info' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'strictly_necessary',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.strictly-necessary',
                'type' => 'editor',
                'default' => 'I agree with the terms and conditions.',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'basic_interaction',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.basic-interaction',
                'type' => 'editor',
                'default' => 'These trackers enable basic interactions and functionalities that allow you to access selected features of our service and facilitate your communication with us.',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'experience_enhancement',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.experience-enhancement',
                'type' => 'editor',
                'default' => 'These trackers help us to provide a personalized user experience by improving the quality of your preference management options, and by enabling the interaction with external networks and platforms.',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'measurements',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.measurement',
                'type' => 'editor',
                'default' => 'These trackers help us to measure traffic and analyze your behavior with the goal of improving our service.',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'targeting_advertising',
                'title' => 'superadmin::app.configuration.index.general.gdpr.cookie-consent.targeting-advertising',
                'type' => 'editor',
                'default' => 'These trackers help us to deliver personalized marketing content to you based on your behavior and to operate, serve and track ads.',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ],

    /**
     * Catalog.
     */
    [
        'key' => 'catalog',
        'name' => 'superadmin::app.configuration.index.catalog.title',
        'info' => 'superadmin::app.configuration.index.catalog.info',
        'sort' => 2,
    ], [
        'key' => 'catalog.products',
        'name' => 'superadmin::app.configuration.index.catalog.products.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.info',
        'icon' => 'settings/product.svg',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.settings',
        'name' => 'superadmin::app.configuration.index.catalog.products.settings.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.settings.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'compare_option',
                'title' => 'superadmin::app.configuration.index.catalog.products.settings.compare-options',
                'type' => 'boolean',
                'default' => 1,
            ], [
                'name' => 'image_search',
                'title' => 'superadmin::app.configuration.index.catalog.products.settings.image-search-option',
                'type' => 'boolean',
                'default' => 1,
            ],
        ],
    ], [
        'key' => 'catalog.products.search',
        'name' => 'superadmin::app.configuration.index.catalog.products.search.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.search.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'engine',
                'title' => 'superadmin::app.configuration.index.catalog.products.search.search-engine',
                'type' => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name' => 'admin_mode',
                'title' => 'superadmin::app.configuration.index.catalog.products.search.admin-mode',
                'info' => 'superadmin::app.configuration.index.catalog.products.search.admin-mode-info',
                'type' => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name' => 'storefront_mode',
                'title' => 'superadmin::app.configuration.index.catalog.products.search.storefront-mode',
                'info' => 'superadmin::app.configuration.index.catalog.products.search.storefront-mode-info',
                'type' => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name' => 'min_query_length',
                'title' => 'superadmin::app.configuration.index.catalog.products.search.min-query-length',
                'info' => 'superadmin::app.configuration.index.catalog.products.search.min-query-length-info',
                'type' => 'number',
                'validation' => 'numeric',
                'default' => '0',
            ], [
                'name' => 'max_query_length',
                'title' => 'superadmin::app.configuration.index.catalog.products.search.max-query-length',
                'info' => 'superadmin::app.configuration.index.catalog.products.search.max-query-length-info',
                'type' => 'number',
                'validation' => 'numeric',
                'default' => '1000',
            ],
        ],
    ], [
        'key' => 'catalog.products.product_view_page',
        'name' => 'superadmin::app.configuration.index.catalog.products.product-view-page.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.product-view-page.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'no_of_related_products',
                'title' => 'superadmin::app.configuration.index.catalog.products.product-view-page.allow-no-of-related-products',
                'type' => 'number',
                'validation' => 'integer|min:0',
            ], [
                'name' => 'no_of_up_sells_products',
                'title' => 'superadmin::app.configuration.index.catalog.products.product-view-page.allow-no-of-up-sells-products',
                'type' => 'number',
                'validation' => 'integer|min:0',
            ],
        ],
    ], [
        'key' => 'catalog.products.cart_view_page',
        'name' => 'superadmin::app.configuration.index.catalog.products.cart-view-page.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.cart-view-page.title-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'no_of_cross_sells_products',
                'title' => 'superadmin::app.configuration.index.catalog.products.cart-view-page.allow-no-of-cross-sells-products',
                'type' => 'number',
                'validation' => 'integer|min:0',
            ],
        ],
    ], [
        'key' => 'catalog.products.storefront',
        'name' => 'superadmin::app.configuration.index.catalog.products.storefront.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.storefront.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'mode',
                'title' => 'superadmin::app.configuration.index.catalog.products.storefront.default-list-mode',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name' => 'products_per_page',
                'title' => 'superadmin::app.configuration.index.catalog.products.storefront.products-per-page',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.catalog.products.storefront.comma-separated',
                'channel_based' => true,
            ], [
                'name' => 'sort_by',
                'title' => 'superadmin::app.configuration.index.catalog.products.storefront.sort-by',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.latest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.storefront.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name' => 'buy_now_button_display',
                'title' => 'superadmin::app.configuration.index.catalog.products.storefront.buy-now-button-display',
                'type' => 'boolean',
            ],
        ],
    ], [
        'key' => 'catalog.products.cache_small_image',
        'name' => 'superadmin::app.configuration.index.catalog.products.small-image.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.small-image.title-info',
        'sort' => 5,
        'fields' => [
            [
                'name' => 'width',
                'title' => 'superadmin::app.configuration.index.catalog.products.small-image.width',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'height',
                'title' => 'superadmin::app.configuration.index.catalog.products.small-image.height',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'url',
                'title' => 'superadmin::app.configuration.index.catalog.products.small-image.placeholder',
                'type' => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key' => 'catalog.products.cache_medium_image',
        'name' => 'superadmin::app.configuration.index.catalog.products.medium-image.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.medium-image.title-info',
        'sort' => 6,
        'fields' => [
            [
                'name' => 'width',
                'title' => 'superadmin::app.configuration.index.catalog.products.medium-image.width',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'height',
                'title' => 'superadmin::app.configuration.index.catalog.products.medium-image.height',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'url',
                'title' => 'superadmin::app.configuration.index.catalog.products.medium-image.placeholder',
                'type' => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key' => 'catalog.products.cache_large_image',
        'name' => 'superadmin::app.configuration.index.catalog.products.large-image.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.large-image.title-info',
        'sort' => 7,
        'fields' => [
            [
                'name' => 'width',
                'title' => 'superadmin::app.configuration.index.catalog.products.large-image.width',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'height',
                'title' => 'superadmin::app.configuration.index.catalog.products.large-image.height',
                'type' => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name' => 'url',
                'title' => 'superadmin::app.configuration.index.catalog.products.large-image.placeholder',
                'type' => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key' => 'catalog.products.review',
        'name' => 'superadmin::app.configuration.index.catalog.products.review.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.review.title-info',
        'sort' => 8,
        'fields' => [
            [
                'name' => 'guest_review',
                'title' => 'superadmin::app.configuration.index.catalog.products.review.allow-guest-review',
                'type' => 'boolean',
            ], [
                'name' => 'customer_review',
                'title' => 'superadmin::app.configuration.index.catalog.products.review.allow-customer-review',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'censoring_reviewer_name',
                'title' => 'superadmin::app.configuration.index.catalog.products.review.censoring-reviewer-name',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'summary',
                'title' => 'superadmin::app.configuration.index.catalog.products.review.summary',
                'type' => 'select',
                'default' => 'review_counts',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.catalog.products.review.display-star-count',
                        'value' => 'star_counts',
                    ], [
                        'title' => 'superadmin::app.configuration.index.catalog.products.review.display-review-count',
                        'value' => 'review_counts',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'catalog.products.attribute',
        'name' => 'superadmin::app.configuration.index.catalog.products.attribute.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.attribute.title-info',
        'sort' => 9,
        'fields' => [
            [
                'name' => 'image_attribute_upload_size',
                'title' => 'superadmin::app.configuration.index.catalog.products.attribute.image-upload-size',
                'type' => 'text',
                'validation' => 'numeric',
            ], [
                'name' => 'file_attribute_upload_size',
                'title' => 'superadmin::app.configuration.index.catalog.products.attribute.file-upload-size',
                'type' => 'text',
                'validation' => 'numeric',
            ],
        ],
    ], [
        'key' => 'catalog.products.social_share',
        'name' => 'superadmin::app.configuration.index.catalog.products.social-share.title',
        'info' => 'superadmin::app.configuration.index.catalog.products.social-share.title-info',
        'sort' => 10,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-social-share',
                'type' => 'boolean',
            ], [
                'name' => 'facebook',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-facebook',
                'type' => 'boolean',
            ], [
                'name' => 'twitter',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-twitter',
                'type' => 'boolean',
            ], [
                'name' => 'pinterest',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-pinterest',
                'type' => 'boolean',
            ], [
                'name' => 'whatsapp',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-whatsapp',
                'type' => 'boolean',
                'info' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-whatsapp-info',
            ], [
                'name' => 'linkedin',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-linkedin',
                'type' => 'boolean',
            ], [
                'name' => 'email',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.enable-share-email',
                'type' => 'boolean',
            ], [
                'name' => 'share_message',
                'title' => 'superadmin::app.configuration.index.catalog.products.social-share.share-message',
                'type' => 'text',
            ],
        ],
    ], [
        'key' => 'catalog.rich_snippets',
        'name' => 'superadmin::app.configuration.index.catalog.rich-snippets.title',
        'info' => 'superadmin::app.configuration.index.catalog.rich-snippets.info',
        'icon' => 'settings/settings.svg',
        'sort' => 2,
    ], [
        'key' => 'catalog.rich_snippets.products',
        'name' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.title',
        'info' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enable',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.enable',
                'type' => 'boolean',
            ], [
                'name' => 'show_sku',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-sku',
                'type' => 'boolean',
            ], [
                'name' => 'show_weight',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-weight',
                'type' => 'boolean',
            ], [
                'name' => 'show_categories',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-categories',
                'type' => 'boolean',
            ], [
                'name' => 'show_images',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-images',
                'type' => 'boolean',
            ], [
                'name' => 'show_reviews',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-reviews',
                'type' => 'boolean',
            ], [
                'name' => 'show_ratings',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-ratings',
                'type' => 'boolean',
            ], [
                'name' => 'show_offers',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.products.show-offers',
                'type' => 'boolean',
            ],
        ],
    ], [
        'key' => 'catalog.rich_snippets.categories',
        'name' => 'superadmin::app.configuration.index.catalog.rich-snippets.categories.title',
        'info' => 'superadmin::app.configuration.index.catalog.rich-snippets.categories.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enable',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.categories.enable',
                'type' => 'boolean',
            ], [
                'name' => 'show_search_input_field',
                'title' => 'superadmin::app.configuration.index.catalog.rich-snippets.categories.show-search-input-field',
                'type' => 'boolean',
            ],
        ],
    ], [
        'key' => 'catalog.inventory',
        'name' => 'superadmin::app.configuration.index.catalog.inventory.title',
        'info' => 'superadmin::app.configuration.index.catalog.inventory.title-info',
        'icon' => 'settings/inventory.svg',
        'sort' => 3,
    ], [
        'key' => 'catalog.inventory.stock_options',
        'name' => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.title',
        'info' => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'back_orders',
                'title' => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.allow-back-orders',
                'type' => 'boolean',
                'default',
            ],
            // [
            //     'name'          => 'maximum_product',
            //     'title'         => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.max-qty-allowed-in-cart',
            //     'type'          => 'text',
            //     'default'       => '10',
            // ], [
            //     'name'          => 'minimum_product',
            //     'title'         => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.min-qty-allowed-in-cart',
            //     'type'          => 'number',
            //     'default'       => '0',
            // ],
            [
                'name' => 'out_of_stock_threshold',
                'title' => 'superadmin::app.configuration.index.catalog.inventory.product-stock-options.out-of-stock-threshold',
                'type' => 'number',
                'default' => '0',
            ],
        ],
    ],

    /**
     * Customer.
     */
    [
        'key' => 'customer',
        'name' => 'superadmin::app.configuration.index.customer.title',
        'info' => 'superadmin::app.configuration.index.customer.info',
        'sort' => 3,
    ], [
        'key' => 'customer.address',
        'name' => 'superadmin::app.configuration.index.customer.address.title',
        'info' => 'superadmin::app.configuration.index.customer.address.info',
        'icon' => 'settings/address.svg',
        'sort' => 1,
    ], [
        'key' => 'customer.address.requirements',
        'name' => 'superadmin::app.configuration.index.customer.address.requirements.title',
        'info' => 'superadmin::app.configuration.index.customer.address.requirements.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'country',
                'title' => 'superadmin::app.configuration.index.customer.address.requirements.country',
                'type' => 'boolean',
                'channel_based' => true,
                'default' => 1,
            ], [
                'name' => 'state',
                'title' => 'superadmin::app.configuration.index.customer.address.requirements.state',
                'type' => 'boolean',
                'channel_based' => true,
                'default' => 1,
            ], [
                'name' => 'postcode',
                'title' => 'superadmin::app.configuration.index.customer.address.requirements.zip',
                'type' => 'boolean',
                'channel_based' => true,
                'default' => 1,
            ],
        ],
    ], [
        'key' => 'customer.address.information',
        'name' => 'superadmin::app.configuration.index.customer.address.information.title',
        'info' => 'superadmin::app.configuration.index.customer.address.information.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'street_lines',
                'title' => 'superadmin::app.configuration.index.customer.address.information.street-lines',
                'type' => 'text',
                'validation' => 'between:1,4|integer',
                'channel_based' => true,
                'default_value' => 1,
            ],
        ],
    ], [
        'key' => 'customer.captcha',
        'name' => 'superadmin::app.configuration.index.customer.captcha.title',
        'info' => 'superadmin::app.configuration.index.customer.captcha.info',
        'icon' => 'settings/captcha.svg',
        'sort' => 2,
    ], [
        'key' => 'customer.captcha.credentials',
        'name' => 'superadmin::app.configuration.index.customer.captcha.credentials.title',
        'info' => 'superadmin::app.configuration.index.customer.captcha.credentials.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'superadmin::app.configuration.index.customer.captcha.credentials.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'site_key',
                'title' => 'superadmin::app.configuration.index.customer.captcha.credentials.site-key',
                'type' => 'text',
                'depends' => 'status:true',
                'validation' => 'required_if:status,1',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'secret_key',
                'title' => 'superadmin::app.configuration.index.customer.captcha.credentials.secret-key',
                'type' => 'text',
                'depends' => 'status:true',
                'validation' => 'required_if:status,1',
                'channel_based' => true,
                'locale_based' => false,
            ],
        ],
    ], [
        'key' => 'customer.settings',
        'name' => 'superadmin::app.configuration.index.customer.settings.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.settings-info',
        'icon' => 'settings/settings.svg',
        'sort' => 3,
    ],
    // [
    //     'key'    => 'customer.settings.login_as_customer',
    //     'name'   => 'superadmin::app.configuration.index.customer.settings.login-as-customer.title',
    //     'info'   => 'superadmin::app.configuration.index.customer.settings.login-as-customer.title-info',
    //     'sort'   => 1,
    //     'fields' => [
    //         [
    //             'name'         => 'login',
    //             'title'        => 'superadmin::app.configuration.index.customer.settings.login-as-customer.allow-option',
    //             'type'         => 'boolean',
    //             'default'      => 1,
    //         ],
    //     ],
    // ],
    [
        'key' => 'customer.settings.wishlist',
        'name' => 'superadmin::app.configuration.index.customer.settings.wishlist.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.wishlist.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'wishlist_option',
                'title' => 'superadmin::app.configuration.index.customer.settings.wishlist.allow-option',
                'type' => 'boolean',
                'default' => 1,
            ],
        ],
    ], [
        'key' => 'customer.settings.login_options',
        'name' => 'superadmin::app.configuration.index.customer.settings.login-options.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.login-options.title-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'redirected_to_page',
                'title' => 'superadmin::app.configuration.index.customer.settings.login-options.redirect-to-page',
                'type' => 'select',
                'default' => 'home',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.customer.settings.login-options.home',
                        'value' => 'home',
                    ], [
                        'title' => 'superadmin::app.configuration.index.customer.settings.login-options.account',
                        'value' => 'account',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'customer.settings.create_new_account_options',
        'name' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'default_group',
                'title' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.default-group.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.default-group.title-info',
                'type' => 'select',
                'default' => 'general',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.default-group.general',
                        'value' => 'general',
                    ], [
                        'title' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.default-group.guest',
                        'value' => 'guest',
                    ], [
                        'title' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.default-group.wholesale',
                        'value' => 'wholesale',
                    ],
                ],
            ], [
                'name' => 'news_letter',
                'title' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.news-letter',
                'info' => 'superadmin::app.configuration.index.customer.settings.create-new-account-option.news-letter-info',
                'type' => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key' => 'customer.settings.newsletter',
        'name' => 'superadmin::app.configuration.index.customer.settings.newsletter.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.newsletter.title-info',
        'sort' => 5,
        'fields' => [
            [
                'name' => 'subscription',
                'title' => 'superadmin::app.configuration.index.customer.settings.newsletter.subscription',
                'info' => 'Enable subscription option for users in the footer section.',
                'type' => 'boolean',
                'default' => 1,
            ],
        ],
    ], [
        'key' => 'customer.settings.email',
        'name' => 'superadmin::app.configuration.index.customer.settings.email.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.email.title-info',
        'sort' => 6,
        'fields' => [
            [
                'name' => 'verification',
                'title' => 'superadmin::app.configuration.index.customer.settings.email.email-verification',
                'type' => 'boolean',
            ],
        ],
    ], [
        'key' => 'customer.settings.social_login',
        'name' => 'superadmin::app.configuration.index.customer.settings.social-login.title',
        'info' => 'superadmin::app.configuration.index.customer.settings.social-login.info',
        'sort' => 7,
        'fields' => [
            [
                'name' => 'enable_facebook',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.enable-facebook',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'facebook_client_id',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.client-id.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.client-id.title-info',
                'type' => 'text',
                'depends' => 'enable_facebook:1',
            ], [
                'name' => 'facebook_client_secret',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.client-secret.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.client-secret.title-info',
                'type' => 'text',
                'depends' => 'enable_facebook:1',
            ], [
                'name' => 'facebook_callback_url',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.redirect.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.facebook.redirect.title-info',
                'type' => 'text',
                'validation' => 'url',
                'depends' => 'enable_facebook:1',
                'default' => config('app.url').'/customer/social-login/facebook/callback',
                'placeholder' => config('app.url').'/customer/social-login/facebook/callback',
            ], [
                'name' => 'enable_twitter',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.enable-twitter',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'twitter_client_id',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.client-id.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.client-id.title-info',
                'type' => 'text',
                'depends' => 'enable_twitter:1',
            ], [
                'name' => 'twitter_client_secret',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.client-secret.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.client-secret.title-info',
                'type' => 'text',
                'depends' => 'enable_twitter:1',
            ], [
                'name' => 'twitter_callback_url',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.redirect.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.twitter.redirect.title-info',
                'type' => 'text',
                'validation' => 'url',
                'depends' => 'enable_twitter:1',
                'default' => config('app.url').'/customer/social-login/twitter/callback',
                'placeholder' => config('app.url').'/customer/social-login/twitter/callback',
            ], [
                'name' => 'enable_google',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.google.enable-google',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'google_client_id',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.google.client-id.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.google.client-id.title-info',
                'type' => 'text',
                'depends' => 'enable_google:1',
            ], [
                'name' => 'google_client_secret',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.google.client-secret.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.google.client-secret.title-info',
                'type' => 'text',
                'depends' => 'enable_google:1',
            ], [
                'name' => 'google_callback_url',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.google.redirect.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.google.redirect.title-info',
                'type' => 'text',
                'validation' => 'url',
                'depends' => 'enable_google:1',
                'default' => config('app.url').'/customer/social-login/google/callback',
                'placeholder' => config('app.url').'/customer/social-login/google/callback',
            ], [
                'name' => 'enable_linkedin-openid',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.enable-linkedin',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'linkedin_client_id',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.client-id.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.client-id.title-info',
                'type' => 'text',
                'depends' => 'enable_linkedin-openid:1',
            ], [
                'name' => 'linkedin_client_secret',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.client-secret.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.client-secret.title-info',
                'type' => 'text',
                'depends' => 'enable_linkedin-openid:1',
            ], [
                'name' => 'linkedin_callback_url',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.redirect.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.linkedin.redirect.title-info',
                'type' => 'text',
                'validation' => 'url',
                'depends' => 'enable_linkedin-openid:1',
                'placeholder' => config('app.url').'/customer/social-login/linkedin-openid/callback',
                'default' => config('app.url').'/customer/social-login/linkedin-openid/callback',
            ], [
                'name' => 'enable_github',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.github.enable-github',
                'type' => 'boolean',
                'channel_based' => true,
            ], [
                'name' => 'github_client_id',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.github.client-id.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.github.client-id.title-info',
                'type' => 'text',
                'depends' => 'enable_github:1',
            ], [
                'name' => 'github_client_secret',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.github.client-secret.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.github.client-secret.title-info',
                'type' => 'text',
                'depends' => 'enable_github:1',
            ], [
                'name' => 'github_callback_url',
                'title' => 'superadmin::app.configuration.index.customer.settings.social-login.github.redirect.title',
                'info' => 'superadmin::app.configuration.index.customer.settings.social-login.github.redirect.title-info',
                'type' => 'text',
                'validation' => 'url',
                'depends' => 'enable_github:1',
                'placeholder' => config('app.url').'/customer/social-login/github/callback',
                'default' => config('app.url').'/customer/social-login/github/callback',
            ],
        ],
    ],

    /**
     * Emails.
     */
    [
        'key' => 'emails',
        'name' => 'superadmin::app.configuration.index.email.title',
        'info' => 'superadmin::app.configuration.index.email.info',
        'sort' => 4,
    ], [
        'key' => 'emails.configure',
        'name' => 'superadmin::app.configuration.index.email.email-settings.title',
        'info' => 'superadmin::app.configuration.index.email.email-settings.info',
        'icon' => 'settings/email.svg',
        'sort' => 1,
    ], [
        'key' => 'emails.configure.email_settings',
        'name' => 'superadmin::app.configuration.index.email.email-settings.title',
        'info' => 'superadmin::app.configuration.index.email.email-settings.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'sender_name',
                'title' => 'superadmin::app.configuration.index.email.email-settings.email-sender-name',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.email-sender-name-tip',
                'validation' => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.from.name'),
            ], [
                'name' => 'shop_email_from',
                'title' => 'superadmin::app.configuration.index.email.email-settings.shop-email-from',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.shop-email-from-tip',
                'validation' => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.from.address'),
            ], [
                'name' => 'admin_name',
                'title' => 'superadmin::app.configuration.index.email.email-settings.admin-name',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.admin-name-tip',
                'validation' => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.admin.name'),
            ], [
                'name' => 'admin_email',
                'title' => 'superadmin::app.configuration.index.email.email-settings.admin-email',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.admin-email-tip',
                'validation' => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.admin.address'),
            ], [
                'name' => 'contact_name',
                'title' => 'superadmin::app.configuration.index.email.email-settings.contact-name',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.contact-name-tip',
                'validation' => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.contact.name'),
            ], [
                'name' => 'contact_email',
                'title' => 'superadmin::app.configuration.index.email.email-settings.contact-email',
                'type' => 'text',
                'info' => 'superadmin::app.configuration.index.email.email-settings.contact-email-tip',
                'validation' => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.contact.address'),
            ],
        ],
    ], [
        'key' => 'emails.general',
        'name' => 'superadmin::app.configuration.index.email.notifications.title',
        'info' => 'superadmin::app.configuration.index.email.notifications.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key' => 'emails.general.notifications',
        'name' => 'superadmin::app.configuration.index.email.notifications.title',
        'info' => 'superadmin::app.configuration.index.email.notifications.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'emails.general.notifications.registration',
                'title' => 'superadmin::app.configuration.index.email.notifications.registration',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.customer_registration_confirmation_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.customer-registration-confirmation-mail-to-admin',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.customer_account_credentials',
                'title' => 'superadmin::app.configuration.index.email.notifications.customer',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_order',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-order',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_order_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-order-mail-to-admin',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_invoice',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-invoice',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_invoice_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-invoice-mail-to-admin',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_refund',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-refund',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_refund_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-refund-mail-to-admin',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_shipment',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-shipment',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_shipment_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-shipment-mail-to-admin',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.new_inventory_source',
                'title' => 'superadmin::app.configuration.index.email.notifications.new-inventory-source',
                'type' => 'boolean',
            ], [
                'name' => 'emails.general.notifications.cancel_order',
                'title' => 'superadmin::app.configuration.index.email.notifications.cancel-order',
                'type' => 'boolean',
            ],  [
                'name' => 'emails.general.notifications.cancel_order_mail_to_admin',
                'title' => 'superadmin::app.configuration.index.email.notifications.cancel-order-mail-to-admin',
                'type' => 'boolean',
            ],
        ],
    ],

    /**
     * Sales.
     */
    [
        'key' => 'sales',
        'name' => 'superadmin::app.configuration.index.sales.title',
        'info' => 'superadmin::app.configuration.index.sales.info',
        'sort' => 5,
    ], [
        'key' => 'sales.shipping',
        'name' => 'superadmin::app.configuration.index.sales.shipping-setting.title',
        'info' => 'superadmin::app.configuration.index.sales.shipping-setting.info',
        'icon' => 'settings/shipping.svg',
        'sort' => 1,
    ], [
        'key' => 'sales.shipping.origin',
        'name' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.title',
        'info' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.title-info',
        'sort' => 0,
        'fields' => [
            [
                'name' => 'country',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.country',
                'type' => 'country',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'state',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.state',
                'type' => 'state',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'city',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.city',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'address',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.street-address',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'zipcode',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.zip',
                'type' => 'text',
                'validation' => 'required|postcode',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'store_name',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.store-name',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'vat_number',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.vat-number',
                'type' => 'text',
                'channel_based' => true,
            ], [
                'name' => 'contact',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.contact-number',
                'type' => 'text',
                'validation' => 'phone',
                'channel_based' => true,
            ], [
                'name' => 'bank_details',
                'title' => 'superadmin::app.configuration.index.sales.shipping-setting.origin.bank-details',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.carriers',
        'name' => 'superadmin::app.configuration.index.sales.shipping-methods.title',
        'info' => 'superadmin::app.configuration.index.sales.shipping-methods.info',
        'icon' => 'settings/shipping-method.svg',
        'sort' => 2,
    ], [
        'key' => 'sales.carriers.free',
        'name' => 'superadmin::app.configuration.index.sales.shipping-methods.free-shipping.page-title',
        'info' => 'superadmin::app.configuration.index.sales.shipping-methods.free-shipping.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.free-shipping.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.free-shipping.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.free-shipping.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ],
        ],
    ], [
        'key' => 'sales.carriers.flatrate',
        'name' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.page-title',
        'info' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'default_rate',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.rate',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1|numeric',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'type',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.title',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.per-unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.per-order',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ],
        ],
    ], [
        'key' => 'sales.payment_methods',
        'name' => 'superadmin::app.configuration.index.sales.payment-methods.page-title',
        'info' => 'superadmin::app.configuration.index.sales.payment-methods.info',
        'icon' => 'settings/payment-method.svg',
        'sort' => 3,
    ], [
        'key' => 'sales.payment_methods.cashondelivery',
        'name' => 'superadmin::app.configuration.index.sales.payment-methods.cash-on-delivery',
        'info' => 'superadmin::app.configuration.index.sales.payment-methods.cash-on-delivery-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'image',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.logo',
                'type' => 'image',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => true,
                'locale_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name' => 'instructions',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.instructions',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'generate_invoice',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type' => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'invoice_status',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'depends' => 'generate_invoice:1',
                'validation' => 'required_if:generate_invoice,1',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.set-order-status',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'order_status',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.set-order-status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending',
                        'value' => Order::STATUS_PENDING,
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => Order::STATUS_PENDING_PAYMENT,
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.processing',
                        'value' => Order::STATUS_PROCESSING,
                    ],
                ],
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sort',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sort-order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.payment_methods.moneytransfer',
        'name' => 'superadmin::app.configuration.index.sales.payment-methods.money-transfer',
        'info' => 'superadmin::app.configuration.index.sales.payment-methods.money-transfer-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'image',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.logo',
                'type' => 'image',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name' => 'generate_invoice',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type' => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'invoice_status',
                'depends' => 'generate_invoice:1',
                'validation' => 'required_if:generate_invoice,1',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'order_status',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.set-order-status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'mailing_address',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.mailing-address',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sort',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sort-order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.payment_methods.paypal_standard',
        'name' => 'superadmin::app.configuration.index.sales.payment-methods.paypal-standard',
        'info' => 'superadmin::app.configuration.index.sales.payment-methods.paypal-standard-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'image',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.logo',
                'type' => 'image',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name' => 'business_account',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.business-account',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => false,
            ],  [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sandbox',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sandbox',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sort',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sort-order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.payment_methods.paypal_smart_button',
        'name' => 'superadmin::app.configuration.index.sales.payment-methods.paypal-smart-button',
        'info' => 'superadmin::app.configuration.index.sales.payment-methods.paypal-smart-button-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.title',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'image',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.logo',
                'type' => 'image',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name' => 'client_id',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.client-id',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.client-id-info',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'client_secret',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.client-secret',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.client-secret-info',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => false,
                'default' => 'CLIENT_SECRET',
            ], [
                'name' => 'accepted_currencies',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.accepted-currencies',
                'info' => 'superadmin::app.configuration.index.sales.payment-methods.accepted-currencies-info',
                'type' => 'text',
                'depends' => 'active:1',
                'validation' => 'required_if:active,1',
                'channel_based' => true,
                'locale_based' => false,
                'default' => 'USD',
            ], [
                'name' => 'active',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.status',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sandbox',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sandbox',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ], [
                'name' => 'sort',
                'title' => 'superadmin::app.configuration.index.sales.payment-methods.sort-order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.order_settings',
        'name' => 'superadmin::app.configuration.index.sales.order-settings.title',
        'info' => 'superadmin::app.configuration.index.sales.order-settings.info',
        'icon' => 'settings/order.svg',
        'sort' => 4,
    ], [
        'key' => 'sales.order_settings.order_creation',
        'name' => 'superadmin::app.configuration.index.sales.order-settings.order-creation.title',
        'info' => 'superadmin::app.configuration.index.sales.order-settings.order-creation.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'max_retry_attempts',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.order-creation.max-retry-attempts',
                'type' => 'number',
                'validation' => 'required|integer|min:1',
                'default' => 3,
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.order_settings.order_number',
        'name' => 'superadmin::app.configuration.index.sales.order-settings.order-number.title',
        'info' => 'superadmin::app.configuration.index.sales.order-settings.order-number.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'order_number_prefix',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.order-number.prefix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
            ], [
                'name' => 'order_number_length',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.order-number.length',
                'type' => 'text',
                'validation' => 'between:1,10|integer',
                'channel_based' => true,
            ], [
                'name' => 'order_number_suffix',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.order-number.suffix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
            ], [
                'name' => 'order_number_generator',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.order-number.generator',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.order_settings.minimum_order',
        'name' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.title',
        'info' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'enable',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.enable',
                'type' => 'boolean',
            ], [
                'name' => 'minimum_order_amount',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.minimum-order-amount',
                'type' => 'number',
                'validation' => 'required_if:enable,1|numeric',
                'depends' => 'enable:1',
                'channel_based' => true,
            ], [
                'name' => 'include_discount_amount',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.include-discount-amount',
                'type' => 'boolean',
                'depends' => 'enable:1',
            ], [
                'name' => 'include_tax_to_amount',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.include-tax-amount',
                'type' => 'boolean',
                'depends' => 'enable:1',
            ], [
                'name' => 'description',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.minimum-order.description',
                'type' => 'textarea',
                'depends' => 'enable:1',
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.order_settings.reorder',
        'name' => 'superadmin::app.configuration.index.sales.order-settings.reorder.title',
        'info' => 'superadmin::app.configuration.index.sales.order-settings.reorder.info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'admin',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.reorder.admin-reorder',
                'info' => 'superadmin::app.configuration.index.sales.order-settings.reorder.admin-reorder-info',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'shop',
                'title' => 'superadmin::app.configuration.index.sales.order-settings.reorder.shop-reorder',
                'info' => 'superadmin::app.configuration.index.sales.order-settings.reorder.shop-reorder-info',
                'type' => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key' => 'sales.invoice_settings',
        'name' => 'superadmin::app.configuration.index.sales.invoice-settings.title',
        'info' => 'superadmin::app.configuration.index.sales.invoice-settings.info',
        'icon' => 'settings/invoice.svg',
        'sort' => 5,
    ], [
        'key' => 'sales.invoice_settings.invoice_number',
        'name' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.title',
        'info' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.info',
        'sort' => 0,
        'fields' => [
            [
                'name' => 'invoice_number_prefix',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.prefix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'invoice_number_length',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.length',
                'type' => 'text',
                'validation' => 'numeric|min:0|max:10',
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'invoice_number_suffix',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.suffix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true,
            ], [
                'name' => 'invoice_number_generator_class',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-number.generator',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.invoice_settings.payment_terms',
        'name' => 'superadmin::app.configuration.index.sales.invoice-settings.payment-terms.title',
        'info' => 'superadmin::app.configuration.index.sales.invoice-settings.payment-terms.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'due_duration',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration',
                'type' => 'text',
                'validation' => 'numeric',
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.invoice_settings.pdf_print_outs',
        'name' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.title',
        'info' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'invoice_id',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.invoice-id-title',
                'info' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.invoice-id-info',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'order_id',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.order-id-title',
                'info' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.order-id-info',
                'type' => 'boolean',
                'default' => true,
            ], [
                'name' => 'logo',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.logo',
                'info' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.logo-info',
                'type' => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
                'channel_based' => true,
            ], [
                'name' => 'footer_text',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.footer-text',
                'info' => 'superadmin::app.configuration.index.sales.invoice-settings.pdf-print-outs.footer-text-info',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ],
        ],
    ], [
        'key' => 'sales.invoice_settings.invoice_reminders',
        'name' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-reminders.title',
        'info' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-reminders.info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'reminders_limit',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-reminders.maximum-limit-of-reminders',
                'type' => 'text',
                'validation' => 'numeric',
                'channel_based' => true,
            ], [
                'name' => 'interval_between_reminders',
                'title' => 'superadmin::app.configuration.index.sales.invoice-settings.invoice-reminders.interval-between-reminders',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1 day',
                        'value' => 'P1D',
                    ], [
                        'title' => '2 days',
                        'value' => 'P2D',
                    ], [
                        'title' => '3 days',
                        'value' => 'P3D',
                    ], [
                        'title' => '4 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '5 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '6 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '7 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '2 weeks',
                        'value' => 'P2W',
                    ], [
                        'title' => '3 weeks',
                        'value' => 'P3W',
                    ], [
                        'title' => '4 weeks',
                        'value' => 'P4W',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.taxes',
        'name' => 'superadmin::app.configuration.index.sales.taxes.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.title-info',
        'icon' => 'settings/tax.svg',
        'sort' => 6,
    ], [
        'key' => 'sales.taxes.categories',
        'name' => 'superadmin::app.configuration.index.sales.taxes.categories.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.categories.title-info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'shipping',
                'title' => 'superadmin::app.configuration.index.sales.taxes.categories.shipping',
                'type' => 'select',
                'default' => 0,
                'options' => 'Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
            ], [
                'name' => 'product',
                'title' => 'superadmin::app.configuration.index.sales.taxes.categories.product',
                'type' => 'select',
                'default' => 0,
                'options' => 'Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
            ],
        ],
    ], [
        'key' => 'sales.taxes.calculation',
        'name' => 'superadmin::app.configuration.index.sales.taxes.calculation.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.calculation.title-info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'based_on',
                'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.based-on',
                'type' => 'select',
                'default' => 'shipping_address',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.shipping-address',
                        'value' => 'shipping_address',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.billing-address',
                        'value' => 'billing_address',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.shipping-origin',
                        'value' => 'shipping_origin',
                    ],
                ],
            ], [
                'name' => 'product_prices',
                'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.product-prices',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.including-tax',
                        'value' => 'including_tax',
                    ],
                ],
            ], [
                'name' => 'shipping_prices',
                'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.shipping-prices',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.calculation.including-tax',
                        'value' => 'including_tax',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.taxes.default_destination_calculation',
        'name' => 'superadmin::app.configuration.index.sales.taxes.default-destination-calculation.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.default-destination-calculation.title-info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'country',
                'title' => 'superadmin::app.configuration.index.sales.taxes.default-destination-calculation.default-country',
                'type' => 'country',
                'default' => '',
            ], [
                'name' => 'state',
                'title' => 'superadmin::app.configuration.index.sales.taxes.default-destination-calculation.default-state',
                'type' => 'state',
                'default' => '',
            ], [
                'name' => 'post_code',
                'title' => 'superadmin::app.configuration.index.sales.taxes.default-destination-calculation.default-post-code',
                'type' => 'text',
                'default' => '',
            ],
        ],
    ], [
        'key' => 'sales.taxes.shopping_cart',
        'name' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'display_prices',
                'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.display-prices',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name' => 'display_subtotal',
                'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.display-subtotal',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name' => 'display_shipping_amount',
                'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.display-shipping-amount',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.taxes.sales',
        'name' => 'superadmin::app.configuration.index.sales.taxes.sales.title',
        'info' => 'superadmin::app.configuration.index.sales.taxes.sales.title-info',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'display_prices',
                'title' => 'superadmin::app.configuration.index.sales.taxes.sales.display-prices',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name' => 'display_subtotal',
                'title' => 'superadmin::app.configuration.index.sales.taxes.sales.display-subtotal',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name' => 'display_shipping_amount',
                'title' => 'superadmin::app.configuration.index.sales.taxes.sales.display-shipping-amount',
                'type' => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.checkout',
        'name' => 'superadmin::app.configuration.index.sales.checkout.title',
        'info' => 'superadmin::app.configuration.index.sales.checkout.info',
        'icon' => 'settings/checkout.svg',
        'sort' => 7,
    ], [
        'key' => 'sales.checkout.shopping_cart',
        'name' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.title',
        'info' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'allow_guest_checkout',
                'title' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.guest-checkout',
                'info' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.guest-checkout-info',
                'type' => 'boolean',
                'default' => 1,
            ], [
                'name' => 'cart_page',
                'title' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.cart-page',
                'info' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.cart-page-info',
                'type' => 'boolean',
                'default' => 2,
            ], [
                'name' => 'cross_sell',
                'title' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.cross-sell',
                'info' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.cross-sell-info',
                'type' => 'boolean',
                'default' => 3,
            ], [
                'name' => 'estimate_shipping',
                'title' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.estimate-shipping',
                'info' => 'superadmin::app.configuration.index.sales.checkout.shopping-cart.estimate-shipping-info',
                'type' => 'boolean',
                'default' => 4,
            ],
        ],
    ], [
        'key' => 'sales.checkout.my_cart',
        'name' => 'superadmin::app.configuration.index.sales.checkout.my-cart.title',
        'info' => 'superadmin::app.configuration.index.sales.checkout.my-cart.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'summary',
                'title' => 'superadmin::app.configuration.index.sales.checkout.my-cart.summary',
                'type' => 'select',
                'default' => 'display_number_of_items_in_cart',
                'options' => [
                    [
                        'title' => 'superadmin::app.configuration.index.sales.checkout.my-cart.display-item-quantities',
                        'value' => 'display_item_quantity',
                    ], [
                        'title' => 'superadmin::app.configuration.index.sales.checkout.my-cart.display-number-in-cart',
                        'value' => 'display_number_of_items_in_cart',
                    ],
                ],
            ],
        ],
    ], [
        'key' => 'sales.checkout.mini_cart',
        'name' => 'superadmin::app.configuration.index.sales.checkout.mini-cart.title',
        'info' => 'superadmin::app.configuration.index.sales.checkout.mini-cart.info',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'display_mini_cart',
                'title' => 'superadmin::app.configuration.index.sales.checkout.mini-cart.display-mini-cart',
                'type' => 'boolean',
                'default' => 1,
            ], [
                'name' => 'offer_info',
                'title' => 'superadmin::app.configuration.index.sales.checkout.mini-cart.mini-cart-offer-info',
                'type' => 'text',
                'default' => 'Get Up To 30% OFF on your 1st order',
                'validation' => 'max:200',
            ],
        ],
    ],
];
