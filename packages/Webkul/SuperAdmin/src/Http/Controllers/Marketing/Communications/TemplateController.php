<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing\Communications;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Marketing\Repositories\TemplateRepository;
use Webkul\SuperAdmin\DataGrids\Marketing\Communications\EmailTemplateDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TemplateRepository $templateRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::marketing.communications.templates.index', [], EmailTemplateDataGrid::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('superadmin::marketing.communications.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'status' => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        Event::dispatch('marketing.templates.create.before');

        $data = request()->only([
            'name',
            'status',
            'content',
        ]);

        $data['content'] = clean_content($data['content']);

        $template = $this->templateRepository->create($data);

        Event::dispatch('marketing.templates.create.after', $template);

        session()->flash('success', trans('superadmin::app.marketing.communications.templates.create.create-success'));

        return redirect()->route('superadmin.marketing.communications.email_templates.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $template = $this->templateRepository->findOrFail($id);

        return view('superadmin::marketing.communications.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'status' => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        Event::dispatch('marketing.templates.update.before', $id);

        $data = request()->only([
            'name',
            'status',
            'content',
        ]);

        $data['content'] = clean_content($data['content']);

        $template = $this->templateRepository->update($data, $id);

        Event::dispatch('marketing.templates.update.after', $template);

        session()->flash('success', trans('superadmin::app.marketing.communications.templates.edit.update-success'));

        return redirect()->route('superadmin.marketing.communications.email_templates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('marketing.templates.delete.before', $id);

            $this->templateRepository->delete($id);

            Event::dispatch('marketing.templates.delete.after', $id);

            return new JsonResponse([
                'message' => trans('superadmin::app.marketing.communications.templates.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.marketing.communications.templates.delete-failed', [
                'name' => 'superadmin::app.marketing.communications.templates.email-template',
            ]),
        ], 400);
    }
}
