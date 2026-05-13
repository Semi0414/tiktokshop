<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-sm:mb-5">
            <a class="grid md:hidden" href="{{ route('shop.customers.account.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.orders.title')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        @if ($orders->isEmpty())
            <div class="rounded-lg border border-zinc-200 bg-white p-8 text-center text-zinc-600">
                @lang('shop::app.customers.account.orders.empty-order')
            </div>
        @else
            {{-- Desktop: plain HTML table --}}
            <div class="max-md:hidden overflow-x-auto rounded-lg border border-zinc-200 bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase text-zinc-600">
                        <tr>
                            <th class="px-4 py-3">@lang('shop::app.customers.account.orders.seller-store')</th>
                            <th class="px-4 py-3">@lang('shop::app.customers.account.orders.order-id')</th>
                            <th class="px-4 py-3">@lang('shop::app.customers.account.orders.order-date')</th>
                            <th class="px-4 py-3">@lang('shop::app.customers.account.orders.total')</th>
                            <th class="px-4 py-3">@lang('shop::app.customers.account.orders.status.title')</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-800">
                                    @if ($order->seller?->name)
                                        {{ $order->seller->name }}
                                    @endif
                                    @if ($order->seller_id)
                                        <span class="text-xs text-zinc-500">· @lang('shop::app.customers.account.orders.seller-id-label') #{{ $order->seller_id }}</span>
                                    @elseif (! $order->seller?->name)
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-zinc-900">#{{ $order->increment_id }}</td>
                                <td class="px-4 py-3 text-zinc-600">{{ $order->created_at?->format('M d, Y H:i') }}</td>
                                <td class="px-4 py-3 font-semibold text-zinc-900">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</td>
                                <td class="px-4 py-3">
                                    @include('shop::customers.account.orders.partials.status-label', ['status' => $order->status])
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('shop.customers.account.orders.view', $order->id) }}" class="text-sm font-medium text-navyBlue underline">
                                        @lang('shop::app.customers.account.orders.action-view')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile: cards --}}
            <div class="space-y-4 md:hidden">
                @foreach ($orders as $order)
                    <a href="{{ route('shop.customers.account.orders.view', $order->id) }}" class="block rounded-lg border border-zinc-200 bg-white p-4 transition hover:bg-zinc-50">
                        <div class="flex justify-between gap-2">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    @lang('shop::app.customers.account.orders.order-id'): #{{ $order->increment_id }}
                                </p>
                                <p class="text-xs text-zinc-500">{{ $order->created_at?->format('M d, Y') }}</p>
                                @if ($order->seller?->name || $order->seller_id)
                                    <p class="mt-1 text-xs text-zinc-600">
                                        @if ($order->seller?->name){{ $order->seller->name }}@endif
                                        @if ($order->seller_id)<span class="text-zinc-500"> · #{{ $order->seller_id }}</span>@endif
                                    </p>
                                @endif
                            </div>
                            <div>@include('shop::customers.account.orders.partials.status-label', ['status' => $order->status])</div>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500">@lang('shop::app.customers.account.orders.subtotal')</p>
                        <p class="text-xl font-semibold text-black">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
    </div>
</x-shop::layouts.account>
