<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
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
            'url' => $this->url,
            'logo_url' => $this->logo_url,
            'status' => $this->status,
            'children' => self::collection($this->children),
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
