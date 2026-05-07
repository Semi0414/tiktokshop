<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | All ACLs related to dashboard will be placed here.
    |
    */
    [
        'key' => 'dashboard',
        'name' => 'superadmin::app.acl.dashboard',
        'route' => 'superadmin.dashboard.index',
        'sort' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sales
    |--------------------------------------------------------------------------
    |
    | All ACLs related to sales will be placed here.
    |
    */
    [
        'key' => 'sales',
        'name' => 'superadmin::app.acl.sales',
        'route' => 'superadmin.sales.orders.landing',
        'sort' => 2,
    ], [
        'key' => 'sales.orders',
        'name' => 'superadmin::app.acl.orders',
        'route' => 'superadmin.sales.orders.landing',
        'sort' => 1,
    ], [
        'key' => 'sales.orders.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.sales.orders.create',
        'sort' => 1,
    ], [
        'key' => 'sales.orders.view',
        'name' => 'superadmin::app.acl.view',
        'route' => 'superadmin.sales.orders.view',
        'sort' => 2,
    ], [
        'key' => 'sales.orders.cancel',
        'name' => 'superadmin::app.acl.cancel',
        'route' => 'superadmin.sales.orders.cancel',
        'sort' => 3,
    ], [
        'key' => 'sales.invoices',
        'name' => 'superadmin::app.acl.invoices',
        'route' => 'superadmin.sales.invoices.index',
        'sort' => 2,
    ], [
        'key' => 'sales.invoices.view',
        'name' => 'superadmin::app.acl.view',
        'route' => 'superadmin.sales.invoices.view',
        'sort' => 1,
    ], [
        'key' => 'sales.invoices.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.sales.invoices.store',
        'sort' => 2,
    ], [
        'key' => 'sales.shipments',
        'name' => 'superadmin::app.acl.shipments',
        'route' => 'superadmin.sales.shipments.index',
        'sort' => 3,
    ], [
        'key' => 'sales.shipments.view',
        'name' => 'superadmin::app.acl.view',
        'route' => 'superadmin.sales.shipments.view',
        'sort' => 1,
    ], [
        'key' => 'sales.shipments.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.sales.shipments.store',
        'sort' => 2,
    ], [
        'key' => 'sales.refunds',
        'name' => 'superadmin::app.acl.refunds',
        'route' => 'superadmin.sales.refunds.index',
        'sort' => 4,
    ], [
        'key' => 'sales.refunds.view',
        'name' => 'superadmin::app.acl.view',
        'route' => 'superadmin.sales.refunds.view',
        'sort' => 1,
    ], [
        'key' => 'sales.refunds.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.sales.refunds.store',
        'sort' => 2,
    ], [
        'key' => 'sales.transactions',
        'name' => 'superadmin::app.acl.transactions',
        'route' => 'superadmin.sales.transactions.index',
        'sort' => 5,
    ], [
        'key' => 'sales.transactions.view',
        'name' => 'superadmin::app.acl.view',
        'route' => 'superadmin.sales.transactions.view',
        'sort' => 1,
    ], [
        'key' => 'sales.wallet-requests',
        'name' => 'superadmin::app.acl.wallet-requests',
        'route' => 'superadmin.sales.wallet-requests.index',
        'sort' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Catalog
    |--------------------------------------------------------------------------
    |
    | All ACLs related to catalog will be placed here.
    |
    */
    [
        'key' => 'catalog',
        'name' => 'superadmin::app.acl.catalog',
        'route' => 'superadmin.catalog.products.index',
        'sort' => 3,
    ], [
        'key' => 'catalog.products',
        'name' => 'superadmin::app.acl.products',
        'route' => 'superadmin.catalog.products.index',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.catalog.products.store',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.copy',
        'name' => 'superadmin::app.acl.copy',
        'route' => 'superadmin.catalog.products.copy',
        'sort' => 2,
    ], [
        'key' => 'catalog.products.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.catalog.products.edit',
        'sort' => 3,
    ], [
        'key' => 'catalog.products.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.catalog.products.delete',
        'sort' => 4,
    ], [
        'key' => 'catalog.categories',
        'name' => 'superadmin::app.acl.categories',
        'route' => 'superadmin.catalog.categories.index',
        'sort' => 2,
    ], [
        'key' => 'catalog.categories.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.catalog.categories.create',
        'sort' => 1,
    ], [
        'key' => 'catalog.categories.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.catalog.categories.edit',
        'sort' => 2,
    ], [
        'key' => 'catalog.categories.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.catalog.categories.delete',
        'sort' => 3,
    ], [
        'key' => 'catalog.attributes',
        'name' => 'superadmin::app.acl.attributes',
        'route' => 'superadmin.catalog.attributes.index',
        'sort' => 3,
    ], [
        'key' => 'catalog.attributes.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.catalog.attributes.create',
        'sort' => 1,
    ], [
        'key' => 'catalog.attributes.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.catalog.attributes.edit',
        'sort' => 2,
    ], [
        'key' => 'catalog.attributes.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.catalog.attributes.delete',
        'sort' => 3,
    ], [
        'key' => 'catalog.families',
        'name' => 'superadmin::app.acl.attribute-families',
        'route' => 'superadmin.catalog.families.index',
        'sort' => 4,
    ], [
        'key' => 'catalog.families.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.catalog.families.create',
        'sort' => 1,
    ], [
        'key' => 'catalog.families.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.catalog.families.edit',
        'sort' => 2,
    ], [
        'key' => 'catalog.families.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.catalog.families.delete',
        'sort' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    |
    | All ACLs related to customers will be placed here.
    |
    */
    [
        'key' => 'customers',
        'name' => 'superadmin::app.acl.customers',
        'route' => 'superadmin.customers.customers.index',
        'sort' => 4,
    ], [
        'key' => 'customers.customers',
        'name' => 'superadmin::app.acl.customers',
        'route' => 'superadmin.customers.customers.index',
        'sort' => 1,
    ], [
        'key' => 'customers.customers.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.customer.create',
        'sort' => 1,
    ], [
        'key' => 'customers.customers.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.customers.customers.edit',
        'sort' => 2,
    ], [
        'key' => 'customers.customers.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.customers.customers.delete',
        'sort' => 3,
    ], [
        'key' => 'customers.addresses',
        'name' => 'superadmin::app.acl.addresses',
        'route' => 'superadmin.customers.customers.addresses.index',
        'sort' => 2,
    ], [
        'key' => 'customers.addresses.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.customers.customers.addresses.create',
        'sort' => 1,
    ], [
        'key' => 'customers.addresses.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.customers.customers.addresses.edit',
        'sort' => 2,
    ], [
        'key' => 'customers.addresses.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.customers.customers.addresses.delete',
        'sort' => 3,
    ], [
        'key' => 'customers.note',
        'name' => 'superadmin::app.acl.note',
        'route' => 'superadmin.customer.note.create',
        'sort' => 3,
    ], [
        'key' => 'customers.groups',
        'name' => 'superadmin::app.acl.groups',
        'route' => 'superadmin.customers.groups.index',
        'sort' => 4,
    ], [
        'key' => 'customers.groups.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.groups.create',
        'sort' => 1,
    ], [
        'key' => 'customers.groups.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.customers.groups.update',
        'sort' => 2,
    ], [
        'key' => 'customers.groups.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.customers.groups.delete',
        'sort' => 3,
    ], [
        'key' => 'customers.reviews',
        'name' => 'superadmin::app.acl.reviews',
        'route' => 'superadmin.customers.customers.review.index',
        'sort' => 5,
    ], [
        'key' => 'customers.reviews.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.customers.customers.review.edit',
        'sort' => 1,
    ], [
        'key' => 'customers.reviews.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.customers.customers.review.delete',
        'sort' => 2,
    ], [
        'key' => 'customers.gdpr_requests',
        'name' => 'superadmin::app.acl.gdpr',
        'route' => 'superadmin.customers.gdpr.index',
        'sort' => 6,
    ], [
        'key' => 'customers.gdpr_requests.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.customers.gdpr.edit',
        'sort' => 1,
    ], [
        'key' => 'customers.gdpr_requests.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.customers.gdpr.delete',
        'sort' => 2,
    ], [
        'key' => 'customers.orders',
        'name' => 'superadmin::app.acl.orders',
        'route' => 'superadmin.sales.orders.customers.index',
        'sort' => 7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sellers
    |--------------------------------------------------------------------------
    */
    [
        'key' => 'sellers',
        'name' => 'superadmin::app.acl.sellers',
        'route' => 'superadmin.sellers.index',
        'sort' => 5,
    ], [
        'key' => 'sellers.all',
        'name' => 'superadmin::app.acl.sellers',
        'route' => 'superadmin.sellers.index',
        'sort' => 1,
    ], [
        'key' => 'sellers.all.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.sellers.store',
        'sort' => 1,
    ], [
        'key' => 'sellers.all.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.sellers.edit',
        'sort' => 2,
    ], [
        'key' => 'sellers.all.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.sellers.mass_delete',
        'sort' => 3,
    ], [
        'key' => 'sellers.orders',
        'name' => 'superadmin::app.acl.seller-orders',
        'route' => 'superadmin.sellers.orders.index',
        'sort' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketing
    |--------------------------------------------------------------------------
    |
    | All ACLs related to marketing will be placed here.
    |
    */
    [
        'key' => 'marketing',
        'name' => 'superadmin::app.acl.marketing',
        'route' => 'superadmin.marketing.promotions.cart_rules.index',
        'sort' => 6,
    ], [
        'key' => 'marketing.promotions',
        'name' => 'superadmin::app.acl.promotions',
        'route' => 'superadmin.marketing.promotions.cart_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules',
        'name' => 'superadmin::app.acl.cart-rules',
        'route' => 'superadmin.marketing.promotions.cart_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.promotions.cart_rules.create',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.copy',
        'name' => 'superadmin::app.acl.copy',
        'route' => 'superadmin.marketing.promotions.cart_rules.copy',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.promotions.cart_rules.edit',
        'sort' => 2,
    ], [
        'key' => 'marketing.promotions.cart_rules.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.promotions.cart_rules.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.promotions.catalog_rules',
        'name' => 'superadmin::app.acl.catalog-rules',
        'route' => 'superadmin.marketing.promotions.catalog_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.catalog_rules.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.promotions.catalog_rules.create',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.catalog_rules.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.promotions.catalog_rules.edit',
        'sort' => 2,
    ], [
        'key' => 'marketing.promotions.catalog_rules.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.promotions.catalog_rules.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications',
        'name' => 'superadmin::app.acl.communications',
        'route' => 'superadmin.marketing.communications.email_templates.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.email_templates',
        'name' => 'superadmin::app.acl.email-templates',
        'route' => 'superadmin.marketing.communications.email_templates.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.email_templates.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.communications.email_templates.create',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.email_templates.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.communications.email_templates.edit',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.email_templates.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.communications.email_templates.delete',
        'sort' => 4,
    ], [
        'key' => 'marketing.communications.events',
        'name' => 'superadmin::app.acl.events',
        'route' => 'superadmin.marketing.communications.events.index',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.events.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.communications.events.update',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.events.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.communications.events.edit',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.events.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.communications.events.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.campaigns',
        'name' => 'superadmin::app.acl.campaigns',
        'route' => 'superadmin.marketing.communications.campaigns.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.campaigns.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.communications.campaigns.create',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.campaigns.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.communications.campaigns.edit',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.campaigns.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.communications.campaigns.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.subscribers',
        'name' => 'superadmin::app.acl.subscribers',
        'route' => 'superadmin.marketing.communications.subscribers.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.subscribers.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.communications.subscribers.edit',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.subscribers.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.communications.subscribers.delete',
        'sort' => 2,
    ], [
        'key' => 'marketing.email_management',
        'name' => 'superadmin::app.acl.email-management',
        'route' => 'superadmin.email-management.index',
        'sort' => 10,
    ], [
        'key' => 'marketing.search_seo',
        'name' => 'superadmin::app.acl.search-seo',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.index',
        'sort' => 4,
    ], [
        'key' => 'marketing.search_seo.url_rewrites',
        'name' => 'superadmin::app.acl.url-rewrites',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.update',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.search_seo.url_rewrites.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_terms',
        'name' => 'superadmin::app.acl.search-terms',
        'route' => 'superadmin.marketing.search_seo.search_terms.index',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_terms.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.search_seo.search_terms.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.search_terms.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.search_seo.search_terms.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_terms.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.search_seo.search_terms.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_synonyms',
        'name' => 'superadmin::app.acl.search-synonyms',
        'route' => 'superadmin.marketing.search_seo.search_synonyms.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.search_seo.search_synonyms.update',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.search_seo.search_synonyms.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.search_seo.search_synonyms.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.sitemaps',
        'name' => 'superadmin::app.acl.sitemaps',
        'route' => 'superadmin.marketing.search_seo.sitemaps.index',
        'sort' => 4,
    ], [
        'key' => 'marketing.search_seo.sitemaps.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.marketing.search_seo.sitemaps.update',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.sitemaps.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.marketing.search_seo.sitemaps.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.sitemaps.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.marketing.search_seo.sitemaps.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.seller_tools',
        'name' => 'superadmin::app.acl.seller-programs',
        'route' => 'superadmin.marketing.seller_tools.seller_level',
        'sort' => 4,
    ], [
        'key' => 'marketing.seller_tools.seller_level',
        'name' => 'superadmin::app.acl.seller-level',
        'route' => 'superadmin.marketing.seller_tools.seller_level',
        'sort' => 1,
    ], [
        'key' => 'marketing.seller_tools.upgrade_packages',
        'name' => 'superadmin::app.acl.upgrade-packages',
        'route' => 'superadmin.marketing.seller_tools.store_upgrade',
        'sort' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    |
    | All Reporting related to reporting will be placed here.
    |
    */
    [
        'key' => 'reporting',
        'name' => 'superadmin::app.acl.reporting',
        'route' => 'superadmin.reporting.sales.index',
        'sort' => 6,
    ], [
        'key' => 'reporting.sales',
        'name' => 'superadmin::app.acl.sales',
        'route' => 'superadmin.reporting.sales.index',
        'sort' => 1,
    ], [
        'key' => 'reporting.customers',
        'name' => 'superadmin::app.acl.customers',
        'route' => 'superadmin.reporting.customers.index',
        'sort' => 2,
    ], [
        'key' => 'reporting.products',
        'name' => 'superadmin::app.acl.products',
        'route' => 'superadmin.reporting.products.index',
        'sort' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS
    |--------------------------------------------------------------------------
    |
    | All ACLs related to cms will be placed here.
    |
    */
    [
        'key' => 'cms',
        'name' => 'superadmin::app.acl.cms',
        'route' => 'superadmin.cms.index',
        'sort' => 7,
    ], [
        'key' => 'cms.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.cms.create',
        'sort' => 1,
    ], [
        'key' => 'cms.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.cms.edit',
        'sort' => 2,
    ], [
        'key' => 'cms.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.cms.delete',
        'sort' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Home page media
    |--------------------------------------------------------------------------
    */
    [
        'key' => 'home_page_media',
        'name' => 'superadmin::app.acl.home-page-media',
        'route' => 'superadmin.home_page_media.index',
        'sort' => 8,
    ], [
        'key' => 'home_page_media.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.home_page_media.store',
        'sort' => 1,
    ], [
        'key' => 'home_page_media.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.home_page_media.edit',
        'sort' => 2,
    ], [
        'key' => 'home_page_media.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.home_page_media.destroy',
        'sort' => 3,
    ], [
        'key' => 'home_page_media.update',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.home_page_media.update',
        'sort' => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | All ACLs related to settings will be placed here.
    |
    */
    [
        'key' => 'settings',
        'name' => 'superadmin::app.acl.settings',
        'route' => 'superadmin.settings.users.index',
        'sort' => 9,
    ], [
        'key' => 'settings.locales',
        'name' => 'superadmin::app.acl.locales',
        'route' => 'superadmin.settings.locales.index',
        'sort' => 1,
    ], [
        'key' => 'settings.locales.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.locales.create',
        'sort' => 1,
    ], [
        'key' => 'settings.locales.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.locales.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.locales.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.locales.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.currencies',
        'name' => 'superadmin::app.acl.currencies',
        'route' => 'superadmin.settings.currencies.index',
        'sort' => 2,
    ], [
        'key' => 'settings.currencies.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.currencies.create',
        'sort' => 1,
    ], [
        'key' => 'settings.currencies.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.currencies.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.currencies.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.currencies.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'superadmin::app.acl.exchange-rates',
        'route' => 'superadmin.settings.exchange_rates.index',
        'sort' => 3,
    ], [
        'key' => 'settings.exchange_rates.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.exchange_rates.create',
        'sort' => 1,
    ], [
        'key' => 'settings.exchange_rates.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.exchange_rates.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.exchange_rates.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.exchange_rates.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.inventory_sources',
        'name' => 'superadmin::app.acl.inventory-sources',
        'route' => 'superadmin.settings.inventory_sources.index',
        'sort' => 4,
    ], [
        'key' => 'settings.inventory_sources.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.inventory_sources.create',
        'sort' => 1,
    ], [
        'key' => 'settings.inventory_sources.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.inventory_sources.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.inventory_sources.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.inventory_sources.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.channels',
        'name' => 'superadmin::app.acl.channels',
        'route' => 'superadmin.settings.channels.index',
        'sort' => 5,
    ], [
        'key' => 'settings.channels.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.channels.create',
        'sort' => 1,
    ], [
        'key' => 'settings.channels.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.channels.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.channels.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.channels.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.users',
        'name' => 'superadmin::app.acl.users',
        'route' => 'superadmin.settings.users.index',
        'sort' => 6,
    ], [
        'key' => 'settings.users.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.users.store',
        'sort' => 1,
    ], [
        'key' => 'settings.users.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.users.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.users.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.users.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.roles',
        'name' => 'superadmin::app.acl.roles',
        'route' => 'superadmin.settings.roles.index',
        'sort' => 7,
    ], [
        'key' => 'settings.roles.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.roles.create',
        'sort' => 1,
    ], [
        'key' => 'settings.roles.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.roles.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.roles.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.roles.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.themes',
        'name' => 'superadmin::app.acl.themes',
        'route' => 'superadmin.settings.themes.index',
        'sort' => 8,
    ], [
        'key' => 'settings.themes.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.themes.store',
        'sort' => 1,
    ], [
        'key' => 'settings.themes.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.themes.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.themes.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.themes.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.taxes',
        'name' => 'superadmin::app.acl.taxes',
        'route' => 'superadmin.settings.taxes.categories.index',
        'sort' => 9,
    ], [
        'key' => 'settings.taxes.tax_categories',
        'name' => 'superadmin::app.acl.tax-categories',
        'route' => 'superadmin.settings.taxes.categories.index',
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_categories.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.taxes.tax_categories.create',
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_categories.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.taxes.categories.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.taxes.tax_categories.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.taxes.categories.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.taxes.tax_rates',
        'name' => 'superadmin::app.acl.tax-rates',
        'route' => 'superadmin.settings.taxes.rates.index',
        'sort' => 2,
    ], [
        'key' => 'settings.taxes.tax_rates.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.taxes.rates.create',
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_rates.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.taxes.rates.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.data_transfer',
        'name' => 'superadmin::app.acl.data-transfer',
        'route' => 'superadmin.settings.data_transfer.imports.index',
        'sort' => 10,
    ], [
        'key' => 'settings.data_transfer.imports',
        'name' => 'superadmin::app.acl.imports',
        'route' => 'superadmin.settings.data_transfer.imports.index',
        'sort' => 1,
    ], [
        'key' => 'settings.data_transfer.imports.create',
        'name' => 'superadmin::app.acl.create',
        'route' => 'superadmin.settings.data_transfer.imports.create',
        'sort' => 1,
    ], [
        'key' => 'settings.data_transfer.imports.edit',
        'name' => 'superadmin::app.acl.edit',
        'route' => 'superadmin.settings.data_transfer.imports.edit',
        'sort' => 2,
    ], [
        'key' => 'settings.data_transfer.imports.delete',
        'name' => 'superadmin::app.acl.delete',
        'route' => 'superadmin.settings.data_transfer.imports.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.data_transfer.imports.import',
        'name' => 'superadmin::app.acl.import',
        'route' => 'superadmin.settings.data_transfer.imports.import',
        'sort' => 4,
    ], [
        'key' => 'settings.crypto',
        'name' => 'superadmin::app.acl.crypto-payout-addresses',
        'route' => 'superadmin.settings.crypto_addresses.index',
        'sort' => 11,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | All ACLs related to configuration will be placed here.
    |
    */
    [
        'key' => 'configuration',
        'name' => 'superadmin::app.acl.configure',
        'route' => 'superadmin.configuration.index',
        'sort' => 10,
    ],
];
