<?php

namespace Webkul\Admin\Http\Controllers\Reporting;

use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * Request param functions.
     *
     * @var array
     */
    protected $typeFunctions = [
        'total-sales' => 'getTotalSalesStats',
        'average-sales' => 'getAverageSalesStats',
        'total-orders' => 'getTotalOrdersStats',
        'purchase-funnel' => 'getPurchaseFunnelStats',
        'abandoned-carts' => 'getAbandonedCartsStats',
        'refunds' => 'getRefundsStats',
        'tax-collected' => 'getTaxCollectedStats',
        'shipping-collected' => 'getShippingCollectedStats',
        'top-payment-methods' => 'getTopPaymentMethods',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $totalSales = $this->reportingHelper->getTotalSalesStats();
        $averageSales = $this->reportingHelper->getAverageSalesStats();
        $totalOrders = $this->reportingHelper->getTotalOrdersStats();
        $refunds = $this->reportingHelper->getRefundsStats();
        $taxCollected = $this->reportingHelper->getTaxCollectedStats();
        $shippingCollected = $this->reportingHelper->getShippingCollectedStats();
        $topPaymentMethods = $this->reportingHelper->getTopPaymentMethods('table');
        $totalSalesTable = $this->reportingHelper->getTotalSalesStats('table');
        $averageSalesTable = $this->reportingHelper->getAverageSalesStats('table');
        $totalOrdersTable = $this->reportingHelper->getTotalOrdersStats('table');
        $refundsTable = $this->reportingHelper->getRefundsStats('table');

        return view('admin::reporting.sales.index')->with([
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate' => $this->reportingHelper->getEndDate(),
            'staticSummary' => [
                'total_sales' => $totalSales['sales']['current']['formatted'] ?? null,
                'average_sales' => $averageSales['sales']['current']['formatted'] ?? null,
                'total_orders' => $totalOrders['orders']['current']['total'] ?? null,
                'refunds' => $refunds['refunds']['current']['formatted'] ?? null,
                'tax_collected' => $taxCollected['tax_collected']['current']['formatted'] ?? null,
                'shipping_collected' => $shippingCollected['shipping_collected']['current']['formatted'] ?? null,
            ],
            'totalSalesTable' => $totalSalesTable,
            'averageSalesTable' => $averageSalesTable,
            'totalOrdersTable' => $totalOrdersTable,
            'refundsTable' => $refundsTable,
            'topPaymentMethodsTable' => $topPaymentMethods,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function view()
    {
        if ($this->validateRequestedType()) {
            abort(404);
        }

        return view('admin::reporting.view')->with([
            'entity' => 'sales',
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate' => $this->reportingHelper->getEndDate(),
        ]);
    }
}
