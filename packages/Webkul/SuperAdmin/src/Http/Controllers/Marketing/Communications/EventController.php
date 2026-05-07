<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Marketing\Repositories\EventRepository;
use Webkul\SuperAdmin\DataGrids\Marketing\Communications\EventDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EventRepository $eventRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::marketing.communications.events.index', [], EventDataGrid::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'date' => 'date|required',
        ]);

        Event::dispatch('marketing.events.create.before');

        $event = $this->eventRepository->create(request()->only([
            'name',
            'description',
            'date',
        ]));

        Event::dispatch('marketing.events.create.after', $event);

        return response()->json([
            'message' => trans('superadmin::app.marketing.communications.events.index.create.success'),
        ], 200);
    }

    /**
     * Event Details
     */
    public function edit(int $id): JsonResponse
    {
        if ($id == 1) {
            return new JsonResponse([
                'message' => trans('superadmin::app.marketing.communications.events.edit-error'),
            ]);
        }

        $event = $this->eventRepository->findOrFail($id);

        return new JsonResponse($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update()
    {
        $id = request()->id;

        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'date' => 'date|required',
        ]);

        Event::dispatch('marketing.events.update.before', $id);

        $event = $this->eventRepository->update(request()->only([
            'name',
            'description',
            'date',
        ]), $id);

        Event::dispatch('marketing.events.update.after', $event);

        return response()->json([
            'message' => trans('superadmin::app.marketing.communications.events.index.edit.success'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $this->eventRepository->findOrFail($id);

        try {
            Event::dispatch('marketing.events.delete.before', $id);

            $this->eventRepository->delete($id);

            Event::dispatch('marketing.events.delete.after', $id);

            return response()->json([
                'message' => trans('superadmin::app.marketing.communications.events.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('superadmin::app.marketing.communications.events.delete-failed', ['name' => 'superadmin::app.marketing.communications.events.index.event']),
        ], 500);
    }
}
