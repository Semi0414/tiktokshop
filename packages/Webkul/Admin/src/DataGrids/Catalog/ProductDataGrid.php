<?php

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Exports\ProductDataGridExport;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\DataGrid\DataGrid;
use Webkul\Product\Helpers\Product;
use Webkul\User\Models\Admin;
use Webkul\User\Support\SellerCommissionPercentRules;

class ProductDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'product_id';

    /**
     * Seller "Product Warehouse" grid: commission, store actions, mass remove from store.
     */
    protected bool $sellerCatalogGridMode = false;

    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct(protected AttributeFamilyRepository $attributeFamilyRepository) {}

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $this->sellerCatalogGridMode = bouncer()->hasPermission('product_management.catalog_products');

        $tablePrefix = DB::getTablePrefix();

        /**
         * Query Builder to fetch records from `product_flat` table
         */
        $queryBuilder = DB::table('product_flat')
            ->distinct()
            ->leftJoin('attribute_families as af', 'product_flat.attribute_family_id', '=', 'af.id')
            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('product_categories as pc', 'product_flat.product_id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function ($leftJoin) {
                $leftJoin->on('pc.category_id', '=', 'ct.category_id')
                    ->where('ct.locale', app()->getLocale());
            })
            ->select(
                'product_flat.locale',
                'product_flat.channel',
                'product_images.path as base_image',
                'pc.category_id',
                'ct.name as category_name',
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'af.name as attribute_family',
            )
            ->addSelect(DB::raw('SUM(DISTINCT '.$tablePrefix.'product_inventories.qty) as quantity'))
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'product_images.id) as images_count'))
            ->where('product_flat.locale', app()->getLocale())
            ->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('products')
                    ->whereColumn('products.id', 'product_flat.product_id');
            })
            ->groupBy('product_flat.product_id');

        if ($this->sellerCatalogGridMode) {
            $sellerId = (int) auth()->guard('admin')->id();
            $queryBuilder->leftJoin('seller_store_products as ssp', function ($join) use ($sellerId) {
                $join->on('product_flat.product_id', '=', 'ssp.product_id')
                    ->where('ssp.seller_id', '=', $sellerId);
            });
            $queryBuilder->addSelect(DB::raw('MAX(ssp.id) as ssp_id'));
            $queryBuilder->addSelect(DB::raw('MAX(ssp.commission_percent) as commission_percent'));
            $queryBuilder->addSelect(DB::raw('MAX(ssp.is_recommended) as is_recommended'));

            /** Only products that belong to this seller's store (not the full catalog). */
            $queryBuilder->whereExists(function ($sub) use ($sellerId) {
                $sub->select(DB::raw(1))
                    ->from('seller_store_products as ssp_scope')
                    ->whereColumn('ssp_scope.product_id', 'product_flat.product_id')
                    ->where('ssp_scope.seller_id', $sellerId);
            });

            $sellerUser = auth()->guard('admin')->user();
            if ($sellerUser instanceof Admin) {
                $allowed = $sellerUser->allowed_product_ids;
                if (is_array($allowed) && count($allowed) > 0) {
                    $queryBuilder->whereIn('product_flat.product_id', array_map('intval', $allowed));
                }
            }
        }

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('channel', 'product_flat.channel');
        $this->addFilter('locale', 'product_flat.locale');
        $this->addFilter('name', 'product_flat.name');
        $this->addFilter('type', 'product_flat.type');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('attribute_family', 'af.id');

        /**
         * Seller panel toolbar (GET params merged into datagrid AJAX — see v-datagrid).
         */
        $sellerProductName = request()->input('seller_product_name');
        if ($sellerProductName !== null && $sellerProductName !== '') {
            $queryBuilder->where('product_flat.name', 'like', '%'.addcslashes((string) $sellerProductName, '%_\\').'%');
        }

        $sellerProductId = request()->input('seller_product_id');
        if ($sellerProductId !== null && $sellerProductId !== '' && is_numeric($sellerProductId)) {
            $queryBuilder->where('product_flat.product_id', (int) $sellerProductId);
        }

        $sellerCategory = request()->input('seller_product_category');
        if ($sellerCategory !== null && $sellerCategory !== '') {
            $queryBuilder->whereExists(function ($sub) use ($sellerCategory) {
                $sub->select(DB::raw(1))
                    ->from('product_categories as pc_filter')
                    ->whereColumn('pc_filter.product_id', 'product_flat.product_id')
                    ->where('pc_filter.category_id', $sellerCategory);
            });
        }

        $sellerProductStatus = request()->input('seller_product_status');
        if ($sellerProductStatus !== null && $sellerProductStatus !== '') {
            $queryBuilder->where('product_flat.status', (int) $sellerProductStatus);
        }

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $channels = core()->getAllChannels();

        if ($channels->count() > 1) {
            $this->addColumn([
                'index' => 'channel',
                'label' => trans('admin::app.catalog.products.index.datagrid.channel'),
                'type' => 'string',
                'filterable' => true,
                'filterable_type' => 'dropdown',
                'filterable_options' => collect($channels)
                    ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->code])
                    ->values()
                    ->toArray(),
                'sortable' => true,
                'visibility' => false,
            ]);
        }

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.catalog.products.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('admin::app.catalog.products.index.datagrid.sku'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'attribute_family',
            'label' => trans('admin::app.catalog.products.index.datagrid.attribute-family'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => $this->attributeFamilyRepository->all(['name as label', 'id as value'])->toArray(),
        ]);

        $this->addColumn([
            'index' => 'base_image',
            'label' => trans('admin::app.catalog.products.index.datagrid.image'),
            'type' => 'string',
            'exportable' => false,
            'closure' => function ($row) {
                if (! $row->base_image) {
                    return;
                }

                return Storage::url($row->base_image);
            },
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('admin::app.catalog.products.index.datagrid.price'),
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('admin::app.catalog.products.index.datagrid.qty'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.catalog.products.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.catalog.products.index.datagrid.status'),
            'type' => 'boolean',
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.catalog.products.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.catalog.products.index.datagrid.disable'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'category_name',
            'label' => trans('admin::app.catalog.products.index.datagrid.category'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => trans('admin::app.catalog.products.index.datagrid.type'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect(config('product_types'))
                ->map(fn ($type) => ['label' => trans($type['name']), 'value' => $type['key']])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);

        if ($this->sellerCatalogGridMode) {
            $this->addColumn([
                'index' => 'commission_percent',
                'label' => trans('admin::app.seller-panel.store-products.commission-percent'),
                'type' => 'decimal',
                'sortable' => true,
            ]);

            $this->addColumn([
                'index' => 'is_recommended',
                'label' => trans('admin::app.seller-panel.store-products.recommended'),
                'type' => 'boolean',
                'sortable' => true,
            ]);
        }
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if ($this->sellerCatalogGridMode) {
            if (bouncer()->hasPermission('catalog.products.edit')) {
                $this->addAction([
                    'icon' => 'icon-sort-right',
                    'title' => trans('admin::app.catalog.products.index.datagrid.edit'),
                    'method' => 'GET',
                    'url' => function ($row) {
                        $filteredChannel = request()->input('filters.channel')[0] ?? null;

                        return route('admin.catalog.products.edit', [
                            'id' => $row->product_id,
                            'channel' => $filteredChannel,
                        ]);
                    },
                ]);
            }

            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.seller-panel.store-products.edit-commission'),
                'method' => 'GET',
                'url' => function ($row) {
                    $sspId = $row->ssp_id ?? null;

                    return $sspId
                        ? route('admin.seller.store-products.edit-commission', ['sellerStoreProduct' => $sspId])
                        : '#';
                },
            ]);

            $this->addAction([
                'icon' => 'icon-sort',
                'title' => trans('admin::app.seller-panel.store-products.toggle-recommended'),
                'method' => 'POST',
                'url' => function ($row) {
                    $sspId = $row->ssp_id ?? null;

                    return $sspId
                        ? route('admin.seller.store-products.toggle-recommended', ['sellerStoreProduct' => $sspId])
                        : '#';
                },
            ]);

            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.seller-panel.store-products.remove-from-store'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    $sspId = $row->ssp_id ?? null;

                    return $sspId
                        ? route('admin.seller.store-products.destroy', ['sellerStoreProduct' => $sspId])
                        : '#';
                },
            ]);

            $this->addAction([
                'icon' => 'icon-add',
                'title' => trans('admin::app.seller-panel.product-warehouse.add-to-store'),
                'method' => 'GET',
                'url' => function ($row) {
                    if (! empty($row->ssp_id)) {
                        return '#';
                    }

                    return route('admin.seller.product-warehouse.attach-one', ['productId' => $row->product_id]);
                },
            ]);

            return;
        }

        if (bouncer()->hasPermission('catalog.products.copy')) {
            $this->addAction([
                'icon' => 'icon-copy',
                'title' => trans('admin::app.catalog.products.index.datagrid.copy'),
                'method' => 'POST',
                'url' => function ($row) {
                    return route('admin.catalog.products.copy', $row->product_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.products.edit')) {
            $this->addAction([
                'icon' => 'icon-sort-right',
                'title' => trans('admin::app.catalog.products.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    $filteredChannel = request()->input('filters.channel')[0] ?? null;

                    return route('admin.catalog.products.edit', [
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
        /**
         * Bulk "Add to store" from catalog: sellers with catalog tab and/or warehouse may attach selected products.
         * Commission modal (15% default; read-only Beginner, editable C–SSS) is handled in mass-action.blade.php.
         */
        $canBulkAddToStore = bouncer()->hasPermission('product_management.catalog_products')
            || bouncer()->hasPermission('product_management.warehouse');

        if ($canBulkAddToStore) {
            $seller = auth()->guard('admin')->user();
            $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

            $this->addMassAction([
                'icon' => 'icon-add',
                'title' => trans('admin::app.seller-panel.product-warehouse.add-to-store'),
                'method' => 'POST',
                'url' => route('admin.seller.product-warehouse.attach'),
                'meta' => [
                    'modal' => 'seller_add_to_store',
                    'commission' => [
                        'readonly' => $rule['readonly'],
                        'min' => $rule['min'],
                        'max' => $rule['max'],
                        'default' => $rule['default'],
                    ],
                ],
            ]);
        }

        /* Seller catalog / warehouse: no mass delete or status change on this grid. */
        if ($this->sellerCatalogGridMode || $canBulkAddToStore) {
            return;
        }

        if (bouncer()->hasPermission('catalog.products.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.catalog.products.index.datagrid.delete'),
                'url' => route('admin.catalog.products.mass_delete'),
                'method' => 'POST',
            ]);
        }

        if (bouncer()->hasPermission('catalog.products.edit')) {
            $this->addMassAction([
                'title' => trans('admin::app.catalog.products.index.datagrid.update-status'),
                'url' => route('admin.catalog.products.mass_update'),
                'method' => 'POST',
                'options' => [
                    [
                        'label' => trans('admin::app.catalog.products.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('admin::app.catalog.products.index.datagrid.disable'),
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
        if ($this->sellerCatalogGridMode) {
            parent::processRequest();

            return;
        }

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
            ->whereIn('product_flat.product_id', $ids);

        if ($ids !== []) {
            $this->queryBuilder
                ->orderBy(DB::raw('FIELD('.DB::getTablePrefix().'product_flat.product_id, '.implode(',', $ids).')'));
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
