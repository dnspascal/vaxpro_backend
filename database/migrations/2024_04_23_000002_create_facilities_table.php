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
        Schema::create('facilities', function (Blueprint $table) {
            
            $table->string('facility_reg_no')->primary();//reg no
            $table->string('facility_name');
            $table->string('contacts');
            $table->unsignedBigInteger('ward_id'); //foreign key for ward table
            $table->unsignedBigInteger('modified_by'); // foreign key for user account
            $table->foreign('ward_id')->references('id')->on('wards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
