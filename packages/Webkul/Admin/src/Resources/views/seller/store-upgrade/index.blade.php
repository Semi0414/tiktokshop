<x-admin::layouts>
    <x-slot:title>
        Store Upgrade
    </x-slot>

    <x-admin::seller.panel :showWorkspaceTabs="false" :breadcrumb="[__('admin::app.components.layouts.sidebar.store-upgrade')]">

    <div class="mb-6 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">Store Upgrade Packages</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Choose the right growth plan for your store visibility.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-5 dark:border-blue-900 dark:from-gray-900 dark:to-gray-900">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Campaign Reach</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">35+</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Products can be promoted in premium campaign slots.</p>
        </div>

        <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 dark:border-emerald-900 dark:from-gray-900 dark:to-gray-900">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Traffic Support</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">5,000</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Estimated traffic lift from managed platform support.</p>
        </div>

        <div class="rounded-xl border border-violet-100 bg-gradient-to-br from-violet-50 to-white p-5 dark:border-violet-900 dark:from-gray-900 dark:to-gray-900">
            <p class="text-xs font-semibold uppercase tracking-wider text-violet-600">Cycle</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">30 Days</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">All packages run on a monthly billing cycle.</p>
        </div>
    </div>

    @php
        $packages = [
            [
                'title' => 'International promotion',
                'price' => '$599',
                'period' => '/ 30Days',
                'features' => ['35 promoted products', 'Priority listing support', 'High-intent global traffic'],
                'featured' => true,
            ],
            [
                'title' => 'Overseas promotion',
                'price' => '$399',
                'period' => '/ 30Days',
                'features' => ['25 promoted products', 'Traffic boost campaigns', 'Regional audience targeting'],
                'featured' => false,
            ],
            [
                'title' => 'Standard promotion',
                'price' => '$199',
                'period' => '/ 30Days',
                'features' => ['10 promoted products', 'Basic listing improvement', 'Entry level support'],
                'featured' => false,
            ],
        ];
    @endphp

    <div class="mt-6 grid gap-5 lg:grid-cols-3">
        @foreach ($packages as $p)
            <div class="rounded-xl border p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md {{ $p['featured'] ? 'border-blue-300 bg-blue-50/40 dark:border-blue-800 dark:bg-blue-950/20' : 'border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900' }}">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $p['title'] }}</p>

                    @if ($p['featured'])
                        <span class="rounded-full bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white">Popular</span>
                    @endif
                </div>

                <div class="mt-3 flex items-baseline gap-1">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $p['price'] }}</p>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $p['period'] }}</p>
                </div>

                <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    @foreach ($p['features'] as $feature)
                        <li class="flex items-start gap-2">
                            <span class="mt-1 inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <button type="button" class="{{ $p['featured'] ? 'primary-button' : 'secondary-button' }} mt-5 w-full justify-center" disabled>
                    Purchase Package
                </button>

                <p class="mt-2 text-xs text-gray-500">Purchase flow can be connected from seller billing module.</p>
            </div>
        @endforeach
    </div>
    </x-admin::seller.panel>
</x-admin::layouts>

