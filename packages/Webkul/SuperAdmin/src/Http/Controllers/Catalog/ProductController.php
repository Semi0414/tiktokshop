<?php

namespace Webkul\SuperAdmin\Http\Controllers\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Core\Rules\Slug;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Helpers\Product;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SuperAdmin\DataGrids\Catalog\ProductDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\InventoryRequest;
use Webkul\SuperAdmin\Http\Requests\MassDestroyRequest;
use Webkul\SuperAdmin\Http\Requests\MassUpdateRequest;
use Webkul\SuperAdmin\Http\Requests\ProductForm;
use Webkul\SuperAdmin\Http\Resources\AttributeResource;
use Webkul\SuperAdmin\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Using const variable for status.
     */
    const ACTIVE_STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductRepository $productRepository,
        protected CustomerRepository $customerRepository,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        $families = $this->attributeFamilyRepository->all();

        return $this->superadminListing('superadmin::catalog.products.index', compact('families'), ProductDataGrid::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $families = $this->attributeFamilyRepository->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
        }

        return view('superadmin::catalog.products.create', compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store()
    {
        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new Slug],
            'super_attributes' => 'array|min:1',
            'super_attributes.*' => 'array|min:1',
        ]);

        if (
            ProductType::hasVariants(request()->input('type'))
            && ! request()->has('super_attributes')
        ) {
            $configurableFamily = $this->attributeFamilyRepository
                ->find(request()->input('attribute_family_id'));

            return new JsonResponse([
                'data' => [
                    'attributes' => AttributeResource::collection($configurableFamily->configurable_attributes),
                ],
            ]);
        }

        Event::dispatch('catalog.product.create.before');

        $product = $this->productRepository->create(request()->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));

        Event::dispatch('catalog.product.create.after', $product);

        session()->flash('success', trans('superadmin::app.catalog.products.create-success'));

        return new JsonResponse([
            'data' => [
                'redirect_url' => route('superadmin.catalog.products.edit', $product->id),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $product = $this->productRepository->findOrFail($id);

        $product->loadMissing(['attribute_values', 'images', 'videos']);

        $attributeValuesByCode = $this->resolveProductAttributeValuesByCode($product);

        foreach ($attributeValuesByCode as $code => $value) {
            $product->setAttribute($code, $value);
        }

        return view('superadmin::catalog.products.edit', compact('product', 'attributeValuesByCode'));
    }

    /**
     * Resolve product attribute values explicitly for edit form rendering.
     */
    private function resolveProductAttributeValuesByCode($product): array
    {
        $requestedChannel = core()->getRequestedChannelCode();
        $requestedLocale = core()->getRequestedLocaleCodeInRequestedChannel();
        $defaultChannel = core()->getDefaultChannelCode();
        $defaultLocale = core()->getDefaultLocaleCodeFromDefaultChannel();

        $resolved = [];
        $flatRow = $product->product_flats()
            ->where('channel', $requestedChannel)
            ->where('locale', $requestedLocale)
            ->first()
            ?: $product->product_flats()
                ->where('channel', $defaultChannel)
                ->where('locale', $defaultLocale)
                ->first();

        foreach ($product->checkInLoadedFamilyAttributes() as $attribute) {
            $rows = $product->attribute_values->where('attribute_id', $attribute->id);

            $candidate = null;

            if ($attribute->value_per_channel && $attribute->value_per_locale) {
                $candidate = $rows->first(fn ($row) => $row->channel === $requestedChannel && $row->locale === $requestedLocale)
                    ?? $rows->first(fn ($row) => is_null($row->channel) && $row->locale === $requestedLocale)
                    ?? $rows->first(fn ($row) => $row->channel === $defaultChannel && $row->locale === $defaultLocale)
                    ?? $rows->first(fn ($row) => is_null($row->channel) && $row->locale === $defaultLocale);
            } elseif ($attribute->value_per_channel) {
                $candidate = $rows->first(fn ($row) => $row->channel === $requestedChannel)
                    ?? $rows->first(fn ($row) => is_null($row->channel))
                    ?? $rows->first(fn ($row) => $row->channel === $defaultChannel);
            } elseif ($attribute->value_per_locale) {
                $candidate = $rows->first(fn ($row) => $row->locale === $requestedLocale)
                    ?? $rows->first(fn ($row) => $row->locale === $defaultLocale)
                    ?? $rows->first(fn ($row) => is_null($row->locale));
            } else {
                $candidate = $rows->first();
            }

            $value = $candidate?->{$attribute->column_name};

            if (
                ($value === null || $value === '')
                && $flatRow
                && isset($flatRow->{$attribute->code})
                && $flatRow->{$attribute->code} !== null
                && $flatRow->{$attribute->code} !== ''
            ) {
                $value = $flatRow->{$attribute->code};
            }

            $resolved[$attribute->code] = $value ?? $attribute->default_value;
        }

        return $resolved;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(ProductForm $request, int $id)
    {
        try {
            Event::dispatch('catalog.product.update.before', $id);

            $product = $this->productRepository->update($request->all(), $id);

            Event::dispatch('catalog.product.update.after', $product);

            session()->flash('success', trans('superadmin::app.catalog.products.update-success'));

            return redirect()->route('superadmin.catalog.products.edit', [
                'id'      => $id,
                'channel' => $request->input('channel'),
                'locale'  => $request->input('locale'),
            ]);
        } catch (\Throwable $e) {
            report($e);

            session()->flash('error', $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Update inventories.
     *
     * @return Response
     */
    public function updateInventories(InventoryRequest $inventoryRequest, int $id)
    {
        $product = $this->productRepository->findOrFail($id);

        Event::dispatch('catalog.product.update.before', $id);

        $this->productInventoryRepository->saveInventories(request()->all(), $product);

        Event::dispatch('catalog.product.update.after', $product);

        return response()->json([
            'message' => trans('superadmin::app.catalog.products.saved-inventory-message'),
            'updatedTotal' => $this->productInventoryRepository->where('product_id', $product->id)->sum('qty'),
        ]);
    }

    /**
     * Uploads downloadable file.
     *
     * @return Response
     */
    public function uploadLink(int $id)
    {
        return response()->json(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Copy a given Product.
     *
     * @return Response
     */
    public function copy(int $id)
    {
        try {
            Event::dispatch('catalog.product.create.before');

            $product = $this->productRepository->copy($id);

            Event::dispatch('catalog.product.create.after', $product);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->to(route('superadmin.catalog.products.index'));
        }

        return response()->json([
            'message' => trans('superadmin::app.catalog.products.product-copied'),
        ]);
    }

    /**
     * Uploads downloadable sample file.
     *
     * @return Response
     */
    public function uploadSample(int $id)
    {
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('catalog.product.delete.before', $id);

            $this->productRepository->delete($id);

            Event::dispatch('catalog.product.delete.after', $id);

            return new JsonResponse([
                'message' => trans('superadmin::app.catalog.products.delete-success'),
            ]);
        } catch (\Exception $e) {
            report($e);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.catalog.products.delete-failed'),
        ], 500);
    }

    /**
     * Mass delete the products.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $productIds = $massDestroyRequest->input('indices');

        try {
            foreach ($productIds as $productId) {
                $product = $this->productRepository->find($productId);

                if (isset($product)) {
                    Event::dispatch('catalog.product.delete.before', $productId);

                    $this->productRepository->delete($productId);

                    Event::dispatch('catalog.product.delete.after', $productId);
                }
            }

            return new JsonResponse([
                'message' => trans('superadmin::app.catalog.products.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update the products.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $productIds = $massUpdateRequest->input('indices');

        foreach ($productIds as $productId) {
            Event::dispatch('catalog.product.update.before', $productId);

            $product = $this->productRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $productId, ['status']);

            Event::dispatch('catalog.product.update.after', $product);
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.catalog.products.index.datagrid.mass-update-success'),
        ], 200);
    }

    /**
     * To be manually invoked when data is seeded into products.
     *
     * @return Response
     */
    public function sync()
    {
        Event::dispatch('products.datagrid.sync', true);

        return redirect()->route('superadmin.catalog.products.index');
    }

    /**
     * Result of search product.
     *
     * @return JsonResponse
     */
    public function search()
    {
        $query = trim(request('query'));

        if (empty($query)) {
            return response()->json([
                'data' => [],
            ]);
        }

        $searchEngine = 'database';

        if (
            core()->getConfigData('catalog.products.search.engine') == 'elastic'
            && core()->getConfigData('catalog.products.search.admin_mode') == 'elastic'
        ) {
            $searchEngine = 'elastic';

            $indexNames = core()->getAllChannels()->map(function ($channel) {
                return Product::formatElasticSearchIndexName($channel->code, app()->getLocale());
            })->toArray();
        }

        $channelId = $this->customerRepository->find(request('customer_id'))->channel_id ?? null;

        $params = [
            'index' => $indexNames ?? null,
            'name' => request('query'),
            'sort' => 'created_at',
            'order' => 'desc',
            'channel_id' => $channelId,
        ];

        if (request()->has('type')) {
            $params['type'] = request('type');
        }

        if (request()->has('exclude_customizable_products')) {
            $params['exclude_customizable_products'] = request('exclude_customizable_products');
        }

        $products = $this->productRepository
            ->setSearchEngine($searchEngine)
            ->getAll($params);

        return ProductResource::collection($products);
    }

    /**
     * Download image or file.
     *
     * @param  int  $productId
     * @param  int  $attributeId
     * @return Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValueRepository->findOneWhere([
            'product_id' => $productId,
            'attribute_id' => $attributeId,
        ]);

        return Storage::download($productAttribute['text_value']);
    }
}
