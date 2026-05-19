<?php
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
?>

<?php if (isset($component)) { $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.email-management.title'); ?>
     <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            <p class="font-semibold"><?php echo app('translator')->get('superadmin::app.email-management.validation-summary'); ?></p>
            <ul class="mt-2 list-inside list-disc text-sm">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            <?php echo app('translator')->get('superadmin::app.email-management.title'); ?>
        </p>

        <button
            type="button"
            id="superadmin-email-compose-open-btn"
            class="primary-button"
            onclick="window.superadminEmailComposeOpen && window.superadminEmailComposeOpen()"
        >
            <?php echo app('translator')->get('superadmin::app.email-management.open-compose-btn'); ?>
        </button>
    </div>

    <?php if(! empty($logsUnavailable)): ?>
        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100">
            <?php echo app('translator')->get('superadmin::app.email-management.migration-hint'); ?>
        </div>
    <?php endif; ?>

    <dialog
        id="superadmin-email-compose-dialog"
        class="email-compose-dialog w-[min(100vw-2rem,min(96rem,1400px))] max-w-[100vw] rounded-xl border border-gray-200 bg-white p-0 text-gray-900 shadow-2xl backdrop:bg-gray-900/60 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        style="width: min(50vw, 42rem); max-width: calc(100vw - 2rem);"
        closedby="any"
    >
        <div class="email-compose-panel flex h-[min(92vh,900px)] max-h-[min(92vh,900px)] w-full min-h-0 flex-col overflow-hidden rounded-xl bg-white dark:bg-gray-900">
            <div class="flex shrink-0 items-center justify-between gap-3 border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <p class="text-base font-semibold text-gray-900 dark:text-white">
                    <?php echo app('translator')->get('superadmin::app.email-management.compose-title'); ?>
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
                action="<?php echo e(route('superadmin.email-management.send')); ?>"
                class="flex min-h-0 flex-1 flex-col"
            >
                <?php echo csrf_field(); ?>

                <div
                    id="email-compose-scroll-lock"
                    class="min-h-0 flex-1 overflow-y-scroll overflow-x-hidden overscroll-contain touch-pan-y px-6 py-4 [scrollbar-gutter:stable]"
                >
                    <div class="grid gap-4 pb-1">

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.recipient-type'); ?>
                            </label>
                            <select
                                id="email-mgmt-recipient-type"
                                name="recipient_type"
                                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                required
                            >
                                <option value="seller" <?php if($rt === 'seller'): echo 'selected'; endif; ?>><?php echo app('translator')->get('superadmin::app.email-management.type-seller'); ?></option>
                                <option value="customer" <?php if($rt === 'customer'): echo 'selected'; endif; ?>><?php echo app('translator')->get('superadmin::app.email-management.type-customer'); ?></option>
                                <option value="custom" <?php if($rt === 'custom'): echo 'selected'; endif; ?>><?php echo app('translator')->get('superadmin::app.email-management.type-custom'); ?></option>
                            </select>
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="custom"
                            style="<?php echo e($rt === 'custom' ? '' : 'display:none'); ?>"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.to-email-custom'); ?>
                            </label>
                            <input
                                type="email"
                                name="to_email"
                                value="<?php echo e($toEmailVal); ?>"
                                autocomplete="email"
                                class="email-mgmt-field-custom rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                placeholder="email@example.com"
                            />
                            <p class="text-xs text-gray-500"><?php echo app('translator')->get('superadmin::app.email-management.hint-custom'); ?></p>
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="seller"
                            style="<?php echo e($rt === 'seller' ? '' : 'display:none'); ?>"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.seller-select'); ?>
                            </label>
                            <select
                                id="email-mgmt-seller-select"
                                name="seller_id"
                                class="email-mgmt-field-seller w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                            >
                                <option value=""><?php echo app('translator')->get('superadmin::app.email-management.select-option-placeholder'); ?></option>
                                <?php if($showSellerExtras): ?>
                                    <option value="<?php echo e($sellerIdVal); ?>" selected><?php echo e($oldSellerLabel); ?></option>
                                <?php endif; ?>
                                <?php $__currentLoopData = $composeSellerOpts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>" <?php if((string) old('seller_id', $sellerIdVal) === (string) $s->id): echo 'selected'; endif; ?>>
                                        <?php echo e($s->name); ?> (<?php echo e($s->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php if(! empty($composeSellerListTruncated)): ?>
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    <?php echo app('translator')->get('superadmin::app.email-management.select-list-truncated-sellers', ['count' => $composeRecipientSelectLimit ?? 500]); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div
                            class="email-mgmt-recipient-block grid gap-2"
                            data-recipient="customer"
                            style="<?php echo e($rt === 'customer' ? '' : 'display:none'); ?>"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.customer-select'); ?>
                            </label>
                            <select
                                id="email-mgmt-customer-select"
                                name="customer_id"
                                class="email-mgmt-field-customer w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                            >
                                <option value=""><?php echo app('translator')->get('superadmin::app.email-management.select-option-placeholder'); ?></option>
                                <?php if($showCustomerExtras): ?>
                                    <option value="<?php echo e($customerIdVal); ?>" selected><?php echo e($oldCustomerLabel); ?></option>
                                <?php endif; ?>
                                <?php $__currentLoopData = $composeCustomerOpts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>" <?php if((string) old('customer_id', $customerIdVal) === (string) $c->id): echo 'selected'; endif; ?>>
                                        <?php echo e($c->name); ?> (<?php echo e($c->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php if(! empty($composeCustomerListTruncated)): ?>
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    <?php echo app('translator')->get('superadmin::app.email-management.select-list-truncated-customers', ['count' => $composeRecipientSelectLimit ?? 500]); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.subject'); ?>
                            </label>
                            <input
                                type="text"
                                name="subject"
                                value="<?php echo e($subjectVal); ?>"
                                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white"
                                required
                            />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php echo app('translator')->get('superadmin::app.email-management.body-html'); ?>
                            </label>
                            <textarea
                                name="body_html"
                                rows="12"
                                class="min-h-[220px] font-mono rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-950 dark:text-white <?php $__errorArgs = ['body_html'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                            ><?php echo e($bodyHtmlVal); ?></textarea>
                            <?php $__errorArgs = ['body_html'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <?php echo app('translator')->get('superadmin::app.email-management.cancel-btn'); ?>
                    </button>

                    <button
                        type="submit"
                        class="primary-button inline-flex min-w-[8.5rem] items-center justify-center gap-2"
                        data-email-compose-submit
                    >
                        <span class="email-compose-btn-label"><?php echo app('translator')->get('superadmin::app.email-management.send-btn'); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <?php if(empty($logsUnavailable)): ?>
        <p class="mb-3 text-lg font-semibold text-gray-800 dark:text-white">
            <?php echo app('translator')->get('superadmin::app.email-management.logs-title'); ?>
        </p>

        <?php if (isset($component)) { $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.ssr','data' => ['datagridPayload' => $datagridPayload,'src' => route('superadmin.email-management.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.ssr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['datagrid-payload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datagridPayload),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.email-management.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $attributes = $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $component = $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>
    <?php endif; ?>

    <?php $__env->startPush('styles'); ?>
        <style>
            /* Native <dialog> backdrop: ensure dimmed area receives clicks (close via JS). */
            .email-compose-dialog::backdrop {
                background: rgba(17, 24, 39, 0.55);
                cursor: pointer;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
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
                    send: <?php echo json_encode(__('superadmin::app.email-management.send-btn'), 15, 512) ?>,
                    sending: <?php echo json_encode(__('superadmin::app.email-management.send-sending'), 15, 512) ?>,
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
                    <?php if($errors->has('body_html') || $errors->has('subject') || $errors->has('recipient_type') || $errors->has('to_email') || $errors->has('seller_id') || $errors->has('customer_id')): ?>
                    window.requestAnimationFrame(function () {
                        if (window.superadminEmailComposeOpen) {
                            window.superadminEmailComposeOpen();
                        }
                    });
                    <?php endif; ?>
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', bootEmailComposeUi);
                } else {
                    bootEmailComposeUi();
                }
            })();
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/email-management/index.blade.php ENDPATH**/ ?>