<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\User\Services\SellerJoinSellerService;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_applications') || ! Schema::hasTable('seller')) {
            return;
        }

        $rows = DB::table('seller_applications')
            ->whereNull('seller_id')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return;
        }

        $service = app(SellerJoinSellerService::class);

        foreach ($rows as $application) {
            $sellerId = $service->ensureSellerForApplication($application);

            DB::table('seller_applications')
                ->where('id', $application->id)
                ->update([
                    'seller_id' => $sellerId,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
