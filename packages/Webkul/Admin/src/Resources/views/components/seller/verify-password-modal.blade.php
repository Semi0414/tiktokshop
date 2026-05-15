{{-- Reusable password confirmation before sensitive seller actions. Registers window.sellerVerifyPasswordThen(onSuccess, onCancel?) --}}
@once
    <style>
        #seller-verify-password-modal {
            position: fixed;
            inset: 0;
            z-index: 99990;
            display: none;
            align-items: center;
            justify-content: center;
            padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
            box-sizing: border-box;
            background: rgba(2, 6, 23, 0.66);
            -webkit-backdrop-filter: blur(1.5px);
            backdrop-filter: blur(1.5px);
        }

        #seller-verify-password-modal.flex {
            display: flex;
        }

        #seller-verify-password-modal .seller-verify-password-card {
            width: min(560px, 100%);
            max-height: min(90vh, calc(100dvh - 32px));
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            box-sizing: border-box;
        }

        #seller-verify-password-modal #seller-verify-password-input {
            min-height: 44px;
            font-size: 16px;
        }

        @media (max-width: 767px) {
            #seller-verify-password-modal {
                align-items: flex-end;
                padding: 0;
            }

            #seller-verify-password-modal .seller-verify-password-card {
                width: 100%;
                max-height: min(92dvh, 100%);
                border-radius: 1rem 1rem 0 0;
                border-left: none;
                border-right: none;
                border-bottom: none;
            }

            #seller-verify-password-modal .seller-verify-password-card__header {
                padding: 1rem 1rem 0.875rem;
            }

            #seller-verify-password-modal .seller-verify-password-card__header h2 {
                font-size: 1.0625rem;
                line-height: 1.35;
            }

            #seller-verify-password-modal .seller-verify-password-card__header p {
                font-size: 0.8125rem;
                line-height: 1.45;
            }

            #seller-verify-password-modal #seller-verify-password-form {
                padding: 1rem 1rem max(1rem, env(safe-area-inset-bottom));
            }

            #seller-verify-password-modal .seller-verify-password-actions {
                flex-direction: column-reverse;
                gap: 0.5rem;
            }

            #seller-verify-password-modal .seller-verify-password-actions .seller-verify-password-btn {
                width: 100%;
                min-height: 44px;
            }
        }

        #seller-verify-password-modal #seller-verify-password-cancel {
            background-color: #ffffff !important;
            color: #334155 !important;
            border-color: #cbd5e1 !important;
        }
        #seller-verify-password-modal #seller-verify-password-cancel:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        .dark #seller-verify-password-modal #seller-verify-password-cancel {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-color: #475569 !important;
        }
        .dark #seller-verify-password-modal #seller-verify-password-cancel:hover {
            background-color: #334155 !important;
            color: #ffffff !important;
        }
        #seller-verify-password-modal #seller-verify-password-submit {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-color: rgba(67, 56, 202, 0.5) !important;
        }
        #seller-verify-password-modal #seller-verify-password-submit:hover:not(:disabled) {
            background-color: #6366f1 !important;
            color: #ffffff !important;
        }
        #seller-verify-password-modal #seller-verify-password-submit:active:not(:disabled) {
            background-color: #4338ca !important;
            color: #ffffff !important;
        }
        #seller-verify-password-modal #seller-verify-password-submit:disabled {
            background-color: #94a3b8 !important;
            color: #ffffff !important;
            border-color: #94a3b8 !important;
        }
    </style>
@endonce
<div
    id="seller-verify-password-modal"
    class="hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="seller-verify-password-title"
