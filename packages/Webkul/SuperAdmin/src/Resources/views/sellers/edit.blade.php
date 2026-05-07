<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.components.layouts.sidebar.sellers')
    </x-slot>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <a
            href="{{ route('superadmin.sellers.view', $seller->id) }}"
            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            @lang('superadmin::app.customers.customers.view.back-btn')
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
        <p class="mb-4 text-lg font-bold text-gray-800 dark:text-white">
            {{ $seller->name }}
        </p>

        <form method="post" action="{{ route('superadmin.sellers.update', $seller->id) }}" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('superadmin::app.customers.customers.view.edit.first-name')
                    </label>
                    <input
                        type="text"
                        name="first_name"
                        required
                        value="{{ old('first_name', trim(strtok((string) $seller->name, ' '))) }}"
                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    @error('first_name')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('superadmin::app.customers.customers.view.edit.last-name')
                    </label>
                    <input
                        type="text"
                        name="last_name"
                        required
                        value="{{ old('last_name', trim(\Illuminate\Support\Str::after((string) $seller->name, ' '))) }}"
                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    @error('last_name')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('superadmin::app.customers.customers.view.edit.email')
                    </label>
                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email', $seller->email) }}"
                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    @error('email')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'referral_code'))
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                            @lang('superadmin::app.sellers.edit.referral-code')
                        </label>
                        <input
                            type="text"
                            name="referral_code"
                            required
                            value="{{ old('referral_code', $seller->referral_code) }}"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        >
                        @error('referral_code')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                @if (\Illuminate\Support\Facades\Schema::hasColumn('seller', 'seller_level'))
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Seller level
                        </label>
                        <select
                            name="seller_level"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        >
                            @foreach (\Webkul\User\Support\SellerCommissionPercentRules::LEVELS as $level)
                                <option value="{{ $level }}" {{ old('seller_level', \Webkul\User\Support\SellerCommissionPercentRules::normalizeLevel($seller->seller_level)) === $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('seller_level')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('Wallet Balance')
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="wallet_balance"
                        required
                        value="{{ old('wallet_balance', $seller->wallet_balance ?? 0) }}"
                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    @error('wallet_balance')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('Credit Score (%)')
                    </label>
                    <input
                        type="number"
                        min="0"
                        max="100"
                        name="credit_score"
                        required
                        value="{{ old('credit_score', $seller->credit_score ?? 100) }}"
                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    @error('credit_score')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        @lang('superadmin::app.marketing.promotions.cart-rules.edit.status')
                    </label>

                    <select
                        name="status"
                        class="w-full max-w-xs rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                        <option value="1" {{ (string) old('status', (string) ((int) $seller->status)) === '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ (string) old('status', (string) ((int) $seller->status)) === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
            </div>

            <input
                type="hidden"
                name="max_visible_products"
                value="{{ old('max_visible_products', $seller->max_visible_products ?? 0) }}"
            >

            @if (! empty($sellerApplication))
                <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-800">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Application Details</p>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Shop name</label>
                            <input type="text" name="shop_name" value="{{ old('shop_name', $sellerApplication->shop_name) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('shop_name')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Country</label>
                            <select name="country" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                <option value="">Select country</option>
                                @foreach (core()->countries() as $country)
                                    <option value="{{ $country->code }}" {{ old('country', $sellerApplication->country) === $country->code ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Shop address</label>
                            <input type="text" name="shop_address" value="{{ old('shop_address', $sellerApplication->shop_address) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('shop_address')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">ID/Passport Number</label>
                            <input type="text" name="id_passport_number" value="{{ old('id_passport_number', $sellerApplication->id_passport_number) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('id_passport_number')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Legal Name</label>
                            <input type="text" name="legal_name" value="{{ old('legal_name', $sellerApplication->legal_name) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('legal_name')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Verify Type</label>
                            <select name="verify_type" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                <option value="email" {{ old('verify_type', $sellerApplication->verify_type) === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="mobile" {{ old('verify_type', $sellerApplication->verify_type) === 'mobile' ? 'selected' : '' }}>Mobile</option>
                            </select>
                            @error('verify_type')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Application Email</label>
                            <input type="email" name="application_email" value="{{ old('application_email', $sellerApplication->email) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('application_email')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Mobile</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $sellerApplication->mobile) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('mobile')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Invite Code</label>
                            <input type="text" name="invite_code" value="{{ old('invite_code', $sellerApplication->invite_code) }}" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            @error('invite_code')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Application Status</label>
                            <select name="application_status" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                <option value="pending" {{ old('application_status', $sellerApplication->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('application_status', $sellerApplication->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('application_status', $sellerApplication->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('application_status')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div> -->
                    </div>

                    <p class="mb-4 mt-6 text-base font-semibold text-gray-800 dark:text-white">Update Documents</p>

                    <div class="grid gap-4 md:grid-cols-4">
                        @foreach ([
                            'shop_logo' => 'Shop Logo',
                            'document_front' => 'Document Front',
                            'document_back' => 'Document Back',
                            'document_selfie' => 'Document Selfie',
                        ] as $fileKey => $fileLabel)
                            @php
                                $existingPath = (string) ($sellerApplication->{$fileKey} ?? '');
                            @endphp
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ $fileLabel }}</p>

                                <label
                                    class="drag-upload-box group relative flex h-28 w-full cursor-pointer items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 transition-all hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-950 dark:hover:border-blue-500"
                                    data-input-id="input_{{ $fileKey }}"
                                >
                                    <div
                                        id="placeholder_{{ $fileKey }}"
                                        class="text-center {{ $existingPath !== '' ? 'hidden' : '' }}"
                                    >
                                        <span class="icon-image text-2xl text-gray-500 dark:text-gray-300"></span>
                                        <p class="mt-1 text-xs font-medium text-gray-600 dark:text-gray-300">Drag & drop</p>
                                        <p class="text-[11px] text-gray-400">JPG, PNG, WEBP</p>
                                    </div>

                                    <img
                                        id="preview_{{ $fileKey }}"
                                        src="{{ $existingPath !== '' ? \Illuminate\Support\Facades\Storage::url($existingPath) : '' }}"
                                        alt="{{ $fileLabel }}"
                                        class="h-full w-full rounded object-cover {{ $existingPath !== '' ? '' : 'hidden' }}"
                                    >

                                    <input
                                        id="input_{{ $fileKey }}"
                                        type="file"
                                        name="{{ $fileKey }}"
                                        accept="image/*"
                                        class="hidden"
                                    >
                                </label>

                                <p
                                    id="name_{{ $fileKey }}"
                                    class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ $existingPath !== '' ? basename($existingPath) : 'No file selected' }}
                                </p>

                                @error($fileKey)
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="primary-button mt-6">
                @lang('superadmin::app.sellers.edit.save')
            </button>
        </form>
    </div>

    @if (! empty($sellerApplication))
        <script>
            document.querySelectorAll('.drag-upload-box').forEach((box) => {
                const inputId = box.getAttribute('data-input-id');
                const input = document.getElementById(inputId);
                const nameLabel = document.getElementById('name_' + inputId.replace('input_', ''));
                const key = inputId.replace('input_', '');
                const preview = document.getElementById('preview_' + key);
                const placeholder = document.getElementById('placeholder_' + key);

                if (! input || ! nameLabel) return;

                const setFileName = () => {
                    const file = input.files?.[0];

                    nameLabel.textContent = file?.name || 'No file selected';

                    if (! file || ! preview || ! placeholder) return;

                    const reader = new FileReader();

                    reader.onload = (event) => {
                        preview.src = event.target?.result?.toString() || '';
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    };

                    reader.readAsDataURL(file);
                };

                box.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    box.classList.add('border-blue-500', 'bg-blue-50');
                });

                box.addEventListener('dragleave', () => {
                    box.classList.remove('border-blue-500', 'bg-blue-50');
                });

                box.addEventListener('drop', (e) => {
                    e.preventDefault();
                    box.classList.remove('border-blue-500', 'bg-blue-50');

                    if (e.dataTransfer?.files?.length) {
                        input.files = e.dataTransfer.files;
                        setFileName();
                    }
                });

                input.addEventListener('change', setFileName);
            });
        </script>
    @endif
</x-superadmin::layouts>
