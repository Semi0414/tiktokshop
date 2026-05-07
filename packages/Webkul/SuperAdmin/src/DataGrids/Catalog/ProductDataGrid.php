<?php

namespace Webkul\SuperAdmin\DataGrids\Catalog;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\DataGrid\DataGrid;
use Webkul\Product\Helpers\Product;
use Webkul\SuperAdmin\Exports\ProductDataGridExport;

class ProductDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'product_id';

    /**
     * Default sort: newest product ID first (descending).
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Default page size for the products grid.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Per page options exposed to the dropdown above the grid.
     *
     * @var array
     */
    protected $perPageOptions = [10, 25, 50, 100, 200];

    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct(protected AttributeFamilyRepository $attributeFamilyRepository) {}

    /**
     * Prepare query builder.
     *
     * Source of truth is the `products` table joined with `product_attribute_values`
     * for name / price / status / url_key, instead of the denormalized `product_flat`
     * table (whose rows from CSV-imported placeholder products have empty fields).
     *
     * Only products that have at least one row in `product_images` are returned.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $locale = app()->getLocale();

        $channel = method_exists(core(), 'getRequestedChannelCode')
            ? (core()->getRequestedChannelCode() ?: 'default')
            : 'default';

        /**
         * Pre-aggregated subqueries (one row per product) so the outer query
         * doesn't fan out across joins.
         */
        $nameSub = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->where('a.code', 'name')
            ->where(function ($q) use ($locale) {
                $q->whereNull('pav.locale')->orWhere('pav.locale', $locale);
            })
            ->select('pav.product_id', 'pav.text_value as value');

        $priceSub = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->where('a.code', 'price')
            ->select('pav.product_id', 'pav.float_value as value');

        $statusSub = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->where('a.code', 'status')
            ->where(function ($q) use ($channel) {
                $q->whereNull('pav.channel')->orWhere('pav.channel', $channel);
            })
            ->select('pav.product_id', 'pav.boolean_value as value');

        $urlKeySub = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->where('a.code', 'url_key')
            ->where(function ($q) use ($locale) {
                $q->whereNull('pav.locale')->orWhere('pav.locale', $locale);
            })
            ->select('pav.product_id', 'pav.text_value as value');

        $visibleSub = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->where('a.code', 'visible_individually')
            ->select('pav.product_id', 'pav.boolean_value as value');

        $imageSub = DB::table('product_images')
            ->select(
                'product_id',
                DB::raw('MIN(id) as base_image_id'),
                DB::raw('COUNT(*) as images_count')
            )
            ->groupBy('product_id');

        $inventorySub = DB::table('product_inventories')
            ->select(
                'product_id',
                DB::raw('SUM(qty) as qty')
            )
            ->groupBy('product_id');

        /**
         * Aggregates from the catalog-related tables shown in the picture: reviews,
         * videos, channels, and the full list of categories per product.
         */
        $reviewsSub = DB::table('product_reviews')
            ->select(
                'product_id',
                DB::raw('COUNT(*) as reviews_count'),
                DB::raw('AVG(rating) as avg_rating')
            )
            ->groupBy('product_id');

        $videosSub = DB::table('product_videos')
            ->select(
                'product_id',
                DB::raw('COUNT(*) as videos_count')
            )
            ->groupBy('product_id');

        $channelsSub = DB::table('product_channels')
            ->select(
                'product_id',
                DB::raw('COUNT(DISTINCT channel_id) as channels_count')
            )
            ->groupBy('product_id');

        $categoriesSub = DB::table('product_categories as pc')
            ->leftJoin('category_translations as ct', function ($j) use ($locale) {
                $j->on('pc.category_id', '=', 'ct.category_id')
                    ->where('ct.locale', $locale);
            })
            ->select(
                'pc.product_id',
                DB::raw('GROUP_CONCAT(DISTINCT '.DB::getTablePrefix().'ct.name SEPARATOR ", ") as names'),
                DB::raw('COUNT(DISTINCT '.DB::getTablePrefix().'pc.category_id) as categories_count')
            )
            ->groupBy('pc.product_id');

        $queryBuilder = DB::table('products')
            ->leftJoin('attribute_families as af', 'products.attribute_family_id', '=', 'af.id')
            ->joinSub($imageSub, 'pi', 'pi.product_id', '=', 'products.id')
            ->leftJoin('product_images as base_img', 'base_img.id', '=', 'pi.base_image_id')
            ->leftJoinSub($inventorySub, 'inv', 'inv.product_id', '=', 'products.id')
            ->leftJoinSub($nameSub, 'pav_name', 'pav_name.product_id', '=', 'products.id')
            ->leftJoinSub($priceSub, 'pav_price', 'pav_price.product_id', '=', 'products.id')
            ->leftJoinSub($statusSub, 'pav_status', 'pav_status.product_id', '=', 'products.id')
            ->leftJoinSub($urlKeySub, 'pav_url', 'pav_url.product_id', '=', 'products.id')
            ->leftJoinSub($visibleSub, 'pav_vis', 'pav_vis.product_id', '=', 'products.id')
            ->leftJoinSub($reviewsSub, 'rv', 'rv.product_id', '=', 'products.id')
            ->leftJoinSub($videosSub, 'vd', 'vd.product_id', '=', 'products.id')
            ->leftJoinSub($channelsSub, 'pchn', 'pchn.product_id', '=', 'products.id')
            ->leftJoinSub($categoriesSub, 'cat', 'cat.product_id', '=', 'products.id')
            ->select(
                DB::raw("'$locale' as locale"),
                DB::raw("'$channel' as channel"),
                'products.id as product_id',
                'products.sku',
                'products.type',
                'products.created_at',
                'products.updated_at',
                'base_img.path as base_image',
                'pi.images_count',
                'pav_name.value as name',
                'pav_price.value as price',
                'pav_status.value as status',
                'pav_url.value as url_key',
                'pav_vis.value as visible_individually',
                'af.name as attribute_family',
                'inv.qty as quantity',
                DB::raw('COALESCE('.DB::getTablePrefix().'rv.reviews_count, 0) as reviews_count'),
                DB::raw('ROUND(COALESCE('.DB::getTablePrefix().'rv.avg_rating, 0), 2) as avg_rating'),
                DB::raw('COALESCE('.DB::getTablePrefix().'vd.videos_count, 0) as videos_count'),
                DB::raw('COALESCE('.DB::getTablePrefix().'pchn.channels_count, 0) as channels_count'),
                'cat.names as category_name',
                DB::raw('COALESCE('.DB::getTablePrefix().'cat.categories_count, 0) as categories_count')
            );

        $this->addFilter('product_id', 'products.id');
        $this->addFilter('channel', DB::raw("'$channel'"));
        $this->addFilter('locale', DB::raw("'$locale'"));
        $this->addFilter('name', 'pav_name.value');
        $this->addFilter('sku', 'products.sku');
        $this->addFilter('type', 'products.type');
        $this->addFilter('status', 'pav_status.value');
        $this->addFilter('price', 'pav_price.value');
        $this->addFilter('quantity', 'inv.qty');
        $this->addFilter('attribute_family', 'af.id');
        $this->addFilter('created_at', 'products.created_at');
        $this->addFilter('updated_at', 'products.updated_at');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * Column order is intentional: image first (visual anchor), then identifying
     * fields (ID, Name, SKU), then catalog metadata (Type, Family, Categories),
     * then commercial fields (Price, Qty, Status), then engagement signals
     * (Reviews, Videos, Channels, Images), then timestamps.
     *
     * @return void
     */
    public function prepareColumns()
    {
        /**
         * 1. Image - visual anchor, first column.
         */
        $this->addColumn([
            'index' => 'base_image',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.image'),
            'type' => 'string',
            'exportable' => false,
            'closure' => function ($row) {
                $placeholder = bagisto_asset('images/product-placeholders/front.svg');

                $renderImg = fn ($src, $extra = '') => '<img src="'.e($src).'" alt="" class="h-12 w-12 rounded '.$extra.' object-cover" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=\''.e($placeholder).'\';this.classList.add(\'border\',\'border-dashed\',\'border-gray-300\');" />';

                if (! $row->base_image) {
                    return $renderImg($placeholder, 'border border-dashed border-gray-300 dark:border-gray-800');
                }

                /**
                 * Path may be either:
                 *   1. A local relative path under the public disk (e.g. `product/1/foo.webp`)
                 *   2. A full external URL (e.g. https://mall-test.s3.amazonaws.com/...)
                 *      — set by `tiktokshop:link-image-urls` for CSV-imported products
                 *      that don't have local binaries downloaded.
                 */
                $path = (string) $row->base_image;

                if (preg_match('#^https?://#i', $path)) {
                    return $renderImg($path);
                }

                /**
                 * Local path — fall back to placeholder when the binary is missing
                 * on disk (avoid the browser showing a broken/forbidden icon).
                 */
                if (! Storage::disk('public')->exists($path)) {
                    return $renderImg($placeholder, 'border border-dashed border-amber-300 dark:border-amber-700');
                }

                return $renderImg(Storage::url($path));
            },
        ]);

        /**
         * 2. Product ID.
         */
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        /**
         * 3. Name.
         */
        $this->addColumn([
            'index' => 'name',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                $name = trim((string) ($row->name ?? ''));

                if ($name === '') {
                    return 'N/A';
                }

                $shortName = Str::limit($name, 60, '...');

                return '<span title="'.e($name).'">'.e($shortName).'</span>';
            },
        ]);

        /**
         * 4. SKU.
         */
        $this->addColumn([
            'index' => 'sku',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        /**
         * 5. Type.
         */
        $this->addColumn([
            'index' => 'type',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.type'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect(config('product_types'))
                ->map(fn ($type) => ['label' => trans($type['name']), 'value' => $type['key']])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);

        /**
         * 6. Attribute Family.
         */
        $this->addColumn([
            'index' => 'attribute_family',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.attribute-family'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => $this->attributeFamilyRepository->all(['name as label', 'id as value'])->toArray(),
        ]);

        /**
         * 7. Categories (multi).
         */
        $this->addColumn([
            'index' => 'category_name',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.category'),
            'type' => 'string',
        ]);

        /**
         * 8. Price.
         */
        $this->addColumn([
            'index' => 'price',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.price'),
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        /**
         * 9. Quantity.
         */
        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.qty'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        /**
         * 10. Status.
         */
        $this->addColumn([
            'index' => 'status',
            'label' => trans('superadmin::app.catalog.products.index.datagrid.status'),
            'type' => 'boolean',
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('superadmin::app.catalog.products.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('superadmin::app.catalog.products.index.datagrid.disable'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
        ]);

        /**
         * 11. Reviews (count + avg rating).
         */
        $this->addColumn([
            'index' => 'reviews_count',
            'label' => 'Reviews',
            'type' => 'integer',
            'sortable' => true,
            'closure' => function ($row) {
                $count = (int) ($row->reviews_count ?? 0);
                $avg = (float) ($row->avg_rating ?? 0);

                if ($count === 0) {
                    return '<span class="text-gray-400">0</span>';
                }

                return '<span class="font-semibold">'.$count.'</span> <span class="text-amber-500">★ '.number_format($avg, 1).'</span>';
            },
        ]);

        /**
         * 12. Videos count.
         */
        $this->addColumn([
            'index' => 'videos_count',
            'label' => 'Videos',
            'type' => 'integer',
            'sortable' => true,
        ]);

        /**
         * 13. Images count.
         */
        $this->addColumn([
            'index' => 'images_count',
            'label' => 'Images',
            'type' => 'integer',
            'sortable' => true,
        ]);

        /**
         * 14. Channels count.
         */
        $this->addColumn([
            'index' => 'channels_count',
            'label' => 'Channels',
            'type' => 'integer',
            'sortable' => true,
        ]);

        /**
         * 15. Created at (least relevant — pushed to the end).
         */
        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Created',
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('catalog.products.copy')) {
            $this->addAction([
                'icon' => 'icon-copy',
                'title' => trans('superadmin::app.catalog.products.index.datagrid.copy'),
                'method' => 'POST',
                'url' => function ($row) {
                    return route('superadmin.catalog.products.copy', $row->product_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.products.edit')) {
            $this->addAction([
                'icon' => 'icon-sort-right',
                'title' => trans('superadmin::app.catalog.products.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    $filteredChannel = request()->input('filters.channel')[0] ?? null;

                    return route('superadmin.catalog.products.edit', [
                        'id' => $row->product_id,
                        'channel' => $filteredChannel,
                    ]);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('catalog.products.delete')) {
            $this->addMassAction([
                'title' => trans('superadmin::app.catalog.products.index.datagrid.delete'),
                'url' => route('superadmin.catalog.products.mass_delete'),
                'method' => 'POST',
            ]);
        }

        if (bouncer()->hasPermission('catalog.products.edit')) {
            $this->addMassAction([
                'title' => trans('superadmin::app.catalog.products.index.datagrid.update-status'),
                'url' => route('superadmin.catalog.products.mass_update'),
                'method' => 'POST',
                'options' => [
                    [
                        'label' => trans('superadmin::app.catalog.products.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('superadmin::app.catalog.products.index.datagrid.disable'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }

    /**
     * Return a custom exporter that includes all product attribute values.
     */
    public function getExporter(): ProductDataGridExport
    {
        return new ProductDataGridExport($this);
    }

    /**
     * Process request.
     */
    protected function processRequest(): void
    {
        if (
            core()->getConfigData('catalog.products.search.engine') != 'elastic'
            || core()->getConfigData('catalog.products.search.admin_mode') != 'elastic'
        ) {
            parent::processRequest();

            return;
        }

        /**
         * Store all request parameters in this variable; avoid using direct request helpers afterward.
         */
        $params = $this->validatedRequest();

        if (isset($params['export']) && (bool) $params['export']) {
            parent::processRequest();

            return;
        }

        $this->dispatchEvent('process_request.before', $this);

        $pagination = $params['pagination'];

        $channelCodes = request()->input('filters.channel') ?? core()->getAllChannels()->pluck('code')->toArray();

        $indexNames = collect($channelCodes)->map(function ($channelCode) {
            return Product::formatElasticSearchIndexName($channelCode, app()->getLocale());
        })->toArray();

        $results = ElasticSearch::search([
            'index' => $indexNames,
            'body' => [
                'from' => ($pagination['page'] * $pagination['per_page']) - $pagination['per_page'],
                'size' => $pagination['per_page'],
                'stored_fields' => [],
                'query' => [
                    'bool' => $this->getElasticFilters($params['filters'] ?? []) ?: new \stdClass,
                ],
                'sort' => $this->getElasticSort($params['sort'] ?? []),
                'track_total_hits' => true,
            ],
        ]);

        $ids = collect($results['hits']['hits'])->pluck('_id')->map(fn ($id) => (int) $id)->values()->toArray();

        /**
         * Elasticsearch can still list product IDs briefly after DB delete (async index jobs).
         * Only load rows that still exist in `products`.
         */
        if ($ids !== []) {
            $existingProductIds = DB::table('products')->whereIn('id', $ids)->pluck('id')->all();
            $keep = array_flip($existingProductIds);
            $ids = array_values(array_filter($ids, static fn ($id) => isset($keep[$id])));
        }

        $this->queryBuilder
            ->whereIn('products.id', $ids);

        if ($ids !== []) {
            $this->queryBuilder
                ->orderBy(DB::raw('FIELD('.DB::getTablePrefix().'products.id, '.implode(',', $ids).')'));
        }

        $total = $results['hits']['total']['value'];

        $this->paginator = new LengthAwarePaginator(
            $total ? $this->queryBuilder->get() : [],
            $total,
            $pagination['per_page'],
            $pagination['page'],
            [
                'path' => request()->url(),
                'query' => [],
            ]
        );

        $this->dispatchEvent('process_request.after', $this);
    }

    /**
     * Process request.
     */
    protected function getElasticFilters($params): array
    {
        $filters = [];

        foreach ($params as $attribute => $value) {
            if (in_array($attribute, ['channel', 'locale'])) {
                continue;
            }

            if ($attribute == 'all') {
                $attribute = 'name';
            }

            $filters['filter'][] = $this->getFilterValue($attribute, $value);
        }

        return $filters;
    }

    /**
     * Return applied filters
     */
    public function getFilterValue(mixed $attribute, mixed $values): array
    {
        switch ($attribute) {
            case 'product_id':
                return [
                    'terms' => [
                        'id' => $values,
                    ],
                ];

            case 'attribute_family':
                return [
                    'terms' => [
                        'attribute_family_id' => $values,
                    ],
                ];

            case 'sku':
            case 'name':
                $filters = [];

                foreach ($values as $value) {
                    $filters['bool']['should'][] = [
                        'match_phrase_prefix' => [
                            $attribute => $value,
                        ],
                    ];
                }

                return $filters;

            default:
                return [
                    'terms' => [
                        $attribute => $values,
                    ],
                ];
        }
    }

    /**
     * Process request.
     */
    protected function getElasticSort($params): array
    {
        $sort = $params['column'] ?? $this->primaryColumn;

        if ($sort == 'type') {
            $sort .= '.keyword';
        }

        if ($sort == 'name') {
            $sort .= '.keyword';
        }

        if ($sort == 'attribute_family') {
            $sort .= '_id';
        }

        if ($sort == 'product_id') {
            $sort = 'id';
        }

        return [
            $sort => [
                'order' => $params['order'] ?? $this->sortOrder,
            ],
        ];
    }
}
