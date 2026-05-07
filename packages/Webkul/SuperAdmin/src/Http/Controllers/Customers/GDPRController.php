<?php

namespace Webkul\SuperAdmin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\GDPR\Repositories\GDPRDataRequestRepository;
use Webkul\SuperAdmin\DataGrids\Customers\GDPRDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class GDPRController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected GDPRDataRequestRepository $gdprDataRequestRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::customers.gdpr.index', [], GDPRDataGrid::class);
    }

    /**
     * Method to show the form for updating a new Data Request.
     */
    public function edit(int $id)
    {
        try {
            $request = $this->gdprDataRequestRepository->findOrFail($id);

            return new JsonResponse([
                'data' => $request,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('superadmin::app.customers.gdpr.index.attribute-reason-error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Method to update the Data Request information.
     */
    public function update(int $id)
    {
        try {
            Event::dispatch('customer.gdpr-request.update.before');

            $gdprRequest = $this->gdprDataRequestRepository->update(request()->all(), $id);

            Event::dispatch('customer.account.gdpr-request.update.after', $gdprRequest);

            return response()->json([
                'message' => trans(key: 'superadmin::app.customers.gdpr.index.update-success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('superadmin::app.customers.gdpr.index.update-success-unsent-email'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            $gdprRequest = $this->gdprDataRequestRepository->findOrFail($id);

            $gdprRequest->delete();

            return new JsonResponse([
                'message' => trans('superadmin::app.customers.gdpr.index.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.gdpr.index.attribute-reason-error'),
        ], 500);
    }
}
