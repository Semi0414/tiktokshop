<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('superadmin_email_logs')) {
            return;
        }

        if (! Schema::hasColumn('superadmin_email_logs', 'body_preview')) {
            Schema::table('superadmin_email_logs', function (Blueprint $table) {
                $table->string('body_preview', 512)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('superadmin_email_logs')) {
            return;
        }

        if (Schema::hasColumn('superadmin_email_logs', 'body_preview')) {
            Schema::table('superadmin_email_logs', function (Blueprint $table) {
                $table->dropColumn('body_preview');
            });
        }
    }
};
