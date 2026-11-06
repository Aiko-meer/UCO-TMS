<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Try dropping the constraint by name first
        try {
            DB::statement('ALTER TABLE soft_copy_requests DROP CHECK `soft_copy_requests.id_attachment`');
        } catch (\Exception $e) {
            // Ignore if name differs
        }

        // 2. Modify the column definition directly. 
        // In MySQL, modifying the column type forces it to clear out any hidden check constraints.
        Schema::table('soft_copy_requests', function (Blueprint $table) {
            $table->string('id_attachment')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Nothing needed here
    }
};