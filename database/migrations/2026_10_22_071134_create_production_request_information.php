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
        Schema::create('production_request_information', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('size');
            $table->string('quantity')->nullable();
            $table->string('type_material');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_request_information');
    }
};
