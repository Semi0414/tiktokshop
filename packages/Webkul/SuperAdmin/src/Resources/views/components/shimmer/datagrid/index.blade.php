@props(['isMultiRow' => false])

<div>
    <x-superadmin::shimmer.datagrid.toolbar />

    <div class="mt-4 flex">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
                <x-superadmin::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />

                <x-superadmin::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />
            </div>
        </div>
    </div>
</div>
