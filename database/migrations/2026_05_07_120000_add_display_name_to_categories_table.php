<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a dedicated `display_name` column to `categories` so that the human
     * readable category title from the imported TikTok Mall exports can be
     * stored without overwriting the locale-specific `category_translations.name`.
     *
     * Populated via `php artisan tiktokshop:populate-category-names` from the
     * large CSV exports.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'display_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('display_name', 512)->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('categories', 'display_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('display_name');
            });
        }
    }
};
