<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Customer\Repositories\CompareItemRepository;
use Webkul\Product\Repositories\ProductRepository;

class CompareController extends Controller
{
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected CompareItemRepository $compareItemRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Compare page (server-rendered products for logged-in customers; guests use localStorage + fetch).
     */
    public function index(): View
    {
        $comparableAttributes = $this->attributeFamilyRepository->getComparableAttributesBelongsToFamily();

        $compareProducts = new Collection;

        if ($customer = auth()->guard('customer')->user()) {
            $productIds = $this->compareItemRepository
                ->findByField('customer_id', $customer->id)
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (count($productIds)) {
                $compareProducts = $this->productRepository->whereIn('id', $productIds)->get();
            }
        }

        return view('shop::compare.index', compact('comparableAttributes', 'compareProducts'));
    }
}
