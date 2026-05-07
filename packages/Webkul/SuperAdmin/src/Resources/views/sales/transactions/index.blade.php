<x-superadmin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('superadmin::app.sales.transactions.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.sales.transactions.index.title')
        </p>

        <x-superadmin::datagrid.export :src="route('superadmin.sales.transactions.index')" />
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-4 rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
        <p class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
            @lang('superadmin::app.sales.transactions.index.create.create-transaction')
        </p>

        <form
            method="post"
            action="{{ route('superadmin.sales.transactions.store') }}"
            class="grid gap-4 md:grid-cols-3"
        >
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.sales.transactions.index.create.invoice-id')
                </label>
                <input
                    type="text"
                    name="invoice_id"
                    required
                    value="{{ old('invoice_id') }}"
                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.sales.transactions.index.create.payment-method')
                </label>
                <select
                    name="payment_method"
                    required
                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                >
                    @foreach (($paymentMethods['payment_methods'] ?? []) as $method)
                        <option value="{{ $method['method'] }}" {{ old('payment_method') === $method['method'] ? 'selected' : '' }}>
                            {{ $method['method_title'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.sales.transactions.index.create.amount')
                </label>
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    name="amount"
                    required
                    value="{{ old('amount') }}"
                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                >
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="primary-button">
                    @lang('superadmin::app.sales.transactions.index.create.save-transaction')
                </button>
            </div>
        </form>
    </div>

    <div class="mt-4">
        <x-superadmin::datagrid.ssr
            :datagrid-payload="$datagridPayload"
            :src="route('superadmin.sales.transactions.index')"
            :isMultiRow="true"
        />
    </div>
</x-superadmin::layouts>
