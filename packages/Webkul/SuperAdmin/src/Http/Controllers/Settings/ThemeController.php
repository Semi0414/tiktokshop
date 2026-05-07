<?php

namespace Webkul\SuperAdmin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\SuperAdmin\DataGrids\Theme\ThemeDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;
use Webkul\SuperAdmin\Http\Requests\MassUpdateRequest;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class ThemeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public ThemeCustomizationRepository $themeCustomizationRepository) {}

    /**
     * Display a listing resource for the available tax rates.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::settings.themes.index', [], ThemeDataGrid::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|string
     */
    public function store()
    {
        if (request()->has('id')) {
            $this->validate(request(), [
                core()->getRequestedLocaleCode().'.options.*.image' => 'image|extensions:jpeg,jpg,png,svg,webp',
            ]);

            $theme = $this->themeCustomizationRepository->find(request()->input('id'));

            return $this->themeCustomizationRepository->uploadImage(request()->all(), $theme);
        }

        $validated = $this->validate(request(), [
            'name' => 'required',
            'sort_order' => 'required|numeric',
            'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content',
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck('id')->toArray())),
            'theme_code' => 'required',
        ]);

        Event::dispatch('theme_customization.create.before');

        $theme = $this->themeCustomizationRepository->create($validated);

        Event::dispatch('theme_customization.create.after', $theme);

        return new JsonResponse([
            'redirect_url' => route('superadmin.settings.themes.edit', $theme->id),
        ]);
    }

    /**
     * Edit the theme
     *
     * @return View
     */
    public function edit(int $id)
    {
        $theme = $this->themeCustomizationRepository->find($id);

        return view('superadmin::settings.themes.edit', compact('theme'));
    }

    /**
     * Update the specified resource
     *
     * @return RedirectResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'sort_order' => 'required|numeric',
            'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content',
            'channel_id' => 'required|in:'.implode(',', (core()->getAllChannels()->pluck('id')->toArray())),
            'theme_code' => 'required',
        ]);

        $locale = request('locale');

        $data = request()->only(
            'locale',
            'type',
            'name',
            'sort_order',
            'channel_id',
            'theme_code',
            'status',
            $locale
        );

        Event::dispatch('theme_customization.update.before', $id);

        $data['status'] = request()->input('status') == 'on';

        $theme = $this->themeCustomizationRepository->update($data, $id);

        Event::dispatch('theme_customization.update.after', $theme);

        session()->flash('success', trans('superadmin::app.settings.themes.update-success'));

        return redirect()->route('superadmin.settings.themes.index');
    }

    /**
     * Delete a specified theme.
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        Event::dispatch('theme_customization.delete.before', $id);

        $this->themeCustomizationRepository->delete($id);

        Storage::deleteDirectory('theme/'.$id);

        Event::dispatch('theme_customization.delete.after', $id);

        return new JsonResponse([
            'message' => trans('superadmin::app.settings.themes.delete-success'),
        ], 200);
    }

    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedThemeIds = $massUpdateRequest->input('indices');

        $this->themeCustomizationRepository->massUpdateStatus([
            'status' => $massUpdateRequest->input('value'),
        ], $selectedThemeIds);

        return new JsonResponse([
            'message' => trans('superadmin::app.settings.themes.update-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $selectedThemeIds = $massDestroyRequest->input('indices');

        foreach ($selectedThemeIds as $themeId) {
            $this->themeCustomizationRepository->delete($themeId);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.settings.themes.update-success'),
        ]);
    }
}
