<v-seller-edit
    :customer="customer"
    @update-customer="updateCustomer"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-seller-edit>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-seller-edit-template"
    >
        @if (bouncer()->hasPermission('sellers.all.edit'))
            <div
                class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
                @click="$refs.sellerEditModal.toggle()"
            >
                @lang('superadmin::app.customers.customers.view.edit.edit-btn')
            </div>
        @endif

        <x-superadmin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form
                @submit="handleSubmit($event, edit)"
                ref="sellerEditForm"
            >
                <x-superadmin::modal ref="sellerEditModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('superadmin::app.customers.customers.view.edit.title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex gap-4 max-sm:flex-wrap">
                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.customers.customers.view.edit.first-name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="first_name"
                                    id="seller_first_name"
                                    ::value="customer.first_name"
                                    rules="required"
                                    :label="trans('superadmin::app.customers.customers.view.edit.first-name')"
                                    :placeholder="trans('superadmin::app.customers.customers.view.edit.first-name')"
                                />

                                <x-superadmin::form.control-group.error control-name="first_name" />
                            </x-superadmin::form.control-group>

                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.customers.customers.view.edit.last-name')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="text"
                                    name="last_name"
                                    ::value="customer.last_name"
                                    id="seller_last_name"
                                    rules="required"
                                    :label="trans('superadmin::app.customers.customers.view.edit.last-name')"
                                    :placeholder="trans('superadmin::app.customers.customers.view.edit.last-name')"
                                />

                                <x-superadmin::form.control-group.error control-name="last_name" />
                            </x-superadmin::form.control-group>
                        </div>

                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.customers.customers.view.edit.email')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="email"
                                name="email"
                                ::value="customer.email"
                                id="seller_email"
                                rules="required|email"
                                :label="trans('superadmin::app.customers.customers.view.edit.email')"
                                placeholder="email@example.com"
                            />

                            <x-superadmin::form.control-group.error control-name="email" />
                        </x-superadmin::form.control-group>

                        @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code'))
                        <x-superadmin::form.control-group>
                            <x-superadmin::form.control-group.label class="required">
                                @lang('superadmin::app.sellers.edit.referral-code')
                            </x-superadmin::form.control-group.label>

                            <p class="mb-2 text-xs text-gray-600 dark:text-gray-400">
                                @lang('superadmin::app.sellers.edit.referral-code-hint')
                            </p>

                            <x-superadmin::form.control-group.control
                                type="text"
                                name="referral_code"
                                id="seller_referral_code"
                                ::value="customer.referral_code"
                                rules="required"
                                :label="trans('superadmin::app.sellers.edit.referral-code')"
                                :placeholder="trans('superadmin::app.sellers.edit.referral-code')"
                            />

                            <x-superadmin::form.control-group.error control-name="referral_code" />
                        </x-superadmin::form.control-group>
                        @endif

                        @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'seller_level'))
                        <x-superadmin::form.control-group class="mb-2.5">
                            <x-superadmin::form.control-group.label class="required">
                                Seller level
                            </x-superadmin::form.control-group.label>

                            <p class="mb-2 text-xs text-gray-600 dark:text-gray-400">
                                Controls allowed commission percentage range in the seller product warehouse and store.
                            </p>

                            <select
                                name="seller_level"
                                id="seller_seller_level"
                                class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-100"
                                rules="required"
                            >
                                @foreach (\Webkul\User\Support\SellerCommissionPercentRules::LEVELS as $level)
                                    <option
                                        value="{{ $level }}"
                                        {{ \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level ?? null) === $level ? 'selected' : '' }}
                                    >
                                        {{ $level }}
                                    </option>
                                @endforeach
                            </select>

                            <x-superadmin::form.control-group.error control-name="seller_level" />
                        </x-superadmin::form.control-group>
                        @endif

                        <x-superadmin::form.control-group class="!mb-0">
                            <x-superadmin::form.control-group.label>
                                @lang('superadmin::app.marketing.promotions.cart-rules.edit.status')
                            </x-superadmin::form.control-group.label>

                            <x-superadmin::form.control-group.control
                                type="hidden"
                                name="status"
                                value="0"
                            />

                            <x-superadmin::form.control-group.control
                                type="switch"
                                name="status"
                                :value="1"
                                :label="trans('superadmin::app.marketing.promotions.cart-rules.edit.status')"
                                ::checked="customer.status"
                            />
                        </x-superadmin::form.control-group>

                        <div class="mt-4 flex gap-4 max-sm:flex-wrap">
                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label>
                                    Wallet Balance
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="number"
                                    step="0.01"
                                    name="wallet_balance"
                                    ::value="customer.wallet_balance"
                                    label="Wallet Balance"
                                    placeholder="0.00"
                                />

                                <x-superadmin::form.control-group.error control-name="wallet_balance" />
                            </x-superadmin::form.control-group>

                            <x-superadmin::form.control-group class="mb-2.5 w-full">
                                <x-superadmin::form.control-group.label>
                                    Credit Score (%)
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="number"
                                    name="credit_score"
                                    min="0"
                                    max="100"
                                    ::value="customer.credit_score"
                                    label="Credit Score"
                                    placeholder="100"
                                />

                                <x-superadmin::form.control-group.error control-name="credit_score" />
                            </x-superadmin::form.control-group>
                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label>
                                    @lang('superadmin::app.sellers.edit.max-visible')
                                </x-superadmin::form.control-group.label>

                                <p class="mb-2 text-xs text-gray-600 dark:text-gray-400">
                                    @lang('superadmin::app.sellers.edit.max-visible-hint')
                                </p>

                                <x-superadmin::form.control-group.control
                                    type="number"
                                    name="max_visible_products"
                                    id="seller_max_visible_products"
                                    min="0"
                                    ::value="customer.max_visible_products ?? 0"
                                    :label="trans('superadmin::app.sellers.edit.max-visible')"
                                    placeholder="0"
                                />

                                <x-superadmin::form.control-group.error control-name="max_visible_products" />
                            </x-superadmin::form.control-group>
                        </div>
                    </x-slot>

                    <x-slot:footer>
                        <x-superadmin::button
                            button-type="submit"
                            class="primary-button justify-center"
                            :title="trans('superadmin::app.customers.customers.view.edit.save-btn')"
                            ::loading="isLoading"
                            ::disabled="isLoading"
                        />
                    </x-slot>
                </x-superadmin::modal>
            </form>
        </x-superadmin::form>
    </script>

    <script type="module">
        window.app.component('v-seller-edit', {
            template: '#v-seller-edit-template',

            props: ['customer'],

            emits: ['update-customer'],

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                edit(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    let formData = new FormData(this.$refs.sellerEditForm);

                    formData.append('_method', 'put');

                    this.$axios.post('{{ route('superadmin.sellers.profile.update', $seller->id) }}', formData)
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emit('update-customer', response.data.data);

                            resetForm();

                            this.isLoading = false;

                            this.$refs.sellerEditModal.close();
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response?.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
