<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_applications')) {
            return;
        }

        if (! Schema::hasColumn('seller_applications', 'seller_id')) {
            Schema::table('seller_applications', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('id');
                $table->index('seller_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seller_applications') && Schema::hasColumn('seller_applications', 'seller_id')) {
            Schema::table('seller_applications', function (Blueprint $table) {
                $table->dropColumn('seller_id');
            });
        }
    }
};
