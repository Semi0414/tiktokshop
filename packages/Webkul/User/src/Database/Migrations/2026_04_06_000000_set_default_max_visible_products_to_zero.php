<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller')) {
            return;
        }

        if (! Schema::hasColumn('seller', 'max_visible_products')) {
            return;
        }

        DB::table('seller')->whereNull('max_visible_products')->update(['max_visible_products' => 0]);
    }

    public function down(): void
    {
        // Intentionally empty: we do not restore previous null semantics.
    }
};
