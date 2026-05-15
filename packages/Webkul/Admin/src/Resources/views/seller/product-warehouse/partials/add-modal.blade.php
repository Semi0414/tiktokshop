@include('admin::seller.partials.sse-modal-styles')

<style>
    #warehouse-add-modal.is-visible {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: fixed !important;
        inset: 0 !important;
        z-index: 100050 !important;
        align-items: center !important;
        justify-content: center !important;
    }

    #warehouse-add-modal.is-visible .sse-modal__backdrop {
        opacity: 1 !important;
    }

    #warehouse-add-modal.is-visible .sse-modal__dialog {
        opacity: 1 !important;
        transform: translateY(0) scale(1) !important;
    }

    @media (max-width: 767px) {
        #warehouse-add-modal.is-visible {
            align-items: flex-end !important;
        }

        #warehouse-add-modal.is-visible .sse-modal__dialog {
            transform: translateY(0) !important;
        }
    }
</style>

<div id="warehouse-add-modal" class="sse-modal sse-modal--add hidden" style="display: none;" aria-hidden="true" role="presentation">
    <div class="sse-modal__backdrop" data-warehouse-modal-dismiss aria-hidden="true"></div>
    <div
        class="sse-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="warehouse-add-modal-title"
        aria-describedby="warehouse-add-modal-hint"
    >
        <header class="sse-modal__header">
            <div class="sse-modal__header-main">
                <span class="sse-modal__header-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 id="warehouse-add-modal-title" class="sse-modal__title">
                        @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-title')
                    </h2>
                    <p id="warehouse-add-modal-hint" class="sse-modal__subtitle">
                        @lang('admin::app.seller-panel.product-warehouse.bulk-add-modal-hint')
                    </p>
                </div>
            </div>
            <button type="button" id="warehouse-add-modal-close" class="sse-modal__close" data-warehouse-modal-dismiss aria-label="@lang('admin::app.acl.cancel')">
                <span aria-hidden="true">&times;</span>
            </button>
        </header>

        <div class="sse-modal__body">
            <form id="warehouse-add-modal-form" method="post" action="{{ route('admin.seller.product-warehouse.attach') }}" class="flex flex-col gap-4">
                @csrf
                <div class="sse-field sse-field--delay-1">
                    <label class="sse-field__label" for="warehouse-add-modal-commission">
                        @lang('admin::app.seller-panel.product-warehouse.bulk-add-seller-profit-label')
                    </label>
                    <input
                        id="warehouse-add-modal-commission"
                        type="number"
                        step="0.01"
                        min="{{ number_format((float) $commissionRule['min'], 2, '.', '') }}"
                        max="{{ number_format((float) $commissionRule['max'], 2, '.', '') }}"
                        name="commission_percent"
                        class="sse-field__input"
                        value="{{ number_format((float) $currentCommissionPercent, 2, '.', '') }}"
                        @if ($commissionRule['readonly']) readonly @endif
                        required
                    />
                </div>
                <div id="warehouse-add-modal-indices"></div>
            </form>
        </div>

        <footer class="sse-modal__footer">
            <button type="button" class="sse-modal__btn sse-modal__btn--ghost" data-warehouse-modal-dismiss>
                @lang('admin::app.acl.cancel')
            </button>
            <button type="submit" form="warehouse-add-modal-form" class="sse-modal__btn sse-modal__btn--primary">
                @lang('admin::app.seller-panel.product-warehouse.bulk-add-confirm')
            </button>
        </footer>
    </div>
</div>

<script>
(function () {
    var existingProductIds = new Set(
        (@json($sellerExistingProductIds ?? [])).map(function (id) {
            return parseInt(id, 10);
        }).filter(function (id) {
            return !Number.isNaN(id);
        })
    );

    function warehouseNotify(type, message) {
        if (window.emitter && typeof window.emitter.emit === 'function') {
            window.emitter.emit('add-flash', { type: type, message: message });
        } else {
            alert(message);
        }
    }

    function warehouseGetModal() {
        return document.getElementById('warehouse-add-modal');
    }

    function warehouseCloseAddModal() {
        var modal = warehouseGetModal();

        if (!modal) {
            return;
        }

        modal.classList.remove('is-visible', 'sse-modal--open', 'flex');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('seller-sse-modal-open');
        document.body.style.overflow = '';
    }

    function warehouseShowAddModal(modal) {
        if (!modal) {
            return;
        }

        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        modal.classList.remove('hidden', 'sse-modal--closing');
        modal.classList.add('flex', 'is-visible', 'sse-modal--open');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('seller-sse-modal-open');
        document.body.style.overflow = 'hidden';
    }

    function warehouseSyncCommission() {
        var pageInput = document.getElementById('warehouse-commission-input');
        var modalInput = document.getElementById('warehouse-add-modal-commission');

        if (pageInput && modalInput) {
            modalInput.value = pageInput.value || modalInput.value;
        }
    }

    function warehouseOpenAddModalWithIds(productIds) {
        var modal = warehouseGetModal();
        var indicesEl = document.getElementById('warehouse-add-modal-indices');

        if (!modal || !indicesEl) {
            return;
        }

        var normalizedIds = (productIds || [])
            .map(function (id) { return parseInt(id, 10); })
            .filter(function (id) { return !Number.isNaN(id); });

        if (!normalizedIds.length) {
            warehouseNotify('warning', @json(__('admin::app.components.datagrid.index.no-records-selected')));

            return;
        }

        var toAdd = normalizedIds.filter(function (id) {
            return !existingProductIds.has(id);
        });

        if (!toAdd.length) {
            warehouseNotify('warning', 'This product is already added to your catalog.');

            return;
        }

        if (toAdd.length < normalizedIds.length) {
            warehouseNotify('warning', 'Some selected products are already added to your catalog. Showing remaining products only.');
        }

        indicesEl.innerHTML = toAdd
            .map(function (id) {
                return '<input type="hidden" name="indices[]" value="' + id + '">';
            })
            .join('');

        warehouseSyncCommission();
        warehouseShowAddModal(modal);
    }

    window.openWarehouseAddModalWithIds = warehouseOpenAddModalWithIds;
    window.openWarehouseAddModalFromSingle = function (id) {
        var n = parseInt(id || '0', 10);
        warehouseOpenAddModalWithIds(Number.isNaN(n) ? [] : [n]);
    };
    window.closeWarehouseAddModal = warehouseCloseAddModal;

    document.addEventListener('click', function (event) {
        if (event.target.closest('[data-warehouse-modal-dismiss]')) {
            event.preventDefault();
            warehouseCloseAddModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            warehouseCloseAddModal();
        }
    });

    var modalForm = document.getElementById('warehouse-add-modal-form');

    if (modalForm) {
        modalForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            var submitButton = modalForm.querySelector('button[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
            }

            try {
                var response = await fetch(modalForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(modalForm),
                    credentials: 'same-origin',
                });

                var payload = await response.json().catch(function () { return {}; });

                if (!response.ok) {
                    warehouseNotify('error', payload.message || 'Unable to add product(s).');

                    return;
                }

                warehouseNotify('success', payload.message || 'Product(s) added to your catalog successfully.');
                warehouseCloseAddModal();
                window.location.reload();
            } catch (error) {
                warehouseNotify('error', 'Unable to add product(s).');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    }
})();
</script>
