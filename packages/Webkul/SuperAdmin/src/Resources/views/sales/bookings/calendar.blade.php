<div class="mt-6 rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
        @lang('superadmin::app.sales.booking.calendar.booking-details')
    </p>

    @if (! empty($calendarBookings) && count($calendarBookings))
        <div class="grid gap-3">
            @foreach ($calendarBookings as $booking)
                <div class="rounded border border-gray-200 p-3 dark:border-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            #{{ $booking->order_id }} - {{ $booking->full_name ?? 'N/A' }}
                        </p>

                        <span class="text-xs font-medium uppercase text-gray-600 dark:text-gray-300">
                            {{ $booking->status ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="mt-2 grid gap-1 text-sm text-gray-600 dark:text-gray-300">
                        <p>
                            @lang('superadmin::app.sales.booking.calendar.time-slot'):
                            {{ $booking->start_formatted ?? 'N/A' }} - {{ $booking->end_formatted ?? 'N/A' }}
                        </p>
                        <p>
                            @lang('superadmin::app.sales.booking.calendar.price'):
                            {{ $booking->total_formatted ?? 'N/A' }}
                        </p>
                        <p>
                            @lang('superadmin::app.sales.booking.calendar.booking-date'):
                            {{ \Carbon\Carbon::parse($booking->created_at)->format('d M, Y') }}
                        </p>
                    </div>

                    <div class="mt-3">
                        <a
                            href="{{ route('superadmin.sales.orders.view', $booking->order_id) }}"
                            class="text-sm font-medium text-blue-600 hover:underline"
                        >
                            @lang('superadmin::app.sales.booking.calendar.view-details')
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-600 dark:text-gray-300">
            No bookings found for current month.
        </p>
    @endif
</div>