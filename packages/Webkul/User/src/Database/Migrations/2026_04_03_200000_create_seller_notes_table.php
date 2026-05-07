<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_notes')) {
            Schema::create('seller_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('seller_id');
                $table->text('note');
                $table->boolean('seller_notified')->default(false);
                $table->timestamps();

                $table->foreign('seller_id')->references('id')->on('seller')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_notes');
    }
};
