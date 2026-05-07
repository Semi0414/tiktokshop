<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_applications')) {
            return;
        }

        if (! Schema::hasColumn('seller_applications', 'password')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE seller_applications MODIFY password VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE seller_applications ALTER COLUMN password DROP NOT NULL');
        } else {
            Schema::table('seller_applications', function (Blueprint $table) {
                $table->string('password')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('seller_applications')) {
            return;
        }

        if (! Schema::hasColumn('seller_applications', 'password')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE seller_applications MODIFY password VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE seller_applications ALTER COLUMN password SET NOT NULL');
        } else {
            Schema::table('seller_applications', function (Blueprint $table) {
                $table->string('password')->nullable(false)->change();
            });
        }
    }
};
