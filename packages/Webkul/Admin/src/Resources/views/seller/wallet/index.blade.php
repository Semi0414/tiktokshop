<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.seller-panel.tabs.my-wallet')
    </x-slot>

    <x-admin::seller.panel active="wallet" :breadcrumb="[__('admin::app.seller-panel.tabs.my-wallet')]">
        @if (session('success'))
            <div class="seller-filter-card mb-4 border-green-200 bg-green-50 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="seller-filter-card mb-4 border-red-200 bg-red-50 text-red-800">
                <ul class="list-inside list-disc text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="seller-filter-card mb-4 flex flex-col gap-4 lg:flex-row lg:items-stretch">
            <div class="flex flex-1 flex-col items-center justify-center border-r border-gray-100 py-4 pr-4 lg:flex-row lg:items-center lg:gap-6">
                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600 lg:mb-0">
                    <span class="icon-sales"></span>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($seller->wallet_balance ?? 0) }}</p>
                    <p class="text-sm text-gray-500">@lang('admin::app.seller.wallet.balance')</p>
                </div>
            </div>

            <div class="flex flex-1 flex-col items-center justify-center border-r border-gray-100 py-4 lg:flex-row lg:items-center lg:gap-6">
                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-orange-100 text-2xl text-orange-600 lg:mb-0">
                    <span class="icon-dashboard"></span>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ core()->formatPrice($totalCredits ?? 0) }}</p>
                    <p class="text-sm text-gray-500">@lang('admin::app.seller.wallet.accumulated-revenue')</p>
                </div>
            </div>

            <div class="flex flex-col justify-center gap-2 lg:min-w-[200px]">
                <a href="{{ route('admin.seller-level.index') }}" class="seller-btn-secondary text-center text-xs">@lang('admin::app.components.layouts.sidebar.seller-level')</a>
                <div class="flex gap-2">
                    <button type="button" onclick="window.sellerWalletOpenDeposit && window.sellerWalletOpenDeposit()" class="seller-btn-primary flex-1 text-xs">@lang('admin::app.seller.wallet.deposit')</button>
                    <button type="button" onclick="window.sellerWalletOpenWithdraw && window.sellerWalletOpenWithdraw()" class="seller-btn-secondary flex-1 text-xs">@lang('admin::app.seller.wallet.withdraw')</button>
                </div>
            </div>
        </div>

        {{-- Deposit modal --}}
        <div id="modal-deposit" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gradient-to-br from-blue-900/60 to-cyan-900/60 p-4 backdrop-blur-[1px]" role="dialog" aria-modal="true">
            <div class="max-h-[90vh] w-1/2 min-w-0 max-w-full overflow-y-auto rounded-xl border border-blue-100 bg-white p-5 shadow-2xl max-sm:w-full dark:border-blue-900/50 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-3 py-2">
                    <p class="text-lg font-semibold text-white">@lang('admin::app.seller.wallet.deposit')</p>
                    <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-800" onclick="closeDepositModal()" aria-label="Close">&times;</button>
                </div>

                <div id="deposit-step-methods">
                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">@lang('admin::app.seller.wallet.choose-deposit-method')</p>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ($depositMethods as $m)
                            <button
                                type="button"
                                class="rounded-md border border-blue-100 bg-blue-50/60 px-3 py-2 text-left text-sm font-medium text-blue-700 hover:border-blue-400 hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-300 dark:hover:border-blue-600"
                                data-code="{{ $m->code }}"
                                data-name="{{ $m->name }}"
                                data-address="{{ e($resolvedDepositAddresses[$m->code] ?? ($m->address_text ?? '')) }}"
                                data-network="{{ $m->network ?? '' }}"
                                data-is-bank="{{ in_array($m->code, $bankDepositMethodCodes ?? [], true) ? '1' : '0' }}"
                                onclick="selectDepositMethod(this)"
                            >
                                {{ $m->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="deposit-step-form" class="hidden">
                    <button type="button" class="mb-3 text-sm font-medium text-blue-600 hover:underline" onclick="depositBackToMethods()">@lang('admin::app.seller.wallet.back')</button>

                    <div id="deposit-method-heading">
                        <p class="mb-1 text-sm font-medium text-gray-900 dark:text-white" id="deposit-method-title"></p>
                        <p class="mb-1 text-xs text-gray-500" id="deposit-method-network"></p>
                    </div>

                    <div id="deposit-bank-notice" class="mb-4 hidden rounded-md border border-amber-200 bg-amber-50 p-3 text-sm leading-relaxed text-amber-900 dark:border-amber-500/40 dark:bg-amber-950/40 dark:text-amber-100">
                        @lang('admin::app.seller.wallet.deposit-bank-instructions')
                    </div>

                    <div id="deposit-method-address-wrap" class="mb-4">
                        <div class="rounded-md border border-blue-100 bg-blue-50 p-3 text-xs break-all text-blue-800 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-200" id="deposit-method-address"></div>
                    </div>

                    <div id="deposit-crypto-fields" class="grid gap-3">
                        <form method="post" action="{{ route('admin.wallet.deposit-request') }}" enctype="multipart/form-data" class="grid gap-3">
                            @csrf
                            <input type="hidden" name="payment_method" id="deposit-payment-method" value="{{ old('payment_method') }}" />
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.amount') (USDT)</label>
                                <input type="number" step="0.01" min="0" name="amount" required value="{{ old('payment_method') && old('amount') ? old('amount') : '' }}" class="w-full rounded-md border border-blue-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-blue-900/40 dark:bg-gray-900 dark:text-white" />
                                @error('amount')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.receipt')</label>
                                <input type="file" name="receipt" class="w-full text-sm" required />
                                @error('receipt')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                            </div>
                            <button
                                type="submit"
                                class="seller-btn-primary text-white"
                                style="background: linear-gradient(90deg, #2563eb 0%, #0891b2 100%) !important;"
                            >
                                @lang('admin::app.seller.wallet.submit-deposit')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Withdraw modal --}}
        <div id="modal-withdraw" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gradient-to-br from-emerald-900/60 to-teal-900/60 p-4 backdrop-blur-[1px]" role="dialog" aria-modal="true">
            <div class="max-h-[90vh] w-1/2 min-w-0 max-w-full overflow-y-auto rounded-xl border border-emerald-100 bg-white p-5 shadow-2xl max-sm:w-full dark:border-emerald-900/40 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-3 py-2">
                    <p class="text-lg font-semibold text-white">@lang('admin::app.seller.wallet.withdraw')</p>
                    <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-800" onclick="closeWithdrawModal()" aria-label="Close">&times;</button>
                </div>

                <div id="withdraw-step-methods" class="rounded-lg border border-emerald-100 bg-emerald-50/40 p-3 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">@lang('admin::app.seller.wallet.choose-withdraw-method')</p>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ($depositMethods as $m)
                            <button
                                type="button"
                                class="rounded-md border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-left text-sm font-medium text-emerald-700 hover:border-emerald-400 hover:bg-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-300 dark:hover:border-emerald-600"
                                data-code="{{ $m->code }}"
                                data-name="{{ $m->name }}"
                                onclick="selectWithdrawMethod(this)"
                            >
                                {{ $m->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="withdraw-step-form" class="hidden rounded-lg border border-emerald-100 bg-emerald-50/40 p-3 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                    <button type="button" class="mb-3 text-sm font-medium text-emerald-600 hover:underline" onclick="withdrawBackToMethods()">@lang('admin::app.seller.wallet.back')</button>
                    <p class="mb-4 text-sm font-semibold text-emerald-700 dark:text-emerald-300" id="withdraw-method-title"></p>

                    <form method="post" action="{{ route('admin.wallet.withdraw-request') }}" class="grid gap-3">
                        @csrf
                        <input type="hidden" name="withdraw_method" id="withdraw-method-code" value="{{ old('withdraw_method') }}" />

                        <div id="withdraw-fields-crypto" class="hidden grid gap-3">
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.destination-address')</label>
                                <input type="text" name="destination_address" value="{{ old('destination_address') }}" class="w-full rounded-md border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none dark:border-emerald-900/40 dark:bg-gray-900 dark:text-white" />
                                @error('destination_address')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.address-label')</label>
                                <input type="text" name="address_label" value="{{ old('address_label') }}" placeholder="e.g. TRC20" class="w-full rounded-md border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none dark:border-emerald-900/40 dark:bg-gray-900 dark:text-white" />
                            </div>
                        </div>

                        <div id="withdraw-fields-bank" class="hidden grid gap-3">
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.bank-name')</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full rounded-md border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none dark:border-emerald-900/40 dark:bg-gray-900 dark:text-white" />
                                @error('bank_name')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.account-number')</label>
                                <input type="text" name="account_number" value="{{ old('account_number') }}" class="w-full rounded-md border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none dark:border-emerald-900/40 dark:bg-gray-900 dark:text-white" />
                                @error('account_number')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs text-gray-500">@lang('admin::app.seller.wallet.amount-usdt')</label>
                            <input type="number" step="0.01" min="0" name="amount" required value="{{ old('amount') }}" class="w-full rounded-md border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none dark:border-emerald-900/40 dark:bg-gray-900 dark:text-white" />
                            @error('amount')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <button
                            type="submit"
                            class="w-full rounded-md px-4 py-2 text-sm font-semibold text-white shadow-md transition"
                            style="background: linear-gradient(90deg, #059669 0%, #0d9488 100%) !important;"
                        >
                            @lang('admin::app.seller.wallet.submit-withdraw')
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mb-3 flex gap-2">
            <a
                href="{{ route('admin.wallet.index', ['wallet_type' => 'deposit']) }}"
                class="{{ ($walletType ?? 'all') === 'deposit' ? 'seller-pill seller-pill--blue' : 'rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 dark:border-gray-700 dark:text-gray-300' }}"
            >
                @lang('admin::app.seller.wallet.deposit')
            </a>
            <a
                href="{{ route('admin.wallet.index', ['wallet_type' => 'withdraw']) }}"
                class="{{ ($walletType ?? 'all') === 'withdraw' ? 'seller-pill seller-pill--blue' : 'rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 dark:border-gray-700 dark:text-gray-300' }}"
            >
                @lang('admin::app.seller.wallet.withdraw')
            </a>
            <a
                href="{{ route('admin.wallet.index', ['wallet_type' => 'all']) }}"
                class="{{ ($walletType ?? 'all') === 'all' ? 'seller-pill seller-pill--green' : 'rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 dark:border-gray-700 dark:text-gray-300' }}"
            >
                @lang('admin::app.seller-panel.filters.all')
            </a>
        </div>

        <x-admin::seller.responsive-table class="seller-filter-card overflow-hidden p-0">
            <x-slot:table>
                <div class="overflow-x-auto">
                    <table class="seller-data-table !border-0">
                    <thead>
                        <tr>
                            <th>@lang('admin::app.seller.wallet.col-type')</th>
                            <th>@lang('admin::app.seller.wallet.col-status')</th>
                            <th>@lang('admin::app.seller.wallet.col-amount')</th>
                            <th>@lang('admin::app.seller.wallet.col-balance-after')</th>
                            <th>@lang('admin::app.seller.wallet.col-remarks')</th>
                            <th>@lang('admin::app.seller.wallet.col-time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $t)
                            @php
                                $walletMeta = \Webkul\Admin\Support\SellerWalletTransactionDisplay::meta($t);
                            @endphp
                            <tr>
                                <td>{{ $walletMeta['type_label'] }}</td>
                                <td>{{ $t->status ?? '—' }}</td>
                                <td>
                                    {{ core()->formatPrice($t->amount) }}
                                    <span class="seller-pill seller-pill--blue ml-1 text-[10px]">USDT</span>
                                </td>
                                <td>{{ core()->formatPrice($t->balance_after ?? 0) }}</td>
                                <td>{{ $t->description ?? '—' }}</td>
                                <td class="whitespace-nowrap">{{ $t->created_at?->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500">@lang('admin::app.seller.fund-record.empty')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </x-slot:table>

            <x-slot:cards>
                @forelse ($transactions as $t)
                    @php
                        $walletMeta = \Webkul\Admin\Support\SellerWalletTransactionDisplay::meta($t);
                    @endphp
                    <article class="seller-mobile-card">
                        <div class="seller-mobile-card__header">
                            <p class="seller-mobile-card__title">{{ $walletMeta['type_label'] }}</p>
                            <span class="text-xs text-gray-500">{{ $t->created_at?->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="seller-mobile-card__rows">
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.wallet.col-status')">
                                {{ $t->status ?? '—' }}
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.wallet.col-amount')">
                                {{ core()->formatPrice($t->amount) }}
                                <span class="seller-pill seller-pill--blue ml-1 text-[10px]">USDT</span>
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.wallet.col-balance-after')">
                                {{ core()->formatPrice($t->balance_after ?? 0) }}
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field :label="__('admin::app.seller.wallet.col-remarks')">
                                {{ $t->description ?? '—' }}
                            </x-admin::seller.mobile-card-field>
                        </div>
                    </article>
                @empty
                    <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500">@lang('admin::app.seller.fund-record.empty')</p>
                @endforelse
            </x-slot:cards>

            <x-slot:footer>
                <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
                    {{ $transactions->links() }}
                </div>
            </x-slot:footer>
        </x-admin::seller.responsive-table>
    </x-admin::seller.panel>

    @include('admin::components.seller.verify-password-modal')

    @push('scripts')
        <script>
            function openDepositModal() {
                document.getElementById('modal-deposit').classList.remove('hidden');
                document.getElementById('modal-deposit').classList.add('flex');
            }

            window.sellerWalletOpenDeposit = function () {
                if (typeof window.sellerVerifyPasswordThen === 'function') {
                    window.sellerVerifyPasswordThen(function () {
                        openDepositModal();
                    });
                } else {
                    openDepositModal();
                }
            };

            window.sellerWalletOpenWithdraw = function () {
                if (typeof window.sellerVerifyPasswordThen === 'function') {
                    window.sellerVerifyPasswordThen(function () {
                        openWithdrawModal();
                    });
                } else {
                    openWithdrawModal();
                }
            };
            function closeDepositModal() {
                document.getElementById('modal-deposit').classList.add('hidden');
                document.getElementById('modal-deposit').classList.remove('flex');
            }
            function selectDepositMethod(el) {
                const isBank = el.dataset.isBank === '1';
                const heading = document.getElementById('deposit-method-heading');
                const cryptoFields = document.getElementById('deposit-crypto-fields');
                const addressWrap = document.getElementById('deposit-method-address-wrap');
                const bankNotice = document.getElementById('deposit-bank-notice');

                if (isBank) {
                    heading.classList.add('hidden');
                    cryptoFields.classList.add('hidden');
                    addressWrap.classList.add('hidden');
                    bankNotice.classList.remove('hidden');
                } else {
                    document.getElementById('deposit-payment-method').value = el.dataset.code;
                    heading.classList.remove('hidden');
                    cryptoFields.classList.remove('hidden');
                    bankNotice.classList.add('hidden');
                    addressWrap.classList.remove('hidden');
                    document.getElementById('deposit-method-title').textContent = el.dataset.name;
                    document.getElementById('deposit-method-network').textContent = el.dataset.network
                        ? '{{ __('admin::app.seller.wallet.network') }}: ' + el.dataset.network
                        : '';
                    document.getElementById('deposit-method-address').textContent = el.dataset.address || '';
                }
                document.getElementById('deposit-step-methods').classList.add('hidden');
                document.getElementById('deposit-step-form').classList.remove('hidden');
            }
            function depositBackToMethods() {
                document.getElementById('deposit-step-form').classList.add('hidden');
                document.getElementById('deposit-step-methods').classList.remove('hidden');
            }

            function openWithdrawModal() {
                document.getElementById('modal-withdraw').classList.remove('hidden');
                document.getElementById('modal-withdraw').classList.add('flex');
            }
            function closeWithdrawModal() {
                document.getElementById('modal-withdraw').classList.add('hidden');
                document.getElementById('modal-withdraw').classList.remove('flex');
            }
            function selectWithdrawMethod(el) {
                const code = el.dataset.code;
                document.getElementById('withdraw-method-code').value = code;
                document.getElementById('withdraw-method-title').textContent = el.dataset.name;
                document.getElementById('withdraw-fields-crypto').classList.add('hidden');
                document.getElementById('withdraw-fields-bank').classList.add('hidden');
                if (code === 'BANK_CARD') {
                    document.getElementById('withdraw-fields-bank').classList.remove('hidden');
                } else {
                    document.getElementById('withdraw-fields-crypto').classList.remove('hidden');
                }
                document.getElementById('withdraw-step-methods').classList.add('hidden');
                document.getElementById('withdraw-step-form').classList.remove('hidden');
            }
            function withdrawBackToMethods() {
                document.getElementById('withdraw-step-form').classList.add('hidden');
                document.getElementById('withdraw-step-methods').classList.remove('hidden');
            }

            @if ($errors->any() && old('payment_method'))
                openDepositModal();
                document.getElementById('deposit-step-methods').classList.add('hidden');
                document.getElementById('deposit-step-form').classList.remove('hidden');
                document.querySelector('[data-code="{{ old('payment_method') }}"]')?.click();
            @endif
            @if ($errors->any() && old('withdraw_method'))
                openWithdrawModal();
                document.getElementById('withdraw-step-methods').classList.add('hidden');
                document.getElementById('withdraw-step-form').classList.remove('hidden');
                document.querySelector('#modal-withdraw [data-code="{{ old('withdraw_method') }}"]')?.click();
            @endif
        </script>
    @endpush
</x-admin::layouts>
