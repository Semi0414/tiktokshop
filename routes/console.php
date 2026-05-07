<?php

use App\Console\Commands\CatalogExportProductImages;
use App\Console\Commands\CatalogImportProductImagesFromCsv;
use App\Console\Commands\TiktokImportCatalog;
use App\Console\Commands\TiktokImportProductImages;
use App\Console\Commands\TiktokImportVariants;
use App\Console\Commands\TiktokNormalizeDuplicateParents;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// TikTok Mall CSV importer.
Artisan::registerCommand(app()->make(TiktokImportCatalog::class));

// TikTok Mall configurable variants importer (detail API).
Artisan::registerCommand(app()->make(TiktokImportVariants::class));

// TikTok Mall image importer from CSV imgUrl columns.
Artisan::registerCommand(app()->make(TiktokImportProductImages::class));

// Export existing catalog product images to disk + mapping (no API fetch).
Artisan::registerCommand(app()->make(CatalogExportProductImages::class));

// Download remote image URLs from CSV into local storage + product_images.
Artisan::registerCommand(app()->make(CatalogImportProductImagesFromCsv::class));

// Normalize duplicate TG parent rows using CSV goodsId.
Artisan::registerCommand(app()->make(TiktokNormalizeDuplicateParents::class));
