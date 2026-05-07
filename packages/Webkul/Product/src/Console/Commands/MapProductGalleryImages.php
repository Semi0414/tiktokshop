<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductImageRepository;

class MapProductGalleryImages extends Command
{
    /**
     * @var string
     */
    protected $signature = 'products:map-gallery-images
                            {--count=5 : Number of gallery images per product}
                            {--skip-existing : Skip products that already have at least one image}
                            {--replace : Delete existing product images then download new ones}
                            {--sku-prefix= : Only products whose SKU starts with this prefix}
                            {--product-id=* : Limit to specific product IDs}
                            {--source=placeholder : placeholder = Picsum seeds; urls = use --urls-file}
                            {--urls-file= : JSON object: {\"SKU\":[\"https://...\",\"https://...\"], ...}}
                            {--sleep-ms=150 : Delay between HTTP downloads (rate limit)}
                            {--limit= : Max number of products to process (testing / batch)}
                            {--no-index : Do not run flat indexer after completion}';

    /**
     * @var string
     */
    protected $description = 'Download multiple images per product and attach them to product_images (maps gallery for catalog).';

    public function handle(ProductImageRepository $productImageRepository): int
    {
        $count = max(1, (int) $this->option('count'));
        $sleepMs = max(0, (int) $this->option('sleep-ms'));
        $source = (string) $this->option('source');
        $urlsFile = $this->option('urls-file');
        $skuPrefix = $this->option('sku-prefix');
        $productIds = array_filter(array_map('intval', (array) $this->option('product-id')));

        $urlsBySku = [];

        if ($source === 'urls') {
            if (empty($urlsFile) || ! is_readable($urlsFile)) {
                $this->error('For --source=urls provide a readable --urls-file= path to JSON mapping SKU => [urls].');

                return self::FAILURE;
            }

            try {
                $decoded = json_decode((string) file_get_contents($urlsFile), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $this->error('Invalid JSON in urls-file: '.$e->getMessage());

                return self::FAILURE;
            }

            if (! is_array($decoded)) {
                $this->error('urls-file must be a JSON object.');

                return self::FAILURE;
            }

            $urlsBySku = $decoded;
        }

        $query = Product::query()->whereNull('parent_id');

        if ($skuPrefix !== null && $skuPrefix !== '') {
            $query->where('sku', 'like', $skuPrefix.'%');
        }

        if ($productIds !== []) {
            $query->whereIn('id', $productIds);
        }

        $limitOption = $this->option('limit');
        $limitN = ($limitOption !== null && $limitOption !== '') ? (int) $limitOption : null;

        $matchCount = (clone $query)->count();

        if ($matchCount === 0) {
            $this->warn('No products matched.');

            return self::SUCCESS;
        }

        $total = $limitN !== null ? min($limitN, $matchCount) : $matchCount;

        $this->info("Processing {$total} product(s), {$count} image(s) each (source: {$source}).");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $ok = 0;
        $failed = 0;
        $processed = 0;

        $query->orderBy('id')->chunkById(50, function ($products) use (
            $count,
            $sleepMs,
            $source,
            $urlsBySku,
            $productImageRepository,
            &$ok,
            &$failed,
            &$processed,
            $limitN,
            $bar
        ) {
            foreach ($products as $product) {
                if ($limitN !== null && $processed >= $limitN) {
                    return false;
                }

                try {
                    $didAdd = DB::transaction(function () use (
                        $product,
                        $count,
                        $sleepMs,
                        $source,
                        $urlsBySku,
                        $productImageRepository
                    ) {
                        $existing = $product->images()->count();

                        if ($this->option('skip-existing') && $existing > 0 && ! $this->option('replace')) {
                            return false;
                        }

                        if ($this->option('replace') && $existing > 0) {
                            foreach ($product->images as $img) {
                                Storage::delete($img->path);
                                $img->delete();
                            }
                        } elseif (! $this->option('replace') && $existing > 0) {
                            return false;
                        }

                        $urls = $this->resolveUrlsForProduct($product, $count, $source, $urlsBySku);

                        $position = 0;

                        foreach ($urls as $url) {
                            if ($sleepMs > 0) {
                                usleep($sleepMs * 1000);
                            }

                            $binary = $this->downloadImage($url);

                            $manager = new ImageManager;

                            $image = $manager->make($binary)->encode('webp', 90);

                            $path = 'product/'.$product->id.'/'.Str::random(40).'.webp';

                            Storage::put($path, (string) $image);

                            $productImageRepository->create([
                                'type' => 'images',
                                'path' => $path,
                                'product_id' => $product->id,
                                'position' => ++$position,
                            ]);
                        }

                        return true;
                    });

                    if ($didAdd) {
                        $ok++;
                    }
                } catch (\Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("Product #{$product->id} ({$product->sku}): ".$e->getMessage());
                }

                $processed++;
                $bar->advance();
            }

            return true;
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. OK: {$ok}, failed: {$failed}.");

        if (! $this->option('no-index')) {
            $this->info('Running flat indexer (selective)...');
            Artisan::call('indexer:index', ['--type' => ['flat']]);
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @param  array<string, list<string>>  $urlsBySku
     * @return list<string>
     */
    protected function resolveUrlsForProduct(Product $product, int $count, string $source, array $urlsBySku): array
    {
        if ($source === 'urls') {
            $list = $urlsBySku[$product->sku] ?? [];

            if ($list === []) {
                throw new \RuntimeException('No URLs in file for SKU '.$product->sku);
            }

            return array_values(array_slice(array_filter($list, 'strlen'), 0, $count));
        }

        $urls = [];

        for ($i = 1; $i <= $count; $i++) {
            $seed = 'p'.$product->id.'-'.$i.'-'.Str::slug((string) $product->sku, '');
            $urls[] = 'https://picsum.photos/seed/'.rawurlencode($seed).'/800/800';
        }

        return $urls;
    }

    protected function downloadImage(string $url): string
    {
        $response = Http::timeout(60)
            ->withOptions(['verify' => true])
            ->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException('HTTP '.$response->status().' for '.$url);
        }

        $body = $response->body();

        if ($body === '' || strlen($body) < 100) {
            throw new \RuntimeException('Empty or too small response from '.$url);
        }

        return $body;
    }
}
