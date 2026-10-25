<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('soft_copy_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('middle_name')->nullable();
            $table->string('email');
            $table->string('year_level');
            $table->string('grad')->nullable();
            $table->string('school_level');
            $table->string('program');
            $table->string('number');
            $table->date('date_photo');
            $table->json('id_attachment')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_copy_requests');
    }
};
