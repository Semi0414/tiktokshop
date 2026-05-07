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

        Schema::table('seller_applications', function (Blueprint $table) {
            $table->string('id_passport_number', 120)->nullable()->change();
            $table->string('legal_name')->nullable()->change();
            $table->string('document_front')->nullable()->change();
            $table->string('document_back')->nullable()->change();
            $table->string('document_selfie')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('seller_applications')) {
            return;
        }

        Schema::table('seller_applications', function (Blueprint $table) {
            $table->string('id_passport_number', 120)->nullable(false)->change();
            $table->string('legal_name')->nullable(false)->change();
            $table->string('document_front')->nullable(false)->change();
            $table->string('document_back')->nullable(false)->change();
            $table->string('document_selfie')->nullable(false)->change();
        });
    }
};
