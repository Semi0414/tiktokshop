<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Streams the very large TikTok Mall product exports (each ~10–15 GB) and
 * builds a map of `tiktok_category_id => human_readable_name` from the
 * `categoryId/categoryName` and `secondaryCategoryId/secondaryCateName` columns.
 *
 * The map is then applied to:
 *   1. `categories.display_name` (dedicated column, see migration)
 *   2. `category_translations.name` (so existing UI / data grid picks it up)
 *
 * The command never loads a full file into memory — it uses fgetcsv row-by-row.
 * Re-runnable: existing display_name values are overwritten only when a CSV
 * provides a non-empty value, so the operation is idempotent.
 */
class TiktokshopPopulateCategoryNames extends Command
{
    protected $signature = 'tiktokshop:populate-category-names
        {--file=* : One or more CSV paths (defaults to the two known files under exports/)}
        {--locale=en : Locale of category_translations rows to update}
        {--dry-run : Print mapped names without writing to DB}
        {--progress-every=50000 : Refresh progress every N rows}';

    protected $description = 'Populate categories.display_name + category_translations.name from the giant TikTok product CSVs.';

    /**
     * Default CSV file locations relative to base_path.
     */
    protected array $defaultFiles = [
        'exports/dhgyiu_all_products_flattened.csv',
        'exports/dhgyiu_all_products_flattened_with_detail_variants_partial_800pages.csv',
    ];

    public function handle(): int
    {
        $files = $this->resolveFiles();

        if (empty($files)) {
            $this->error('No CSV files found. Pass --file=<path> or place the exports under '.base_path('exports').'.');

            return self::FAILURE;
        }

        $locale = (string) $this->option('locale');
        $dryRun = (bool) $this->option('dry-run');
        $progressEvery = max(1000, (int) $this->option('progress-every'));

        $this->info('Building tiktok_category_id => display_name map ...');
        $map = [];

        foreach ($files as $file) {
            $this->scanFile($file, $map, $progressEvery);
        }

        $this->info('Captured '.count($map).' unique category ⇄ name mappings.');

        if (empty($map)) {
            $this->warn('No usable category names found; nothing to update.');

            return self::SUCCESS;
        }

        $updated = $this->applyToDatabase($map, $locale, $dryRun);

        $this->info(($dryRun ? '[dry-run] would update ' : 'Updated ').$updated.' categories.');

        return self::SUCCESS;
    }

    /**
     * Resolve the list of files to scan.
     *
     * @return list<string>
     */
    protected function resolveFiles(): array
    {
        $opt = (array) $this->option('file');

        if (empty($opt)) {
            $opt = $this->defaultFiles;
        }

        $resolved = [];

        foreach ($opt as $file) {
            $path = $file;

            if (! str_starts_with($path, DIRECTORY_SEPARATOR)
                && ! preg_match('#^[A-Za-z]:[\\\\/]#', $path)) {
                $path = base_path($file);
            }

            if (is_file($path)) {
                $resolved[] = $path;
            } else {
                $this->warn("CSV not found, skipping: {$path}");
            }
        }

        return $resolved;
    }

    /**
     * Stream a single CSV file, picking up category id/name pairs into $map.
     *
     * The variants export has thousands of columns per row, which makes the
     * built-in `fgetcsv` extremely slow because it allocates an array per row.
     * We only need the first 12 columns (categoryId/categoryName/secondary*),
     * so we run a tiny RFC-4180-aware streaming parser that captures only the
     * leading columns and discards the rest of each row at byte-level speed.
     */
    protected function scanFile(string $path, array &$map, int $progressEvery): void
    {
        $this->newLine();
        $this->info("Scanning: {$path}");

        $size = @filesize($path);

        if ($size !== false) {
            $this->line('  size: '.$this->humanBytes($size));
        }

        $handle = fopen($path, 'rb');

        if (! $handle) {
            $this->warn('  unable to open file.');

            return;
        }

        $maxColumns = 12; // categoryId, categoryName, secondaryCategoryId, secondaryCateName lie at idx 8..11
        $rowsIter = $this->streamPrefixRows($handle, $maxColumns);

        $header = $rowsIter->current();

        if (! is_array($header)) {
            $this->warn('  empty / unreadable header.');
            fclose($handle);

            return;
        }

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }

