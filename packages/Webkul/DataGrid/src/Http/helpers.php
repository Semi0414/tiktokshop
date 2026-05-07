<?php

use Webkul\DataGrid\DataGrid;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;

if (! function_exists('datagrid')) {
    /**
     * Datagrid helper.
     */
    function datagrid(string $datagridClass): DataGrid
    {
        if (! is_subclass_of($datagridClass, DataGrid::class)) {
            throw new InvalidDataGridException("'{$datagridClass}' must extend the '".DataGrid::class."' class.");
        }

        return app($datagridClass);
    }
}

if (! function_exists('datagrid_formatted')) {
    /**
     * Same as {@see DataGrid()} then {@see DataGrid::getFormattedData()} without JSON envelope.
     *
     * @return array<string, mixed>
     */
    function datagrid_formatted(string $datagridClass): array
    {
        return datagrid($datagridClass)->getFormattedData();
    }
}
