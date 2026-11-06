<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Move the read flag off the shared requestor row and onto each request table.
 *
 * `requestors` is the common parent for every request type, so keeping a single
 * `token` there meant one flag shared by *all* request types and *all* accounts.
 * The flag now lives beside the request it describes.
 *
 * Note: this is a schema correction, not the per-account fix. A column on a
 * request row is still one value shared by every account; per-account read
 * state lives in `notification_reads` (user_id + notification_key).
 */
return new class extends Migration
{
    /**
     * Child tables that own the flag from now on, and the column that links
     * them back to `requestors`.
     */
    private array $children = [
        'socmed_request_informations',
        'publishing_request_informaitons',
        'photo_vid_request_informations',
    ];

    public function up(): void
    {
        // 1. Make sure every request table has the column.
        foreach ($this->children as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'token')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->integer('token')->default(0);
                });
            }
        }

        // 2. Carry existing state across before the source column disappears,
        //    so nothing that was already read turns unread.
        if (Schema::hasTable('requestors') && Schema::hasColumn('requestors', 'token')) {
            foreach ($this->children as $table) {
                if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'token')) {
                    continue;
                }

                DB::table($table)->update([
                    'token' => DB::raw(
                        'COALESCE((SELECT r.token FROM requestors r WHERE r.request_id = '.$table.'.request_id LIMIT 1), 0)'
                    ),
                ]);
            }

            // 3. Drop the duplicate from the shared parent table.
            Schema::table('requestors', function (Blueprint $table) {
                $table->dropColumn('token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('requestors') && ! Schema::hasColumn('requestors', 'token')) {
            Schema::table('requestors', function (Blueprint $table) {
                $table->integer('token')->default(0);
            });
        }
    }
};