>
    <div class="seller-verify-password-card overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
        <div class="seller-verify-password-card__header border-b border-slate-100 bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-4 dark:border-slate-800">
            <h2 id="seller-verify-password-title" class="text-lg font-semibold text-white">
                {{ __('admin::app.account.verify-password.title') }}
            </h2>
            <p class="mt-1 text-sm text-indigo-100">
                {{ __('admin::app.account.verify-password.description') }}
            </p>
        </div>
        <form id="seller-verify-password-form" class="space-y-4 px-5 py-5" action="#" method="post" autocomplete="off">
            <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                {{ __('admin::app.account.verify-password.hint') }}
            </p>
            <div>
                <label for="seller-verify-password-input" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">
                    {{ __('admin::app.account.verify-password.placeholder') }}
                </label>
                <input
                    id="seller-verify-password-input"
                    name="seller_gate_password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-950 dark:text-white"
                />
            </div>
            <p id="seller-verify-password-error" class="hidden text-sm text-red-600 dark:text-red-400"></p>
            <div class="seller-verify-password-actions flex flex-col-reverse gap-2 pt-1 sm:flex-row sm:flex-wrap sm:justify-end">
                <button
                    type="button"
                    id="seller-verify-password-cancel"
                    class="seller-verify-password-btn w-full rounded-lg border px-4 py-2.5 text-sm font-medium shadow-sm transition-colors sm:w-auto !border-slate-300 !bg-white !text-slate-700 hover:!bg-slate-100 hover:!text-slate-900 dark:!border-slate-600 dark:!bg-slate-800 dark:!text-slate-100 dark:hover:!bg-slate-700 dark:hover:!text-white"
                >
                    {{ __('admin::app.account.verify-password.cancel') }}
                </button>
                <button
                    type="submit"
                    id="seller-verify-password-submit"
                    class="seller-verify-password-btn w-full rounded-lg border px-4 py-2.5 text-sm font-semibold shadow-md transition-colors hover:shadow-lg disabled:cursor-not-allowed sm:w-auto sm:min-w-[10rem] !border-indigo-700/40 !bg-indigo-600 !text-white hover:!bg-indigo-500 hover:!text-white active:!bg-indigo-800 active:!text-white disabled:!border-slate-400 disabled:!bg-slate-400 disabled:!text-white disabled:hover:!bg-slate-400 dark:!border-indigo-400/40 dark:!bg-indigo-600 dark:!text-white dark:hover:!bg-indigo-500 dark:active:!bg-indigo-800 dark:disabled:!bg-slate-500 dark:disabled:!text-white"
                >
                    {{ __('admin::app.account.verify-password.submit-password') }}
                </button>
            </div>
        </form>
    </div>
</div>

