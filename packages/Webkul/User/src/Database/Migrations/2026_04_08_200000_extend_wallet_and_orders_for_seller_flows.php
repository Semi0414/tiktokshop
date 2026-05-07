<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller_wallet_transactions')) {
            Schema::table('seller_wallet_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('seller_wallet_transactions', 'status')) {
                    $table->string('status', 24)->default('completed')->after('type');
                }
                if (! Schema::hasColumn('seller_wallet_transactions', 'kind')) {
                    $table->string('kind', 48)->default('legacy')->after('status');
                }
                if (! Schema::hasColumn('seller_wallet_transactions', 'payment_method')) {
                    $table->string('payment_method', 64)->nullable()->after('kind');
                }
                if (! Schema::hasColumn('seller_wallet_transactions', 'meta')) {
                    $table->json('meta')->nullable()->after('payment_method');
                }
                if (! Schema::hasColumn('seller_wallet_transactions', 'receipt_path')) {
                    $table->string('receipt_path', 512)->nullable()->after('meta');
                }
            });

            DB::table('seller_wallet_transactions')->whereNull('status')->update(['status' => 'completed']);
            DB::table('seller_wallet_transactions')->whereNull('kind')->update(['kind' => 'legacy']);
        }

        if (! Schema::hasTable('seller_deposit_method_configs')) {
            Schema::create('seller_deposit_method_configs', function (Blueprint $table) {
                $table->id();
                $table->string('code', 32)->unique();
                $table->string('name');
                $table->text('address_text')->nullable();
                $table->string('network')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            $now = now();
            $methods = [
                ['code' => 'USDT', 'name' => 'USDT (TRC20)', 'address_text' => 'TX_REPLACE_WITH_PLATFORM_USDT_ADDRESS', 'network' => 'TRC20', 'sort_order' => 1],
                ['code' => 'ETH', 'name' => 'Ethereum (ETH)', 'address_text' => '0x_REPLACE_WITH_PLATFORM_ETH_ADDRESS', 'network' => 'ERC20', 'sort_order' => 2],
                ['code' => 'BTC', 'name' => 'Bitcoin (BTC)', 'address_text' => 'bc1_REPLACE_WITH_PLATFORM_BTC_ADDRESS', 'network' => 'BTC', 'sort_order' => 3],
                ['code' => 'USDC', 'name' => 'USDC', 'address_text' => '0x_REPLACE_WITH_PLATFORM_USDC_ADDRESS', 'network' => 'ERC20', 'sort_order' => 4],
                ['code' => 'BANK_CARD', 'name' => 'Bank Card', 'address_text' => 'Transfer to the company bank account. Support will verify your receipt.', 'network' => null, 'sort_order' => 5],
            ];

            foreach ($methods as $m) {
                DB::table('seller_deposit_method_configs')->insert(array_merge($m, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'seller_make_order_at')) {
                    $table->timestamp('seller_make_order_at')->nullable()->after('seller_approval_status');
                }
                if (! Schema::hasColumn('orders', 'seller_commission_credited')) {
                    $table->boolean('seller_commission_credited')->default(false)->after('seller_make_order_at');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_deposit_method_configs');

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'seller_commission_credited')) {
                    $table->dropColumn('seller_commission_credited');
                }
                if (Schema::hasColumn('orders', 'seller_make_order_at')) {
                    $table->dropColumn('seller_make_order_at');
                }
            });
        }

        if (Schema::hasTable('seller_wallet_transactions')) {
            Schema::table('seller_wallet_transactions', function (Blueprint $table) {
                foreach (['receipt_path', 'meta', 'payment_method', 'kind', 'status'] as $col) {
                    if (Schema::hasColumn('seller_wallet_transactions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
