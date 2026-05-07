<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename base tables (existing deployments).
        if (Schema::hasTable('admins') && ! Schema::hasTable('seller')) {
            DB::statement('RENAME TABLE admins TO seller');
        }

        if (Schema::hasTable('admin_password_resets') && ! Schema::hasTable('seller_password_resets')) {
            DB::statement('RENAME TABLE admin_password_resets TO seller_password_resets');
        }

        if (Schema::hasTable('seller')) {
            if (! Schema::hasColumn('seller', 'wallet_balance')) {
                Schema::table('seller', function (Blueprint $table) {
                    $table->decimal('wallet_balance', 12, 2)->default(0);
                });
            }

            if (! Schema::hasColumn('seller', 'credit_score')) {
                Schema::table('seller', function (Blueprint $table) {
                    $table->unsignedInteger('credit_score')->default(100);
                });
            }
        }

        // Wallet transaction ledger for sellers.
        if (! Schema::hasTable('seller_wallet_transactions')) {
            Schema::create('seller_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->decimal('amount', 12, 2);
                $table->enum('type', ['credit', 'debit']);
                $table->string('description')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->decimal('balance_before', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Not reversible for deployments with unknown data.
    }
};
