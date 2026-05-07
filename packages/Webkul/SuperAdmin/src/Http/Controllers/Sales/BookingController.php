<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sales;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Webkul\BookingProduct\Repositories\BookingRepository;
use Webkul\SuperAdmin\DataGrids\Sales\BookingDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected BookingRepository $bookingRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfMonth()->endOfDay();

        $calendarBookings = $this->bookingRepository->getBookings([strtotime($startDate), strtotime($endDate)])
            ->map(function ($booking) {
                $booking['start_formatted'] = Carbon::createFromTimestamp($booking->start)->format('d M, Y h:i A');
                $booking['end_formatted'] = Carbon::createFromTimestamp($booking->end)->format('d M, Y h:i A');
                $booking['total_formatted'] = core()->formatBasePrice($booking->total);

                return $booking;
            });

        return $this->superadminListing(
            'superadmin::sales.bookings.index',
            compact('calendarBookings'),
            BookingDataGrid::class
        );
    }

    /**
     * Returns a listing of the resource.
     *
     * @return Response
     */
    public function get()
    {
        if (! request('view_type')) {
            return app(BookingDataGrid::class)->process();
        }

        $startDate = request()->get('startDate')
            ? Carbon::createFromTimeString(request()->get('startDate').' 00:00:01')
            : Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');

        $endDate = request()->get('endDate')
            ? Carbon::createFromTimeString(request()->get('endDate').' 23:59:59')
            : Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

        $bookings = $this->bookingRepository->getBookings([strtotime($startDate), strtotime($endDate)])
            ->map(function ($booking) {
                $booking['start'] = Carbon::createFromTimestamp($booking->start)->format('Y-m-d h:i A');

                $booking['end'] = Carbon::createFromTimestamp($booking->end)->format('Y-m-d h:i A');

                $booking->total = core()->formatBasePrice($booking->total);

                return $booking;
            });

        return response()->json([
            'bookings' => $bookings,
        ]);
    }
}
