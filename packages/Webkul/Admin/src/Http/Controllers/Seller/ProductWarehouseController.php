<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\DataGrids\Seller\WarehouseProductDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerStoreProduct;
use Webkul\User\Support\SellerCommissionPercentRules;

class ProductWarehouseController extends Controller
{
    public const SESSION_COMMISSION_KEY = 'seller_warehouse_commission_percent';

    public function index()
    {
        if (request()->ajax()) {
            if (request()->boolean('debug_payload')) {
                $dataGrid = app(WarehouseProductDataGrid::class);
                $query = $dataGrid->prepareQueryBuilder();

                $perPage = max(1, min((int) request()->input('per_page', 10), 100));
                $page = max((int) request()->input('page', 1), 1);
                $paginator = $query->paginate($perPage, ['*'], 'page', $page);

                return new JsonResponse([
                    'data' => $paginator->items(),
                    'meta' => [
                        'current_page' => $paginator->currentPage(),
                        'per_page' => $paginator->perPage(),
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                        'total' => $paginator->total(),
                        'last_page' => $paginator->lastPage(),
                    ],
                ]);
            }

            return datagrid(WarehouseProductDataGrid::class)->process();
        }

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        if (! session()->has(self::SESSION_COMMISSION_KEY)) {
            session([self::SESSION_COMMISSION_KEY => $rule['default']]);
        }

        $totalProducts = $this->countTotalWarehouseCatalogProducts();

        $currentCommission = (float) (session(self::SESSION_COMMISSION_KEY) ?? $rule['default']);

        $debugDataGrid = app(WarehouseProductDataGrid::class);
        $debugQuery = $debugDataGrid->prepareQueryBuilder();
        $debugPaginator = $debugQuery->paginate(10);
        $debugPageIds = collect($debugPaginator->items())
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        $sellerExistingProductIds = [];

        if (! empty($debugPageIds)) {
            $sellerExistingProductIds = DB::table('seller_store_products')
                ->where('seller_id', $seller->id)
                ->whereIn('product_id', $debugPageIds)
                ->pluck('product_id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();
        }

        $warehouseDebugPayload = [
            'data' => $debugPaginator->items(),
            'meta' => [
                'current_page' => $debugPaginator->currentPage(),
                'per_page' => $debugPaginator->perPage(),
                'from' => $debugPaginator->firstItem(),
                'to' => $debugPaginator->lastItem(),
                'total' => $debugPaginator->total(),
                'last_page' => $debugPaginator->lastPage(),
            ],
        ];

        return view('admin::seller.product-warehouse.index', [
            'seller' => $seller,
            'commissionRule' => $rule,
            'warehouseTotalProducts' => $totalProducts,
            'currentCommissionPercent' => $currentCommission,
            'warehouseDebugPayload' => $warehouseDebugPayload,
            'warehouseDebugPaginator' => $debugPaginator,
            'sellerExistingProductIds' => $sellerExistingProductIds,
        ]);
    }

    /**
     * Save commission % for subsequent "Add to store" mass actions (popup equivalent).
     */
    public function saveCommission(Request $request): JsonResponse
    {
        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        if ($rule['readonly']) {
            session([self::SESSION_COMMISSION_KEY => 15.0]);

            return new JsonResponse([
                'message' => trans('admin::app.seller-panel.product-warehouse.commission-saved'),
                'commission_percent' => 15.0,
            ]);
        }

        $data = $request->validate([
            'commission_percent' => 'required|numeric|min:0|max:100',
        ]);

        $pct = round((float) $data['commission_percent'], 2);

        if (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
            return new JsonResponse([
                'message' => trans('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                    'min' => $rule['min'],
                    'max' => $rule['max'],
                ]),
            ], 422);
        }

        session([self::SESSION_COMMISSION_KEY => $pct]);

        return new JsonResponse([
            'message' => trans('admin::app.seller-panel.product-warehouse.commission-saved'),
            'commission_percent' => $pct,
        ]);
    }

