<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller_applications')) {
            return;
        }

        Schema::create('seller_applications', function (Blueprint $table) {
            $table->id();
            $table->string('shop_logo')->nullable();
            $table->string('shop_name');
            $table->string('shop_address', 500);
            $table->string('country', 120);
            $table->string('id_passport_number', 120)->nullable();
            $table->string('legal_name')->nullable();
            $table->string('document_front')->nullable();
            $table->string('document_back')->nullable();
            $table->string('document_selfie')->nullable();
            $table->enum('verify_type', ['email', 'mobile'])->default('email');
            $table->string('email')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('password');
            $table->string('invite_code', 120)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_applications');
    }
};
