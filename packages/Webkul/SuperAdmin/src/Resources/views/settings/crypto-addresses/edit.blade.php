<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.crypto-addresses.edit-title')
    </x-slot>

    <div class="mb-6 flex flex-col gap-2">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.crypto-addresses.edit-title')
        </p>

        <a
            href="{{ route('superadmin.settings.crypto_addresses.index') }}"
            class="text-sm text-blue-600 hover:underline dark:text-blue-400"
        >
            @lang('superadmin::app.settings.crypto-addresses.back')
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form
            method="post"
            action="{{ route('superadmin.settings.crypto_addresses.update', $address->id) }}"
            class="grid gap-4 md:grid-cols-2"
        >
            @csrf
            @method('PUT')

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.settings.crypto-addresses.network')
                </label>
                <select
                    name="network_type"
                    required
                    class="w-full max-w-md rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-950 dark:text-white"
                >
                    @foreach ($networkOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('network_type', $address->network_type) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('network_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.settings.crypto-addresses.address')
                </label>
                <textarea
                    name="address"
                    required
                    rows="3"
                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 font-mono text-sm text-gray-800 dark:border-gray-800 dark:bg-gray-950 dark:text-white"
                    placeholder="@lang('superadmin::app.settings.crypto-addresses.address-placeholder')"
                >{{ old('address', $address->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    @lang('superadmin::app.settings.crypto-addresses.label-optional')
                </label>
                <input
                    type="text"
                    name="label"
                    value="{{ old('label', $address->label) }}"
                    class="w-full max-w-md rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-950 dark:text-white"
                />
                @error('label')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <button type="submit" class="primary-button">
                    @lang('superadmin::app.settings.crypto-addresses.update')
                </button>
                <a
                    href="{{ route('superadmin.settings.crypto_addresses.index') }}"
                    class="secondary-button"
                >
                    @lang('superadmin::app.settings.crypto-addresses.back')
                </a>
            </div>
        </form>
    </div>
</x-superadmin::layouts>