@pushOnce('scripts', 'seller-verify-password-gate')
    <script>
        (function initSellerPasswordGate() {
            const verifyUrl = @json(route('admin.account.verify-password'));
            const messages = {
                required: @json(__('admin::app.account.verify-password.required')),
                generic: @json(__('admin::app.account.verify-password.invalid')),
            };

            /**
             * Shared on window so the submit handler always reads the same pending
             * callback as sellerVerifyPasswordThen (avoids stale closure if scripts re-run).
             */
            window.__sellerVerifyPasswordGate = window.__sellerVerifyPasswordGate || {
                pendingSuccess: null,
                pendingCancel: null,
                busy: false,
                pendingMakeOrderOrderId: null,
                pendingBulkIndices: null,
            };
            const gate = window.__sellerVerifyPasswordGate;
            if (gate.pendingMakeOrderOrderId === undefined) {
                gate.pendingMakeOrderOrderId = null;
            }
            if (gate.pendingBulkIndices === undefined) {
                gate.pendingBulkIndices = null;
            }

            let listenersAttached = false;

            function el(id) {
                return document.getElementById(id);
            }

            function showError(text) {
                const box = el('seller-verify-password-error');
                if (!box) {
                    return;
                }
                box.textContent = text || '';
                if (text) {
                    box.classList.remove('hidden');
                } else {
                    box.classList.add('hidden');
                }
            }

            function openModal() {
                const root = el('seller-verify-password-modal');
                const input = el('seller-verify-password-input');
                if (!root || !input) {
                    return;
                }
                showError('');
                input.value = '';
                root.classList.remove('hidden');
                root.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(function () {
                    input.focus();
                }, 50);
            }

            window.__sellerPasswordGateOpen = function () {
                openModal();
            };

            function closeModal() {
                const root = el('seller-verify-password-modal');
                const input = el('seller-verify-password-input');
                const submit = el('seller-verify-password-submit');
                if (root) {
                    root.classList.add('hidden');
                    root.classList.remove('flex');
                }
                document.body.style.overflow = '';
                if (input) {
                    input.value = '';
                }
                showError('');
                if (submit) {
                    submit.disabled = false;
                }
            }

            function finishCancel() {
                const cancelFn = gate.pendingCancel;
                gate.pendingSuccess = null;
                gate.pendingCancel = null;
                gate.pendingMakeOrderOrderId = null;
                gate.pendingBulkIndices = null;
                gate.busy = false;
                closeModal();
                if (typeof cancelFn === 'function') {
                    cancelFn();
                }
            }

            window.sellerVerifyPasswordThen = function (successFn, cancelFn) {
                if (typeof successFn !== 'function') {
                    return;
                }
                gate.pendingMakeOrderOrderId = null;
                gate.pendingBulkIndices = null;
                gate.pendingSuccess = successFn;
                gate.pendingCancel = typeof cancelFn === 'function' ? cancelFn : null;
                openModal();
            };

            async function verifyAndRun() {
                const root = el('seller-verify-password-modal');
                const input = el('seller-verify-password-input');
                const submit = el('seller-verify-password-submit');

                if (!root || !input || !submit) {
                    return;
                }

                if (gate.busy) {
                    return;
                }

                const pwd = (input.value || '').trim();
                if (!pwd) {
                    showError(messages.required);
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                gate.busy = true;
                submit.disabled = true;
                showError('');

                try {
                    const makeOrderId = gate.pendingMakeOrderOrderId;
                    const bulkIndices = gate.pendingBulkIndices;

                    if (makeOrderId != null && typeof window.__sellerPostMakeOrderWithPassword === 'function') {
                        gate.pendingMakeOrderOrderId = null;
                        closeModal();
                        try {
                            await window.__sellerPostMakeOrderWithPassword(makeOrderId, pwd);
                        } catch (actionErr) {
                            if (window.emitter && typeof window.emitter.emit === 'function') {
                                window.emitter.emit('add-flash', {
                                    type: 'error',
                                    message: actionErr && actionErr.message ? actionErr.message : messages.generic,
                                });
                            } else {
                                alert(actionErr && actionErr.message ? actionErr.message : String(actionErr || messages.generic));
                            }
                        }
                        return;
                    }

                    if (Array.isArray(bulkIndices) && bulkIndices.length && typeof window.__sellerPostBulkMakeOrderWithPassword === 'function') {
                        gate.pendingBulkIndices = null;
                        closeModal();
                        try {
                            await window.__sellerPostBulkMakeOrderWithPassword(bulkIndices, pwd);
                        } catch (actionErr) {
                            if (window.emitter && typeof window.emitter.emit === 'function') {
                                window.emitter.emit('add-flash', {
                                    type: 'error',
                                    message: actionErr && actionErr.message ? actionErr.message : messages.generic,
                                });
                            } else {
                                alert(actionErr && actionErr.message ? actionErr.message : String(actionErr || messages.generic));
                            }
                        }
                        return;
                    }

                    const res = await fetch(verifyUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ password: pwd }),
                    });

                    const data = await res.json().catch(function () {
                        return {};
                    });

                    if (!res.ok) {
                        showError(data.message || messages.generic);
                        return;
                    }

                    const run = gate.pendingSuccess;
                    gate.pendingSuccess = null;
                    gate.pendingCancel = null;
                    closeModal();

                    if (typeof run === 'function') {
                        try {
                            await Promise.resolve(run());
                        } catch (actionErr) {
                            if (window.emitter && typeof window.emitter.emit === 'function') {
                                window.emitter.emit('add-flash', {
                                    type: 'error',
                                    message: actionErr && actionErr.message ? actionErr.message : messages.generic,
                                });
                            } else {
                                alert(actionErr && actionErr.message ? actionErr.message : String(actionErr || messages.generic));
                            }
                        }
                    }
                } catch (err) {
                    showError(messages.generic);
                } finally {
                    gate.busy = false;
                    submit.disabled = false;
                }
            }

            function boot() {
                const root = el('seller-verify-password-modal');
                const form = el('seller-verify-password-form');
                const input = el('seller-verify-password-input');
                const submit = el('seller-verify-password-submit');
                const cancel = el('seller-verify-password-cancel');

                if (!root || !form || !input || !submit) {
                    return;
                }

                if (listenersAttached) {
                    return;
                }
                listenersAttached = true;

                root.addEventListener('click', function (e) {
                    if (e.target === root) {
                        finishCancel();
                    }
                });

                if (cancel) {
                    cancel.addEventListener('click', function () {
                        finishCancel();
                    });
                }

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    verifyAndRun();
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        verifyAndRun();
                    }
                    if (e.key === 'Escape') {
                        finishCancel();
                    }
                });
            }

            function scheduleBoot() {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', boot, { once: true });
                } else {
                    boot();
                }
            }

            scheduleBoot();
        })();
    </script>
@endPushOnce
