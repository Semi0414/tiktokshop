<div id="seller-store-native-edit-modal" class="sse-modal hidden" style="display: none;" aria-hidden="true" role="presentation">
    <div class="sse-modal__backdrop" data-sse-modal-dismiss aria-hidden="true"></div>
    <div class="sse-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="seller-store-native-edit-modal-title">
        <header class="sse-modal__header">
            <div class="sse-modal__header-main">
                <span class="sse-modal__header-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 id="seller-store-native-edit-modal-title" class="sse-modal__title">
                        @lang('admin::app.seller-panel.store-products.edit-modal-title')
                    </h2>
                    <p id="seller-store-native-product-label" class="sse-modal__subtitle"></p>
                </div>
            </div>
            <button type="button" id="seller-store-native-modal-close" class="sse-modal__close" aria-label="@lang('admin::app.acl.cancel')">
                <span aria-hidden="true">&times;</span>
            </button>
        </header>
        <div class="sse-modal__body">
            <div class="sse-field sse-field--delay-1">
                <label class="sse-field__label" for="seller-store-native-commission">
                    @lang('admin::app.seller-panel.product-warehouse.commission-title') (%)
                </label>
                <input id="seller-store-native-commission" type="number" step="0.01" class="sse-field__input" />
                <p id="seller-store-native-range-hint" class="sse-field__hint"></p>
            </div>
            <label class="sse-check sse-field sse-field--delay-2" for="seller-store-native-recommended">
                <input type="checkbox" id="seller-store-native-recommended" class="sse-check__input" />
                <span class="sse-check__box" aria-hidden="true"></span>
                <span class="sse-check__text">@lang('admin::app.seller-panel.store-products.recommended')</span>
            </label>
            <div class="sse-field sse-field--delay-3">
                <label class="sse-field__label" for="seller-store-native-status">Status</label>
                <select id="seller-store-native-status" class="sse-field__input sse-field__select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
        <footer class="sse-modal__footer">
            <button type="button" id="seller-store-native-cancel" class="sse-modal__btn sse-modal__btn--ghost">@lang('admin::app.acl.cancel')</button>
            <button type="button" id="seller-store-native-save" class="sse-modal__btn sse-modal__btn--primary">@lang('admin::app.account.edit.save-btn')</button>
        </footer>
    </div>
</div>

@include('admin::seller.partials.sse-modal-styles')

