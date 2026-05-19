<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'datagridPayload' => null,
    'paginationNamespace' => null,
    'isMultiRow' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'datagridPayload' => null,
    'paginationNamespace' => null,
    'isMultiRow' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $bootstrapJsonId = '';

    $dgBootstrapEncoded = null;

    if ($datagridPayload !== null) {
        $bootstrapJsonId = 'sdgb_'.str_replace('.', '', uniqid('', true));

        $dgBootstrapEncoded = json_encode(
            $datagridPayload,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE
        );

        if ($dgBootstrapEncoded === false) {
            report(new \RuntimeException(
                'SuperAdmin datagrid JSON encode failed (grid id '.($datagridPayload['id'] ?? 'unknown').'): '.json_last_error_msg(),
            ));

            $dgBootstrapEncoded = json_encode([
                'id' => '',
                'columns' => [],
                'actions' => [],
                'mass_actions' => [],
                'records' => [],
                'meta' => [
                    'primary_column' => 'id',
                    'from' => null,
                    'to' => null,
                    'total' => 0,
                    'per_page_options' => [10, 20, 30, 40, 50],
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        }
    }

    $listingSrc = trim((string) ($attributes->get('src') ?? ''));

    $appliedBootstrap = \Webkul\SuperAdmin\Support\DatagridSsrApplied::forToolbar(
        request(),
        is_array($datagridPayload['meta'] ?? null) ? $datagridPayload['meta'] : [],
        $paginationNamespace
    );

    /** Unique per grid instance — avoids duplicate DOM ids when multiple SSR grids mount on one page (e.g. customer/seller view). */
    $massCheckboxSuffix = $bootstrapJsonId !== '' ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $bootstrapJsonId) : 'dg';
?>

<?php if($bootstrapJsonId !== ''): ?>
    <script
        type="application/json"
        id="<?php echo e($bootstrapJsonId); ?>"
    ><?php echo $dgBootstrapEncoded; ?></script>
<?php endif; ?>

<v-datagrid
    <?php echo e($attributes); ?>

    :tableless="<?php echo json_encode(true, 15, 512) ?>"
    :applied-bootstrap="<?php echo \Illuminate\Support\Js::from($appliedBootstrap)->toHtml() ?>"
    :bootstrap="<?php echo \Illuminate\Support\Js::from($datagridPayload ?? null)->toHtml() ?>"
    bootstrap-json-id="<?php echo e($bootstrapJsonId); ?>"
    :pagination-namespace="<?php echo \Illuminate\Support\Js::from($paginationNamespace)->toHtml() ?>"
>
    <?php if(isset($table)): ?>
        <?php echo e($table); ?>

    <?php else: ?>
        <?php echo $__env->make('superadmin::components.datagrid.ssr-default-table', [
            'datagridPayload' => $datagridPayload ?? [],
            'listingSrc' => $listingSrc,
            'paginationNamespace' => $paginationNamespace,
            'massCheckboxSuffix' => $massCheckboxSuffix,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</v-datagrid>

<?php echo $__env->make('superadmin::components.datagrid.scripts-register', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/components/datagrid/ssr.blade.php ENDPATH**/ ?>