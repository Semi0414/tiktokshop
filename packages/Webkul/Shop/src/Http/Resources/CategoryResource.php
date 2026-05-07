<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $displayName = $this->getDisplayName((string) $this->name);

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $displayName,
            'display_name' => $displayName,
            'slug' => $this->slug,
            'status' => $this->status,
            'position' => $this->position,
            'display_mode' => $this->display_mode,
            'has_products' => DB::table('product_categories')->where('category_id', $this->id)->exists(),
            'description' => $this->description,
            'logo' => $this->when($this->logo_path, [
                'small_image_url' => url('cache/small/'.$this->logo_path),
                'medium_image_url' => url('cache/medium/'.$this->logo_path),
                'large_image_url' => url('cache/large/'.$this->logo_path),
                'original_image_url' => url('cache/original/'.$this->logo_path),
            ]),
            'banner' => $this->when($this->banner_path, [
                'small_image_url' => url('cache/small/'.$this->banner_path),
                'medium_image_url' => url('cache/medium/'.$this->banner_path),
                'large_image_url' => url('cache/large/'.$this->banner_path),
                'original_image_url' => url('cache/original/'.$this->banner_path),
            ]),
            'meta' => [
                'title' => $this->meta_title,
                'keywords' => $this->meta_keywords,
                'description' => $this->meta_description,
            ],
            'translations' => $this->translations,
            'additional' => $this->additional,
        ];
    }

    private function getDisplayName(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            return 'Category';
        }

        $name = preg_replace('/^category\\s+/i', '', $name) ?? $name;
        $name = preg_replace('/\\b[0-9a-f]{10,}\\b/i', '', $name) ?? $name;
        $name = preg_replace('/[_\\-]+/', ' ', $name) ?? $name;
        $name = preg_replace('/\\s+/', ' ', trim($name)) ?? $name;

        return $name !== '' ? $name : 'Category';
    }
}
