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
        Schema::create('requestors', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('email');
            $table->string('department');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('middle')->nullable();
            $table->integer('token')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requestors');
    }
};