    /**
     * Mass action: add selected warehouse products to seller store.
     */
    public function attach(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'indices' => ['required', 'array'],
            'indices.*' => ['integer'],
        ]);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        if ($request->filled('commission_percent')) {
            $pct = round((float) $request->input('commission_percent'), 2);

            if ($rule['readonly']) {
                $pct = 15.0;
            } elseif (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
                $message = trans('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                    'min' => $rule['min'],
                    'max' => $rule['max'],
                ]);

                if (! $request->expectsJson()) {
                    return redirect()->back()->with('error', $message);
                }

                return new JsonResponse(['message' => $message], 422);
            }
        } else {
            $pct = (float) (session(self::SESSION_COMMISSION_KEY) ?? $rule['default']);

            if ($rule['readonly']) {
                $pct = 15.0;
            } elseif (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
                $message = trans('admin::app.seller-panel.product-warehouse.set-commission-first');

                if (! $request->expectsJson()) {
                    return redirect()->back()->with('error', $message);
                }

                return new JsonResponse(['message' => $message], 422);
            }
        }

        $productIds = array_unique(array_map('intval', $request->input('indices', [])));

        if ($productIds === []) {
            $message = trans('admin::app.components.datagrid.index.no-records-selected');

            if (! $request->expectsJson()) {
                return redirect()->back()->with('error', $message);
            }

            return new JsonResponse(['message' => $message], 422);
        }

        $locale = app()->getLocale();
        $channel = core()->getCurrentChannelCode();

        $validIds = DB::table('product_flat')
            ->where('locale', $locale)
            ->where('channel', $channel)
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $existing = DB::table('seller_store_products')
            ->where('seller_id', $seller->id)
            ->whereIn('product_id', $validIds)
            ->pluck('product_id')
            ->all();

        $toInsert = array_values(array_diff($validIds, array_map('intval', $existing)));

        foreach ($toInsert as $productId) {
            SellerStoreProduct::query()->create([
                'seller_id' => $seller->id,
                'product_id' => $productId,
                'commission_percent' => $pct,
                'is_recommended' => false,
            ]);
        }

        $skipped = count($validIds) - count($toInsert);

        $message = trans('admin::app.seller-panel.product-warehouse.attach-success', [
            'added' => count($toInsert),
            'skipped' => $skipped,
        ]);

        if (! $request->expectsJson()) {
            return redirect()->back()->with('success', $message);
        }

        return new JsonResponse([
            'message' => $message,
        ]);
    }

    /**
     * Add one catalog product to the seller store (GET redirect; used from catalog grid row action).
     */
    public function attachOne(int $productId): RedirectResponse
    {
        $sub = Request::create(
            route('admin.seller.product-warehouse.attach'),
            'POST',
            ['indices' => [$productId]]
        );
        $sub->headers->set('Accept', 'text/html');

        return $this->attach($sub);
    }

    /**
     * Count of warehouse-eligible products (matches {@see WarehouseProductDataGrid}): active, visible, channel/locale, excluding already in this seller's store.
     */
    protected function countTotalWarehouseCatalogProducts(): int
    {
        $locale = app()->getLocale();
        $channel = core()->getCurrentChannelCode();
        $sellerId = (int) auth()->guard('admin')->id();

        $q = DB::table('product_flat')
            ->where('locale', $locale)
            ->where('channel', $channel)
            ->where('status', 1)
            ->where('visible_individually', 1);

        if ($sellerId > 0) {
            $q->whereNotExists(function ($sub) use ($sellerId) {
                $sub->select(DB::raw(1))
                    ->from('seller_store_products as ssp')
                    ->whereColumn('ssp.product_id', 'product_flat.product_id')
                    ->where('ssp.seller_id', $sellerId);
            });
        }

        return (int) $q->distinct()->count('product_flat.product_id');
    }
}
