<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Helpers\Review;

class ProductResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->reviewHelper = app(Review::class);

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $productTypeInstance = $this->getTypeInstance();

        /**
         * Listing/search API only needs card fields. Skipping gallery + full description
         * avoids heavy Storage::has loops per image and large JSON (faster home/search).
         */
        $isListingIndex = $request->routeIs('shop.api.products.index');

        $defaultVariantId = null;

        if ($this->type === 'configurable' && $this->relationLoaded('variants')) {
            $defaultVariantId = optional($this->variants->first())->id;
        }

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $isListingIndex ? '' : $this->description,
            'url_key' => $this->url_key,
            'base_image' => product_image()->getProductBaseImage($this),
            'images' => $isListingIndex ? [] : product_image()->getGalleryImages($this),
            'is_new' => (bool) $this->new,
            'is_featured' => (bool) $this->featured,
            'on_sale' => (bool) $productTypeInstance->haveDiscount(),
            'is_saleable' => (bool) $productTypeInstance->isSaleable(),
            'is_wishlist' => (bool) (optional(auth()->guard('customer')->user())->wishlist_items
                ?->where('channel_id', core()->getCurrentChannel()->id)
                ?->where('product_id', $this->id)
                ?->count() ?? 0),
            'min_price' => core()->formatPrice($productTypeInstance->getMinimalPrice()),
            'prices' => $productTypeInstance->getProductPrices(),
            'price_html' => $productTypeInstance->getPriceHtml(),
            'default_variant_id' => $defaultVariantId,
            'ratings' => [
                'average' => $this->reviewHelper->getAverageRating($this),
                'total' => $this->reviewHelper->getTotalRating($this),
            ],
            'reviews' => [
                'total' => $this->reviewHelper->getTotalReviews($this),
            ],
        ];
    }
}
