@php
    $allowedMenuKeys = config('seller-panel.sidebar_allowed_menu_keys', []);
    if (! is_array($allowedMenuKeys)) {
        $allowedMenuKeys = [];
    }
@endphp

@foreach (menu()->getItems('admin') as $menuItem)
    @if (! in_array($menuItem->getKey(), $allowedMenuKeys, true))
        @continue
    @endif

    @if ($menuItem->haveChildren())
        @php
            $visibleChildren = $menuItem->getChildren()->filter(function ($sub) use ($allowedMenuKeys) {
                return in_array($sub->getKey(), $allowedMenuKeys, true);
            });
        @endphp
        @if ($visibleChildren->isEmpty())
            @continue
        @endif

        <details
            class="mb-2 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
            @if ($menuItem->isActive()) open @endif
        >
            <summary
                class="flex cursor-pointer list-none items-center gap-2 px-3 py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-100 [&::-webkit-details-marker]:hidden"
            >
                <span class="{{ $menuItem->getIcon() }} shrink-0 text-xl"></span>
                <span class="min-w-0 flex-1">{{ $menuItem->getName() }}</span>
                <span class="shrink-0 text-xs text-gray-500" aria-hidden="true">▾</span>
            </summary>

            <div class="flex flex-col gap-1 border-t border-gray-100 px-2 py-2 dark:border-gray-800">
                @foreach ($visibleChildren as $subMenuItem)
                    <a
                        href="{{ $subMenuItem->getUrl() }}"
                        class="{{ $subMenuItem->isActive() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300' : 'text-gray-700 dark:text-gray-200' }} rounded-md px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800"
                        onclick="if (typeof closeAdminMobileSidebar === 'function') { closeAdminMobileSidebar(); }"
                    >
                        {{ $subMenuItem->getName() }}
                    </a>
                @endforeach
            </div>
        </details>
    @else
        <a
            href="{{ $menuItem->getUrl() }}"
            class="{{ $menuItem->isActive() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950' : 'text-gray-800 dark:text-gray-100' }} mb-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline hover:bg-gray-50 dark:hover:bg-gray-800"
            onclick="if (typeof closeAdminMobileSidebar === 'function') { closeAdminMobileSidebar(); }"
        >
            <span class="{{ $menuItem->getIcon() }} shrink-0 text-xl"></span>
            <span>{{ $menuItem->getName() }}</span>
        </a>
    @endif
@endforeach
