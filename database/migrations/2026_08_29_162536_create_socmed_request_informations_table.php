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
        Schema::create('socmed_request_informations', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('purpose');
            $table->date('date_needed');
            $table->string('category');
            $table->string('specification');
            $table->string('content_information')->nullable;
            $table->string('section');
            $table->string('approve');
            $table->string('produce')->nullable();
            $table->date('published')->nullable();
            $table->integer('status');
            $table->json('content_attachement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socmed_request_informations');
    }
};
