<?php

namespace Webkul\SuperAdmin\Http\Controllers\Catalog\Product;

use Illuminate\Http\JsonResponse;
use Webkul\Product\Helpers\ConfigurableOption;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class ConfigurableController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ConfigurableOption $configurableOptionHelper
    ) {}

    /**
     * Returns the compare items of the customer.
     */
    public function options(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $this->configurableOptionHelper->getConfigurationConfig($product),
        ]);
    }
}
