<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller')) {
            if (! Schema::hasColumn('seller', 'referral_code')) {
                Schema::table('seller', function (Blueprint $table) {
                    $table->string('referral_code', 32)->nullable()->unique()->after('email');
                });
            }

            DB::table('seller')
                ->where(function ($q) {
                    $q->whereNull('referral_code')->orWhere('referral_code', '');
                })
                ->orderBy('id')
                ->chunkById(100, function ($sellers) {
                    foreach ($sellers as $row) {
                        DB::table('seller')->where('id', $row->id)->update([
                            'referral_code' => $this->makeUniqueSellerReferralCode(),
                        ]);
                    }
                });
        }

        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'referral_seller_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->unsignedInteger('referral_seller_id')->nullable()->after('channel_id');
                $table->index('referral_seller_id');
            });
        }
    }

    protected function makeUniqueSellerReferralCode(): string
    {
        for ($i = 0; $i < 50; $i++) {
            $code = strtoupper(Str::random(8));
            if (! DB::table('seller')->where('referral_code', $code)->exists()) {
                return $code;
            }
        }

        return strtoupper(Str::random(10)).(string) random_int(10, 99);
    }

    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'referral_seller_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropIndex(['referral_seller_id']);
                $table->dropColumn('referral_seller_id');
            });
        }

        if (Schema::hasTable('seller') && Schema::hasColumn('seller', 'referral_code')) {
            Schema::table('seller', function (Blueprint $table) {
                $table->dropColumn('referral_code');
            });
        }
    }
};
