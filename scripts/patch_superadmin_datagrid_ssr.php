<?php

/**
 * One-off patch: Super Admin listing controllers — add datagridPayload via withDatagridPayload().
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$dir = $root.'/packages/Webkul/SuperAdmin/src/Http/Controllers';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($rii as $fileInfo) {
    if (! $fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
        continue;
    }

    $path = $fileInfo->getPathname();

    if (str_ends_with($path, DIRECTORY_SEPARATOR.'SellerController.php')) {
        continue;
    }

    $code = file_get_contents($path);
    $original = $code;

    // Pattern: view(..., compact(...));
    $code = preg_replace_callback(
        '/if\s*\(\s*request\(\)->ajax\(\)\s*\)\s*\{\s*return\s+datagrid\(([A-Za-z0-9_\\\\]+)::class\)->process\(\);\s*\}\s*return\s+view\(\s*\'([^\']+)\'\s*,\s*compact\(\s*([^)]+)\s*\)\s*\)\s*;/s',
        static function (array $m): string {
            $grid = $m[1];
            $viewPath = $m[2];
            $compactArgs = $m[3];

            return "if (request()->ajax()) {\n            return datagrid({$grid}::class)->process();\n        }\n\n        return view('{$viewPath}', \$this->withDatagridPayload(compact({$compactArgs}), {$grid}::class));";
        },
        $code
    );

    // Pattern: view('path'); without compact
    $code = preg_replace_callback(
        '/if\s*\(\s*request\(\)->ajax\(\)\s*\)\s*\{\s*return\s+datagrid\(([A-Za-z0-9_\\\\]+)::class\)->process\(\);\s*\}\s*return\s+view\(\s*\'([^\']+)\'\s*\)\s*;/s',
        static function (array $m): string {
            $grid = $m[1];
            $viewPath = $m[2];

            return "if (request()->ajax()) {\n            return datagrid({$grid}::class)->process();\n        }\n\n        return view('{$viewPath}', \$this->withDatagridPayload([], {$grid}::class));";
        },
        $code
    );

    if ($code !== $original) {
        file_put_contents($path, $code);
        fwrite(STDOUT, "Patched: {$path}\n");
    }
}
