<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class HomePageMedia extends Model
{
    protected $table = 'home_page_media';

    protected $fillable = [
        'media_type',
        'path',
        'original_filename',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Public URL: prefer Laravel route that streams from `storage/app/public` so video/image
     * works when `public/storage` symlink or web-server read permissions are broken.
     */
    public function getPublicUrl(): string
    {
        if ($this->path === null || $this->path === '') {
            return '';
        }

        $normalized = ltrim(str_replace('\\', '/', (string) $this->path), '/');
        if (str_contains($normalized, '..')) {
            return '';
        }

        $file = basename($normalized);
        if ($file === '' || $file === '.' || $file === '..' || ! str_starts_with($normalized, 'home-page-media/')) {
            return asset('storage/'.$normalized);
        }

        if (Route::has('shop.home_page_media.file')) {
            return route('shop.home_page_media.file', ['file' => $file], true);
        }

        return asset('storage/'.$normalized);
    }
}
