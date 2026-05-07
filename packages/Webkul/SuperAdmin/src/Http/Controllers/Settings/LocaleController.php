<?php

namespace Webkul\SuperAdmin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Core\Rules\Code;
use Webkul\SuperAdmin\DataGrids\Settings\LocalesDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class LocaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected LocaleRepository $localeRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::settings.locales.index', [], LocalesDataGrid::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:locales,code', new Code],
            'name' => 'required',
            'direction' => 'required|in:ltr,rtl',
            'logo_path' => 'array',
            'logo_path.*' => 'image|extensions:jpeg,jpg,png,svg,webp',
        ]);

        $this->localeRepository->create(request()->only([
            'code',
            'name',
            'direction',
            'logo_path',
        ]));

        return new JsonResponse([
            'message' => trans('superadmin::app.settings.locales.index.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $locale = $this->localeRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $locale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $this->validate(request(), [
            'name' => 'required',
            'direction' => 'required|in:ltr,rtl',
            'logo_path' => 'array',
            'logo_path.*' => 'image|extensions:jpeg,jpg,png,svg,webp',
        ]);

        $this->localeRepository->update(request()->only([
            'name',
            'direction',
            'logo_path',
        ]), request()->id);

        return new JsonResponse([
            'message' => trans('superadmin::app.settings.locales.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $locale = $this->localeRepository->findOrFail($id);

        if ($locale->count() == 1) {
            return response()->json([
                'message' => trans('superadmin::app.settings.locales.index.last-delete-error'),
            ], 400);
        }

        try {
            $locale->delete($id);

            return new JsonResponse([
                'message' => trans('superadmin::app.settings.locales.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('superadmin::app.settings.locales.index.delete-failed'),
            ], 500);
        }
    }
}
