<?php

namespace Webkul\Admin\DataGrids\Seller;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\User\Support\SellerCommissionPercentRules;

class SellerStoreProductDataGrid extends DataGrid
{
    protected $primaryColumn = 'ssp_id';

    protected $sortColumn = 'ssp_id';

    public function prepareQueryBuilder()
    {
        $seller = auth()->guard('admin')->user();
        $sellerId = (int) ($seller?->id ?? 0);

        $locale = app()->getLocale();
        $channel = core()->getCurrentChannelCode();

        $queryBuilder = DB::table('seller_store_products as ssp')
            ->join('product_flat as pf', 'ssp.product_id', '=', 'pf.product_id')
            ->where('ssp.seller_id', $sellerId)
            ->where('pf.locale', $locale)
            ->where('pf.channel', $channel)
            ->select(
                'ssp.id as ssp_id',
                'ssp.product_id',
                'ssp.commission_percent',
                'ssp.is_recommended',
                'pf.sku',
                'pf.name',
                'pf.type',
                'pf.status',
                'pf.price',
            );

        $sellerProductName = request()->input('seller_product_name');
        if ($sellerProductName !== null && $sellerProductName !== '') {
            $queryBuilder->where('pf.name', 'like', '%'.addcslashes((string) $sellerProductName, '%_\\').'%');
        }

        $sellerProductId = request()->input('seller_product_id');
        if ($sellerProductId !== null && $sellerProductId !== '' && is_numeric($sellerProductId)) {
            $queryBuilder->where('pf.product_id', (int) $sellerProductId);
        }

        $sellerCategory = request()->input('seller_product_category');
        if ($sellerCategory !== null && $sellerCategory !== '') {
            $queryBuilder->whereExists(function ($sub) use ($sellerCategory) {
                $sub->select(DB::raw(1))
                    ->from('product_categories as pc_filter')
                    ->whereColumn('pc_filter.product_id', 'pf.product_id')
                    ->where('pc_filter.category_id', $sellerCategory);
            });
        }

        $sellerProductStatus = request()->input('seller_product_status');
        if ($sellerProductStatus !== null && $sellerProductStatus !== '') {
            $queryBuilder->where('pf.status', (int) $sellerProductStatus);
        }

        $sellerProductRecommended = request()->input('seller_product_recommended');
        if ($sellerProductRecommended === '1') {
            $queryBuilder->where('ssp.is_recommended', 1);
        } elseif ($sellerProductRecommended === '0') {
            $queryBuilder->where('ssp.is_recommended', 0);
        }

        $this->addFilter('product_id', 'pf.product_id');
        $this->addFilter('name', 'pf.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.catalog.products.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('admin::app.catalog.products.index.datagrid.sku'),
            'type' => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'commission_percent',
            'label' => trans('admin::app.seller-panel.store-products.commission-percent'),
            'type' => 'decimal',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'is_recommended',
            'label' => trans('admin::app.seller-panel.store-products.recommended'),
            'type' => 'string',
            'sortable' => true,
            'closure' => function ($row) {
                return (int) ($row->is_recommended ?? 0) === 1
                    ? trans('admin::app.seller-panel.store-products.recommended-on')
                    : trans('admin::app.seller-panel.store-products.recommended-off');
            },
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('admin::app.catalog.products.index.datagrid.price'),
            'type' => 'decimal',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.catalog.products.index.datagrid.id'),
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.catalog.products.index.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'closure' => function ($row) {
                return (int) ($row->status ?? 0) === 1
                    ? trans('admin::app.seller-panel.store-products.status-active')
                    : trans('admin::app.seller-panel.store-products.status-inactive');
            },
        ]);
    }

    public function prepareActions()
    {
        /* Row action column hidden; sellers use mass actions (bulk edit / remove). */
    }

    public function prepareMassActions()
    {
        $seller = auth()->guard('admin')->user();
        $rule = SellerCommissionPercentRules::forLevel($seller->seller_level ?? null);

        $this->addMassAction([
            'icon' => 'icon-edit',
            'title' => trans('admin::app.seller-panel.store-products.bulk-edit'),
            'method' => 'POST',
            'url' => route('admin.seller.store-products.bulk-update'),
            'meta' => [
                'modal' => 'seller_store_bulk_edit',
                'commission' => [
                    'readonly' => $rule['readonly'],
                    'min' => $rule['min'],
                    'max' => $rule['max'],
                    'default' => $rule['default'],
                ],
            ],
        ]);

        $this->addMassAction([
            'icon' => 'icon-delete',
            'title' => trans('admin::app.seller-panel.store-products.mass-remove'),
            'method' => 'POST',
            'url' => route('admin.seller.store-products.mass-destroy'),
        ]);
    }
}
