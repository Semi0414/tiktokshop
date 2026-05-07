<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'wallet_balance')) {
                $table->decimal('wallet_balance', 12, 2)->default(0)->after('is_suspended');
            }

            if (! Schema::hasColumn('customers', 'credit_score')) {
                $table->unsignedInteger('credit_score')->default(100)->after('wallet_balance');
            }
        });

        if (! Schema::hasTable('customer_wallet_transactions')) {
            Schema::create('customer_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'wallet_balance')) {
                $table->dropColumn('wallet_balance');
            }

            if (Schema::hasColumn('customers', 'credit_score')) {
                $table->dropColumn('credit_score');
            }
        });

        Schema::dropIfExists('customer_wallet_transactions');
    }
};

