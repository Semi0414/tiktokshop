<?php

$root = dirname(__DIR__);

$exclude = array_flip([
    'packages/Webkul/SuperAdmin/src/Resources/views/components/datagrid/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/customers/customers/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/catalog/products/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/sellers/orders/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/settings/users/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/customers/reviews/index.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/customers/customers/view/orders.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/customers/customers/view/invoices.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/customers/customers/view/reviews.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/sellers/view/orders.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/sellers/view/invoices.blade.php',
    'packages/Webkul/SuperAdmin/src/Resources/views/sellers/view/reviews.blade.php',
]);

$viewsDir = $root.'/packages/Webkul/SuperAdmin/src/Resources/views';

$rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir)
);

foreach ($rii as $f) {
    if (! $f->isFile() || $f->getExtension() !== 'php') {
        continue;
    }

    $full = $f->getPathname();

    $p = str_replace('\\', '/', substr($full, strlen($root) + 1));

    if (isset($exclude[$p])) {
        continue;
    }

    $t = file_get_contents($full);

    $n = str_replace('<x-superadmin::datagrid ', '<x-superadmin::datagrid.ssr ', $t);
    $n = str_replace('</x-superadmin::datagrid>', '</x-superadmin::datagrid.ssr>', $n);

    if ($n !== $t) {
        file_put_contents($full, $n);
        fwrite(STDOUT, "updated {$p}\n");
    }
}
