<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Helpers\Reporting\Product as ProductReporting;
use Webkul\Admin\Helpers\Reporting\Visitor as VisitorReporting;
use Webkul\Admin\Support\SellerDashboardStats;
use Webkul\User\Models\Admin;

class DashboardController extends Controller
{
    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'over-all' => 'getOverAllStats',
        'today' => 'getTodayStats',
        'stock-threshold-products' => 'getStockThresholdProducts',
        'total-sales' => 'getSalesStats',
        'total-visitors' => 'getVisitorStats',
        'top-selling-products' => 'getTopSellingProducts',
        'top-customers' => 'getTopCustomers',
    ];

    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Dashboard $dashboardHelper,
        protected ProductReporting $productReporting,
        protected VisitorReporting $visitorReporting,
    ) {}

    /**
     * Dashboard page.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        $user = auth()->guard('admin')->user();
        $isPlatformAdmin = SellerDashboardStats::isPlatformAdmin($user instanceof Admin ? $user : null);

        $startDate = $this->dashboardHelper->getStartDate();
        $endDate = $this->dashboardHelper->getEndDate();

        if ($isPlatformAdmin) {
            $dashboardStats = SellerDashboardStats::forPlatform(
                $this->dashboardHelper,
                $this->productReporting,
                $this->visitorReporting
            );
        } else {
            $dashboardStats = $user instanceof Admin
                ? SellerDashboardStats::forSeller($user, $startDate, $endDate)
                : SellerDashboardStats::forPlatform(
                    $this->dashboardHelper,
                    $this->productReporting,
                    $this->visitorReporting
                );
        }

        return view('admin::dashboard.index')->with([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isPlatformAdmin' => $isPlatformAdmin,
            'dashboardStats' => $dashboardStats,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function stats()
    {
        $stats = $this->dashboardHelper->{$this->typeFunctions[request()->query('type')]}();

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->dashboardHelper->getDateRange(),
        ]);
    }
}
