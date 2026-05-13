<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow wallet / non-order seller notifications (nullable order_id + seller_id + optional deep link).
     */
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $this->dropMysqlForeignKeysOnNotificationsOrderId();
            $this->makeMysqlNotificationsOrderIdNullable();
        } elseif ($driver === 'sqlite') {
            Schema::table('notifications', function (Blueprint $table) {
                $table->unsignedInteger('order_id')->nullable()->change();
            });
        }

        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('order_id');
            }

            if (! Schema::hasColumn('notifications', 'summary')) {
                $table->string('summary', 512)->nullable()->after('read');
            }

            if (! Schema::hasColumn('notifications', 'action_route')) {
                $table->string('action_route', 191)->nullable()->after('summary');
            }

            if (! Schema::hasColumn('notifications', 'action_params')) {
                $table->json('action_params')->nullable()->after('action_route');
            }
        });

        if ($driver === 'mysql' && ! $this->mysqlNotificationsOrderIdForeignExists()) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('notifications', 'seller_id')) {
            if ($driver === 'sqlite') {
                foreach (DB::table('notifications')->whereNotNull('order_id')->whereNull('seller_id')->cursor() as $row) {
                    $sid = DB::table('orders')->where('id', $row->order_id)->value('seller_id');
                    DB::table('notifications')->where('id', $row->id)->update(['seller_id' => $sid]);
                }
            } else {
                DB::statement('
                    UPDATE notifications AS n
                    INNER JOIN orders AS o ON o.id = n.order_id
                    SET n.seller_id = o.seller_id
                    WHERE n.order_id IS NOT NULL AND n.seller_id IS NULL
                ');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('notifications') || ! Schema::hasColumn('notifications', 'seller_id')) {
            return;
        }

        DB::table('notifications')->whereNull('order_id')->delete();

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $this->dropMysqlForeignKeysOnNotificationsOrderId();
            $this->makeMysqlNotificationsOrderIdNotNull();
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['seller_id', 'summary', 'action_route', 'action_params']);
        });

        if ($driver === 'mysql' && ! $this->mysqlNotificationsOrderIdForeignExists()) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        }
    }

    /**
     * Drop every FK on notifications.order_id (name differs across installs / manual DDL).
     */
    protected function dropMysqlForeignKeysOnNotificationsOrderId(): void
    {
        $schema = Schema::getConnection()->getDatabaseName();

        $rows = DB::select(
            'SELECT DISTINCT kcu.CONSTRAINT_NAME AS name
             FROM information_schema.KEY_COLUMN_USAGE kcu
             INNER JOIN information_schema.TABLE_CONSTRAINTS tc
                ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
               AND tc.TABLE_NAME = kcu.TABLE_NAME
               AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
               AND tc.CONSTRAINT_TYPE = ?
             WHERE kcu.TABLE_SCHEMA = ?
               AND kcu.TABLE_NAME = ?
               AND kcu.COLUMN_NAME = ?
               AND kcu.REFERENCED_TABLE_NAME IS NOT NULL',
            ['FOREIGN KEY', $schema, 'notifications', 'order_id']
        );

        foreach ($rows as $row) {
            $name = preg_replace('/[^a-zA-Z0-9_]/', '', $row->name ?? '');

            if ($name !== '') {
                DB::statement('ALTER TABLE `notifications` DROP FOREIGN KEY `'.$name.'`');
            }
        }
    }

    protected function mysqlNotificationsOrderIdForeignExists(): bool
    {
        $schema = Schema::getConnection()->getDatabaseName();

        $row = DB::selectOne(
            'SELECT COUNT(*) AS c
             FROM information_schema.KEY_COLUMN_USAGE kcu
             WHERE kcu.TABLE_SCHEMA = ?
               AND kcu.TABLE_NAME = ?
               AND kcu.COLUMN_NAME = ?
               AND kcu.REFERENCED_TABLE_NAME = ?',
            [$schema, 'notifications', 'order_id', 'orders']
        );

        return $row && (int) ($row->c ?? 0) > 0;
    }

    protected function makeMysqlNotificationsOrderIdNullable(): void
    {
        $schema = Schema::getConnection()->getDatabaseName();

        $col = DB::selectOne(
            'SELECT COLUMN_TYPE AS column_type, IS_NULLABLE AS is_nullable
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$schema, 'notifications', 'order_id']
        );

        if (! $col) {
            return;
        }

        if (($col->is_nullable ?? '') === 'YES') {
            return;
        }

        $type = $col->column_type ?? 'INT UNSIGNED';

        DB::statement('ALTER TABLE `notifications` MODIFY `order_id` '.$type.' NULL');
    }

    protected function makeMysqlNotificationsOrderIdNotNull(): void
    {
        $schema = Schema::getConnection()->getDatabaseName();

        $col = DB::selectOne(
            'SELECT COLUMN_TYPE AS column_type
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$schema, 'notifications', 'order_id']
        );

        $type = $col->column_type ?? 'INT UNSIGNED';

        DB::statement('ALTER TABLE `notifications` MODIFY `order_id` '.$type.' NOT NULL');
    }
};
