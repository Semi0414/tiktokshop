@if (bouncer()->hasPermission('customers.customers.edit'))
    <button
        type="button"
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
        onclick="document.getElementById('customer-edit-dialog').showModal()"
    >
        @lang('superadmin::app.customers.customers.view.edit.edit-btn')
    </button>

    <dialog
        id="customer-edit-dialog"
        class="w-full max-w-2xl rounded-lg p-0 backdrop:bg-black/40"
    >
        <form
            id="customer-edit-form"
            class="bg-white p-4 dark:bg-gray-900"
        >
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="mb-4 flex items-center justify-between">
                <p class="text-lg font-bold text-gray-800 dark:text-white">
                    @lang('superadmin::app.customers.customers.view.edit.title')
                </p>

                <button
                    type="button"
                    class="text-gray-500 hover:text-gray-700"
                    onclick="document.getElementById('customer-edit-dialog').close()"
                >
                    X
                </button>
            </div>

            <div id="customer-edit-errors" class="mb-3 hidden rounded border border-red-200 bg-red-50 p-2 text-sm text-red-700"></div>

            <div class="grid gap-3">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.first-name')
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" name="first_name" value="{{ $customer->first_name }}" placeholder="@lang('superadmin::app.customers.customers.view.edit.first-name')">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.last-name')
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" name="last_name" value="{{ $customer->last_name }}" placeholder="@lang('superadmin::app.customers.customers.view.edit.last-name')">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.email')
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" name="email" value="{{ $customer->email }}" placeholder="email@example.com">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.contact-number')
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" name="phone" value="{{ $customer->phone }}" placeholder="@lang('superadmin::app.customers.customers.view.edit.contact-number')">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.date-of-birth')
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" type="date" name="date_of_birth" value="{{ $customer->date_of_birth }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.gender')
                    </label>
                    <select class="w-full rounded border px-3 py-2.5 text-sm" name="gender">
                        <option value="Male" @selected($customer->gender === 'Male')>@lang('superadmin::app.customers.customers.view.edit.male')</option>
                        <option value="Female" @selected($customer->gender === 'Female')>@lang('superadmin::app.customers.customers.view.edit.female')</option>
                        <option value="Other" @selected($customer->gender === 'Other')>@lang('superadmin::app.customers.customers.view.edit.other')</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.customers.customers.view.edit.customer-group')
                    </label>
                    <select class="w-full rounded border px-3 py-2.5 text-sm" name="customer_group_id">
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @selected((int) $customer->customer_group_id === (int) $group->id)>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @lang('superadmin::app.marketing.promotions.cart-rules.edit.status')
                    </label>
                    <select class="w-full rounded border px-3 py-2.5 text-sm" name="status">
                        <option value="1" @selected((int) $customer->status === 1)>@lang('superadmin::app.customers.customers.view.active')</option>
                        <option value="0" @selected((int) $customer->status === 0)>@lang('superadmin::app.customers.customers.view.inactive')</option>
                    </select>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="is_suspended" value="0">
                    <input type="checkbox" name="is_suspended" value="1" @checked($customer->is_suspended)>
                    @lang('superadmin::app.customers.customers.view.edit.suspended')
                </label>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Wallet Balance
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" type="number" step="0.01" name="wallet_balance" value="{{ $customer->wallet_balance ?? 0 }}" placeholder="Wallet Balance">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Credit Score
                    </label>
                    <input class="w-full rounded border px-3 py-2.5 text-sm" type="number" min="0" max="100" name="credit_score" value="{{ $customer->credit_score ?? 100 }}" placeholder="Credit Score">
                </div>

                @if (\Illuminate\Support\Facades\Schema::hasColumn('customers', 'referral_seller_id'))
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('superadmin::app.customers.customers.view.edit.referral-code')
                        </label>
                        <input class="w-full rounded border px-3 py-2.5 text-sm" name="referral_code" value="{{ $customer->seller_referral_code }}" placeholder="@lang('superadmin::app.customers.customers.view.edit.referral-code-placeholder')">
                    </div>
                @endif
            </div>

            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" class="transparent-button" onclick="document.getElementById('customer-edit-dialog').close()">
                    @lang('superadmin::app.customers.customers.view.back-btn')
                </button>

                <button type="submit" class="primary-button">
                    @lang('superadmin::app.customers.customers.view.edit.save-btn')
                </button>
            </div>
        </form>
    </dialog>

    @pushOnce('scripts')
        <script>
            (function () {
                const form = document.getElementById('customer-edit-form');
                const errorsBox = document.getElementById('customer-edit-errors');
                const dialog = document.getElementById('customer-edit-dialog');

                if (!form || !errorsBox || !dialog) {
                    return;
                }

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    errorsBox.classList.add('hidden');
                    errorsBox.innerHTML = '';

                    const formData = new FormData(form);

                    try {
                        const response = await fetch("{{ route('superadmin.customers.customers.update', $customer->id) }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            const messages = payload.errors
                                ? Object.values(payload.errors).flat()
                                : [payload.message || 'Validation failed'];

                            errorsBox.innerHTML = messages.map((msg) => `<div>${msg}</div>`).join('');
                            errorsBox.classList.remove('hidden');
                            return;
                        }

                        dialog.close();
                        window.location.reload();
                    } catch (error) {
                        errorsBox.innerHTML = '<div>Unable to update customer right now.</div>';
                        errorsBox.classList.remove('hidden');
                    }
                });
            })();
        </script>
    @endPushOnce
@endif
