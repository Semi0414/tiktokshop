(function () {
    alert('JS loaded: seller-store-products.js');

    function parseConfig() {
        var el = document.getElementById('store-products-frontend-config');

        if (!el) {
            return {};
        }

        try {
            return JSON.parse(el.textContent || '{}');
        } catch (e) {
            return {};
        }
    }

    var config = parseConfig();
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function flash(type, message) {
        if (window.emitter && typeof window.emitter.emit === 'function') {
            window.emitter.emit('add-flash', { type: type, message: message });
        } else {
            alert(message);
        }
    }

    function selectedIds() {
        return Array.from(document.querySelectorAll('.store-products-row-checkbox:checked'))
            .map(function (cb) { return parseInt(cb.value, 10); })
            .filter(function (id) { return !Number.isNaN(id); });
    }

    function modalEls() {
        return {
            modal: document.getElementById('seller-store-native-edit-modal'),
            close: document.getElementById('seller-store-native-modal-close'),
            cancel: document.getElementById('seller-store-native-cancel'),
            save: document.getElementById('seller-store-native-save'),
            label: document.getElementById('seller-store-native-product-label'),
            commission: document.getElementById('seller-store-native-commission'),
            recommended: document.getElementById('seller-store-native-recommended'),
            status: document.getElementById('seller-store-native-status'),
            hint: document.getElementById('seller-store-native-range-hint'),
        };
    }

    var state = { mode: 'single', ids: [], updateUrl: '' };

    function openModal() {
        var els = modalEls();
        if (!els.modal) return;
        els.modal.classList.remove('hidden');
        els.modal.classList.add('flex');
    }

    function closeModal() {
        var els = modalEls();
        if (!els.modal) return;
        els.modal.classList.add('hidden');
        els.modal.classList.remove('flex');
    }

    function applyRule(rule) {
        var els = modalEls();
        if (!els.commission || !els.hint) return;

        var readonly = !!rule.readonly;
        els.commission.readOnly = readonly;
        els.commission.min = String(rule.min ?? 0);
        els.commission.max = String(rule.max ?? 100);
        els.commission.value = String(rule.default ?? 15);
        els.hint.textContent = readonly
            ? 'Fixed commission for your level.'
            : ('Allowed range: ' + String(rule.min ?? 0) + '% to ' + String(rule.max ?? 100) + '%');
    }

    function openBulkModal() {
        var ids = selectedIds();
        if (!ids.length) {
            alert(config.messages?.select_one || 'Select at least one product.');
            return;
        }

        var els = modalEls();
        state.mode = 'bulk';
        state.ids = ids;
        state.updateUrl = '';

        if (els.label) els.label.textContent = 'Bulk edit for ' + ids.length + ' products';
        if (els.recommended) els.recommended.checked = false;
        if (els.status) els.status.value = '1';

        applyRule(config.commission_rule || { readonly: true, min: 15, max: 15, default: 15 });
        openModal();
    }

    function openSingleModal(url) {
        var els = modalEls();
        state.mode = 'single';
        state.ids = [];
        state.updateUrl = url;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            credentials: 'same-origin',
        })
            .then(function (res) { return res.json(); })
            .then(function (d) {
                if (els.label) {
                    var label = (d.product_name || '') + (d.sku ? (' · ' + d.sku) : '');
                    els.label.textContent = label;
                }

                if (els.commission) {
                    els.commission.value = String(d.commission_percent ?? d.commission_rule?.default ?? 15);
                }

                if (els.recommended) {
                    els.recommended.checked = !!d.is_recommended;
                }

                if (els.status) {
                    els.status.value = String(d.status ?? 1);
                }

                applyRule(d.commission_rule || config.commission_rule || { readonly: true, min: 15, max: 15, default: 15 });
                state.updateUrl = d.update_url || url;
                openModal();
            })
            .catch(function () {
                flash('error', config.messages?.edit_modal_error || 'Failed to load edit data.');
            });
    }

    function submitModal() {
        var els = modalEls();
        if (!els.commission || !els.status || !els.recommended) return;

        var pct = parseFloat(els.commission.value || '0');
        var rule = config.commission_rule || { readonly: true, min: 15, max: 15, default: 15 };
        if (rule.readonly) {
            pct = parseFloat(String(rule.default ?? 15));
        }

        if (Number.isNaN(pct) || pct < Number(rule.min ?? 0) || pct > Number(rule.max ?? 100)) {
            flash('warning', config.messages?.invalid_range || 'Invalid commission range.');
            return;
        }

        var url = state.mode === 'bulk' ? config.bulk_update_url : state.updateUrl;
        var method = state.mode === 'bulk' ? 'POST' : 'PUT';
        var payload = {
            commission_percent: pct,
            is_recommended: els.recommended.checked ? 1 : 0,
            status: parseInt(els.status.value || '1', 10),
        };

        if (state.mode === 'bulk') {
            payload.indices = state.ids;
        }

        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload),
        })
            .then(function (res) {
                return res.json().then(function (data) { return { ok: res.ok, data: data }; });
            })
            .then(function (res) {
                if (!res.ok) {
                    flash('error', res.data?.message || 'Unable to save.');
                    return;
                }

                flash('success', res.data?.message || 'Saved successfully.');
                closeModal();
                window.location.reload();
            })
            .catch(function () {
                flash('error', 'Unable to save.');
            });
    }

    function init() {
        var selectAll = document.getElementById('store-products-select-all');
        var bulkEditBtn = document.getElementById('store-products-bulk-edit-btn');
        var bulkRemoveForm = document.getElementById('store-products-bulk-remove-form');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                Array.from(document.querySelectorAll('.store-products-row-checkbox')).forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
            });
        }

        if (bulkEditBtn) {
            bulkEditBtn.addEventListener('click', openBulkModal);
        }

        Array.from(document.querySelectorAll('.store-products-edit-btn')).forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = btn.getAttribute('data-modal-url') || '';
                if (!url) return;
                openSingleModal(url);
            });
        });

        var els = modalEls();
        if (els.close) els.close.addEventListener('click', closeModal);
        if (els.cancel) els.cancel.addEventListener('click', closeModal);
        if (els.modal) {
            els.modal.addEventListener('click', function (e) {
                if (e.target === els.modal) closeModal();
            });
        }
        if (els.save) els.save.addEventListener('click', submitModal);

        window.submitStoreProductsBulkRemove = function (event) {
            var ids = selectedIds();
            if (!ids.length) {
                event.preventDefault();
                alert(config.messages?.select_one || 'Select at least one product.');
                return false;
            }

            if (!confirm(config.messages?.remove_confirm || 'Remove selected products from store?')) {
                event.preventDefault();
                return false;
            }

            if (!bulkRemoveForm) return true;
            Array.from(bulkRemoveForm.querySelectorAll('input[name="indices[]"]')).forEach(function (el) { el.remove(); });
            ids.forEach(function (id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'indices[]';
                input.value = String(id);
                bulkRemoveForm.appendChild(input);
            });

            return true;
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
