<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Older installs may have `superadmin_email_logs` without `mail_type` because
     * create_superadmin_email_logs_table exits early when the table already exists.
     */
    public function up(): void
    {
        if (! Schema::hasTable('superadmin_email_logs')) {
            return;
        }

        if (! Schema::hasColumn('superadmin_email_logs', 'mail_type')) {
            Schema::table('superadmin_email_logs', function (Blueprint $table) {
                $table->string('mail_type', 32)->default('custom')->after('subject');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('superadmin_email_logs')) {
            return;
        }

        if (Schema::hasColumn('superadmin_email_logs', 'mail_type')) {
            Schema::table('superadmin_email_logs', function (Blueprint $table) {
                $table->dropColumn('mail_type');
            });
        }
    }
};
