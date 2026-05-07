@php
    $composeDefaults = $composeDefaults ?? null;
    $cd = $composeDefaults ?? [];
    $rt = old('recipient_type', $cd['recipient_type'] ?? 'seller');

    $oldSellerLabel = null;
    if (old('seller_id')) {
        $s = \Webkul\User\Models\Admin::query()->find(old('seller_id'));
        $oldSellerLabel = $s ? $s->name.' ('.$s->email.')' : null;
    } elseif ($composeDefaults && ($composeDefaults['recipient_type'] ?? '') === 'seller' && ! empty($composeDefaults['recipient_id'])) {
        $oldSellerLabel = $composeDefaults['seller_label'] ?? null;
    }

    $oldCustomerLabel = null;
    if (old('customer_id')) {
        $c = \Webkul\Customer\Models\Customer::query()->find(old('customer_id'));
        $oldCustomerLabel = $c ? $c->name.' ('.$c->email.')' : null;
    } elseif ($composeDefaults && ($composeDefaults['recipient_type'] ?? '') === 'customer' && ! empty($composeDefaults['recipient_id'])) {
        $oldCustomerLabel = $composeDefaults['customer_label'] ?? null;
    }

    $sellerIdVal = old('seller_id');
    if ($sellerIdVal === null && ($cd['recipient_type'] ?? '') === 'seller') {
        $sellerIdVal = $cd['recipient_id'] ?? null;
    }

    $customerIdVal = old('customer_id');
    if ($customerIdVal === null && ($cd['recipient_type'] ?? '') === 'customer') {
        $customerIdVal = $cd['recipient_id'] ?? null;
    }

    $toEmailVal = old('to_email', $cd['to_email'] ?? '');
    $subjectVal = old('subject', $cd['subject'] ?? '');
    $bodyHtmlVal = old('body_html', $cd['body_html'] ?? '');
    $composeSellerOpts = isset($composeSellerOptions) ? $composeSellerOptions : collect();
    $composeCustomerOpts = isset($composeCustomerOptions) ? $composeCustomerOptions : collect();
    $sellerIdsListed = $composeSellerOpts->pluck('id')->map(fn ($id) => (int) $id)->all();
    $customerIdsListed = $composeCustomerOpts->pluck('id')->map(fn ($id) => (int) $id)->all();
    $showSellerExtras = $sellerIdVal && $oldSellerLabel && ! in_array((int) $sellerIdVal, $sellerIdsListed, true);
    $showCustomerExtras = $customerIdVal && $oldCustomerLabel && ! in_array((int) $customerIdVal, $customerIdsListed, true);
@endphp

