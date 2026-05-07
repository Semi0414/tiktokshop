<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Enums\AttributeTypeEnum;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketing\Repositories\SearchSynonymRepository;
use Webkul\Product\Contracts\Product;

class ProductRepository extends Repository
{
    /**
     * Search engine.
     */
    protected $searchEngine = 'database';

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ElasticSearchRepository $elasticSearchRepository,
        protected SearchSynonymRepository $searchSynonymRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Product::class;
    }

    /**
     * Create product.
     *
     * @return Product
     */
    public function create(array $data)
    {
        $typeInstance = app(config('product_types.'.$data['type'].'.class'));

        $product = $typeInstance->create($data);

        return $product;
    }

    /**
     * Update product.
     *
     * @param  int  $id
     * @param  array  $attributes
     * @return Product
     */
    public function update(array $data, $id, $attributes = [])
    {
        $product = $this->findOrFail($id);

        $product = $product->getTypeInstance()->update($data, $id, $attributes);

        $product->refresh();

        return $product;
    }

    /**
     * Suggest products based on query.
     */
    public function getSuggestions(?string $query): ?string
    {
        if (
            $this->searchEngine == 'elastic'
            && ! empty($query)
        ) {
            return $this->elasticSearchRepository->getSuggestions($query);
        }

        return null;
    }

    /**
     * Copy product.
     *
     * @param  int  $id
     * @return Product
     */
    public function copy($id)
    {
        $product = $this->with([
            'attribute_family',
            'categories',
            'customer_group_prices',
            'inventories',
            'inventory_sources',
        ])->findOrFail($id);

        if ($product->parent_id) {
            throw new \Exception(trans('product::app.datagrid.variant-already-exist-message'));
        }

        return DB::transaction(function () use ($product) {
            $copiedProduct = $product->getTypeInstance()->copy();

            return $copiedProduct;
        });
    }

    /**
     * Copy product.
     */
    public function setSearchEngine(string $searchEngine): self
    {
        $this->searchEngine = $searchEngine;

        return $this;
    }

    /**
     * Return product by filtering through attribute values.
     *
     * @param  string  $code
     * @param  mixed  $value
     * @return Product
     */
    public function findByAttributeCode($code, $value)
    {
        $attribute = $this->attributeRepository->findOneByField('code', $code);

        $attributeValues = $this->productAttributeValueRepository->findWhere([
            'attribute_id' => $attribute->id,
            $attribute->column_name => $value,
        ]);

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $filteredAttributeValues = $attributeValues
                    ->where('channel', core()->getRequestedChannelCode())
                    ->where('locale', core()->getRequestedLocaleCode());

                if ($attributeValues->isEmpty()) {
                    $filteredAttributeValues = $attributeValues
                        ->where('channel', core()->getRequestedChannelCode())
                        ->where('locale', core()->getDefaultLocaleCodeFromDefaultChannel());
                }
            } else {
                $filteredAttributeValues = $attributeValues
                    ->where('channel', core()->getRequestedChannelCode());
            }
        } else {
            if ($attribute->value_per_locale) {
                $filteredAttributeValues = $attributeValues
                    ->where('locale', core()->getRequestedLocaleCode());

                if ($filteredAttributeValues->isEmpty()) {
                    $filteredAttributeValues = $attributeValues
                        ->where('locale', core()->getDefaultLocaleCodeFromDefaultChannel());
                }
            } else {
                $filteredAttributeValues = $attributeValues;
            }
        }

        // Deterministic pick when duplicate url_key exists (same slug, different products).
        return $filteredAttributeValues->sortBy('product_id')->first()?->product;
    }

    /**
     * Retrieve product from slug without throwing an exception.
     */
    public function findBySlug(string $slug): ?Product
    {
        if ($this->searchEngine == 'elastic') {
            $indices = $this->elasticSearchRepository->search([
                'url_key' => $slug,
            ], [
                'type' => '',
                'from' => 0,
                'limit' => 1,
                'sort' => 'id',
                'order' => 'desc',
            ]);

            return $this->find(current($indices['ids']));
        }

        return $this->findByAttributeCode('url_key', $slug);
    }

    /**
     * Retrieve product from slug.
     */
    public function findBySlugOrFail(string $slug): ?Product
    {
        $product = $this->findBySlug($slug);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $slug
            );
        }

        return $product;
    }

    /**
     * Get all products.
     *
     * @return Collection
     */
    public function getAll(array $params = [])
    {
        if ($this->searchEngine == 'elastic') {
            return $this->searchFromElastic($params);
        }

        return $this->searchFromDatabase($params);
    }

    /**
     * Search product from database.
     *
     * @return Collection
     */
    public function searchFromDatabase(array $params = [])
    {
        $params['url_key'] ??= null;

        if (! empty($params['query'])) {
            $params['name'] = $params['query'];
        }

        $query = $this->with([
            'attribute_family',
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
            'variants',
            'variants.attribute_family',
            'variants.attribute_values',
            'variants.price_indices',
            'variants.inventory_indices',
        ])->scopeQuery(function ($query) use ($params) {
            $prefix = DB::getTablePrefix();

            $qb = $query->distinct()
                ->select('products.*')
                ->leftJoin('products as variants', DB::raw('COALESCE('.$prefix.'variants.parent_id, '.$prefix.'variants.id)'), '=', 'products.id')
                ->leftJoin('product_price_indices', function ($join) {
                    $customerGroup = $this->customerRepository->getCurrentGroup();

                    $join->on('products.id', '=', 'product_price_indices.product_id')
                        ->where('product_price_indices.customer_group_id', $customerGroup->id);
                });

            if (! empty($params['category_id'])) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id')
                    ->whereIn('product_categories.category_id', explode(',', (string) $params['category_id']));
            }

            if (! empty($params['channel_id'])) {
                $qb->leftJoin('product_channels', 'products.id', '=', 'product_channels.product_id')
                    ->whereIn('product_channels.channel_id', explode(',', (string) $params['channel_id']));
            }

            /**
             * Storefront: hide products that have no gallery rows (buyer-facing listings only).
             */
            if (! empty($params['require_product_image'])) {
                $qb->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('product_images')
                        ->whereColumn('product_images.product_id', 'products.id');
                });
            }

            if (! empty($params['import_only'])) {
                $qb->where('products.sku', 'like', 'TG-%');
            }

            if (! empty($params['type'])) {
                $qb->where('products.type', $params['type']);

                if (
                    $params['type'] === 'simple'
                    && ! empty($params['exclude_customizable_products'])
                ) {
                    $qb->leftJoin('product_customizable_options', 'products.id', '=', 'product_customizable_options.product_id')
                        ->whereNull('product_customizable_options.id');
                }
            }

            if (! empty($params['visible_product_ids']) && is_array($params['visible_product_ids'])) {
                $ids = $params['visible_product_ids'];

                if ($ids === []) {
                    $qb->whereRaw('1 = 0');
                } else {
                    $qb->whereIn('products.id', $ids);
                }
            }

            /**
             * Filter query by price.
             */
            if (! empty($params['price'])) {
                $priceRange = explode(',', $params['price']);

                $qb->whereBetween('product_price_indices.min_price', [
                    core()->convertToBasePrice(current($priceRange)),
                    core()->convertToBasePrice(end($priceRange)),
                ]);
            }

            /**
             * Retrieve all the filterable attributes.
             * Exclude toolbar / system keys (sort, limit, new, featured, …) so they are not
             * mistaken for attribute codes (avoids undefined $params keys and bad SQL).
             */
            $excludeFromAttributeKeys = [
                'sort', 'limit', 'mode', 'page', 'query', 'seller_preview', 'spv', 'suggest',
                'import_only', 'visible_product_ids', 'visible_product_ids_order', 'price', 'category_id', 'type',
                'exclude_customizable_products', 'channel_id', 'require_product_image',
            ];

            $attributeKeys = array_values(array_unique(array_merge(
                ['name', 'status', 'visible_individually', 'url_key'],
                array_diff(array_keys($params), $excludeFromAttributeKeys)
            )));

            $filterableAttributes = $this->attributeRepository->getProductDefaultAttributes($attributeKeys);

            /**
             * Filter the required attributes.
             */
            $attributes = $filterableAttributes->whereIn('code', [
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter collection by required attributes.
             */
            foreach ($attributes as $attribute) {
                $alias = $attribute->code.'_product_attribute_values';

                $qb->leftJoin('product_attribute_values as '.$alias, 'products.id', '=', $alias.'.product_id')
                    ->where($alias.'.attribute_id', $attribute->id);

                if ($attribute->code == 'name') {
                    $nameQuery = isset($params['name']) ? trim((string) urldecode($params['name'])) : '';

                    if ($nameQuery === '') {
                        $qb->whereNotNull($alias.'.text_value');
                    } else {
                        $synonyms = $this->searchSynonymRepository->getSynonymsByQuery($nameQuery);

                        $qb->where(function ($subQuery) use ($alias, $synonyms) {
                            foreach ($synonyms as $synonym) {
                                $synonym = trim((string) $synonym);

                                if ($synonym === '') {
                                    continue;
                                }

                                $subQuery->orWhere($alias.'.text_value', 'like', '%'.$synonym.'%');
                            }
                        });
                    }
                } elseif ($attribute->code == 'url_key') {
                    if (empty($params['url_key'])) {
                        $qb->whereNotNull($alias.'.text_value');
                    } else {
                        $qb->where($alias.'.text_value', 'like', '%'.urldecode($params['url_key']).'%');
                    }
                } else {
                    if (! array_key_exists($attribute->code, $params) || is_null($params[$attribute->code])) {
                        continue;
                    }

                    $qb->where($alias.'.'.$attribute->column_name, 1);
                }
            }

            /**
             * Filter the filterable attributes.
             */
            $attributes = $filterableAttributes->whereNotIn('code', [
                'price',
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter query by attributes.
             */
            if ($attributes->isNotEmpty()) {
                $qb->where(function ($filterQuery) use ($qb, $params, $attributes, $prefix) {
                    $aliases = [
                        'products' => 'product_attribute_values',
                        'variants' => 'variant_attribute_values',
                    ];

                    foreach ($aliases as $table => $tableAlias) {
                        $filterQuery->orWhere(function ($subFilterQuery) use ($qb, $params, $attributes, $prefix, $table, $tableAlias) {
                            foreach ($attributes as $attribute) {
                                if (! array_key_exists($attribute->code, $params)) {
                                    continue;
                                }

                                $alias = $attribute->code.'_'.$tableAlias;

                                $qb->leftJoin('product_attribute_values as '.$alias, function ($join) use ($table, $alias, $attribute) {
                                    $join->on($table.'.id', '=', $alias.'.product_id');

                                    $join->where($alias.'.attribute_id', $attribute->id);
                                });

                                if (in_array($attribute->type, [
                                    AttributeTypeEnum::CHECKBOX->value,
                                    AttributeTypeEnum::MULTISELECT->value,
                                ])) {
                                    $paramValues = explode(',', (string) $params[$attribute->code]);

                                    $subFilterQuery->where(function ($query) use ($paramValues, $alias, $attribute, $prefix) {
                                        foreach ($paramValues as $value) {
                                            $query->orWhereRaw("FIND_IN_SET(?, {$prefix}{$alias}.{$attribute->column_name})", [$value]);
                                        }
                                    });
                                } else {
                                    $subFilterQuery->whereIn($alias.'.'.$attribute->column_name, explode(',', (string) $params[$attribute->code]));
                                }
                            }
                        });
                    }
                });

                $qb->groupBy('products.id');
            }

            /**
             * Sort collection.
             */
            if (! empty($params['visible_product_ids_order']) && is_array($params['visible_product_ids_order'])) {
                $orderedIds = array_values(array_unique(array_filter(array_map('intval', $params['visible_product_ids_order']))));

                if ($orderedIds !== []) {
                    $idsList = implode(',', $orderedIds);

                    $qb->orderByRaw('FIELD(products.id, '.$idsList.')');
                }

                return $qb->groupBy('products.id');
            }

            $sortOptions = $this->getSortOptions($params);

            if ($sortOptions['order'] != 'rand') {
                $attribute = $this->attributeRepository->findOneByField('code', $sortOptions['sort']);

                if ($attribute) {
                    if ($attribute->code === 'price') {
                        $qb->orderBy('product_price_indices.min_price', $sortOptions['order']);
                    } else {
                        $alias = 'sort_product_attribute_values';

                        $qb->leftJoin('product_attribute_values as '.$alias, function ($join) use ($alias, $attribute) {
                            $join->on('products.id', '=', $alias.'.product_id')
                                ->where($alias.'.attribute_id', $attribute->id);

                            if ($attribute->value_per_channel) {
                                if ($attribute->value_per_locale) {
                                    $join->where($alias.'.channel', core()->getRequestedChannelCode())
                                        ->where($alias.'.locale', core()->getRequestedLocaleCode());
                                } else {
                                    $join->where($alias.'.channel', core()->getRequestedChannelCode());
                                }
                            } else {
                                if ($attribute->value_per_locale) {
                                    $join->where($alias.'.locale', core()->getRequestedLocaleCode());
                                }
                            }
                        })
                            ->orderBy($alias.'.'.$attribute->column_name, $sortOptions['order']);
                    }
                } else {
                    if ($sortOptions['sort'] === 'id') {
                        $qb->orderBy('products.id', $sortOptions['order']);
                    } else {
                        /* `created_at` is not an attribute so it will be in else case */
                        $qb->orderBy('products.created_at', $sortOptions['order']);
                    }
                }
            } else {
                return $qb->inRandomOrder();
            }

            return $qb->groupBy('products.id');
        });

        $limit = $this->getPerPageLimit($params);

        return $query->paginate($limit);
    }

    /**
     * Search product from elastic search.
     *
     * @return Collection
     */
    public function searchFromElastic(array $params = [])
    {
        $currentPage = Paginator::resolveCurrentPage('page');

        $limit = $this->getPerPageLimit($params);

        $sortOptions = $this->getSortOptions($params);

        $indices = $this->elasticSearchRepository->search($params, [
            'from' => ($currentPage * $limit) - $limit,
            'limit' => $limit,
            'sort' => $sortOptions['sort'],
            'order' => $sortOptions['order'],
        ]);

        $query = $this->with([
            'attribute_family',
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
            'variants',
            'variants.attribute_family',
            'variants.attribute_values',
            'variants.price_indices',
            'variants.inventory_indices',
        ])->scopeQuery(function ($query) use ($params, $indices) {
            $qb = $query->distinct()
                ->whereIn('products.id', $indices['ids']);

            if (
                ! empty($params['type'])
                && $params['type'] === 'simple'
                && ! empty($params['exclude_customizable_products'])
            ) {
                $qb->leftJoin('product_customizable_options', 'products.id', '=', 'product_customizable_options.product_id')
                    ->whereNull('product_customizable_options.id');
            }

            $qb->orderBy(DB::raw('FIELD(id, '.implode(',', $indices['ids']).')'));

            return $qb;
        });

        $items = $indices['total'] ? $query->get() : [];

        $results = new LengthAwarePaginator($items, $indices['total'], $limit, $currentPage, [
            'path' => request()->url(),
            'query' => $params,
        ]);

        return $results;
    }

    /**
     * Fetch per page limit from toolbar helper. Adapter for this repository.
     */
    public function getPerPageLimit(array $params): int
    {
        return product_toolbar()->getLimit($params);
    }

    /**
     * Fetch sort option from toolbar helper. Adapter for this repository.
     */
    public function getSortOptions(array $params): array
    {
        return product_toolbar()->getOrder($params);
    }

    /**
     * Returns product's super attribute with options.
     *
     * @param  Product  $product
     * @return Collection
     */
    public function getSuperAttributes($product)
    {
        $superAttributes = [];

        foreach ($product->super_attributes as $key => $attribute) {
            $superAttributes[$key] = $attribute->toArray();

            foreach ($attribute->options as $option) {
                $superAttributes[$key]['options'][] = [
                    'id' => $option->id,
                    'admin_name' => $option->admin_name,
                    'sort_order' => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }

        return $superAttributes;
    }

    /**
     * Return category product maximum price.
     *
     * @param  int  $categoryId
     * @return float
     */
    public function getMaxPrice($params = [])
    {
        if ($this->searchEngine == 'elastic') {
            return $this->elasticSearchRepository->getMaxPrice($params);
        }

        $customerGroup = $this->customerRepository->getCurrentGroup();

        $query = $this->model
            ->leftJoin('product_price_indices', 'products.id', 'product_price_indices.product_id')
            ->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
            ->where('product_price_indices.customer_group_id', $customerGroup->id);

        if (! empty($params['category_id'])) {
            $query->where('product_categories.category_id', $params['category_id']);
        }

        return $query->max('min_price') ?? 0;
    }
}
