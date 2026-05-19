<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Shop\Support\SellerPreview;
use Webkul\Shop\Support\TikStoreProductDummyDisplay;
use Webkul\User\Models\Admin;

class TikStoreController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected TikStoreProductDummyDisplay $tikStoreDummy
    ) {}

    public function index(Request $request): View
    {
        $seller = $this->resolveSeller($request);
        $categoryId = $this->parseCategoryId($request);
        $filters = $this->filterParamsFromRequest($request);

        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        $carouselCategories = $this->categoryRepository
            ->getChildCategories((int) core()->getCurrentChannel()->root_category_id)
            ->sortBy('position')
            ->values()
            ->map(fn ($cat) => (object) [
                'id' => $cat->id,
                'name' => $this->categoryStringLabel($cat),
                'logo_url' => $cat->logo_url,
            ]);

        if ($seller) {
            $recommendedPreview = $this->fetchSellerRecommendedPreview($seller->id, $channel, $locale, 10);
            $latestPreview = $this->fetchSellerStoreLatestPreview($seller->id, $channel, $locale, 10);
            $gridProducts = $this->paginateSellerProducts($seller->id, $channel, $locale, $categoryId, $filters);
        } else {
            $recommendedPreview = $this->fetchGlobalRecommendedPreview($channel, $locale, 10);
            $latestPreview = $this->fetchGlobalLatestPreview($channel, $locale, 10);
            $gridProducts = $this->paginateGlobalProducts($channel, $locale, $categoryId, $filters);
        }

        return view('shop::tik-store.index', [
            'seller' => $seller,
            'carouselCategories' => $carouselCategories,
            'recommendedPreview' => $recommendedPreview,
            'latestPreview' => $latestPreview,
            'gridProducts' => $gridProducts,
            'selectedCategoryId' => $categoryId,
            'filters' => $filters,
            'linkParams' => $this->mergedQueryParams($seller, $filters, null),
            'heroSlides' => $this->heroSlideUrls(),
            'apiSellersSearchUrl' => route('shop.api.sellers.search', ['name_only' => 1]),
            'wishlistedProductIds' => $this->wishlistedProductIds(),
        ]);
    }

    /**
     * Query string for TikStore links (seller, category, active filters).
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    protected function mergedQueryParams(?Admin $seller, array $filters, ?int $categoryId): array
    {
        $params = [];
        $request = request();

        if ($request->query('global') === '1') {
            $params['global'] = '1';
        }

        if ($request->filled('ref')) {
            $params['ref'] = (string) $request->query('ref');
        }

        if ($seller) {
            $params['seller'] = $seller->id;
        }

        if ($categoryId) {
            $params['category'] = $categoryId;
        }

        foreach ($filters as $key => $value) {
            if ($value === null || $value === '' || $value === false) {
                continue;
            }

            if ($key === 'sort' && $value === 'newest') {
                continue;
            }

            $params[$key] = $value;
        }

        return $params;
    }

    /**
     * Paginated list: all recommended products (seller-scoped or global).
     */
    public function recommended(Request $request): View
    {
        $seller = $this->resolveSeller($request);
        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        if ($seller) {
            $products = $this->querySellerRecommended($seller->id, $channel, $locale)
                ->orderByDesc('ssp.updated_at')
                ->orderByDesc('ssp.id')
                ->paginate(24)
                ->withQueryString();
        } else {
            $products = $this->queryGlobalRecommended($channel, $locale)
                ->orderBy('pf.product_id', 'asc')
                ->paginate(24)
                ->withQueryString();
        }

        $products->setCollection($this->decorateProductRows($products->getCollection()));

        return view('shop::tik-store.list', [
            'seller' => $seller,
            'title' => __('Recommended products'),
            'products' => $products,
            'apiSellersSearchUrl' => route('shop.api.sellers.search', ['name_only' => 1]),
            'backUrl' => route('shop.tik-store.index', array_filter(['seller' => $seller?->id])),
            'wishlistedProductIds' => $this->wishlistedProductIds(),
        ]);
    }

    /**
     * Paginated list: all products (newest first), seller store or global catalog.
     */
    public function allProducts(Request $request): View
    {
        $seller = $this->resolveSeller($request);
        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        if ($seller) {
            $products = $this->querySellerAllProducts($seller->id, $channel, $locale)
                ->orderByDesc('ssp.updated_at')
                ->orderByDesc('ssp.id')
                ->paginate(24)
                ->withQueryString();
        } else {
            $products = $this->queryGlobalAllProducts($channel, $locale)
                ->orderByDesc('pf.updated_at')
                ->orderByDesc('pf.product_id')
                ->paginate(24)
                ->withQueryString();
        }

        $products->setCollection($this->decorateProductRows($products->getCollection()));

        return view('shop::tik-store.list', [
            'seller' => $seller,
            'title' => __('All products'),
            'products' => $products,
            'apiSellersSearchUrl' => route('shop.api.sellers.search', ['name_only' => 1]),
            'backUrl' => route('shop.tik-store.index', array_filter(['seller' => $seller?->id])),
            'wishlistedProductIds' => $this->wishlistedProductIds(),
        ]);
    }

    protected function resolveSeller(Request $request): ?Admin
    {
        /**
         * Full-catalog TikStore (ignore seller preview session / stale session).
         * Super Admin "Visit TikStore" links use ?global=1&ref=...
         */
        if ($request->query('global') === '1') {
            return null;
        }

        $id = $request->query('seller');
        if ($id !== null && $id !== '' && is_numeric($id)) {
            $seller = Admin::query()
                ->where('id', (int) $id)
                ->where('status', 1)
                ->first();

            if ($seller) {
                return $seller;
            }
        }

        $previewId = SellerPreview::resolveSellerIdFromSession();
        if ($previewId > 0) {
            return Admin::query()->whereKey($previewId)->first();
        }

        return null;
    }

    protected function parseCategoryId(Request $request): ?int
    {
        $c = $request->query('category');
        if ($c === null || $c === '' || ! is_numeric($c)) {
            return null;
        }

        return (int) $c;
    }

    protected function fetchGlobalRecommendedPreview(string $channel, string $locale, int $limit): Collection
    {
        $rows = $this->queryGlobalRecommended($channel, $locale)
            ->orderBy('pf.product_id', 'asc')
            ->limit($limit)
            ->get();

        return $this->decorateProductRows($rows);
    }

    protected function fetchGlobalLatestPreview(string $channel, string $locale, int $limit): Collection
    {
        $rows = $this->queryGlobalAllProducts($channel, $locale)
            ->orderByDesc('pf.updated_at')
            ->orderByDesc('pf.product_id')
            ->limit($limit)
            ->get();

        return $this->decorateProductRows($rows);
    }

    protected function fetchSellerRecommendedPreview(int $sellerId, string $channel, string $locale, int $limit): Collection
    {
        $rows = $this->querySellerRecommended($sellerId, $channel, $locale)
            ->orderByDesc('ssp.updated_at')
            ->orderByDesc('ssp.id')
            ->limit($limit)
            ->get();

        return $this->decorateProductRows($rows);
    }

    protected function fetchSellerStoreLatestPreview(int $sellerId, string $channel, string $locale, int $limit): Collection
    {
        $rows = $this->querySellerAllProducts($sellerId, $channel, $locale)
            ->orderByDesc('ssp.updated_at')
            ->orderByDesc('ssp.id')
            ->limit($limit)
            ->get();

        return $this->decorateProductRows($rows);
    }

    protected function paginateGlobalProducts(string $channel, string $locale, ?int $categoryId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->queryGlobalAllProducts($channel, $locale);

        $this->applyCategoryFilter($query, $categoryId);
        $this->applyGridFilters($query, $filters);

        $this->applySort($query, $filters['sort'] ?? 'newest');

        $paginator = $query
            ->paginate(24)
            ->withQueryString();

        $paginator->setCollection($this->decorateProductRows($paginator->getCollection()));

        return $paginator;
    }

    protected function paginateSellerProducts(int $sellerId, string $channel, string $locale, ?int $categoryId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->querySellerAllProducts($sellerId, $channel, $locale);

        $this->applyCategoryFilter($query, $categoryId);
        $this->applyGridFilters($query, $filters);

        $this->applySort($query, $filters['sort'] ?? 'newest', true);

        $paginator = $query
            ->paginate(24)
            ->withQueryString();

        $paginator->setCollection($this->decorateProductRows($paginator->getCollection()));

        return $paginator;
    }

    /**
     * Theme hero images (storage path under public).
     *
     * @return array<int, array{src:string, alt:string}>
     */
    protected function heroSlideUrls(): array
    {
        $paths = [
            'storage/theme/1/cDUsChlshOk9TdbuNwWU78J1f6LmYVG7mfnwtpqs.webp',
            'storage/theme/1/eZcrwu8sQTRaMc5YroLssyyUbNVxWbXlVxh4CXgE.webp',
            'storage/theme/1/7ALoZ2RdNEor19xQ52fC1cmm5uD51xW6kgjH5qzf.webp',
            'storage/theme/1/yabS9XMVogtynq1puvzNn10skGxJNBPZ6udb6Edh.webp',
        ];

        $alt = __('Get Ready For New Collection');

        return array_map(fn (string $p) => [
            'src' => asset($p),
            'alt' => $alt,
        ], $paths);
    }

    /**
     * @return array<string, mixed>
     */
    protected function filterParamsFromRequest(Request $request): array
    {
        $filters = [];

        if ($request->filled('min_price') && is_numeric($request->query('min_price'))) {
            $filters['min_price'] = (float) $request->query('min_price');
        }

        if ($request->filled('max_price') && is_numeric($request->query('max_price'))) {
            $filters['max_price'] = (float) $request->query('max_price');
        }

        if ($request->filled('min_rating') && is_numeric($request->query('min_rating'))) {
            $filters['min_rating'] = (float) $request->query('min_rating');
        }

        if ($request->boolean('free_shipping')) {
            $filters['free_shipping'] = true;
        }

        if ($request->boolean('dispatch_24h')) {
            $filters['dispatch_24h'] = true;
        }

        $sort = (string) $request->query('sort', 'newest');
        $allowed = ['newest', 'price_asc', 'price_desc', 'top_rated'];
        $filters['sort'] = in_array($sort, $allowed, true) ? $sort : 'newest';

        return $filters;
    }

    protected function effectivePriceSql(): string
    {
        return '(CASE
            WHEN pf.special_price IS NOT NULL AND pf.special_price > 0
                AND (pf.special_price_from IS NULL OR DATE(pf.special_price_from) <= CURDATE())
                AND (pf.special_price_to IS NULL OR DATE(pf.special_price_to) >= CURDATE())
            THEN LEAST(pf.price, pf.special_price)
            ELSE pf.price
        END)';
    }

    /**
     * Pseudo rating (deterministic) for filtering when reviews are sparse — matches card display.
     */
    protected function pseudoRatingSql(): string
    {
        return '(3.6 + (MOD(pf.product_id, 15) / 10.0))';
    }

    /**
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     */
    protected function applyGridFilters($query, array $filters): void
    {
        $eff = $this->effectivePriceSql();

        if (! empty($filters['min_price'])) {
            $query->whereRaw($eff.' >= ?', [(float) $filters['min_price']]);
        }

        if (! empty($filters['max_price'])) {
            $query->whereRaw($eff.' <= ?', [(float) $filters['max_price']]);
        }

        if (! empty($filters['min_rating'])) {
            $min = (float) $filters['min_rating'];
            $query->whereRaw($this->pseudoRatingSql().' >= ?', [$min]);
        }

        $free = ! empty($filters['free_shipping']);
        $dispatch = ! empty($filters['dispatch_24h']);

        if ($free && $dispatch) {
            $query->whereRaw('MOD(pf.product_id, 3) IN (0, 1)');
        } elseif ($free) {
            $query->whereRaw('MOD(pf.product_id, 3) = 0');
        } elseif ($dispatch) {
            $query->whereRaw('MOD(pf.product_id, 3) = 1');
        }
    }

    /**
     * @param  Builder  $query
     */
    protected function applySort($query, string $sort, bool $sellerScope = false): void
    {
        $eff = $this->effectivePriceSql();
        $pseudo = $this->pseudoRatingSql();

        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw($eff.' ASC')
                    ->orderBy('pf.product_id', 'asc');
                break;

            case 'price_desc':
                $query->orderByRaw($eff.' DESC')
                    ->orderBy('pf.product_id', 'desc');
                break;

            case 'top_rated':
                $query->orderByRaw($pseudo.' DESC')
                    ->orderBy('pf.updated_at', 'desc');
                break;

            case 'newest':
            default:
                if ($sellerScope) {
                    $query->orderByDesc('ssp.updated_at')
                        ->orderByDesc('ssp.id');
                } else {
                    $query->orderByDesc('pf.updated_at')
                        ->orderByDesc('pf.product_id');
                }
                break;
        }
    }

    protected function queryGlobalRecommended(string $channel, string $locale)
    {
        return $this->baseProductFlatQuery($channel, $locale);
    }

    protected function queryGlobalAllProducts(string $channel, string $locale)
    {
        return $this->baseProductFlatQuery($channel, $locale);
    }

    protected function querySellerRecommended(int $sellerId, string $channel, string $locale)
    {
        return $this->baseProductFlatQuery($channel, $locale)
            ->join('seller_store_products as ssp', 'ssp.product_id', '=', 'pf.product_id')
            ->where('ssp.seller_id', $sellerId)
            ->where('ssp.is_recommended', 1)
            ->addSelect('ssp.id as ssp_id');
    }

    protected function querySellerAllProducts(int $sellerId, string $channel, string $locale)
    {
        return $this->baseProductFlatQuery($channel, $locale)
            ->join('seller_store_products as ssp', 'ssp.product_id', '=', 'pf.product_id')
            ->where('ssp.seller_id', $sellerId)
            ->addSelect('ssp.id as ssp_id');
    }

    protected function baseProductFlatQuery(string $channel, string $locale)
    {
        $imageSql = '(SELECT pi.path FROM product_images pi WHERE pi.product_id = pf.product_id ORDER BY pi.position ASC, pi.id ASC LIMIT 1)';

        return DB::table('product_flat as pf')
            ->where('pf.channel', $channel)
            ->where('pf.locale', $locale)
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->whereNull('pf.parent_id')
            ->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('product_images as pi')
                    ->whereColumn('pi.product_id', 'pf.product_id');
            })
            ->select(
                'pf.product_id',
                'pf.sku',
                'pf.name',
                'pf.url_key',
                'pf.price',
                'pf.special_price',
                'pf.special_price_from',
                'pf.special_price_to',
            )
            ->selectRaw($imageSql.' as img_path');
    }

    protected function applyCategoryFilter($query, ?int $categoryId): void
    {
        if (! $categoryId) {
            return;
        }

        $query->whereExists(function ($sub) use ($categoryId) {
            $sub->select(DB::raw(1))
                ->from('product_categories as pc')
                ->whereColumn('pc.product_id', 'pf.product_id')
                ->where('pc.category_id', $categoryId);
        });
    }

    /**
     * Product IDs in the current customer's wishlist (for TikStore cards).
     *
     * @return array<int, int>
     */
    protected function wishlistedProductIds(): array
    {
        if (! auth()->guard('customer')->check()) {
            return [];
        }

        return auth()->guard('customer')->user()
            ->wishlist_items()
            ->where('channel_id', core()->getCurrentChannel()->id)
            ->pluck('product_id')
            ->all();
    }

    /**
     * @param  Collection<int, object>  $rows
     * @return Collection<int, object>
     */
    protected function decorateProductRows(Collection $rows): Collection
    {
        $rows = $rows->map(function ($row) {
            $path = $row->img_path ?? null;
            $row->image_url = $path ? Storage::url($path) : '';
            $row->product_url = $row->url_key
                ? $this->productUrlWithPid($row->url_key, (int) $row->product_id)
                : '#';
            $row->final_price = $this->tikStoreDummy->resolveFinalPrice($row);
            $row->show_strike = $this->tikStoreDummy->shouldShowStrikePrice($row);

            return $this->tikStoreDummy->enrich($row);
        });

        return $this->enrichCartMeta($rows);
    }

    /**
     * Product type + default variant for TikStore quick add-to-cart.
     *
     * @param  Collection<int, object>  $rows
     * @return Collection<int, object>
     */
    protected function enrichCartMeta(Collection $rows): Collection
    {
        $productIds = $rows->pluck('product_id')->map(fn ($id) => (int) $id)->filter()->unique()->values()->all();

        if ($productIds === []) {
            return $rows;
        }

        $types = DB::table('products')
            ->whereIn('id', $productIds)
            ->pluck('type', 'id');

        $defaultVariants = DB::table('products')
            ->whereIn('parent_id', $productIds)
            ->where('type', 'simple')
            ->selectRaw('parent_id, MIN(id) as variant_id')
            ->groupBy('parent_id')
            ->pluck('variant_id', 'parent_id');

        return $rows->map(function ($row) use ($types, $defaultVariants) {
            $pid = (int) ($row->product_id ?? 0);
            $type = (string) ($types[$pid] ?? 'simple');
            $variantId = (int) ($defaultVariants[$pid] ?? 0);

            $row->product_type = $type;
            $row->default_variant_id = $type === 'configurable' && $variantId > 0 ? $variantId : null;
            $row->can_quick_add = in_array($type, ['simple', 'virtual'], true)
                || ($type === 'configurable' && $variantId > 0);

            return $row;
        });
    }

    /**
     * Show the category string as stored (name/slug), including import-style string ids — not the numeric PK.
     */
    protected function categoryStringLabel(object $category): string
    {
        $try = trim((string) ($category->name ?? ''));
        if ($try !== '') {
            return $try;
        }

        foreach ($category->translations ?? [] as $translation) {
            $try = trim((string) ($translation->name ?? ''));
            if ($try !== '') {
                return $try;
            }
        }

        $try = trim((string) ($category->slug ?? ''));
        if ($try !== '') {
            return $try;
        }

        foreach ($category->translations ?? [] as $translation) {
            $try = trim((string) ($translation->slug ?? ''));
            if ($try !== '') {
                return $try;
            }
        }

        return (string) $category->id;
    }

    /**
     * Canonical product URL with pid so PDP resolves the correct product when url_key collides.
     */
    protected function productUrlWithPid(string $urlKey, int $productId): string
    {
        $base = route('shop.product_or_category.index', $urlKey);

        return $base.(str_contains($base, '?') ? '&' : '?').'pid='.$productId;
    }
}
