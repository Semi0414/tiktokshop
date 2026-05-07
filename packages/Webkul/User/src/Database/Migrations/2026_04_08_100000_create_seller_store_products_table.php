<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_store_products')) {
            Schema::create('seller_store_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('seller_id');
                $table->unsignedInteger('product_id');
                $table->decimal('commission_percent', 5, 2);
                $table->boolean('is_recommended')->default(false);
                $table->timestamps();

                $table->unique(['seller_id', 'product_id']);
                $table->index('seller_id');
                $table->index('product_id');
                $table->index(['seller_id', 'is_recommended']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_store_products');
    }
};
