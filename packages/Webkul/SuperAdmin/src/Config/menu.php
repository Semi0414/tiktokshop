<?php

return [
    /**
     * Dashboard.
     */
    [
        'key' => 'dashboard',
        'name' => 'superadmin::app.components.layouts.sidebar.dashboard',
        'route' => 'superadmin.dashboard.index',
        'sort' => 1,
        'icon' => 'icon-dashboard',
    ],

    /**
     * Sales.
     */
    [
        'key' => 'sales',
        'name' => 'superadmin::app.components.layouts.sidebar.sales',
        'route' => 'superadmin.sales.orders.landing',
        'sort' => 2,
        'icon' => 'icon-sales',
    ], [
        'key' => 'sales.orders',
        'name' => 'superadmin::app.components.layouts.sidebar.orders',
        'route' => 'superadmin.sales.orders.landing',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'sales.shipments',
        'name' => 'superadmin::app.components.layouts.sidebar.shipments',
        'route' => 'superadmin.sales.shipments.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'sales.invoices',
        'name' => 'superadmin::app.components.layouts.sidebar.invoices',
        'route' => 'superadmin.sales.invoices.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'sales.refunds',
        'name' => 'superadmin::app.components.layouts.sidebar.refunds',
        'route' => 'superadmin.sales.refunds.index',
        'sort' => 4,
        'icon' => '',
    ], [
        'key' => 'sales.transactions',
        'name' => 'superadmin::app.components.layouts.sidebar.transactions',
        'route' => 'superadmin.sales.transactions.index',
        'sort' => 5,
        'icon' => '',
    ], [
        'key' => 'sales.wallet-requests',
        'name' => 'superadmin::app.components.layouts.sidebar.wallet-requests',
        'route' => 'superadmin.sales.wallet-requests.index',
        'sort' => 6,
        'icon' => '',
    ], [
        'key' => 'sales.bookings',
        'name' => 'superadmin::app.components.layouts.sidebar.booking-product',
        'route' => 'superadmin.sales.bookings.index',
        'sort' => 7,
        'icon' => '',
    ],

    /**
     * Catalog.
     */
    [
        'key' => 'catalog',
        'name' => 'superadmin::app.components.layouts.sidebar.catalog',
        'route' => 'superadmin.catalog.products.index',
        'sort' => 3,
        'icon' => 'icon-product',
    ], [
        'key' => 'catalog.products',
        'name' => 'superadmin::app.components.layouts.sidebar.products',
        'route' => 'superadmin.catalog.products.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'catalog.categories',
        'name' => 'superadmin::app.components.layouts.sidebar.categories',
        'route' => 'superadmin.catalog.categories.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'catalog.attributes',
        'name' => 'superadmin::app.components.layouts.sidebar.attributes',
        'route' => 'superadmin.catalog.attributes.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'catalog.families',
        'name' => 'superadmin::app.components.layouts.sidebar.attribute-families',
        'route' => 'superadmin.catalog.families.index',
        'sort' => 4,
        'icon' => '',
    ],

    /**
     * Customers.
     */
    [
        'key' => 'customers',
        'name' => 'superadmin::app.components.layouts.sidebar.customers',
        'route' => 'superadmin.customers.customers.index',
        'sort' => 4,
        'icon' => 'icon-customer-2',
    ], [
        'key' => 'customers.customers',
        'name' => 'superadmin::app.components.layouts.sidebar.customers',
        'route' => 'superadmin.customers.customers.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'customers.groups',
        'name' => 'superadmin::app.components.layouts.sidebar.groups',
        'route' => 'superadmin.customers.groups.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'customers.reviews',
        'name' => 'superadmin::app.components.layouts.sidebar.reviews',
        'route' => 'superadmin.customers.customers.review.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'customers.gdpr_requests',
        'name' => 'superadmin::app.components.layouts.sidebar.gdpr-data-requests',
        'route' => 'superadmin.customers.gdpr.index',
        'sort' => 4,
        'icon' => '',
    ], [
        'key' => 'customers.orders',
        'name' => 'superadmin::app.components.layouts.sidebar.orders',
        'route' => 'superadmin.sales.orders.customers.index',
        'sort' => 5,
        'icon' => '',
    ],

    /**
     * Sellers.
     */
    [
        'key' => 'sellers',
        'name' => 'superadmin::app.components.layouts.sidebar.sellers',
        'route' => 'superadmin.sellers.index',
        'sort' => 5,
        'icon' => 'icon-customer-2',
    ], [
        'key' => 'sellers.all',
        'name' => 'superadmin::app.components.layouts.sidebar.all-sellers',
        'route' => 'superadmin.sellers.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'sellers.orders',
        'name' => 'superadmin::app.components.layouts.sidebar.seller-orders',
        'route' => 'superadmin.sellers.orders.dashboard',
        'sort' => 2,
        'icon' => '',
    ],

    /**
     * Home page media (landing hero).
     */
    [
        'key' => 'home_page_media',
        'name' => 'superadmin::app.components.layouts.sidebar.home-page-media',
        'route' => 'superadmin.home_page_media.index',
        'sort' => 6,
        'icon' => 'icon-image',
    ],

    /**
     * CMS.
     */
    [
        'key' => 'cms',
        'name' => 'superadmin::app.components.layouts.sidebar.cms',
        'route' => 'superadmin.cms.index',
        'sort' => 7,
        'icon' => 'icon-cms',
    ],

    /**
     * Marketing.
     */
    [
        'key' => 'marketing',
        'name' => 'superadmin::app.components.layouts.sidebar.marketing',
        'route' => 'superadmin.marketing.promotions.catalog_rules.index',
        'sort' => 8,
        'icon' => 'icon-promotion',
        'icon-class' => 'promotion-icon',
    ], [
        'key' => 'marketing.promotions',
        'name' => 'superadmin::app.components.layouts.sidebar.promotions',
        'route' => 'superadmin.marketing.promotions.catalog_rules.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'marketing.promotions.catalog_rules',
        'name' => 'superadmin::app.marketing.promotions.index.catalog-rule-title',
        'route' => 'superadmin.marketing.promotions.catalog_rules.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'marketing.promotions.cart_rules',
        'name' => 'superadmin::app.marketing.promotions.index.cart-rule-title',
        'route' => 'superadmin.marketing.promotions.cart_rules.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'marketing.email_management',
        'name' => 'superadmin::app.components.layouts.sidebar.email-management',
        'route' => 'superadmin.email-management.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'marketing.communications',
        'name' => 'superadmin::app.components.layouts.sidebar.communications',
        'route' => 'superadmin.marketing.communications.email_templates.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'marketing.communications.email_templates',
        'name' => 'superadmin::app.components.layouts.sidebar.email-templates',
        'route' => 'superadmin.marketing.communications.email_templates.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'marketing.communications.events',
        'name' => 'superadmin::app.components.layouts.sidebar.events',
        'route' => 'superadmin.marketing.communications.events.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'marketing.communications.campaigns',
        'name' => 'superadmin::app.components.layouts.sidebar.campaigns',
        'route' => 'superadmin.marketing.communications.campaigns.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'marketing.communications.subscribers',
        'name' => 'superadmin::app.components.layouts.sidebar.newsletter-subscriptions',
        'route' => 'superadmin.marketing.communications.subscribers.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'marketing.search_seo',
        'name' => 'superadmin::app.components.layouts.sidebar.search-seo',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.index',
        'sort' => 4,
        'icon' => '',
    ], [
        'key' => 'marketing.search_seo.url_rewrites',
        'name' => 'superadmin::app.components.layouts.sidebar.url-rewrites',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'marketing.search_seo.search_terms',
        'name' => 'superadmin::app.components.layouts.sidebar.search-terms',
        'route' => 'superadmin.marketing.search_seo.search_terms.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'marketing.search_seo.search_synonyms',
        'name' => 'superadmin::app.components.layouts.sidebar.search-synonyms',
        'route' => 'superadmin.marketing.search_seo.search_synonyms.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'marketing.search_seo.sitemaps',
        'name' => 'superadmin::app.components.layouts.sidebar.sitemaps',
        'route' => 'superadmin.marketing.search_seo.sitemaps.index',
        'sort' => 4,
        'icon' => '',
    ], [
        'key' => 'marketing.seller_tools',
        'name' => 'superadmin::app.components.layouts.sidebar.seller-programs',
        'route' => 'superadmin.marketing.seller_tools.seller_level',
        'sort' => 5,
        'icon' => '',
    ], [
        'key' => 'marketing.seller_tools.seller_level',
        'name' => 'superadmin::app.components.layouts.sidebar.seller-level',
        'route' => 'superadmin.marketing.seller_tools.seller_level',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'marketing.seller_tools.upgrade_packages',
        'name' => 'superadmin::app.components.layouts.sidebar.upgrade-packages',
        'route' => 'superadmin.marketing.seller_tools.store_upgrade',
        'sort' => 2,
        'icon' => '',
    ],

    /**
     * Reporting.
     */
    [
        'key' => 'reporting',
        'name' => 'superadmin::app.components.layouts.sidebar.reporting',
        'route' => 'superadmin.reporting.sales.index',
        'sort' => 9,
        'icon' => 'icon-report',
        'icon-class' => 'report-icon',
    ], [
        'key' => 'reporting.sales',
        'name' => 'superadmin::app.components.layouts.sidebar.sales',
        'route' => 'superadmin.reporting.sales.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'reporting.customers',
        'name' => 'superadmin::app.components.layouts.sidebar.customers',
        'route' => 'superadmin.reporting.customers.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'reporting.products',
        'name' => 'superadmin::app.components.layouts.sidebar.products',
        'route' => 'superadmin.reporting.products.index',
        'sort' => 3,
        'icon' => '',
    ],

    /**
     * Settings.
     */
    [
        'key' => 'settings',
        'name' => 'superadmin::app.components.layouts.sidebar.settings',
        'route' => 'superadmin.settings.locales.index',
        'sort' => 10,
        'icon' => 'icon-settings',
        'icon-class' => 'settings-icon',
    ], [
        'key' => 'settings.locales',
        'name' => 'superadmin::app.components.layouts.sidebar.locales',
        'route' => 'superadmin.settings.locales.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'settings.currencies',
        'name' => 'superadmin::app.components.layouts.sidebar.currencies',
        'route' => 'superadmin.settings.currencies.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'superadmin::app.components.layouts.sidebar.exchange-rates',
        'route' => 'superadmin.settings.exchange_rates.index',
        'sort' => 3,
        'icon' => '',
    ], [
        'key' => 'settings.inventory_sources',
        'name' => 'superadmin::app.components.layouts.sidebar.inventory-sources',
        'route' => 'superadmin.settings.inventory_sources.index',
        'sort' => 4,
        'icon' => '',
    ], [
        'key' => 'settings.channels',
        'name' => 'superadmin::app.components.layouts.sidebar.channels',
        'route' => 'superadmin.settings.channels.index',
        'sort' => 5,
        'icon' => '',
    ], [
        'key' => 'settings.users',
        'name' => 'superadmin::app.components.layouts.sidebar.users',
        'route' => 'superadmin.settings.users.index',
        'sort' => 6,
        'icon' => '',
    ], [
        'key' => 'settings.roles',
        'name' => 'superadmin::app.components.layouts.sidebar.roles',
        'route' => 'superadmin.settings.roles.index',
        'sort' => 7,
        'icon' => '',
    ], [
        'key' => 'settings.themes',
        'name' => 'superadmin::app.components.layouts.sidebar.themes',
        'route' => 'superadmin.settings.themes.index',
        'sort' => 8,
        'icon' => '',
    ], [
        'key' => 'settings.taxes',
        'name' => 'superadmin::app.components.layouts.sidebar.taxes',
        'route' => 'superadmin.settings.taxes.categories.index',
        'sort' => 9,
        'icon' => '',
    ], [
        'key' => 'settings.taxes.tax_categories',
        'name' => 'superadmin::app.components.layouts.sidebar.tax-categories',
        'route' => 'superadmin.settings.taxes.categories.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'settings.taxes.tax_rates',
        'name' => 'superadmin::app.components.layouts.sidebar.tax-rates',
        'route' => 'superadmin.settings.taxes.rates.index',
        'sort' => 2,
        'icon' => '',
    ], [
        'key' => 'settings.data_transfer',
        'name' => 'superadmin::app.components.layouts.sidebar.data-transfer',
        'route' => 'superadmin.settings.data_transfer.imports.index',
        'sort' => 10,
        'icon' => '',
    ], [
        'key' => 'settings.data_transfer.imports',
        'name' => 'superadmin::app.components.layouts.sidebar.imports',
        'route' => 'superadmin.settings.data_transfer.imports.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key' => 'settings.crypto',
        'name' => 'superadmin::app.components.layouts.sidebar.crypto-payout-addresses',
        'route' => 'superadmin.settings.crypto_addresses.index',
        'sort' => 5,
        'icon' => '',
    ],

    /**
     * Configuration.
     */
    [
        'key' => 'configuration',
        'name' => 'superadmin::app.components.layouts.sidebar.configure',
        'route' => 'superadmin.configuration.index',
        'sort' => 11,
        'icon' => 'icon-configuration',
    ],
];
