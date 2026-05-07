<?php

namespace Webkul\SuperAdmin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\SuperAdmin\DataGrids\Customers\ReviewDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;
use Webkul\SuperAdmin\Http\Requests\MassUpdateRequest;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::customers.reviews.index', [], ReviewDataGrid::class);
    }

    /**
     * Review edit screen (classic submit; legacy JSON retained for AJAX callers).
     */
    public function edit(int $id): JsonResponse|View
    {
        $review = $this->productReviewRepository->with(['images', 'product'])->findOrFail($id);

        if ($this->expectsJsonListingResponse()) {
            $review->date = $review->created_at->format('Y-m-d');

            return new JsonResponse([
                'data' => $review,
            ]);
        }

        return view('superadmin::customers.reviews.edit', [
            'review' => $review,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse|RedirectResponse
    {
        $this->validate(request(), [
            'status' => 'required|in:approved,disapproved,pending',
        ]);

        Event::dispatch('customer.review.update.before', $id);

        $review = $this->productReviewRepository->update([
            'status' => request()->input('status'),
        ], $id);

        Event::dispatch('customer.review.update.after', $review);

        if ($this->expectsJsonListingResponse()) {
            return new JsonResponse([
                'message' => trans('superadmin::app.customers.reviews.index.edit.update-success'),
            ]);
        }

        session()->flash('success', trans('superadmin::app.customers.reviews.index.edit.update-success'));

        return redirect()->route('superadmin.customers.customers.review.edit', $id);
    }

    /**
     * Delete the review of the current product
     */
    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        try {
            Event::dispatch('customer.review.delete.before', $id);

            $this->productReviewRepository->delete($id);

            Event::dispatch('customer.review.delete.after', $id);

            if ($this->expectsJsonListingResponse()) {
                return new JsonResponse([
                    'message' => trans('superadmin::app.customers.reviews.index.datagrid.delete-success', ['name' => 'Review']),
                ]);
            }

            session()->flash('success', trans('superadmin::app.customers.reviews.index.datagrid.delete-success', ['name' => 'Review']));

            return redirect()->route('superadmin.customers.customers.review.index');
        } catch (\Exception $e) {
            if ($this->expectsJsonListingResponse()) {
                return new JsonResponse([
                    'message' => trans('superadmin::app.response.delete-failed', ['name' => 'Review']),
                ], 500);
            }

            session()->flash('error', trans('superadmin::app.response.delete-failed', ['name' => 'Review']));

            return redirect()->back();
        }
    }

    protected function expectsJsonListingResponse(): bool
    {
        $request = request();

        return $request->expectsJson()
            || $request->ajax()
            || str_contains((string) $request->header('Accept', ''), 'application/json');
    }

    /**
     * Mass delete the reviews on the products.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            foreach ($indices as $index) {
                Event::dispatch('customer.review.delete.before', $index);

                $this->productReviewRepository->delete($index);

                Event::dispatch('customer.review.delete.after', $index);
            }

            return new JsonResponse([
                'message' => trans('superadmin::app.customers.reviews.index.datagrid.mass-delete-success'),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass approve the reviews on the products.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $indices = $massUpdateRequest->input('indices');

        foreach ($indices as $id) {
            Event::dispatch('customer.review.update.before', $id);

            $review = $this->productReviewRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $id);

            Event::dispatch('customer.review.update.after', $review);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.customers.reviews.index.datagrid.mass-update-success'),
        ], 200);
    }
}
