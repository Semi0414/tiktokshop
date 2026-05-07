<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Exports existing catalog images from storage to a folder and writes mapping files.
 * Does not fetch products from external APIs — only reads product_images + product sku.
 */
class CatalogExportProductImages extends Command
{
    protected $signature = 'catalog:export-product-images
        {--output= : Directory under storage/app (default: exports/catalog-images/{timestamp})}
        {--disk=public : Filesystem disk where product image paths live}
    ';

    protected $description = 'Copy all product_images files to disk and write JSON + CSV mapping (images only, no catalog API fetch).';

    public function handle(): int
    {
        $diskName = (string) $this->option('disk');

        $outputRelative = $this->option('output');
        if ($outputRelative === null || $outputRelative === '') {
            $outputRelative = 'exports/catalog-images/'.date('Y-m-d_His');
        }

        $outputRelative = trim(str_replace(['\\', '..'], ['/', ''], (string) $outputRelative), '/');
        $exportRoot = storage_path('app/'.$outputRelative);

        if (! is_dir($exportRoot) && ! mkdir($exportRoot, 0755, true) && ! is_dir($exportRoot)) {
            $this->error("Cannot create directory: {$exportRoot}");

            return self::FAILURE;
        }

        $imagesDir = $exportRoot.'/images';

        if (! is_dir($imagesDir) && ! mkdir($imagesDir, 0755, true) && ! is_dir($imagesDir)) {
            $this->error("Cannot create directory: {$imagesDir}");

            return self::FAILURE;
        }

        $disk = Storage::disk($diskName);

        $this->info("Export root: {$exportRoot}");
        $this->info("Disk: {$diskName}");

        $mapping = [];
        $stats = [
            'image_rows' => 0,
            'copied' => 0,
            'missing' => 0,
            'empty_path' => 0,
            'write_failed' => 0,
        ];

        $query = DB::table('product_images')
            ->join('products', 'products.id', '=', 'product_images.product_id')
            ->select([
                'product_images.id as image_id',
                'product_images.product_id',
                'product_images.path',
                'product_images.position',
                'product_images.type',
                'products.sku',
            ])
            ->orderBy('product_images.id');

        foreach ($query->cursor() as $row) {
            $stats['image_rows']++;
            $path = trim((string) $row->path);

            if ($path === '') {
                $stats['empty_path']++;
                $mapping[] = $this->mapRow($row, null, 'empty_path', $outputRelative);

                continue;
            }

            if (! $disk->exists($path)) {
                $stats['missing']++;
                $mapping[] = $this->mapRow($row, null, 'missing_on_disk', $outputRelative);

                continue;
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'bin');
            $safeFile = 'p'.$row->product_id.'_img'.$row->image_id.'_pos'.$row->position.'.'.$ext;
            $relativeUnderExport = 'images/'.$safeFile;
            $fullDest = $imagesDir.'/'.$safeFile;

            try {
                $bytes = $disk->get($path);
                if (file_put_contents($fullDest, $bytes) === false) {
                    $stats['write_failed']++;
                    $mapping[] = $this->mapRow($row, null, 'write_failed', $outputRelative);

                    continue;
                }
            } catch (\Throwable $e) {
                $stats['write_failed']++;
                $mapping[] = $this->mapRow($row, null, 'read_error: '.$e->getMessage(), $outputRelative);

                continue;
            }

            $stats['copied']++;
            $mapping[] = $this->mapRow($row, $relativeUnderExport, 'ok', $outputRelative);
        }

        $jsonPath = $exportRoot.'/mapping.json';
        file_put_contents($jsonPath, json_encode($mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $csvPath = $exportRoot.'/mapping.csv';
        $this->writeCsv($csvPath, $mapping);

        $this->newLine();
        $this->info('Done.');
        $this->line('mapping.json: '.$jsonPath);
        $this->line('mapping.csv:  '.$csvPath);
        $this->line('images folder: '.$imagesDir);
        foreach ($stats as $k => $v) {
            $this->line($k.': '.$v);
        }

        return self::SUCCESS;
    }

    private function mapRow(object $row, ?string $exportedRelative, string $status, string $outputRelative): array
    {
        $exported = $exportedRelative !== null
            ? 'storage/app/'.$outputRelative.'/'.$exportedRelative
            : null;

        return [
            'product_id' => (int) $row->product_id,
            'sku' => (string) $row->sku,
            'image_id' => (int) $row->image_id,
            'position' => (int) $row->position,
            'type' => $row->type,
            'storage_path' => (string) $row->path,
            'exported_relative' => $exported,
            'status' => $status,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function writeCsv(string $path, array $rows): void
    {
        $fh = fopen($path, 'w');

        if (! $fh) {
            return;
        }

        if ($rows !== []) {
            fputcsv($fh, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($fh, array_map(static fn ($v) => is_scalar($v) || $v === null ? $v : json_encode($v), $r));
            }
        }

        fclose($fh);
    }
}
