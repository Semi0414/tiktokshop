<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.cms.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.cms.index')" />

            <!-- Create New Pages Button -->
            @if (bouncer()->hasPermission('cms.create'))
                <a
                    href="{{ route('admin.cms.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.cms.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.cms.index.datagrid.id')</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.cms.index.datagrid.page-title')</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.cms.index.datagrid.url-key')</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">@lang('admin::app.cms.index.datagrid.channel')</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse (($pagesPaginator ?? null)?->items() ?? [] as $page)
                    <tr>
                        <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $page->id }}</td>
                        <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $page->page_title }}</td>
                        <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $page->url_key }}</td>
                        <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $page->channel_codes ?: '—' }}</td>
                        <td class="px-3 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('shop.cms.page', $page->url_key) }}" target="_blank" class="secondary-button !px-2.5 !py-1 text-xs">View</a>
                                @if (bouncer()->hasPermission('cms.edit'))
                                    <a href="{{ route('admin.cms.edit', $page->id) }}" class="primary-button !px-2.5 !py-1 text-xs">Edit</a>
                                @endif
                                @if (bouncer()->hasPermission('cms.delete'))
                                    <form method="post" action="{{ route('admin.cms.delete', $page->id) }}" onsubmit="return confirm('Delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="secondary-button !px-2.5 !py-1 text-xs">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            @lang('admin::app.components.datagrid.table.no-records-available')
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 flex items-center justify-between gap-3 text-xs text-gray-600 dark:text-gray-300">
            <span>
                Showing {{ $pagesPaginator->firstItem() ?? 0 }} to {{ $pagesPaginator->lastItem() ?? 0 }} of {{ $pagesPaginator->total() ?? 0 }}
            </span>
            <div>
                {{ ($pagesPaginator ?? null)?->links() }}
            </div>
        </div>
    </div>

</x-admin::layouts>
