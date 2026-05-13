<?php

namespace Webkul\Admin\Http\Controllers\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Admin\DataGrids\Seller\SellerStoreProductDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\User\Models\Admin;
use Webkul\User\Models\SellerStoreProduct;
use Webkul\User\Support\SellerCommissionPercentRules;

class SellerStoreProductsController extends Controller
{
    private const MIN_ACCOUNT_AGE_DAYS_FOR_STORE_PRODUCT_REMOVAL = 90;

    public function index()
    {
        if (request()->ajax()) {
            return datagrid(SellerStoreProductDataGrid::class)->process();
        }

        $categoryFilterOptions = $this->categoryFilterOptions();

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $storeProductsTotalCount = SellerStoreProduct::query()
            ->where('seller_id', (int) $seller->id)
            ->count();
        $commissionRule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        $storeProductRemovalMinAccountDays = self::MIN_ACCOUNT_AGE_DAYS_FOR_STORE_PRODUCT_REMOVAL;
        $sellerAccountAgeDays = $seller->created_at
            ? (int) $seller->created_at->diffInDays(now())
            : 0;
        $canRemoveStoreProducts = $this->sellerMayRemoveStoreProducts($seller);

        $debugDataGrid = app(SellerStoreProductDataGrid::class);
        $debugQuery = $debugDataGrid->prepareQueryBuilder();
        $debugPaginator = $debugQuery->paginate(10)->appends(request()->query());
        $storeProductsDebugPayload = [
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

        return view('admin::seller.store-products.index', compact(
            'categoryFilterOptions',
            'seller',
            'storeProductsTotalCount',
            'commissionRule',
            'storeProductsDebugPayload',
            'debugPaginator',
            'storeProductRemovalMinAccountDays',
            'sellerAccountAgeDays',
            'canRemoveStoreProducts',
        ));
    }

    /**
     * @return Collection<int, object>
     */
    protected function categoryFilterOptions()
    {
        $locale = app()->getLocale();
        $channel = core()->getCurrentChannel();

        $query = DB::table('category_translations')
            ->join('categories', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)
            ->orderBy('category_translations.name')
            ->select('categories.id as category_id', 'category_translations.name');

        if (Schema::hasTable('category_channels')) {
            $query->join('category_channels', 'categories.id', '=', 'category_channels.category_id')
                ->where('category_channels.channel_id', $channel->id);
        }

        return $query->get();
    }

    public function editCommission(SellerStoreProduct $sellerStoreProduct): RedirectResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        return redirect()->route('admin.seller.store-products.index');
    }

    /**
     * JSON for the store-product edit modal (commission + recommended).
     */
    public function modalData(SellerStoreProduct $sellerStoreProduct): JsonResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        $sellerStoreProduct->load('product');
        $flatStatus = DB::table('product_flat')
            ->where('product_id', (int) $sellerStoreProduct->product_id)
            ->where('locale', app()->getLocale())
            ->where('channel', core()->getCurrentChannelCode())
            ->value('status');

