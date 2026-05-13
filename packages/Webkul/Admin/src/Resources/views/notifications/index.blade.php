@php
    $tabs = [
        ['key' => 'all', 'query' => []],
        ['key' => 'pending', 'query' => ['status' => 'pending']],
        ['key' => 'pending_payment', 'query' => ['status' => 'pending_payment']],
        ['key' => 'processing', 'query' => ['status' => 'processing']],
        ['key' => 'canceled', 'query' => ['status' => 'canceled']],
        ['key' => 'completed', 'query' => ['status' => 'completed']],
        ['key' => 'closed', 'query' => ['status' => 'closed']],
    ];

    $statusMessageKey = function (?string $orderStatus): ?string {
        if (! $orderStatus) {
            return null;
        }

        return match ($orderStatus) {
            'pending' => 'admin::app.notifications.order-status-messages.pending',
            'pending_payment' => 'admin::app.notifications.order-status-messages.pending-payment',
            'processing' => 'admin::app.notifications.order-status-messages.processing',
            'canceled' => 'admin::app.notifications.order-status-messages.canceled',
            'completed' => 'admin::app.notifications.order-status-messages.completed',
            'closed' => 'admin::app.notifications.order-status-messages.closed',
            default => null,
        };
    };

    $statusIcon = function (string $orderStatus): string {
        return match ($orderStatus) {
            'pending' => 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600 dark:!text-amber-600',
            'pending_payment' => 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600 dark:!text-amber-600',
            'processing' => 'icon-sort-right rounded-full bg-green-100 text-2xl text-green-600 dark:!text-green-600',
            'canceled' => 'icon-cancel-1 rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
            'completed' => 'icon-done rounded-full bg-blue-100 text-2xl text-blue-600 dark:!text-blue-600',
            'closed' => 'icon-repeat rounded-full bg-red-100 text-2xl text-red-600 dark:!text-red-600',
            default => 'icon',
        };
    };

    $walletEventIcon = 'icon-information rounded-full bg-blue-100 text-2xl text-blue-600 dark:!text-blue-600';
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.notifications.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.before') !!}

    <main
        class="w-full min-w-0 max-w-full pb-8"
        aria-labelledby="notifications-page-heading"
    >
        <header class="mb-5">
            <h1 id="notifications-page-heading" class="pt-1.5 text-xl font-bold leading-7 text-gray-800 dark:text-white sm:text-2xl sm:leading-8">
                @lang('admin::app.notifications.title')
            </h1>
            <p id="notifications-page-desc" class="mt-1 max-w-3xl text-sm text-gray-600 dark:text-gray-300 sm:text-base">
                @lang('admin::app.notifications.description-text')
            </p>
        </header>

        <div
            class="w-full min-w-0"
            aria-describedby="notifications-page-desc"
        >
            <div class="box-shadow flex min-h-[320px] w-full min-w-0 max-w-full flex-col rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 sm:min-h-[calc(100vh-220px)]">
                <div class="min-h-0 min-w-0 flex-1">
                    <nav
                        id="notifications-filter-tablist"
                        class="journal-scroll flex overflow-x-auto overflow-y-hidden border-b dark:border-gray-800"
                        aria-label="@lang('admin::app.notifications.filter-tabs')"
                    >
                        @foreach ($tabs as $tab)
                            @php
                                $isActive = $currentStatus === $tab['key'];
                                $count = $tab['key'] === 'all'
                                    ? $totalAll + (int) ($nonOrderCount ?? 0)
                                    : ($statusCountsByStatus[$tab['key']] ?? 0);
                                $href = count($tab['query'])
                                    ? route('admin.notification.index', $tab['query'])
                                    : route('admin.notification.index');
                            @endphp
                            <a
                                href="{{ $href }}"
                                @class([
                                    'flex shrink-0 items-center gap-1.5 border-b-2 px-3 py-3 text-left sm:px-4 sm:py-4',
                                    'border-blue-600 dark:border-blue-600' => $isActive,
                                    'border-transparent hover:bg-gray-100 dark:hover:bg-gray-950' => ! $isActive,
                                ])
                                @if ($isActive) aria-current="page" @endif
                            >
                                <span class="whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 sm:text-base">
                                    @if ($tab['key'] === 'all')
                                        @lang('admin::app.notifications.order-status-messages.all')
                                    @else
                                        @php $tabLabelKey = $statusMessageKey($tab['key']); @endphp
                                        @if ($tabLabelKey)
                                            @lang($tabLabelKey)
                                        @else
                                            {{ $tab['key'] }}
                                        @endif
                                    @endif
                                </span>
                                <span class="rounded-full bg-gray-400 px-1.5 py-px text-xs font-semibold text-white" aria-hidden="true">
                                    {{ $count }}
                                </span>
                            </a>
                        @endforeach
                    </nav>

                    <div
                        id="notifications-tabpanel"
                        role="region"
                        aria-label="@lang('admin::app.notifications.title')"
                        tabindex="0"
                        class="outline-none"
                    >
                        @if ($paginator->count())
                            <ol class="journal-scroll m-0 max-h-[min(60vh,520px)] list-none space-y-0 overflow-y-auto p-0 sm:max-h-[calc(100vh-330px)]">
                                @foreach ($paginator as $notification)
                                    @php
                                        $order = $notification->order;
                                        $orderStatus = $order?->status;
                                        $isWalletRow = (bool) ($notification->summary && ! $notification->order_id);
                                    @endphp
                                    <li class="border-b border-gray-100 last:border-b-0 dark:border-gray-800">
                                        <a
                                            href="{{ route('admin.notification.open', $notification->id) }}"
                                            class="flex min-h-[3.5rem] items-start gap-2 p-3 hover:bg-gray-50 sm:gap-1.5 sm:p-4 dark:hover:bg-gray-950"
                                        >
                                            @if ($isWalletRow)
                                                <span class="{{ $walletEventIcon }} h-fit shrink-0" aria-hidden="true"></span>
                                            @elseif ($orderStatus)
                                                <span class="h-fit shrink-0 rounded-full text-2xl {{ $statusIcon($orderStatus) }}" aria-hidden="true"></span>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                @if ($isWalletRow)
                                                    <p @class([
                                                        'm-0 text-sm text-gray-800 dark:text-white sm:text-base',
                                                        'font-semibold' => ! $notification->read,
                                                        'font-normal' => $notification->read,
                                                    ])>
                                                        {{ $notification->summary }}
                                                    </p>
                                                @elseif ($order)
                                                    <p @class([
                                                        'm-0 text-sm text-gray-800 dark:text-white sm:text-base',
                                                        'font-semibold' => ! $notification->read,
                                                        'font-normal' => $notification->read,
                                                    ])>
                                                        #{{ $order->increment_id }}
                                                        @if ($orderStatus && ($rowMsgKey = $statusMessageKey($orderStatus)))
                                                            {{ __($rowMsgKey) }}
                                                        @elseif ($orderStatus)
                                                            <span class="text-gray-500">{{ $orderStatus }}</span>
                                                        @endif
                                                    </p>
                                                @else
                                                    <p class="m-0 text-sm text-gray-800 dark:text-white sm:text-base">
                                                        @lang('admin::app.notifications.title')
                                                    </p>
                                                @endif

                                                @if ($order && ($order->datetime ?? null))
                                                    <time class="mt-0.5 block text-xs text-gray-600 dark:text-gray-300" datetime="{{ $order->created_at }}">
                                                        {{ $order->datetime }}
                                                    </time>
                                                @elseif ($notification->created_at)
                                                    <time class="mt-0.5 block text-xs text-gray-600 dark:text-gray-300" datetime="{{ $notification->created_at->toIso8601String() }}">
                                                        {{ $notification->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                                                    </time>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div
                                class="m-0 px-4 py-8 text-center text-sm text-gray-600 dark:text-gray-300 sm:px-6 sm:text-base"
                                role="status"
                            >
                                @lang('admin::app.notifications.no-record')
                            </div>
                        @endif
                    </div>
                </div>

                <nav
                    class="flex flex-wrap items-center gap-x-2 gap-y-2 border-t border-gray-200 p-3 dark:border-gray-800 sm:p-4"
                    aria-label="@lang('admin::app.notifications.pagination-nav')"
                >
                    <span class="inline-flex min-h-[2.25rem] items-center rounded-md border border-gray-200 bg-white px-2 py-1.5 text-center text-sm leading-6 text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 max-sm:hidden">
                        {{ $paginator->perPage() }}
                    </span>

                    <span class="whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        @lang('admin::app.notifications.per-page')
                    </span>

                    <span class="whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $paginator->currentPage() }}
                    </span>

                    <span class="whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        @lang('admin::app.notifications.of')
                    </span>

                    <span class="whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $paginator->lastPage() }}
                    </span>

                    <div class="ms-auto flex items-center gap-2">
                        @if ($paginator->onFirstPage())
                            <span class="inline-flex cursor-not-allowed items-center justify-center rounded-md border border-gray-200 bg-white p-1.5 text-gray-400 opacity-40 dark:border-gray-800 dark:bg-gray-900" aria-hidden="true">
                                <span class="icon-sort-left rtl:icon-sort-right text-2xl"></span>
                            </span>
                        @else
                            <a
                                href="{{ $paginator->previousPageUrl() }}"
                                class="inline-flex items-center justify-center rounded-md border border-gray-200 bg-white p-1.5 text-gray-600 transition-all hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-950"
                                title="@lang('admin::app.notifications.pagination-previous')"
                            >
                                <span class="icon-sort-left rtl:icon-sort-right text-2xl" aria-hidden="true"></span>
                                <span class="sr-only">@lang('admin::app.notifications.pagination-previous')</span>
                            </a>
                        @endif

                        @if (! $paginator->hasMorePages())
                            <span class="inline-flex cursor-not-allowed items-center justify-center rounded-md border border-gray-200 bg-white p-1.5 text-gray-400 opacity-40 dark:border-gray-800 dark:bg-gray-900" aria-hidden="true">
                                <span class="icon-sort-right rtl:icon-sort-left text-2xl"></span>
                            </span>
                        @else
                            <a
                                href="{{ $paginator->nextPageUrl() }}"
                                class="inline-flex items-center justify-center rounded-md border border-gray-200 bg-white p-1.5 text-gray-600 transition-all hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-950"
                                title="@lang('admin::app.notifications.pagination-next')"
                            >
                                <span class="icon-sort-right rtl:icon-sort-left text-2xl" aria-hidden="true"></span>
                                <span class="sr-only">@lang('admin::app.notifications.pagination-next')</span>
                            </a>
                        @endif

                        <form method="post" action="{{ route('admin.notification.read_all') }}" class="ms-2 inline">
                            @csrf
                            <button
                                type="submit"
                                class="cursor-pointer rounded-md border border-gray-200 bg-white px-2 py-1.5 text-xs font-semibold text-blue-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-blue-400 dark:hover:bg-gray-950"
                            >
                                @lang('admin::app.notifications.read-all')
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </main>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.after') !!}
</x-admin::layouts>
