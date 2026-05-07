<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerSearchResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when(! $request->boolean('name_only'), $this->email),
            'image_url' => $this->image_url,
            'visit_store_url' => route('shop.seller.visit', $this->id),
        ];
    }
}
