<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.sales.wallet-requests.index.title')
    </x-slot>

    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('superadmin::app.sales.wallet-requests.index.title')
            </p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @lang('superadmin::app.sales.wallet-requests.index.subtitle')
            </p>
        </div>

        @php
            $statusFilter = $statusFilter ?? request('status');
            $typeParam = $type ?? request('type');
            $typeQuery = fn (?string $t) => array_filter(['type' => $t, 'status' => $statusFilter]);
            $statusQuery = fn (?string $s) => array_filter(['type' => $typeParam, 'status' => $s]);
        @endphp
        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap gap-2">
                <span class="self-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.filter-type-label')</span>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $typeQuery(null)) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ ! $typeParam ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-all')
                </a>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $typeQuery('deposit')) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ $typeParam === 'deposit' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-deposit')
                </a>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $typeQuery('withdraw')) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ $typeParam === 'withdraw' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-withdraw')
                </a>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="self-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.filter-status-label')</span>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $statusQuery(null)) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ ! $statusFilter ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-status-all')
                </a>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $statusQuery('pending')) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ $statusFilter === 'pending' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-status-pending')
                </a>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $statusQuery('approved')) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ $statusFilter === 'approved' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-status-approved')
                </a>
                <a
                    href="{{ route('superadmin.sales.wallet-requests.index', $statusQuery('rejected')) }}"
                    class="rounded-md border px-3 py-1.5 text-sm {{ $statusFilter === 'rejected' ? 'border-blue-600 bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200' }}"
                >
                    @lang('superadmin::app.sales.wallet-requests.index.filter-status-rejected')
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950/40 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-id')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-type')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-seller')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-email')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-amount')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-status')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-date')</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-receipt')</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">@lang('superadmin::app.sales.wallet-requests.index.col-actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($requests as $t)
                        @php
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
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950/50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $t->id }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                @if ($isDeposit)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">@lang('superadmin::app.sales.wallet-requests.index.type-deposit')</span>
                                @else
                                    <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800 dark:bg-orange-900/40 dark:text-orange-200">@lang('superadmin::app.sales.wallet-requests.index.type-withdraw')</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $t->seller?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $t->seller?->email ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ core()->formatPrice($t->amount) }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                @if ($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_PENDING)
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-900 dark:bg-amber-900/40 dark:text-amber-100">@lang('superadmin::app.sales.wallet-requests.index.status-pending')</span>
                                @elseif ($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_COMPLETED)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">@lang('superadmin::app.sales.wallet-requests.index.status-approved')</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/40 dark:text-red-200">@lang('superadmin::app.sales.wallet-requests.index.status-rejected')</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $t->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($isDeposit && $t->receipt_path)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($t->receipt_path) }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline dark:text-blue-400">
                                        @lang('superadmin::app.sales.wallet-requests.index.view-receipt')
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        class="secondary-button py-1.5 text-xs"
                                        data-wallet-view="{{ json_encode($viewPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                        onclick="openWalletViewModal(this)"
                                    >
                                        @lang('superadmin::app.sales.wallet-requests.index.action-view')
                                    </button>
                                    @if ($t->status === \Webkul\User\Models\SellerWalletTransaction::STATUS_PENDING)
                                        <button
                                            type="button"
                                            class="primary-button py-1.5 text-xs"
                                            data-action="{{ route('superadmin.sales.wallet-requests.approve', $t) }}"
                                            data-amount="{{ $t->amount }}"
                                            data-deposit="{{ $isDeposit ? '1' : '0' }}"
                                            onclick="openWalletApproveModal(this)"
                                        >
                                            @lang('superadmin::app.sales.wallet-requests.index.action-approve')
                                        </button>
                                        <form
                                            method="post"
                                            action="{{ route('superadmin.sales.wallet-requests.reject', $t) }}"
                                            class="inline"
                                            onsubmit="return confirm(this.dataset.confirmMsg);"
                                            data-confirm-msg="{{ e($isDeposit ? __('superadmin::app.sales.wallet-requests.confirm-reject-deposit') : __('superadmin::app.sales.wallet-requests.confirm-reject-withdraw')) }}"
                                        >
                                            @csrf
                                            <button type="submit" class="secondary-button py-1.5 text-xs text-red-700 dark:text-red-300">
                                                @lang('superadmin::app.sales.wallet-requests.index.action-reject')
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                @lang('superadmin::app.sales.wallet-requests.index.empty')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-800">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- Approve modal --}}
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
                @csrf
                <div>
                    <label for="wallet-approve-amount" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('superadmin::app.sales.wallet-requests.index.modal-amount-label')</label>
                    <input
                        type="number"
                        name="amount"
                        id="wallet-approve-amount"
                        step="0.01"
                        min="0.01"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="secondary-button" onclick="closeWalletApproveModal()">@lang('superadmin::app.sales.wallet-requests.index.modal-cancel')</button>
                    <button type="submit" class="primary-button">@lang('superadmin::app.sales.wallet-requests.index.modal-submit')</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View request details (modal) --}}
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
                    @lang('superadmin::app.sales.wallet-requests.index.view-modal-title')
                </h2>
                <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-800" onclick="closeWalletViewModal()" aria-label="Close">&times;</button>
            </div>
            <div id="wallet-view-modal-body" class="space-y-3 text-sm text-gray-700 dark:text-gray-200" style="padding: 20px;"></div>
            <div class="mt-6 flex justify-end">
                <button type="button" class="secondary-button" onclick="closeWalletViewModal()">@lang('superadmin::app.sales.wallet-requests.index.modal-cancel')</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const walletViewLabels = {
                id: @json(__('superadmin::app.sales.wallet-requests.index.view-field-id')),
                type: @json(__('superadmin::app.sales.wallet-requests.index.col-type')),
                amount: @json(__('superadmin::app.sales.wallet-requests.index.view-field-amount')),
                status: @json(__('superadmin::app.sales.wallet-requests.index.col-status')),
                payment_method: @json(__('superadmin::app.sales.wallet-requests.index.view-field-method')),
                description: @json(__('superadmin::app.sales.wallet-requests.index.view-field-description')),
                created_at: @json(__('superadmin::app.sales.wallet-requests.index.view-field-created')),
                meta: @json(__('superadmin::app.sales.wallet-requests.index.view-field-meta')),
            };
            const walletMetaLabels = {
                bank_name: @json(__('superadmin::app.sales.wallet-requests.index.view-meta-bank-name')),
                account_number: @json(__('superadmin::app.sales.wallet-requests.index.view-meta-account')),
                destination_address: @json(__('superadmin::app.sales.wallet-requests.index.view-meta-destination')),
                address_label: @json(__('superadmin::app.sales.wallet-requests.index.view-meta-address-label')),
                network: @json(__('superadmin::app.sales.wallet-requests.index.view-meta-network')),
                method_name: @json(__('superadmin::app.sales.wallet-requests.index.view-field-method')),
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
                    ? @json(__('superadmin::app.sales.wallet-requests.index.modal-title-deposit'))
                    : @json(__('superadmin::app.sales.wallet-requests.index.modal-title-withdraw'));
                if (help) {
                    help.textContent = isDeposit
                        ? @json(__('superadmin::app.sales.wallet-requests.index.modal-amount-help'))
                        : @json(__('superadmin::app.sales.wallet-requests.index.modal-amount-help-withdraw'));
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
    @endpush
</x-superadmin::layouts>
