@php
    use Webkul\Sales\Models\Order;
@endphp
@switch($status)
    @case(Order::STATUS_PROCESSING)
        <span class="label-processing text-sm">{{ __('shop::app.customers.account.orders.status.options.processing') }}</span>
        @break
    @case(Order::STATUS_COMPLETED)
        <span class="label-active text-sm">{{ __('shop::app.customers.account.orders.status.options.completed') }}</span>
        @break
    @case(Order::STATUS_CANCELED)
        <span class="label-canceled text-sm">{{ __('shop::app.customers.account.orders.status.options.canceled') }}</span>
        @break
    @case(Order::STATUS_CLOSED)
        <span class="label-closed text-sm">{{ __('shop::app.customers.account.orders.status.options.closed') }}</span>
        @break
    @case(Order::STATUS_PENDING)
        <span class="label-pending text-sm">{{ __('shop::app.customers.account.orders.status.options.pending') }}</span>
        @break
    @case(Order::STATUS_PENDING_PAYMENT)
        <span class="label-pending text-sm">{{ __('shop::app.customers.account.orders.status.options.pending-payment') }}</span>
        @break
    @case(Order::STATUS_FRAUD)
        <span class="label-canceled text-sm">{{ __('shop::app.customers.account.orders.status.options.fraud') }}</span>
        @break
    @default
        <span class="text-sm text-zinc-600">{{ $status }}</span>
@endswitch
