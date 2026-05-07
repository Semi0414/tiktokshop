<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller') && ! Schema::hasColumn('seller', 'seller_level')) {
            Schema::table('seller', function (Blueprint $table) {
                $table->string('seller_level', 16)->nullable()->default('Beginner')->after('referral_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seller') && Schema::hasColumn('seller', 'seller_level')) {
            Schema::table('seller', function (Blueprint $table) {
                $table->dropColumn('seller_level');
            });
        }
    }
};
