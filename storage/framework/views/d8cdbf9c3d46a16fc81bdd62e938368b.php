<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'datagridPayload' => [],
    'listingSrc' => '',
    'paginationNamespace' => null,
    'massCheckboxSuffix' => 'dg',
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
    'datagridPayload' => [],
    'listingSrc' => '',
    'paginationNamespace' => null,
    'massCheckboxSuffix' => 'dg',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    use Webkul\SuperAdmin\Support\DatagridSsrUrlBuilder;

    $request = request();
    $records = $datagridPayload['records'] ?? [];
    $columns = collect($datagridPayload['columns'] ?? [])->where('visibility', true)->values()->all();
    $massActions = $datagridPayload['mass_actions'] ?? [];
    $meta = $datagridPayload['meta'] ?? [];
    $primary = $meta['primary_column'] ?? 'id';
    $hasMass = count($massActions) > 0;
    $hasRowActions = count($datagridPayload['actions'] ?? []) > 0;
    $colSpanEmpty = max(1, count($columns) + ($hasMass ? 1 : 0) + ($hasRowActions ? 1 : 0));
?>

<div class="table-responsive box-shadow grid w-full overflow-x-auto rounded bg-white dark:bg-gray-900">
    <div class="w-full">
        <div class="border-b bg-gray-50 px-4 py-2.5 dark:border-gray-800 dark:bg-gray-950">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                        <?php if($hasMass): ?>
                            <?php
                                $hdrCheckId = 'mass_action_select_all_records_ssr_'.$massCheckboxSuffix;
                            ?>
                            <th scope="col" class="w-14 px-2 py-2 text-start align-middle">
                                <label for="<?php echo e($hdrCheckId); ?>">
                                    <input
                                        type="checkbox"
                                        id="<?php echo e($hdrCheckId); ?>"
                                        data-dg-ssr-select-all
                                        class="peer hidden"
                                        aria-label="<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.mass-actions.select-action'); ?>"
                                    />

                                    <span class="icon-uncheckbox cursor-pointer rounded-md text-2xl peer-checked:text-blue-600 peer-checked:icon-checked"></span>
                                </label>
                            </th>
                        <?php endif; ?>

                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $sortHref = DatagridSsrUrlBuilder::sortUrl($request, $listingSrc, $column, $paginationNamespace);
                            ?>
                            <th scope="col" class="px-3 py-2 text-start text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 align-middle">
                                <?php if($sortHref && ($column['sortable'] ?? false)): ?>
                                    <a href="<?php echo e($sortHref); ?>" class="inline-flex items-center gap-1 hover:text-gray-900 dark:hover:text-white">
                                        <?php echo e($column['label'] ?? ''); ?>


                                        <?php if(($request->input('sort.column') ?? '') === ($column['index'] ?? '')): ?>
                                            <span class="icon-<?php echo e(($request->input('sort.order') ?? 'desc') === 'asc' ? 'down-stat' : 'up-stat'); ?> text-base"></span>
                                        <?php endif; ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo e($column['label'] ?? ''); ?>

                                <?php endif; ?>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($hasRowActions): ?>
                            <th scope="col" class="px-3 py-2 text-end text-xs font-semibold uppercase text-gray-600 dark:text-gray-300 align-middle">
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.table.actions'); ?>
                            </th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $rowId = data_get($record, $primary);
                            $ridLabel = 'mass_action_select_record_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $rowId);
                        ?>
                        <tr class="transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                            <?php if($hasMass): ?>
                                <td class="px-2 py-3 align-middle">
                                    <label for="<?php echo e($ridLabel); ?>">
                                        <input
                                            type="checkbox"
                                            id="<?php echo e($ridLabel); ?>"
                                            data-dg-ssr-mass
                                            value="<?php echo e($rowId); ?>"
                                            class="peer hidden"
                                            aria-label="<?php echo app('translator')->get('superadmin::app.components.datagrid.toolbar.mass-actions.select-action'); ?>"
                                        />

                                        <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"></span>
                                    </label>
                                </td>
                            <?php endif; ?>

                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $idx = $column['index'] ?? '';
                                    $cell = data_get($record, $idx);
                                ?>
                                <td class="px-3 py-3 text-sm text-gray-700 dark:text-gray-200 align-middle break-words">
                                    <?php if($cell !== null && $cell !== '' && is_string($cell)): ?>
                                        <?php echo $cell; ?>

                                    <?php elseif($cell !== null && $cell !== ''): ?>
                                        <?php echo e($cell); ?>

                                    <?php endif; ?>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($hasRowActions): ?>
                                <td class="px-3 py-3 text-end align-middle">
                                    <?php $__currentLoopData = data_get($record, 'actions', []) ?: []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $method = strtoupper((string) ($action['method'] ?? 'GET'));
                                            $actUrl = (string) ($action['url'] ?? '');
                                            $confirmMessage = __('superadmin::app.components.modal.confirm.message');
                                        ?>
                                        <?php if($method === 'GET'): ?>
                                            <a
                                                href="<?php echo e($actUrl !== '' ? $actUrl : '#'); ?>"
                                                title="<?php echo e($action['title'] ?? ''); ?>"
                                                class="<?php echo e(($action['icon'] ?? '')); ?> cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 inline-block"
                                            ></a>
                                        <?php elseif($actUrl !== ''): ?>
                                            <form
                                                method="POST"
                                                action="<?php echo e($actUrl); ?>"
                                                class="inline-flex"
                                                onsubmit="return confirm(<?php echo json_encode($confirmMessage, 15, 512) ?>);"
                                            >
                                                <?php echo csrf_field(); ?>
                                                <?php if(in_array($method, ['PUT', 'PATCH', 'DELETE'])): ?>
                                                    <?php echo method_field($method); ?>
                                                <?php endif; ?>

                                                <button
                                                    type="submit"
                                                    title="<?php echo e($action['title'] ?? ''); ?>"
                                                    class="<?php echo e(($action['icon'] ?? '')); ?> inline-block cursor-pointer rounded-md border-0 bg-transparent p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                                >
                                                    <?php if(empty($action['icon'])): ?>
                                                        <span class="text-sm"><?php echo e($action['title'] ?? ''); ?></span>
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td
                                colspan="<?php echo e($colSpanEmpty); ?>"
                                class="px-4 py-12 text-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                <?php echo app('translator')->get('superadmin::app.components.datagrid.table.no-records-available'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\SuperAdmin\src\Providers/../Resources/views/components/datagrid/ssr-default-table.blade.php ENDPATH**/ ?>