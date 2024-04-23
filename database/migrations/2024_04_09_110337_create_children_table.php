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
        Schema::create('children', function (Blueprint $table) {
            //foreign keys.........
            $table->string('card_no')->primary();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('surname');
            $table->integer('parent_id');
            $table->integer('facility_id');
            $table->integer('ward_id');
            $table->integer('address_name');
            $table->string('house_no');
            $table->date('date_of_birth');
            $table->string('modified_by');
            
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('facility_id')->references('facility_reg_no')->on('facilities')->onDelete('cascade');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
            $table->foreign('modified_by')->references('staff_id')->on('health_workers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
