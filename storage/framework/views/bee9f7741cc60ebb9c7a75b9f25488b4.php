<?php if (! $__env->hasRenderedOnce('seller-sse-modal-styles')): $__env->markAsRenderedOnce('seller-sse-modal-styles');
$__env->startPush('styles'); ?>
<style>
    #seller-store-native-edit-modal.admin-seller-add-modal-layer,
    #seller-store-remove-blocked-modal.admin-seller-add-modal-layer,
    #warehouse-add-modal.admin-seller-add-modal-layer {
        z-index: 100050 !important;
    }

    body.seller-sse-modal-open,
    body.seller-store-edit-modal-open {
        overflow: hidden !important;
    }

    .sse-modal {
        position: fixed;
        inset: 0;
        z-index: 100050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right)) max(1rem, env(safe-area-inset-bottom)) max(1rem, env(safe-area-inset-left));
        box-sizing: border-box;
        font-family: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.32s ease, visibility 0s linear 0.32s;
    }

    .sse-modal.flex,
    .sse-modal.admin-seller-add-modal-layer {
        display: flex;
    }

    .sse-modal.sse-modal--open {
        visibility: visible;
        opacity: 1;
        transition: opacity 0.32s ease, visibility 0s;
    }

    .sse-modal__backdrop {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99, 102, 241, 0.35) 0%, transparent 55%),
            radial-gradient(ellipse 70% 50% at 100% 100%, rgba(255, 0, 80, 0.2) 0%, transparent 50%),
            rgba(2, 6, 23, 0.72);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
        opacity: 0;
        transition: opacity 0.32s ease;
    }

    .sse-modal.sse-modal--open .sse-modal__backdrop { opacity: 1; }
    .sse-modal.sse-modal--closing .sse-modal__backdrop { opacity: 0; }

    .sse-modal__dialog {
        position: relative;
        z-index: 1;
        width: min(520px, 100%);
        max-height: min(92dvh, calc(100dvh - 2rem));
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.45);
        background: linear-gradient(165deg, #ffffff 0%, #f8fafc 48%, #eef2ff 100%);
        box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.08), 0 32px 64px -24px rgba(15, 23, 42, 0.45), 0 12px 28px -12px rgba(79, 70, 229, 0.25);
        opacity: 0;
        transform: translateY(28px) scale(0.94);
        transition: transform 0.42s cubic-bezier(0.22, 1.12, 0.36, 1), opacity 0.32s ease;
    }

    .dark .sse-modal__dialog {
        border-color: rgba(99, 102, 241, 0.35);
        background: linear-gradient(165deg, #1e1b4b 0%, #0f172a 55%, #111827 100%);
        box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.2), 0 32px 64px -20px rgba(0, 0, 0, 0.65);
    }

    .sse-modal.sse-modal--open .sse-modal__dialog {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .sse-modal.sse-modal--closing .sse-modal__dialog {
        opacity: 0;
        transform: translateY(18px) scale(0.97);
        transition: transform 0.26s ease-in, opacity 0.22s ease-in;
    }

    .sse-modal__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 1.25rem 1.25rem 1rem;
        background: linear-gradient(125deg, #4f46e5 0%, #7c3aed 42%, #ff0050 100%);
        color: #fff;
    }

    .sse-modal__header-main {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        min-width: 0;
    }

    .sse-modal__header-icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: rgba(255, 255, 255, 0.18);
        animation: sse-icon-pulse 2.4s ease-in-out infinite;
    }

    @keyframes sse-icon-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.04); }
    }

    .sse-modal__title {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 700;
        line-height: 1.3;
        color: #fff;
    }

    .sse-modal__subtitle {
        margin: 0.25rem 0 0;
        font-size: 0.75rem;
        line-height: 1.45;
        color: rgba(255, 255, 255, 0.88);
        word-break: break-word;
    }

    .sse-modal__close {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        border: none;
        border-radius: 0.625rem;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .sse-modal__close:hover { background: rgba(255, 255, 255, 0.28); transform: scale(1.05); }

    .sse-modal__body {
        flex: 1;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .sse-field { opacity: 0; transform: translateY(10px); }

    .sse-modal.sse-modal--open .sse-field {
        animation: sse-field-in 0.48s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .sse-modal.sse-modal--open .sse-field--delay-1 { animation-delay: 0.08s; }
    .sse-modal.sse-modal--open .sse-field--delay-2 { animation-delay: 0.14s; }
    .sse-modal.sse-modal--open .sse-field--delay-3 { animation-delay: 0.2s; }

    @keyframes sse-field-in {
        to { opacity: 1; transform: translateY(0); }
    }

    .sse-field__label {
        display: block;
        margin-bottom: 0.375rem;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
    }

    .dark .sse-field__label { color: #94a3b8; }

    .sse-field__input,
    .sse-field__select {
        width: 100%;
        min-height: 2.75rem;
        box-sizing: border-box;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background: #fff;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        color: #0f172a;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
    }

    .dark .sse-field__input,
    .dark .sse-field__select {
        border-color: #334155;
        background: #1e293b;
        color: #f1f5f9;
    }

    .sse-field__input:focus,
    .sse-field__select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.22);
        transform: translateY(-1px);
    }

    .sse-field__hint {
        margin: 0.375rem 0 0;
        font-size: 0.6875rem;
        color: #64748b;
    }

    .sse-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(255, 255, 255, 0.95) 100%);
        cursor: pointer;
    }

    .sse-check:has(.sse-check__input:checked) {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
    }

    .sse-check__input { position: absolute; opacity: 0; width: 0; height: 0; }

    .sse-check__box {
        flex-shrink: 0;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.375rem;
        border: 2px solid #cbd5e1;
        background: #fff;
        transition: all 0.2s ease;
    }

    .sse-check__input:checked + .sse-check__box {
        border-color: #4f46e5;
        background: linear-gradient(135deg, #6366f1, #ff0050);
    }

    .sse-check__input:checked + .sse-check__box::after {
        content: "";
        display: block;
        width: 100%;
        height: 100%;
        background: center/0.65rem no-repeat url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 10'%3E%3Cpath fill='none' stroke='%23fff' stroke-width='2' d='M1 5l3 3 7-7'/%3E%3C/svg%3E");
    }

    .sse-check__text { font-size: 0.875rem; font-weight: 500; color: #334155; }
    .dark .sse-check__text { color: #e2e8f0; }

    .sse-modal__footer {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.625rem;
        padding: 1rem 1.25rem 1.25rem;
        border-top: 1px solid rgba(226, 232, 240, 0.9);
        background: rgba(248, 250, 252, 0.85);
    }

    .sse-modal__btn {
        appearance: none;
        min-height: 2.75rem;
        padding: 0.5rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .sse-modal__btn--ghost {
        border-color: #e2e8f0;
        background: #fff;
        color: #475569;
    }

    .sse-modal__btn--primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ff0050 100%);
        background-size: 200% 200%;
        color: #fff;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
        animation: sse-btn-shimmer 4s ease infinite;
    }

    @keyframes sse-btn-shimmer {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @media (max-width: 767px) {
        .sse-modal { align-items: flex-end; padding: 0; }
        .sse-modal__dialog {
            width: 100%;
            max-height: min(94dvh, 100%);
            border-radius: 1.25rem 1.25rem 0 0;
            transform: translateY(100%);
        }
        .sse-modal.sse-modal--open .sse-modal__dialog { transform: translateY(0); }
        .sse-modal.sse-modal--closing .sse-modal__dialog { transform: translateY(100%); }
        .sse-modal__footer {
            flex-direction: column-reverse;
            padding-bottom: max(1.25rem, env(safe-area-inset-bottom));
        }
        .sse-modal__btn { width: 100%; }
    }

    
    .sse-modal--add .sse-modal__header {
        background: linear-gradient(125deg, #059669 0%, #0d9488 42%, #4f46e5 100%);
    }

    @media (prefers-reduced-motion: reduce) {
        .sse-modal, .sse-modal__backdrop, .sse-modal__dialog, .sse-field,
        .sse-modal__header-icon, .sse-modal__btn--primary {
            animation: none !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
<?php $__env->stopPush(); endif; ?><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/Admin/src/resources/views/seller/partials/sse-modal-styles.blade.php ENDPATH**/ ?>