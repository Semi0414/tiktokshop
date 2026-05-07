<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('superadmin_email_logs')) {
            return;
        }

        Schema::create('superadmin_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to_email');
            $table->string('recipient_type', 32); // seller|customer|custom
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('subject');
            $table->string('mail_type', 32)->default('custom'); // welcome|custom
            $table->string('status', 16)->default('sent'); // sent|failed
            $table->text('error_message')->nullable();
            $table->string('body_preview', 512)->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('superadmin_email_logs');
    }
};
