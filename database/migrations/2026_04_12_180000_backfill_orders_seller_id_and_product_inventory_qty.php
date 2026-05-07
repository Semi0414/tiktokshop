<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the database updates.
     *
     * - Set seller_id = 3 on orders where seller_id is null.
     * - Set qty = 1000 on all product_inventories rows.
     * - Rebuild inventory indices so storefront stock checks stay in sync.
     */
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'seller_id')) {
            DB::table('orders')->whereNull('seller_id')->update(['seller_id' => 3]);
        }

        if (Schema::hasTable('product_inventories')) {
            DB::table('product_inventories')->update(['qty' => 1000]);
        }

        Artisan::call('indexer:index', [
            '--type' => ['inventory'],
            '--mode' => ['full'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