<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.email-management.title')
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            <p class="font-semibold">@lang('superadmin::app.email-management.validation-summary')</p>
            <ul class="mt-2 list-inside list-disc text-sm">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.email-management.title')
        </p>

        <button
            type="button"
            id="superadmin-email-compose-open-btn"
            class="primary-button"
            onclick="window.superadminEmailComposeOpen && window.superadminEmailComposeOpen()"
        >
            @lang('superadmin::app.email-management.open-compose-btn')
        </button>
    </div>

    @if (! empty($logsUnavailable))
        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100">
            @lang('superadmin::app.email-management.migration-hint')
        </div>
    @endif

    <dialog
        id="superadmin-email-compose-dialog"
        class="email-compose-dialog w-[min(100vw-2rem,min(96rem,1400px))] max-w-[100vw] rounded-xl border border-gray-200 bg-white p-0 text-gray-900 shadow-2xl backdrop:bg-gray-900/60 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        style="width: min(50vw, 42rem); max-width: calc(100vw - 2rem);"
        closedby="any"
    >
        <div class="email-compose-panel flex h-[min(92vh,900px)] max-h-[min(92vh,900px)] w-full min-h-0 flex-col overflow-hidden rounded-xl bg-white dark:bg-gray-900">
            <div class="flex shrink-0 items-center justify-between gap-3 border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <p class="text-base font-semibold text-gray-900 dark:text-white">
                    @lang('superadmin::app.email-management.compose-title')
                </p>

                <button
                    type="button"
                    class="inline-flex min-h-10 min-w-10 cursor-pointer items-center justify-center rounded-md text-2xl leading-none text-gray-500 hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                    data-email-compose-close
                    aria-label="Close"
                    onclick="window.superadminEmailComposeClose && window.superadminEmailComposeClose(); return false;"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form
                id="superadmin-email-compose-form"
                method="post"
                action="{{ route('superadmin.email-management.send') }}"
                class="flex min-h-0 flex-1 flex-col"
            >
                @csrf

                <div
                    id="email-compose-scroll-lock"
                    class="min-h-0 flex-1 overflow-y-scroll overflow-x-hidden overscroll-contain touch-pan-y px-6 py-4 [scrollbar-gutter:stable]"
                >
                    <div class="grid gap-4 pb-1">

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.recipient-type')
                            </label>
                            <select
                                id="email-mgmt-recipient-type"
                                name="recipient_type"
                                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                required
                            >
                                <option value="seller" @selected($rt === 'seller')>@lang('superadmin::app.email-management.type-seller')</option>
                                <option value="customer" @selected($rt === 'customer')>@lang('superadmin::app.email-management.type-customer')</option>
                                <option value="custom" @selected($rt === 'custom')>@lang('superadmin::app.email-management.type-custom')</option>
                            </select>
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="custom"
                            style="{{ $rt === 'custom' ? '' : 'display:none' }}"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.to-email-custom')
                            </label>
                            <input
                                type="email"
                                name="to_email"
                                value="{{ $toEmailVal }}"
                                autocomplete="email"
                                class="email-mgmt-field-custom rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                placeholder="email@example.com"
                            />
                            <p class="text-xs text-gray-500">@lang('superadmin::app.email-management.hint-custom')</p>
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="seller"
                            style="{{ $rt === 'seller' ? '' : 'display:none' }}"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.seller-select')
                            </label>
                            <select
                                id="email-mgmt-seller-select"
                                name="seller_id"
                                class="email-mgmt-field-seller w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                            >
                                <option value="">@lang('superadmin::app.email-management.select-option-placeholder')</option>
                                @if ($showSellerExtras)
                                    <option value="{{ $sellerIdVal }}" selected>{{ $oldSellerLabel }}</option>
                                @endif
                                @foreach ($composeSellerOpts as $s)
                                    <option value="{{ $s->id }}" @selected((string) old('seller_id', $sellerIdVal) === (string) $s->id)>
                                        {{ $s->name }} ({{ $s->email }})
                                    </option>
                                @endforeach
                            </select>
                            @if (! empty($composeSellerListTruncated))
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    @lang('superadmin::app.email-management.select-list-truncated-sellers', ['count' => $composeRecipientSelectLimit ?? 500])
                                </p>
                            @endif
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="customer"
                            style="{{ $rt === 'customer' ? '' : 'display:none' }}"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.customer-select')
                            </label>
                            <select
                                id="email-mgmt-customer-select"
                                name="customer_id"
                                class="email-mgmt-field-customer w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                            >
                                <option value="">@lang('superadmin::app.email-management.select-option-placeholder')</option>
                                @if ($showCustomerExtras)
                                    <option value="{{ $customerIdVal }}" selected>{{ $oldCustomerLabel }}</option>
                                @endif
                                @foreach ($composeCustomerOpts as $c)
                                    <option value="{{ $c->id }}" @selected((string) old('customer_id', $customerIdVal) === (string) $c->id)>
                                        {{ $c->name }} ({{ $c->email }})
                                    </option>
                                @endforeach
                            </select>
                            @if (! empty($composeCustomerListTruncated))
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    @lang('superadmin::app.email-management.select-list-truncated-customers', ['count' => $composeRecipientSelectLimit ?? 500])
                                </p>
                            @endif
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.subject')
                            </label>
                            <input
                                type="text"
                                name="subject"
                                value="{{ $subjectVal }}"
                                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                required
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.email-management.body-html')
                            </label>
                            <textarea
                                name="body_html"
                                rows="12"
                                class="min-h-[220px] font-mono rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white @error('body_html') border-red-500 @enderror"
                                required
                            >{{ $bodyHtmlVal }}</textarea>
                            @error('body_html')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap justify-end gap-2 border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <button
                        type="button"
                        class="secondary-button"
                        data-email-compose-cancel
                        onclick="window.superadminEmailComposeClose && window.superadminEmailComposeClose(); return false;"
                    >
                        @lang('superadmin::app.email-management.cancel-btn')
                    </button>

                    <button
                        type="submit"
                        class="primary-button inline-flex min-w-[8.5rem] items-center justify-center gap-2"
                        data-email-compose-submit
                    >
                        <span class="email-compose-btn-label">@lang('superadmin::app.email-management.send-btn')</span>
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    @if (empty($logsUnavailable))
        <p class="mb-3 text-lg font-semibold text-gray-800 dark:text-white">
            @lang('superadmin::app.email-management.logs-title')
        </p>

        <x-superadmin::datagrid.ssr :datagrid-payload="$datagridPayload" :src="route('superadmin.email-management.index')" />
    @endif

    @push('styles')
        <style>
            /* Native <dialog> backdrop: ensure dimmed area receives clicks (close via JS). */
            .email-compose-dialog::backdrop {
                background: rgba(17, 24, 39, 0.55);
                cursor: pointer;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                window.superadminEmailComposeSending = false;

                /** Close compose modal (X, Cancel, backdrop). Safe to call from inline onclick. */
                window.superadminEmailComposeClose = function () {
                    if (window.superadminEmailComposeSending) {
                        return;
                    }
                    const d = document.getElementById('superadmin-email-compose-dialog');
                    if (d && typeof d.close === 'function' && d.open) {
                        d.close();
                    }
                };

                const emailComposeLabels = {
                    send: @json(__('superadmin::app.email-management.send-btn')),
                    sending: @json(__('superadmin::app.email-management.send-sending')),
                };

                function setEmailComposeSending(sending) {
                    window.superadminEmailComposeSending = !!sending;
                    const form = document.getElementById('superadmin-email-compose-form');
                    const dlg = document.getElementById('superadmin-email-compose-dialog');
                    const submitBtn = form ? form.querySelector('[data-email-compose-submit]') : null;
                    const labelEl = submitBtn ? submitBtn.querySelector('.email-compose-btn-label') : null;

                    if (submitBtn && labelEl) {
                        if (sending) {
                            labelEl.textContent = '';
                            const spin = document.createElement('span');
                            spin.className = 'inline-block h-4 w-4 shrink-0 animate-spin rounded-full border-2 border-white border-t-transparent';
                            spin.setAttribute('aria-hidden', 'true');
                            const text = document.createElement('span');
                            text.textContent = emailComposeLabels.sending;
                            labelEl.appendChild(spin);
                            labelEl.appendChild(document.createTextNode(' '));
                            labelEl.appendChild(text);
                        } else {
                            labelEl.textContent = emailComposeLabels.send;
                        }
                        submitBtn.disabled = sending;
                        submitBtn.setAttribute('aria-busy', sending ? 'true' : 'false');
                        submitBtn.classList.toggle('cursor-wait', sending);
                        submitBtn.classList.toggle('opacity-90', sending);
                    }

                    if (dlg) {
                        dlg.querySelectorAll('[data-email-compose-close], [data-email-compose-cancel]').forEach(function (btn) {
                            btn.disabled = sending;
                        });
                    }

                    /* Lock only the scrollable fields area — not the whole form — or Cancel/Close stay unclickable. */
                    const scrollLock = document.getElementById('email-compose-scroll-lock');
                    if (scrollLock) {
                        scrollLock.classList.toggle('pointer-events-none', sending);
                        scrollLock.classList.toggle('opacity-60', sending);
                    }
                }

                const composeDialog = document.getElementById('superadmin-email-compose-dialog');
                if (composeDialog) {
                    composeDialog.addEventListener('cancel', function (e) {
                        if (window.superadminEmailComposeSending) {
                            e.preventDefault();
                        }
                    });

                    composeDialog.addEventListener('close', function () {
                        setEmailComposeSending(false);
                        const prev = document.body.getAttribute('data-prev-overflow');
                        document.body.style.overflow = prev !== null ? prev : '';
                        document.body.removeAttribute('data-prev-overflow');
                    });

                    /* Backdrop: click outside .email-compose-panel (target is usually <dialog> itself). */
                    composeDialog.addEventListener('click', function (e) {
                        if (window.superadminEmailComposeSending) {
                            return;
                        }
                        const panel = composeDialog.querySelector('.email-compose-panel');
                        if (panel && panel.contains(e.target)) {
                            return;
                        }
                        composeDialog.close();
                    });

                    /* Backup if inline onclick on X / Cancel does not run. */
                    composeDialog.addEventListener('click', function (e) {
                        if (window.superadminEmailComposeSending) {
                            return;
                        }
                        const btn = e.target.closest('[data-email-compose-close], [data-email-compose-cancel]');
                        if (! btn) {
                            return;
                        }
                        e.preventDefault();
                        composeDialog.close();
                    });
                }

                function syncRecipientBlocks() {
                    const typeEl = document.getElementById('email-mgmt-recipient-type');
                    const v = typeEl ? typeEl.value : 'seller';

                    document.querySelectorAll('.email-mgmt-recipient-block').forEach(function (el) {
                        const match = el.getAttribute('data-recipient') === v;
                        el.style.display = match ? '' : 'none';
                        el.querySelectorAll('input, select, textarea').forEach(function (field) {
                            field.disabled = ! match;
                        });
                    });
                }

                window.superadminEmailComposeOpen = function () {
                    const dialog = document.getElementById('superadmin-email-compose-dialog');
                    if (! dialog) {
                        return;
                    }
                    setEmailComposeSending(false);
                    document.body.setAttribute('data-prev-overflow', document.body.style.overflow || '');
                    document.body.style.overflow = 'hidden';
                    dialog.showModal();
                    syncRecipientBlocks();
                };

                const composeFormEl = document.getElementById('superadmin-email-compose-form');
                if (composeFormEl) {
                    composeFormEl.addEventListener('submit', function (e) {
                        if (window.superadminEmailComposeSending) {
                            e.preventDefault();

                            return;
                        }

                        const form = e.target;

                        if (! form.checkValidity()) {
                            return;
                        }

                        e.preventDefault();
                        setEmailComposeSending(true);
                        /* Let the browser paint disabled + spinner before navigation (native submit has no loading UI). */
                        window.requestAnimationFrame(function () {
                            window.requestAnimationFrame(function () {
                                form.submit();
                            });
                        });
                    });
                }

                function initEmailComposePage() {
                    const typeEl = document.getElementById('email-mgmt-recipient-type');
                    if (typeEl) {
                        typeEl.addEventListener('change', function () {
                            syncRecipientBlocks();
                        });
                    }
                    syncRecipientBlocks();
                }

                function openComposeFromResendQuery() {
                    const params = new URLSearchParams(window.location.search);
                    if (! params.get('compose_from')) {
                        return;
                    }
                    window.requestAnimationFrame(function () {
                        if (window.superadminEmailComposeOpen) {
                            window.superadminEmailComposeOpen();
                        }
                    });
                }

                function bootEmailComposeUi() {
                    initEmailComposePage();
                    openComposeFromResendQuery();
                    @if ($errors->has('body_html') || $errors->has('subject') || $errors->has('recipient_type') || $errors->has('to_email') || $errors->has('seller_id') || $errors->has('customer_id'))
                    window.requestAnimationFrame(function () {
                        if (window.superadminEmailComposeOpen) {
                            window.superadminEmailComposeOpen();
                        }
                    });
                    @endif
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', bootEmailComposeUi);
                } else {
                    bootEmailComposeUi();
                }
            })();
        </script>
    @endpush
</x-superadmin::layouts>
