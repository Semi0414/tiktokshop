<?php

namespace Webkul\Admin\DataGrids\Seller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;
use Webkul\User\Support\SellerCommissionPercentRules;

class WarehouseProductDataGrid extends DataGrid
{
    protected $primaryColumn = 'product_id';

    protected $sortColumn = 'product_id';

    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();
        $seller = auth()->guard('admin')->user();
        $sellerId = (int) ($seller?->id ?? 0);

        $locale = app()->getLocale();
        $channel = core()->getCurrentChannelCode();

        $queryBuilder = DB::table('product_flat')
            ->leftJoin('attribute_families as af', 'product_flat.attribute_family_id', '=', 'af.id')
            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
            ->select(
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'af.name as attribute_family',
            )
            ->addSelect(DB::raw('COALESCE(SUM('.$tablePrefix.'product_inventories.qty), 0) as quantity'))
            ->addSelect(DB::raw(
                '(SELECT pi.path FROM '.$tablePrefix.'product_images pi WHERE pi.product_id = '.$tablePrefix.'product_flat.product_id ORDER BY pi.position ASC, pi.id ASC LIMIT 1) as base_image'
            ))
            ->where('product_flat.locale', $locale)
            ->where('product_flat.channel', $channel)
            ->where('product_flat.status', 1)
            ->where('product_flat.visible_individually', 1)
            ->groupBy(
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'af.name'
            );

        if ($sellerId > 0) {
            $queryBuilder->whereNotExists(function ($sub) use ($sellerId) {
                $sub->select(DB::raw(1))
                    ->from('seller_store_products as ssp')
                    ->whereColumn('ssp.product_id', 'product_flat.product_id')
                    ->where('ssp.seller_id', $sellerId);
            });
        }

        $sellerProductName = request()->input('seller_warehouse_product_name');
        if ($sellerProductName !== null && $sellerProductName !== '') {
            $queryBuilder->where('product_flat.name', 'like', '%'.addcslashes((string) $sellerProductName, '%_\\').'%');
        }

        $sellerProductId = request()->input('seller_warehouse_product_id');
        if ($sellerProductId !== null && $sellerProductId !== '' && is_numeric($sellerProductId)) {
            $queryBuilder->where('product_flat.product_id', (int) $sellerProductId);
        }

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('name', 'product_flat.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'base_image',
            'label' => trans('admin::app.catalog.products.index.datagrid.image'),
            'type' => 'string',
            'sortable' => false,
            'exportable' => false,
            'closure' => function ($row) {
                if (empty($row->base_image)) {
                    return '<span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded border border-dashed border-gray-200 bg-gray-50 text-xs text-gray-400 dark:border-gray-700 dark:bg-gray-900">—</span>';
                }

                $src = e(Storage::url($row->base_image));

                return '<img src="'.$src.'" alt="" class="h-12 w-12 shrink-0 rounded border border-gray-200 bg-white object-cover dark:border-gray-700" loading="lazy" />';
            },
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.catalog.products.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('admin::app.catalog.products.index.datagrid.sku'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('admin::app.catalog.products.index.datagrid.price'),
            'type' => 'decimal',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('admin::app.catalog.products.index.datagrid.qty'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.catalog.products.index.datagrid.id'),
            'type' => 'integer',
            'sortable' => true,
        ]);
    }

    public function prepareActions() {}

    public function prepareMassActions()
    {
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller instanceof Admin ? ($seller->seller_level ?? null) : null);

        $this->addMassAction([
            'icon' => 'icon-add',
            'title' => trans('admin::app.seller-panel.product-warehouse.add-to-store'),
            'method' => 'POST',
            'url' => route('admin.seller.product-warehouse.attach'),
            'meta' => [
                'modal' => 'seller_add_to_store',
                'commission' => [
                    'readonly' => $rule['readonly'],
                    'min' => $rule['min'],
                    'max' => $rule['max'],
                    'default' => $rule['default'],
                ],
            ],
        ]);
    }
}
