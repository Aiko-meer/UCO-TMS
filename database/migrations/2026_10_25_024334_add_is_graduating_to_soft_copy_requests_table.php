<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soft_copy_requests', function (Blueprint $table) {
            // Check before adding so it doesn't crash if it already exists
            if (!Schema::hasColumn('soft_copy_requests', 'is_graduating')) {
                $table->string('is_graduating')->default('No')->after('number');
            }
            if (!Schema::hasColumn('soft_copy_requests', 'id_number')) {
                $table->string('id_number')->nullable()->after('is_graduating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('soft_copy_requests', function (Blueprint $table) {
            // Only drop the columns if they actually exist in the database
            $columnsToDrop = [];
            
            if (Schema::hasColumn('soft_copy_requests', 'is_graduating')) {
                $columnsToDrop[] = 'is_graduating';
            }
            if (Schema::hasColumn('soft_copy_requests', 'id_number')) {
                $columnsToDrop[] = 'id_number';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
