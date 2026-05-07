<?php

namespace Webkul\SuperAdmin\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Merge SSR datagrid payload for Super Admin listings (no AJAX fetch for rows).
     *
     * @param  array<string, mixed>  $viewData
     * @return array<string, mixed>
     */
    protected function withDatagridPayload(array $viewData, string $datagridClass): array
    {
        return array_merge($viewData, [
            'datagridPayload' => datagrid_formatted($datagridClass),
        ]);
    }

    /**
     * Listing page: SSR payload by default; Excel/CSV export uses the same URL with export=1 (full-page GET).
     *
     * @param  array<string, mixed>  $viewData
     */
    protected function superadminListing(string $view, array $viewData, string $datagridClass): ViewContract|BinaryFileResponse|JsonResponse
    {
        if (request()->boolean('export')) {
            return datagrid($datagridClass)->process();
        }

        return view($view, array_merge($viewData, [
            'datagridPayload' => datagrid_formatted($datagridClass),
        ]));
    }

    /**
     * Datagrid export when pagination is namespaced (multiple grids on one page).
     */
    protected function resolveNestedPaginationExport(string $datagridClass, string $nestedKey): BinaryFileResponse|JsonResponse
    {
        $defaults = ['page' => 1, 'per_page' => 10];
        $fromRequest = request()->input($nestedKey.'.pagination', []);
        $fromRequest = is_array($fromRequest) ? $fromRequest : [];

        request()->merge([
            'pagination' => array_merge($defaults, $fromRequest),
        ]);

        return datagrid($datagridClass)->process();
    }

    /**
     * Run a datagrid with pagination read from a nested query key (e.g. customer_orders[pagination][page]),
     * so multiple grids on one page do not share the same pagination[page] input.
     *
     * @return array<string, mixed>
     */
    protected function datagridFormattedWithNestedPagination(string $datagridClass, string $nestedKey): array
    {
        $defaults = ['page' => 1, 'per_page' => 10];
        $fromRequest = request()->input($nestedKey.'.pagination', []);
        $fromRequest = is_array($fromRequest) ? $fromRequest : [];

        request()->merge([
            'pagination' => array_merge($defaults, $fromRequest),
        ]);

        return datagrid_formatted($datagridClass);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function redirectToLogin()
    {
        return redirect()->route('superadmin.session.create');
    }
}
