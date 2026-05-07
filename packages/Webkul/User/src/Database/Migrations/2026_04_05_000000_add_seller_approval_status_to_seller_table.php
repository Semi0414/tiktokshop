<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller')) {
            return;
        }

        if (! Schema::hasColumn('seller', 'seller_approval_status')) {
            Schema::table('seller', function (Blueprint $table) {
                $table->string('seller_approval_status', 32)->default('approved')->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seller') && Schema::hasColumn('seller', 'seller_approval_status')) {
            Schema::table('seller', function (Blueprint $table) {
                $table->dropColumn('seller_approval_status');
            });
        }
    }
};
