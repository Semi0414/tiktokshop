<x-superadmin::layouts>
    <x-slot:title>
        @lang('superadmin::app.settings.crypto-addresses.title')
    </x-slot>

    <div class="mb-6 flex flex-col gap-2">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.crypto-addresses.title')
        </p>

        <p class="text-sm text-gray-600 dark:text-gray-300">
            @lang('superadmin::app.settings.crypto-addresses.description')
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
            @lang('superadmin::app.settings.crypto-addresses.add-title')
        </p>

        <form
            method="post"
            action="{{ route('superadmin.settings.crypto_addresses.store') }}"
            class="grid gap-4 md:grid-cols-2"
        >
            @csrf

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
                        <option value="{{ $value }}">{{ $label }}</option>
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
                >{{ old('address') }}</textarea>
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
                    value="{{ old('label') }}"
                    class="w-full max-w-md rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 dark:border-gray-800 dark:bg-gray-950 dark:text-white"
                />
                @error('label')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="primary-button">
                    @lang('superadmin::app.settings.crypto-addresses.save')
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="border-b border-gray-200 px-6 py-4 text-base font-semibold text-gray-800 dark:border-gray-800 dark:text-white">
            @lang('superadmin::app.settings.crypto-addresses.saved-title')
        </p>

        @if ($addresses->isEmpty())
            <p class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                @lang('superadmin::app.settings.crypto-addresses.empty')
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.settings.crypto-addresses.network')
                            </th>
                            <th class="px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.settings.crypto-addresses.address')
                            </th>
                            <th class="px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.settings.crypto-addresses.label-optional')
                            </th>
                            <th class="px-6 py-3 text-right font-medium text-gray-700 dark:text-gray-300">
                                @lang('superadmin::app.settings.crypto-addresses.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($addresses as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                                <td class="px-6 py-3 text-gray-800 dark:text-gray-200">
                                    {{ $networkOptions[$row->network_type] ?? $row->network_type }}
                                </td>
                                <td class="max-w-md break-all px-6 py-3 font-mono text-xs text-gray-700 dark:text-gray-300">
                                    {{ $row->address }}
                                </td>
                                <td class="px-6 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $row->label ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a
                                            href="{{ route('superadmin.settings.crypto_addresses.edit', $row->id) }}"
                                            class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            @lang('superadmin::app.settings.crypto-addresses.edit')
                                        </a>
                                        <form
                                            method="post"
                                            action="{{ route('superadmin.settings.crypto_addresses.destroy', $row->id) }}"
                                            class="inline"
                                            onsubmit="return confirm('@lang('superadmin::app.settings.crypto-addresses.delete-confirm')');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-sm text-red-600 hover:underline dark:text-red-400"
                                            >
                                                @lang('superadmin::app.settings.crypto-addresses.delete')
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-superadmin::layouts>
