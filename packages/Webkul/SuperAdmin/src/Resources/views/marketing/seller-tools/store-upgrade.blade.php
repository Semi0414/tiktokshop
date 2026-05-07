<x-superadmin::layouts>
    <x-slot:title>
        Store Upgrade
    </x-slot>

    <div class="mb-6 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">Shop Upgrade Package List</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Manage and review seller promotion upgrade packages.
            </p>
        </div>
    </div>

    <div class="rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
        <div class="grid gap-4 md:grid-cols-3">
            @php
                $packages = [
                    ['title' => 'International promotion', 'price' => '$599 / 30Days'],
                    ['title' => 'Overseas promotion', 'price' => '$399 / 30Days'],
                    ['title' => 'Standard promotion', 'price' => '$199 / 30Days'],
                ];
            @endphp

            @foreach ($packages as $p)
                <div class="rounded-md border border-gray-200 p-4 dark:border-gray-800">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        {{ $p['title'] }}
                    </p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $p['price'] }}
                    </p>
                    <button type="button" class="primary-button mt-4 w-full justify-center" disabled>
                        Purchase Package
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</x-superadmin::layouts>

