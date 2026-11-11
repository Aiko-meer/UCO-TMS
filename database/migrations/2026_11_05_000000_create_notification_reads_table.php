<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-account read receipts for the notification bell.
 *
 * Notifications themselves are derived from the request tables, so nothing is
 * duplicated: a notification is unread for a user simply because there is no
 * row here for (user_id, notification_key). That makes read state strictly
 * per-account, which is the whole point - one user opening a request must not
 * clear it for anybody else.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notification_reads')) {
            return;
        }

        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('notification_key', 191);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'notification_key'], 'notification_reads_user_key_unique');
            $table->index('notification_key');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_reads');
    }
};
