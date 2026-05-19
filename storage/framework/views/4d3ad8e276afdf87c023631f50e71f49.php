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
        <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.title'); ?>
            </p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.subtitle'); ?>
            </p>
        </div>

        <?php
            $statusFilter = $statusFilter ?? request('status');
            $typeParam = $type ?? request('type');
            $typeQuery = fn (?string $t) => array_filter(['type' => $t, 'status' => $statusFilter]);
            $statusQuery = fn (?string $s) => array_filter(['type' => $typeParam, 'status' => $s]);
        ?>
        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap gap-2">
                <span class="self-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-type-label'); ?></span>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $typeQuery(null))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e(! $typeParam ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-all'); ?>
                </a>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $typeQuery('deposit'))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e($typeParam === 'deposit' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-deposit'); ?>
                </a>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $typeQuery('withdraw'))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e($typeParam === 'withdraw' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-withdraw'); ?>
                </a>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="self-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-status-label'); ?></span>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $statusQuery(null))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e(! $statusFilter ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-status-all'); ?>
                </a>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $statusQuery('pending'))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e($statusFilter === 'pending' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-status-pending'); ?>
                </a>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $statusQuery('approved'))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e($statusFilter === 'approved' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-status-approved'); ?>
                </a>
                <a
                    href="<?php echo e(route('superadmin.sales.wallet-requests.index', $statusQuery('rejected'))); ?>"
                    class="rounded-md border px-3 py-1.5 text-sm <?php echo e($statusFilter === 'rejected' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200'); ?>"
                >
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.filter-status-rejected'); ?>
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950/40 dark:text-green-200">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200">
            <ul class="list-inside list-disc">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-id'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-type'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-seller'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-email'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-amount'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-status'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-date'); ?></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-receipt'); ?></th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600 dark:text-gray-400"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.col-actions'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isDeposit = $t->kind === \Webkul\User\Models\SellerWalletTransaction::KIND_DEPOSIT_REQUEST;
                            $viewPayload = [
                                'id' => $t->id,
                                'type' => $isDeposit ? 'deposit' : 'withdraw',
                                'amount' => (float) $t->amount,
                                'status' => $t->status,
                                'payment_method' => $t->payment_method,
                                'description' => $t->description,
                                'meta' => $t->meta ?? [],
                                'created_at' => $t->created_at?->format('Y-m-d H:i:s'),
                            ];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950/50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100"><?php echo e($t->id); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <?php if($isDeposit): ?>
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.type-deposit'); ?></span>
                                <?php else: ?>
                                    <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800 dark:bg-orange-900/40 dark:text-orange-200"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.type-withdraw'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100"><?php echo e($t->seller?->name ?? '—'); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?php echo e($t->seller?->email ?? '—'); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e(core()->formatPrice($t->amount)); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <?php if($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_PENDING): ?>
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-900 dark:bg-amber-900/40 dark:text-amber-100"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.status-pending'); ?></span>
                                <?php elseif($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_COMPLETED): ?>
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.status-approved'); ?></span>
                                <?php else: ?>
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/40 dark:text-red-200"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.status-rejected'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?php echo e($t->created_at?->format('Y-m-d H:i')); ?></td>
                            <td class="px-4 py-3 text-sm">
                                <?php if($isDeposit && $t->receipt_path): ?>
                                    <a href="<?php echo e(\Illuminate\Support\Facades\Storage::disk('public')->url($t->receipt_path)); ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline dark:text-blue-400">
                                        <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.view-receipt'); ?>
                                    </a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        class="secondary-button py-1.5 text-xs"
                                        data-wallet-view="<?php echo e(json_encode($viewPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)); ?>"
                                        onclick="openWalletViewModal(this)"
                                    >
                                        <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.action-view'); ?>
                                    </button>
                                    <?php if($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_PENDING): ?>
                                        <button
                                            type="button"
                                            class="primary-button py-1.5 text-xs"
                                            data-action="<?php echo e(route('superadmin.sales.wallet-requests.approve', $t)); ?>"
                                            data-amount="<?php echo e($t->amount); ?>"
                                            data-deposit="<?php echo e($isDeposit ? '1' : '0'); ?>"
                                            onclick="openWalletApproveModal(this)"
                                        >
                                            <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.action-approve'); ?>
                                        </button>
                                        <form
                                            method="post"
                                            action="<?php echo e(route('superadmin.sales.wallet-requests.reject', $t)); ?>"
                                            class="inline"
                                            onsubmit="return confirm(this.dataset.confirmMsg);"
                                            data-confirm-msg="<?php echo e(e($isDeposit ? __('superadmin::app.sales.wallet-requests.confirm-reject-deposit') : __('superadmin::app.sales.wallet-requests.confirm-reject-withdraw'))); ?>"
                                        >
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="secondary-button py-1.5 text-xs text-red-700 dark:text-red-300">
                                                <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.action-reject'); ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.empty'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($requests->hasPages()): ?>
            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-800">
                <?php echo e($requests->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    
    <div
        id="wallet-approve-modal"
        class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="wallet-approve-modal-title"
        style="
    width: 50%;
    justify-self: center;
"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-900">
            <h2 id="wallet-approve-modal-title" class="mb-1 text-lg font-semibold text-gray-900 dark:text-white"></h2>
            <p id="wallet-approve-modal-help" class="mb-4 text-xs text-gray-500 dark:text-gray-400"></p>

            <form id="wallet-approve-form" method="post" class="grid gap-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="wallet-approve-amount" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.modal-amount-label'); ?></label>
                    <input
                        type="number"
                        name="amount"
                        id="wallet-approve-amount"
                        step="0.01"
                        min="0.01"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="secondary-button" onclick="closeWalletApproveModal()"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.modal-cancel'); ?></button>
                    <button type="submit" class="primary-button"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.modal-submit'); ?></button>
                </div>
            </form>
        </div>
    </div>

    
    <div
        id="wallet-view-modal"
        class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="wallet-view-modal-title"
        style="
    width: 50%;
    justify-self: center;
"
    >
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white p-6 shadow-xl dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between gap-2">
                <h2 id="wallet-view-modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                    <?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.view-modal-title'); ?>
                </h2>
                <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-800" onclick="closeWalletViewModal()" aria-label="Close">&times;</button>
            </div>
            <div id="wallet-view-modal-body" class="space-y-3 text-sm text-gray-700 dark:text-gray-200" style="padding: 20px;"></div>
            <div class="mt-6 flex justify-end">
                <button type="button" class="secondary-button" onclick="closeWalletViewModal()"><?php echo app('translator')->get('superadmin::app.sales.wallet-requests.index.modal-cancel'); ?></button>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            const walletViewLabels = {
                id: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-id'), 15, 512) ?>,
                type: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.col-type'), 15, 512) ?>,
                amount: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-amount'), 15, 512) ?>,
                status: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.col-status'), 15, 512) ?>,
                payment_method: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-method'), 15, 512) ?>,
                description: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-description'), 15, 512) ?>,
                created_at: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-created'), 15, 512) ?>,
                meta: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-meta'), 15, 512) ?>,
            };
            const walletMetaLabels = {
                bank_name: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-meta-bank-name'), 15, 512) ?>,
                account_number: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-meta-account'), 15, 512) ?>,
                destination_address: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-meta-destination'), 15, 512) ?>,
                address_label: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-meta-address-label'), 15, 512) ?>,
                network: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-meta-network'), 15, 512) ?>,
                method_name: <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.view-field-method'), 15, 512) ?>,
            };

            function openWalletViewModal(btn) {
                const d = JSON.parse(btn.getAttribute('data-wallet-view'));
                const body = document.getElementById('wallet-view-modal-body');
                body.innerHTML = '';

                function row(label, value) {
                    const el = document.createElement('div');
                    el.className = 'border-b border-gray-100 pb-2 dark:border-gray-800';
                    el.innerHTML = '<p class="text-xs font-medium text-gray-500 dark:text-gray-400">' + label + '</p>'
                        + '<p class="mt-0.5 break-words">' + (value === null || value === undefined || value === '' ? '—' : String(value)) + '</p>';
                    body.appendChild(el);
                }

                row(walletViewLabels.id, d.id);
                row(walletViewLabels.type, d.type);
                row(walletViewLabels.amount, typeof d.amount === 'number' ? d.amount.toFixed(2) : d.amount);
                row(walletViewLabels.status, d.status);
                row(walletViewLabels.payment_method, d.payment_method);
                row(walletViewLabels.description, d.description);
                row(walletViewLabels.created_at, d.created_at);

                if (d.meta && typeof d.meta === 'object') {
                    const metaRow = document.createElement('div');
                    metaRow.className = 'pt-1';
                    metaRow.innerHTML = '<p class="text-xs font-medium text-gray-500 dark:text-gray-400">' + walletViewLabels.meta + '</p>';
                    const ul = document.createElement('ul');
                    ul.className = 'mt-1 list-inside list-disc space-y-1 text-sm';
                    for (const [k, v] of Object.entries(d.meta)) {
                        if (v === null || v === undefined || v === '') continue;
                        const li = document.createElement('li');
                        const label = walletMetaLabels[k] || k;
                        li.textContent = label + ': ' + String(v);
                        ul.appendChild(li);
                    }
                    metaRow.appendChild(ul);
                    body.appendChild(metaRow);
                }

                const modal = document.getElementById('wallet-view-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeWalletViewModal() {
                const modal = document.getElementById('wallet-view-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.getElementById('wallet-view-modal')?.addEventListener('click', function (e) {
                if (e.target === this) closeWalletViewModal();
            });

            function openWalletApproveModal(btn) {
                const modal = document.getElementById('wallet-approve-modal');
                const form = document.getElementById('wallet-approve-form');
                const title = document.getElementById('wallet-approve-modal-title');
                const help = document.getElementById('wallet-approve-modal-help');
                const amountInput = document.getElementById('wallet-approve-amount');
                const isDeposit = btn.dataset.deposit === '1';
                title.textContent = isDeposit
                    ? <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.modal-title-deposit'), 15, 512) ?>
                    : <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.modal-title-withdraw'), 15, 512) ?>;
                if (help) {
                    help.textContent = isDeposit
                        ? <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.modal-amount-help'), 15, 512) ?>
                        : <?php echo json_encode(__('superadmin::app.sales.wallet-requests.index.modal-amount-help-withdraw'), 15, 512) ?>;
                }
                form.action = btn.dataset.action;
                amountInput.value = btn.dataset.amount || '';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeWalletApproveModal() {
                const modal = document.getElementById('wallet-approve-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            document.getElementById('wallet-approve-modal')?.addEventListener('click', function (e) {
                if (e.target === this) closeWalletApproveModal();
            });
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
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/wallet-requests/index.blade.php ENDPATH**/ ?>