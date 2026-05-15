@pushOnce('styles', 'admin-workspace-surface-styles')
    <style>
        .admin-workspace-surface,
        .seller-workspace-surface {
            background: linear-gradient(
                165deg,
                #eef2ff 0%,
                #f8fafc 38%,
                #e0f2fe 72%,
                #f1f5f9 100%
            ) !important;
        }

        .dark .admin-workspace-surface,
        .dark .seller-workspace-surface {
            background: linear-gradient(
                165deg,
                #0f172a 0%,
                #1e1b4b 45%,
                #0c4a6e 100%
            ) !important;
        }

        .seller-panel-scope {
            background: transparent !important;
        }

        .admin-workspace-surface .box-shadow.bg-white,
        .admin-workspace-surface section.box-shadow.bg-white,
        .seller-workspace-surface .box-shadow.bg-white,
        .seller-workspace-surface section.box-shadow.bg-white,
        .seller-order-view-page .box-shadow.bg-white,
        .seller-order-view-page section.box-shadow.bg-white {
            background: rgba(255, 255, 255, 0.88) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-color: rgba(226, 232, 240, 0.92) !important;
        }

        .dark .admin-workspace-surface .box-shadow.bg-white,
        .dark .admin-workspace-surface section.box-shadow.bg-white,
        .dark .seller-workspace-surface .box-shadow.bg-white,
        .dark .seller-workspace-surface section.box-shadow.bg-white,
        .dark .seller-order-view-page .box-shadow.bg-white,
        .dark .seller-order-view-page section.box-shadow.bg-white {
            background: rgba(17, 24, 39, 0.9) !important;
            border-color: rgba(55, 65, 81, 0.9) !important;
        }

        .seller-workspace-surface .seller-filter-card,
        .seller-workspace-surface .seller-summary-card,
        .seller-panel-scope .seller-filter-card,
        .seller-panel-scope .seller-summary-card {
            background: rgba(255, 255, 255, 0.82) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-color: rgba(226, 232, 240, 0.9) !important;
        }

        .dark .seller-workspace-surface .seller-filter-card,
        .dark .seller-workspace-surface .seller-summary-card,
        .dark .seller-panel-scope .seller-filter-card,
        .dark .seller-panel-scope .seller-summary-card {
            background: rgba(17, 24, 39, 0.85) !important;
            border-color: rgba(55, 65, 81, 0.9) !important;
        }

        .seller-panel-scope .seller-responsive-table {
            background: linear-gradient(
                145deg,
                rgba(255, 255, 255, 0.88) 0%,
                rgba(248, 250, 252, 0.94) 100%
            ) !important;
            border-color: rgba(226, 232, 240, 0.92) !important;
        }

        .dark .seller-panel-scope .seller-responsive-table {
            background: linear-gradient(
                145deg,
                rgba(17, 24, 39, 0.9) 0%,
                rgba(30, 27, 75, 0.85) 100%
            ) !important;
            border-color: rgba(55, 65, 81, 0.9) !important;
        }

        .seller-panel-scope #seller-warehouse-grid,
        .seller-panel-scope .seller-panel-inner > .rounded-md.bg-white,
        .seller-panel-scope .seller-panel-inner > .mt-5.rounded-md.bg-white,
        .seller-panel-scope .mb-4.rounded-xl.border.bg-white,
        .seller-panel-scope .mb-4.rounded-lg.border.bg-white {
            background: rgba(255, 255, 255, 0.82) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .dark .seller-panel-scope #seller-warehouse-grid,
        .dark .seller-panel-scope .seller-panel-inner > .rounded-md.bg-white,
        .dark .seller-panel-scope .seller-panel-inner > .mt-5.rounded-md.bg-white,
        .dark .seller-panel-scope .mb-4.rounded-xl.border.bg-white,
        .dark .seller-panel-scope .mb-4.rounded-lg.border.bg-white {
            background: rgba(17, 24, 39, 0.85) !important;
        }

        .seller-panel-scope .seller-mobile-card {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-color: rgba(226, 232, 240, 0.95) !important;
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.07);
        }

        .dark .seller-panel-scope .seller-mobile-card {
            background: rgba(17, 24, 39, 0.9) !important;
            border-color: rgba(55, 65, 81, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
        }

        @media (max-width: 767px) {
            .seller-panel-scope .seller-filter-card,
            .seller-panel-scope #seller-shop-order-grid,
            .seller-panel-scope #seller-store-product-grid,
            .seller-panel-scope #seller-warehouse-grid {
                -webkit-backdrop-filter: none;
                backdrop-filter: none;
            }
        }

        .admin-workspace-surface .mt-auto > .border-t.bg-white,
        .seller-workspace-surface .mt-auto > .border-t.bg-white {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .dark .admin-workspace-surface .mt-auto > .border-t.bg-white,
        .dark .seller-workspace-surface .mt-auto > .border-t.bg-white {
            background: rgba(17, 24, 39, 0.85) !important;
        }
    </style>
@endPushOnce
