<?php

namespace Webkul\SuperAdmin\Http\Controllers;

use Illuminate\View\View;
use Webkul\SuperAdmin\Helpers\Dashboard;
use Webkul\SuperAdmin\Helpers\Reporting\Visitor as VisitorReporting;
use Webkul\SuperAdmin\Support\SuperAdminDashboardStats;

class DashboardController extends Controller
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Dashboard $dashboardHelper,
        protected VisitorReporting $visitorReporting,
    ) {}

    /**
     * Dashboard page (server-rendered; no AJAX stats endpoint).
     */
    public function index(): View
    {
        $startDate = $this->dashboardHelper->getStartDate();
        $endDate = $this->dashboardHelper->getEndDate();

        $dashboardStats = SuperAdminDashboardStats::build(
            $this->dashboardHelper,
            $this->visitorReporting
        );

        return view('superadmin::dashboard.index')->with([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dashboardStats' => $dashboardStats,
        ]);
    }
}
