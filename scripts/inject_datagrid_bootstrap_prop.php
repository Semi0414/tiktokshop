<?php

declare(strict_types=1);

$root = dirname(__DIR__).'/packages/Webkul/SuperAdmin/src/Resources/views';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($rii as $fileInfo) {
    if (! $fileInfo->isFile() || ! str_ends_with($fileInfo->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $fileInfo->getPathname();
    $content = file_get_contents($path);

    if (! preg_match('/<x-superadmin::datagrid(\s|>)/', $content)) {
        continue;
    }

    if (str_contains($content, ':datagrid-payload="$datagridPayload"')) {
        continue;
    }

    $updated = preg_replace(
        '/<x-superadmin::datagrid\s+/u',
        '<x-superadmin::datagrid :datagrid-payload="$datagridPayload" ',
        $content
    );

    if ($updated !== $content) {
        file_put_contents($path, $updated);
        fwrite(STDOUT, $path.PHP_EOL);
    }
}
