<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\SuperAdmin\DataGrids\Marketing\Promotions\CatalogRuleDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\CatalogRuleRequest;

class CatalogRuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CatalogRuleRepository $catalogRuleRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::marketing.promotions.catalog-rules.index', [], CatalogRuleDataGrid::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('superadmin::marketing.promotions.catalog-rules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CatalogRuleRequest $catalogRuleRequest)
    {
        Event::dispatch('promotions.catalog_rule.create.before');

        $catalogRule = $this->catalogRuleRepository->create($catalogRuleRequest->all());

        Event::dispatch('promotions.catalog_rule.create.after', $catalogRule);

        session()->flash('success', trans('superadmin::app.marketing.promotions.catalog-rules.create-success'));

        return redirect()->route('superadmin.marketing.promotions.catalog_rules.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $catalogRule = $this->catalogRuleRepository->findOrFail($id);

        return view('superadmin::marketing.promotions.catalog-rules.edit', compact('catalogRule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(CatalogRuleRequest $catalogRuleRequest, int $id)
    {
        $this->catalogRuleRepository->findOrFail($id);

        Event::dispatch('promotions.catalog_rule.update.before', $id);

        $catalogRule = $this->catalogRuleRepository->update($catalogRuleRequest->all(), $id);

        Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);

        session()->flash('success', trans('superadmin::app.marketing.promotions.catalog-rules.update-success'));

        return redirect()->route('superadmin.marketing.promotions.catalog_rules.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->catalogRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.catalog_rule.delete.before', $id);

            $this->catalogRuleRepository->delete($id);

            Event::dispatch('promotions.catalog_rule.delete.after', $id);

            return new JsonResponse([
                'message' => trans('superadmin::app.marketing.promotions.catalog-rules.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('superadmin::app.marketing.promotions.catalog-rules.delete-failed'),
            ], 400);
        }
    }
}
