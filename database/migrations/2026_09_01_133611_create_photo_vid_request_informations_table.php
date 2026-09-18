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
        Schema::create('photo_vid_request_informations', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->date('date_needed');
            $table->string('category');
            $table->string('section');
            $table->string('approve');
            $table->string('produce')->nullable();
            $table->date('published')->nullable();
            $table->integer('status');
            $table->string('venue');
            $table->string('event');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('purpose_document');
            $table->string('equipment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_vid_request_informations');
    }
};
