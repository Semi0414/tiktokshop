<?php

namespace Webkul\SuperAdmin\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\Core\Rules\Slug;
use Webkul\SuperAdmin\DataGrids\CMS\CMSPageDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository) {}

    /**
     * Loads the index page showing the static pages resources.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::cms.index', [], CMSPageDataGrid::class);
    }

    /**
     * To create a new CMS page.
     *
     * @return View
     */
    public function create()
    {
        return view('superadmin::cms.create');
    }

    /**
     * To store a new CMS page in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->validate(request(), [
            'url_key' => ['required', 'unique:cms_page_translations,url_key', new Slug],
            'page_title' => 'required',
            'html_content' => 'required',
            'channels' => 'required|array|min:1',
        ]);

        Event::dispatch('cms.page.create.before');

        $data = request()->only([
            'page_title',
            'channels',
            'html_content',
            'meta_title',
            'url_key',
            'meta_keywords',
            'meta_description',
        ]);

        $data['html_content'] = clean_content($data['html_content']);

        $page = $this->pageRepository->create($data);

        Event::dispatch('cms.page.create.after', $page);

        session()->flash('success', trans('superadmin::app.cms.create-success'));

        return redirect()->route('superadmin.cms.index');
    }

    /**
     * To edit a previously created CMS page.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $page = $this->pageRepository->findOrFail($id);

        return view('superadmin::cms.edit', compact('page'));
    }

    /**
     * To update the previously created CMS page in storage.
     *
     * @return Response
     */
    public function update(int $id)
    {
        $locale = core()->getRequestedLocaleCode();

        $this->validate(request(), [
            $locale.'.url_key' => ['required', new Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->pageRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('superadmin::app.cms.index.already-taken', ['name' => 'Page']));
                }
            }],
            $locale.'.page_title' => 'required',
            $locale.'.html_content' => 'required',
            'channels' => 'required|array|min:1',
        ]);

        Event::dispatch('cms.page.update.before', $id);

        $localeData = request()->input($locale);

        $localeData['html_content'] = clean_content($localeData['html_content']);

        $page = $this->pageRepository->update([
            $locale => $localeData,
            'channels' => request()->input('channels'),
            'locale' => $locale,
        ], $id);

        Event::dispatch('cms.page.update.after', $page);

        session()->flash('success', trans('superadmin::app.cms.update-success'));

        return redirect()->route('superadmin.cms.index');
    }

    /**
     * To delete the previously create CMS page.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            Event::dispatch('cms.page.delete.before', $id);

            $this->pageRepository->delete($id);

            Event::dispatch('cms.page.delete.after', $id);

            return new JsonResponse(['message' => trans('superadmin::app.cms.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => trans('superadmin::app.cms.no-resource')]);
        }
    }

    /**
     * To mass delete the CMS resource from storage.
     */
    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('cms.page.delete.before', $index);

            $this->pageRepository->delete($index);

            Event::dispatch('cms.page.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.cms.index.datagrid.mass-delete-success'),
        ], 200);
    }
}
