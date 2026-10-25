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
        Schema::create('trademark_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('salutation');
            $table->string('f_name');
            $table->string('middle_name')->nullable();
            $table->string('l_name');
            $table->string('category_requestor');
            $table->string('position');
            $table->string('department_org');
            $table->string('contact_number');
            $table->string('email');
            $table->string('office_address');
            $table->string('trademark_to_use');
            $table->date('date_to_use');
            $table->string('category_trademark_use');
            $table->string('target_audience');
            $table->string('materials_media');
            $table->string('location_use');
            $table->text('purpose_of_use');
            $table->text('produce');
            $table->text('token')->nullable();
            $table->text('note')->nullable();
            $table->string('approved_by')->nullable();
            $table->integer('status')->default(0); // 0: Pending, 1: Approved, 2: Rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trademark_requests');
    }
};
