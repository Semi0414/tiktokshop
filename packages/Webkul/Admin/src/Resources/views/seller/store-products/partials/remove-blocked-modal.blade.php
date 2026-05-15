@php
    $removalMinDays = (int) ($storeProductRemovalMinAccountDays ?? 90);
    $sellerAgeDays = (int) ($sellerAccountAgeDays ?? 0);
@endphp

<div
    id="seller-store-remove-blocked-modal"
    class="sse-modal sse-modal--warning hidden"
    style="display: none;"
    aria-hidden="true"
    role="presentation"
>
    <div class="sse-modal__backdrop" data-sse-modal-dismiss aria-hidden="true"></div>
    <div
        class="sse-modal__dialog"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="seller-store-remove-blocked-title"
        aria-describedby="seller-store-remove-blocked-desc"
    >
        <header class="sse-modal__header">
            <div class="sse-modal__header-main">
                <span class="sse-modal__header-icon sse-modal__header-icon--warning" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 id="seller-store-remove-blocked-title" class="sse-modal__title">
                        @lang('admin::app.seller-panel.store-products.remove-not-allowed-title')
                    </h2>
                    <p class="sse-modal__subtitle">
                        @lang('admin::app.seller-panel.store-products.remove-not-allowed-subtitle', ['days' => $removalMinDays])
                    </p>
                </div>
            </div>
            <button type="button" id="seller-store-remove-blocked-close" class="sse-modal__close" aria-label="@lang('admin::app.seller-panel.store-products.remove-not-allowed-dismiss')">
                <span aria-hidden="true">&times;</span>
            </button>
        </header>

        <div class="sse-modal__body sse-modal__body--message-only">
            <div class="sse-remove-blocked-card sse-field sse-field--delay-1">
                <p id="seller-store-remove-blocked-desc" class="sse-remove-blocked-card__text">
                    @lang('admin::app.seller-panel.store-products.remove-not-allowed-description', [
                        'required' => $removalMinDays,
                        'current' => $sellerAgeDays,
                    ])
                </p>
                <p class="sse-remove-blocked-card__note">
                    @lang('admin::app.seller-panel.store-products.remove-not-allowed-footnote')
                </p>
            </div>
        </div>

        <footer class="sse-modal__footer">
            <button type="button" class="sse-modal__btn sse-modal__btn--primary sse-remove-blocked-ok" data-sse-remove-blocked-dismiss>
                @lang('admin::app.seller-panel.store-products.remove-not-allowed-dismiss')
            </button>
        </footer>
    </div>
</div>

@pushOnce('styles', 'seller-store-remove-blocked-modal-styles')
<style>
    .sse-modal--warning .sse-modal__header {
        background: linear-gradient(125deg, #c2410c 0%, #ea580c 42%, #ef4444 100%);
    }

    .sse-modal__header-icon--warning {
        animation: sse-warning-pulse 2s ease-in-out infinite;
    }

    @keyframes sse-warning-pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.25); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 8px rgba(255, 255, 255, 0); }
    }

    .sse-modal__body--message-only {
        padding-top: 1rem;
        padding-bottom: 0.5rem;
    }

    .sse-remove-blocked-card {
        padding: 1rem 1.125rem;
        border-radius: 0.875rem;
        border: 1px solid #fed7aa;
        background: linear-gradient(145deg, #fffbeb 0%, #fff7ed 55%, #ffffff 100%);
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.08);
    }

    .dark .sse-remove-blocked-card {
        border-color: rgba(251, 146, 60, 0.35);
        background: linear-gradient(145deg, rgba(67, 20, 7, 0.5) 0%, rgba(30, 27, 75, 0.35) 100%);
    }

    .sse-remove-blocked-card__text {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.55;
        color: #7c2d12;
        font-weight: 500;
    }

    .dark .sse-remove-blocked-card__text {
        color: #fed7aa;
    }

    .sse-remove-blocked-card__note {
        margin: 0.75rem 0 0;
        padding-top: 0.75rem;
        border-top: 1px dashed rgba(234, 88, 12, 0.35);
        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1.45;
        color: #b45309;
    }

    .dark .sse-remove-blocked-card__note {
        color: #fdba74;
        border-top-color: rgba(251, 146, 60, 0.35);
    }

    .sse-modal--warning .sse-modal__btn--primary {
        background: linear-gradient(135deg, #ea580c 0%, #f97316 50%, #ef4444 100%);
        background-size: 200% 200%;
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.4);
    }

    .sse-modal--warning.sse-modal--open .sse-field--delay-1 {
        animation-delay: 0.1s;
    }

    @media (max-width: 767px) {
        .sse-modal--warning .sse-modal__footer .sse-modal__btn {
            width: 100%;
        }
    }
</style>
@endPushOnce
