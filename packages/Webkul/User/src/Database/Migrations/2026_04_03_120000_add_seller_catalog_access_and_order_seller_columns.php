<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller')) {
            Schema::table('seller', function (Blueprint $table) {
                if (! Schema::hasColumn('seller', 'allowed_product_ids')) {
                    $table->json('allowed_product_ids')->nullable()->after('credit_score');
                }

                if (! Schema::hasColumn('seller', 'max_visible_products')) {
                    $table->unsignedInteger('max_visible_products')->nullable()->after('allowed_product_ids');
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'seller_id')) {
                    $table->unsignedBigInteger('seller_id')->nullable()->after('channel_id');
                }

                if (! Schema::hasColumn('orders', 'seller_approval_status')) {
                    $table->string('seller_approval_status', 32)->nullable()->after('seller_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'seller_approval_status')) {
                    $table->dropColumn('seller_approval_status');
                }

                if (Schema::hasColumn('orders', 'seller_id')) {
                    $table->dropColumn('seller_id');
                }
            });
        }

        if (Schema::hasTable('seller')) {
            Schema::table('seller', function (Blueprint $table) {
                if (Schema::hasColumn('seller', 'max_visible_products')) {
                    $table->dropColumn('max_visible_products');
                }

                if (Schema::hasColumn('seller', 'allowed_product_ids')) {
                    $table->dropColumn('allowed_product_ids');
                }
            });
        }
    }
};