        return new JsonResponse([
            'product_name' => $sellerStoreProduct->product?->name ?? '',
            'sku' => $sellerStoreProduct->product?->sku ?? '',
            'commission_percent' => (float) $sellerStoreProduct->commission_percent,
            'is_recommended' => (bool) $sellerStoreProduct->is_recommended,
            'status' => (int) ($flatStatus ?? 1),
            'commission_rule' => $rule,
            'update_url' => route('admin.seller.store-products.update', $sellerStoreProduct),
        ]);
    }

    /**
     * Update commission and recommended flag (modal / API).
     */
    public function updateStoreProduct(Request $request, SellerStoreProduct $sellerStoreProduct): JsonResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        $data = $request->validate([
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'is_recommended' => 'required|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($rule['readonly']) {
            $sellerStoreProduct->commission_percent = 15.0;
        } else {
            $pct = round((float) ($data['commission_percent'] ?? 0), 2);

            if (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
                return new JsonResponse([
                    'message' => trans('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                        'min' => $rule['min'],
                        'max' => $rule['max'],
                    ]),
                ], 422);
            }

            $sellerStoreProduct->commission_percent = $pct;
        }

        if ($data['is_recommended'] && ! $sellerStoreProduct->is_recommended) {
            $count = SellerStoreProduct::query()
                ->where('seller_id', $seller->id)
                ->where('is_recommended', true)
                ->where('id', '!=', $sellerStoreProduct->id)
                ->count();

            if ($count >= 100) {
                return new JsonResponse([
                    'message' => trans('admin::app.seller-panel.store-products.recommended-limit'),
                ], 422);
            }
        }

        $sellerStoreProduct->is_recommended = (bool) $data['is_recommended'];
        $sellerStoreProduct->save();

        if (array_key_exists('status', $data)) {
            $status = (int) $data['status'];

            DB::table('product_flat')
                ->where('product_id', (int) $sellerStoreProduct->product_id)
                ->update(['status' => $status]);
        }

        return new JsonResponse([
            'message' => trans('admin::app.seller-panel.store-products.store-product-saved'),
        ]);
    }

    /**
     * Bulk update commission and recommended for selected store-product rows.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        $data = $request->validate([
            'indices' => 'required|array|min:1',
            'indices.*' => 'integer',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'is_recommended' => 'required|boolean',
            'status' => 'nullable|boolean',
        ]);

        $ids = array_map('intval', $data['indices']);

        $models = SellerStoreProduct::query()
            ->where('seller_id', $seller->id)
            ->whereIn('id', $ids)
            ->get();

        if ($models->count() !== count($ids)) {
            return new JsonResponse(['message' => 'Invalid selection.'], 422);
        }

        if ($data['is_recommended']) {
            $t = SellerStoreProduct::query()
                ->where('seller_id', $seller->id)
                ->where('is_recommended', true)
                ->count();

            $sOn = $models->where('is_recommended', true)->count();
            $newTotal = $t - $sOn + $models->count();

            if ($newTotal > 100) {
                return new JsonResponse([
                    'message' => trans('admin::app.seller-panel.store-products.recommended-limit'),
                ], 422);
            }
        }

        if ($rule['readonly']) {
            $pct = 15.0;
        } else {
            $pct = round((float) ($data['commission_percent'] ?? 0), 2);

            if (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
                return new JsonResponse([
                    'message' => trans('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                        'min' => $rule['min'],
                        'max' => $rule['max'],
                    ]),
                ], 422);
            }
        }

        foreach ($models as $ssp) {
            $ssp->commission_percent = $pct;
            $ssp->is_recommended = (bool) $data['is_recommended'];
            $ssp->save();
        }

        if (array_key_exists('status', $data)) {
            $status = (int) $data['status'];
            $productIds = $models->pluck('product_id')->map(fn ($id) => (int) $id)->all();

            DB::table('product_flat')
                ->whereIn('product_id', $productIds)
                ->update(['status' => $status]);
        }

        return new JsonResponse([
            'message' => trans('admin::app.seller-panel.store-products.bulk-updated', ['count' => $models->count()]),
        ]);
    }

    public function updateCommission(Request $request, SellerStoreProduct $sellerStoreProduct): RedirectResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        if ($rule['readonly']) {
            $sellerStoreProduct->commission_percent = 15.0;
            $sellerStoreProduct->save();

            return redirect()
                ->back()
                ->with('success', trans('admin::app.seller-panel.store-products.commission-updated'));
        }

        $data = $request->validate([
            'commission_percent' => 'required|numeric|min:0|max:100',
        ]);

        $pct = round((float) $data['commission_percent'], 2);

        if (! SellerCommissionPercentRules::isPercentAllowed($seller->seller_level, $pct)) {
            return redirect()->back()->withInput()->withErrors([
                'commission_percent' => trans('admin::app.seller-panel.product-warehouse.commission-out-of-range', [
                    'min' => $rule['min'],
                    'max' => $rule['max'],
                ]),
            ]);
        }

        $sellerStoreProduct->commission_percent = $pct;
        $sellerStoreProduct->save();

        return redirect()
            ->back()
            ->with('success', trans('admin::app.seller-panel.store-products.commission-updated'));
    }

    /**
     * Mass remove from store by catalog product IDs (Product Warehouse grid).
     */
    public function massDestroyByProductIds(MassDestroyRequest $request): JsonResponse
    {
        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        if ($msg = $this->storeProductRemovalBlockMessage($seller)) {
            return new JsonResponse(['message' => $msg], 422);
        }

        $productIds = array_map('intval', $request->input('indices', []));

        $deleted = SellerStoreProduct::query()
            ->where('seller_id', $seller->id)
            ->whereIn('product_id', $productIds)
            ->delete();

        return new JsonResponse([
            'message' => trans('admin::app.seller-panel.store-products.mass-removed', ['count' => $deleted]),
        ]);
    }

    public function toggleRecommended(SellerStoreProduct $sellerStoreProduct): JsonResponse|RedirectResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        $wantOn = ! $sellerStoreProduct->is_recommended;

        if ($wantOn) {
            $count = SellerStoreProduct::query()
                ->where('seller_id', $seller->id)
                ->where('is_recommended', true)
                ->where('id', '!=', $sellerStoreProduct->id)
                ->count();

            if ($count >= 100) {
                $message = trans('admin::app.seller-panel.store-products.recommended-limit');

                if (request()->expectsJson()) {
                    return new JsonResponse(['message' => $message], 422);
                }

                return redirect()
                    ->route('admin.seller.store-products.index')
                    ->with('error', $message);
            }
        }

        $sellerStoreProduct->is_recommended = $wantOn;
        $sellerStoreProduct->save();

        if (request()->expectsJson()) {
            return new JsonResponse([
                'message' => trans('admin::app.seller-panel.store-products.recommended-updated'),
                'is_recommended' => $sellerStoreProduct->is_recommended,
            ]);
        }

        return redirect()
            ->route('admin.seller.store-products.index')
            ->with('success', trans('admin::app.seller-panel.store-products.recommended-updated'));
    }

    public function destroy(SellerStoreProduct $sellerStoreProduct): JsonResponse|RedirectResponse
    {
        $this->authorizeSellerStoreProduct($sellerStoreProduct);

        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        if ($msg = $this->storeProductRemovalBlockMessage($seller)) {
            if (request()->ajax()) {
                return new JsonResponse(['message' => $msg], 422);
            }

            return redirect()
                ->route('admin.seller.store-products.index')
                ->with('error', $msg);
        }

        $sellerStoreProduct->delete();

        if (request()->ajax()) {
            return new JsonResponse([
                'message' => trans('admin::app.seller-panel.store-products.removed'),
            ]);
        }

        return redirect()
            ->route('admin.seller.store-products.index')
            ->with('success', trans('admin::app.seller-panel.store-products.removed'));
    }

    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        /** @var Admin $seller */
        $seller = auth()->guard('admin')->user();

        if ($msg = $this->storeProductRemovalBlockMessage($seller)) {
            return new JsonResponse(['message' => $msg], 422);
        }

        $ids = array_map('intval', $request->input('indices', []));

        $deleted = SellerStoreProduct::query()
            ->where('seller_id', $seller->id)
            ->whereIn('id', $ids)
            ->delete();

        return new JsonResponse([
            'message' => trans('admin::app.seller-panel.store-products.mass-removed', ['count' => $deleted]),
        ]);
    }

    protected function authorizeSellerStoreProduct(SellerStoreProduct $sellerStoreProduct): void
    {
        if ((int) $sellerStoreProduct->seller_id !== (int) auth()->guard('admin')->id()) {
            abort(403);
        }
    }

    protected function sellerMayRemoveStoreProducts(Admin $seller): bool
    {
        return $this->storeProductRemovalBlockMessage($seller) === null;
    }

    protected function storeProductRemovalBlockMessage(Admin $seller): ?string
    {
        if (! $seller->created_at) {
            return trans('admin::app.seller-panel.store-products.remove-account-age-unknown');
        }

        if ($seller->created_at->greaterThan(now()->subDays(self::MIN_ACCOUNT_AGE_DAYS_FOR_STORE_PRODUCT_REMOVAL))) {
            return trans('admin::app.seller-panel.store-products.remove-account-age-server', [
                'days' => self::MIN_ACCOUNT_AGE_DAYS_FOR_STORE_PRODUCT_REMOVAL,
            ]);
        }

        return null;
    }
}
