<?php

/**
 * TikTok-style seller panel: workspace tabs + route names that use the chrome (hide default Bagisto submenu tabs).
 */
return [

    /**
     * Role IDs that may see all sellers' orders on admin sales routes (platform administrators).
     * Must be set explicitly via PLATFORM_ADMIN_ROLE_IDS (e.g. "1"). Default is empty so sellers
     * who share the same role_id as staff still only see orders.seller_id = their id.
     */
    'platform_admin_role_ids' => array_values(array_filter(array_map(
        'intval',
        explode(',', (string) env('PLATFORM_ADMIN_ROLE_IDS', ''))
    ), static fn (int $id) => $id > 0)),

    /** Profit shown on financial statement when COGS unavailable (fraction of completed sales). */
    'financial_profit_margin' => env('SELLER_FINANCIAL_PROFIT_MARGIN', 0.35),
    'routes_hide_default_submenu_tabs' => [
        'admin.seller.*',
        'admin.wallet.*',
        'admin.store-upgrade.*',
        'admin.seller-level.*',
        'admin.seller.purchase-history.*',
        'admin.sales.orders.index',
        'admin.sales.refunds.index',
        'admin.catalog.products.*',
        'admin.seller.store-products.index',
        'admin.seller.store-products.mass-destroy',
        'admin.seller.store-products.edit-commission',
        'admin.seller.store-products.update-commission',
        'admin.seller.store-products.toggle-recommended',
        'admin.seller.store-products.destroy',
        'admin.seller.store-products.mass-destroy-by-product-ids',
        'admin.customers.customers.review.index',
        'admin.seller.product-warehouse.index',
        'admin.seller.product-warehouse.commission',
        'admin.seller.product-warehouse.attach',
        'admin.seller.product-warehouse.attach-one',
        'admin.settings.inventory_sources.index',
        'admin.settings.inventory_sources.create',
        'admin.settings.inventory_sources.edit',
        'admin.dashboard.index',
    ],

    /**
     * Horizontal workspace tabs (shown on all seller chrome pages).
     */
    'tabs' => [
        ['key' => 'dashboard', 'route' => 'admin.dashboard.index', 'label' => 'admin::app.seller-panel.tabs.dashboard'],
        ['key' => 'fund_record', 'route' => 'admin.seller.fund-record.index', 'label' => 'admin::app.seller-panel.tabs.fund-record'],
        ['key' => 'financial_statement', 'route' => 'admin.seller.financial-statement.index', 'label' => 'admin::app.seller-panel.tabs.financial-statement'],
        ['key' => 'wallet', 'route' => 'admin.wallet.index', 'label' => 'admin::app.seller-panel.tabs.my-wallet'],
        ['key' => 'shop_order', 'route' => 'admin.sales.orders.index', 'label' => 'admin::app.seller-panel.tabs.shop-order'],
        ['key' => 'store_products', 'route' => 'admin.seller.store-products.index', 'label' => 'admin::app.seller-panel.tabs.store-products'],
        ['key' => 'refund_request', 'route' => 'admin.sales.refunds.index', 'label' => 'admin::app.seller-panel.tabs.refund-request'],
        ['key' => 'product_review', 'route' => 'admin.customers.customers.review.index', 'label' => 'admin::app.seller-panel.tabs.product-review'],
        ['key' => 'product_warehouse', 'route' => 'admin.seller.product-warehouse.index', 'label' => 'admin::app.seller-panel.tabs.product-warehouse-browse'],
        ['key' => 'catalog_products', 'route' => 'admin.catalog.products.index', 'label' => 'admin::app.seller-panel.tabs.product-warehouse'],
    ],

    /**
     * Seller sidebar + mobile drawer: which admin menu keys may appear (top-level and children).
     * Others / Marketing Tools groups are omitted from the main menu config; store upgrade & seller level stay as standalone links.
     */
    'sidebar_allowed_menu_keys' => [
        'dashboard',
        'sales',
        'financial_statement',
        'wallet',
        'fund_record',
        'product_management',
        'product_management.store_products',
        'product_management.refunds',
        'product_management.reviews',
        'product_management.warehouse',
        'seller_purchase_history',
        'seller_store_upgrade',
        'seller_level',
    ],
];
