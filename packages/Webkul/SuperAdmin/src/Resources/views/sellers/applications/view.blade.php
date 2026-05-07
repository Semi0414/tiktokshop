<x-superadmin::layouts>
    <x-slot:title>
        Seller Application #{{ $application->id }}
    </x-slot>

    <div class="mb-5 flex items-center justify-between gap-4">
        <a
            href="{{ route('superadmin.sellers.index') }}"
            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            Back
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-[2fr_1fr]">
        <div class="rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
            <p class="mb-4 text-lg font-bold text-gray-800 dark:text-white">
                Business information
            </p>

            <div class="grid gap-3 text-sm text-gray-800 dark:text-gray-200">
                <p><span class="font-semibold">Shop name:</span> {{ $application->shop_name }}</p>
                <p><span class="font-semibold">Shop address:</span> {{ $application->shop_address }}</p>
                <p><span class="font-semibold">Country:</span> {{ core()->country_name($application->country) ?: $application->country }}</p>
                <p><span class="font-semibold">ID/passport number:</span> {{ $application->id_passport_number }}</p>
                <p><span class="font-semibold">Legal name:</span> {{ $application->legal_name }}</p>
                <p><span class="font-semibold">Verify type:</span> {{ ucfirst($application->verify_type) }}</p>
                <p><span class="font-semibold">Email:</span> {{ $application->email ?? '-' }}</p>
                <p><span class="font-semibold">Mobile:</span> {{ $application->mobile ?? '-' }}</p>
                <p><span class="font-semibold">Invite code:</span> {{ $application->invite_code ?? '-' }}</p>
            </div>
        </div>

        <div class="rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
            <p class="mb-4 text-lg font-bold text-gray-800 dark:text-white">
                Status
            </p>

            <p class="mb-3 text-sm text-gray-700 dark:text-gray-300">
                Current status:
                <span class="font-semibold">{{ ucfirst($application->status) }}</span>
            </p>

            <form method="post" action="{{ route('superadmin.sellers.applications.status', $application->id) }}">
                @csrf

                <x-superadmin::form.control-group>
                    <x-superadmin::form.control-group.label>
                        Change status
                    </x-superadmin::form.control-group.label>

                    <select
                        name="status"
                        class="flex min-h-[39px] w-full rounded-md border bg-white px-3 py-2 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                    >
                        <option value="pending" @selected($application->status === 'pending')>Pending</option>
                        <option value="approved" @selected($application->status === 'approved')>Approved</option>
                        <option value="rejected" @selected($application->status === 'rejected')>Rejected</option>
                    </select>
                </x-superadmin::form.control-group>

                <x-superadmin::button
                    button-type="submit"
                    class="primary-button mt-4 justify-center"
                    :title="'Save'"
                />
            </form>

            <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                Note: password is stored only as hash in applicant table. Creating actual seller login from this application is a separate manual or automated step.
            </p>
        </div>
    </div>

    <div class="mt-6 rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
        <p class="mb-3 text-lg font-bold text-gray-800 dark:text-white">
            Documents
        </p>

        <div class="grid gap-4 md:grid-cols-4">
            @if ($application->shop_logo)
                <div>
                    <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Shop logo</p>
                    <img
                        src="{{ Storage::url($application->shop_logo) }}"
                        alt="Shop logo"
                        class="max-h-40 rounded-md border border-gray-200 dark:border-gray-800"
                    >
                </div>
            @endif

            <div>
                <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Front of document</p>
                <img
                    src="{{ Storage::url($application->document_front) }}"
                    alt="Front document"
                    class="max-h-40 rounded-md border border-gray-200 dark:border-gray-800"
                >
            </div>

            <div>
                <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Back of document</p>
                <img
                    src="{{ Storage::url($application->document_back) }}"
                    alt="Back document"
                    class="max-h-40 rounded-md border border-gray-200 dark:border-gray-800"
                >
            </div>

            <div>
                <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Selfie with document</p>
                <img
                    src="{{ Storage::url($application->document_selfie) }}"
                    alt="Selfie"
                    class="max-h-40 rounded-md border border-gray-200 dark:border-gray-800"
                >
            </div>
        </div>
    </div>
</x-superadmin::layouts>

