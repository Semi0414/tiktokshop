<x-superadmin::layouts>
    <x-slot:title>
        Seller Applications
    </x-slot>

    <div class="mb-5 flex items-center justify-between gap-4">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Seller Applications
        </p>
    </div>

    <div class="rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-collapse">
                <thead>
                    <tr class="text-left text-sm text-gray-600 dark:text-gray-300">
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">ID</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Shop Name</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Country</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Email/Mobile</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Status</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Created At</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 dark:text-gray-200">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                            <td class="border-b border-gray-100 px-3 py-2">#{{ $app->id }}</td>
                            <td class="border-b border-gray-100 px-3 py-2">{{ $app->shop_name }}</td>
                            <td class="border-b border-gray-100 px-3 py-2">{{ core()->country_name($app->country) ?: $app->country }}</td>
                            <td class="border-b border-gray-100 px-3 py-2">
                                {{ $app->email ?: $app->mobile }}
                            </td>
                            <td class="border-b border-gray-100 px-3 py-2">
                                <span class="label-{{ $app->status === 'approved' ? 'active' : ($app->status === 'rejected' ? 'canceled' : 'pending') }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="border-b border-gray-100 px-3 py-2">
                                {{ $app->created_at }}
                            </td>
                            <td class="border-b border-gray-100 px-3 py-2">
                                <a
                                    href="{{ route('superadmin.sellers.applications.view', $app->id) }}"
                                    class="primary-button inline-flex justify-center"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-gray-500">
                                No applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
</x-superadmin::layouts>