        $headerIndex = array_flip($header);
        $idxPrimaryId = $headerIndex['categoryId'] ?? null;
        $idxPrimaryName = $headerIndex['categoryName'] ?? null;
        $idxSecondaryId = $headerIndex['secondaryCategoryId'] ?? null;
        $idxSecondaryName = $headerIndex['secondaryCateName'] ?? null;

        if ($idxPrimaryId === null && $idxSecondaryId === null) {
            $this->warn('  no categoryId/secondaryCategoryId columns found in header. Skipping file.');
            fclose($handle);

            return;
        }

        $rowsIter->next();

        $rowCount = 0;
        $newPairs = 0;
        $startedAt = microtime(true);
        $beforeMappings = count($map);

        while ($rowsIter->valid()) {
            $row = $rowsIter->current();
            $rowsIter->next();

            $rowCount++;

            if ($idxPrimaryId !== null && $idxPrimaryName !== null) {
                $cid = isset($row[$idxPrimaryId]) ? trim((string) $row[$idxPrimaryId]) : '';
                $cname = isset($row[$idxPrimaryName]) ? trim((string) $row[$idxPrimaryName]) : '';

                if ($cid !== '' && $cname !== '' && ! isset($map[$cid])) {
                    $map[$cid] = $cname;
                    $newPairs++;
                }
            }

            if ($idxSecondaryId !== null && $idxSecondaryName !== null) {
                $cid = isset($row[$idxSecondaryId]) ? trim((string) $row[$idxSecondaryId]) : '';
                $cname = isset($row[$idxSecondaryName]) ? trim((string) $row[$idxSecondaryName]) : '';

                if ($cid !== '' && $cname !== '' && ! isset($map[$cid])) {
                    $map[$cid] = $cname;
                    $newPairs++;
                }
            }

            if ($rowCount % $progressEvery === 0) {
                $elapsed = max(0.001, microtime(true) - $startedAt);
                $rate = (int) ($rowCount / $elapsed);

                $this->output->write(sprintf(
                    "\r  rows: %s | new mappings: %s | total: %s | %s rows/s",
                    number_format($rowCount),
                    number_format($newPairs),
                    number_format(count($map)),
                    number_format($rate)
                ));
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info(sprintf(
            '  done. rows scanned: %s, new mappings from this file: %s, total: %s',
            number_format($rowCount),
            number_format(count($map) - $beforeMappings),
            number_format(count($map))
        ));
    }

    /**
     * Generator that yields one array of the first `$maxColumns` fields per CSV
     * row, while still tracking RFC-4180 quoting for the entire row so that
     * commas / newlines inside `"..."` (e.g. inside the description column)
     * never confuse row boundaries.
     *
     * Bytes after the Nth field are still iterated to find the row terminator,
     * but the parser does NOT append them anywhere — making it both correct
     * and constant-memory regardless of how wide each row is.
     *
     * @return \Generator<int, array<int, string>>
     */
    protected function streamPrefixRows($handle, int $maxColumns): \Generator
    {
        $cols = [];
        $cur = '';
        $inQuote = false;
        $atFieldStart = true;   // true at line start or right after a separator comma
        $colDone = false;       // true once we've already captured $maxColumns columns
        $hasContent = false;    // distinguishes empty trailing newlines from data rows
        $bufSize = 1 << 16;     // 64 KB

        while (! feof($handle)) {
            $buf = fread($handle, $bufSize);

            if ($buf === false || $buf === '') {
                break;
            }

            $len = strlen($buf);

            for ($i = 0; $i < $len; $i++) {
                $ch = $buf[$i];

                if ($inQuote) {
                    if ($ch === '"') {
                        // Peek next char for `""` escape, possibly across buffers.
                        if ($i + 1 < $len) {
                            $next = $buf[$i + 1];
                        } else {
                            $next = false;

                            if (! feof($handle)) {
                                $peek = fgetc($handle);

                                if ($peek !== false) {
                                    $buf .= $peek;
                                    $len++;
                                    $next = $peek;
                                }
                            }
                        }

                        if ($next === '"') {
                            if (! $colDone) {
                                $cur .= '"';
                            }
                            $i++; // skip the second quote of the escape

                            continue;
                        }

                        $inQuote = false;

                        continue;
                    }

                    // Inside a quoted field: any char (including , \n \r) is data.
                    if (! $colDone) {
                        $cur .= $ch;
                    }

                    continue;
                }

                // ---- not in quote ---- //

                if ($atFieldStart) {
                    $atFieldStart = false;

                    if ($ch === '"') {
                        $inQuote = true;

                        continue;
                    }
                }

                if ($ch === ',') {
                    if (! $colDone) {
                        $cols[] = $cur;
                        $cur = '';

                        if (count($cols) >= $maxColumns) {
                            $colDone = true;
                        }
                    }
                    $atFieldStart = true;
                    $hasContent = true;

                    continue;
                }

                if ($ch === "\n" || $ch === "\r") {
                    if ($hasContent || $cur !== '' || ! empty($cols)) {
                        if (! $colDone) {
                            $cols[] = $cur;
                        }

                        yield $cols;
                    }

                    $cols = [];
                    $cur = '';
                    $colDone = false;
                    $hasContent = false;
                    $atFieldStart = true;

                    // Skip CRLF pair.
                    if ($ch === "\r" && $i + 1 < $len && $buf[$i + 1] === "\n") {
                        $i++;
                    }

                    continue;
                }

                if (! $colDone) {
                    $cur .= $ch;
                }
                $hasContent = true;
            }
        }

        if ($hasContent || $cur !== '' || ! empty($cols)) {
            if (! $colDone) {
                $cols[] = $cur;
            }

            yield $cols;
        }
    }

    /**
     * Apply the captured map to the database in a single transaction.
     */
    protected function applyToDatabase(array $map, string $locale, bool $dryRun): int
    {
        $hasDisplayNameColumn = Schema::hasColumn('categories', 'display_name');

        if (! $hasDisplayNameColumn) {
            $this->warn('Column `categories.display_name` is missing. Run `php artisan migrate` first.');
        }

        $categories = DB::table('categories')
            ->whereNotNull('additional')
            ->where('additional', '<>', '')
            ->select('id', 'additional')
            ->get();

        $updates = 0;
        $imageUpdates = 0;

        DB::beginTransaction();

        try {
            foreach ($categories as $category) {
                $additional = json_decode((string) $category->additional, true);

                if (! is_array($additional)) {
                    continue;
                }

                $tiktokId = $additional['tiktok_category_id'] ?? null;

                if (! $tiktokId || ! isset($map[$tiktokId])) {
                    continue;
                }

                $newName = $map[$tiktokId];

                if ($dryRun) {
                    $this->line("  [dry] cat {$category->id} ({$tiktokId}) → {$newName}");
                    $updates++;

                    continue;
                }

                if ($hasDisplayNameColumn) {
                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update([
                            'display_name' => $newName,
                            'updated_at' => now(),
                        ]);
                }

                DB::table('category_translations')
                    ->where('category_id', $category->id)
                    ->where('locale', $locale)
                    ->update([
                        'name' => $newName,
                    ]);

                $updates++;
            }

            if (! $dryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Failed: '.$e->getMessage());

            return 0;
        }

        if ($imageUpdates > 0) {
            $this->info("Also populated logo_path on {$imageUpdates} categories from CSV.");
        }

        return $updates;
    }

    protected function humanBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return number_format($bytes, 2).' '.$units[$i];
    }
}
