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
            
            $table->string('facility_reg_no');
            $table->string('facility_name');
            $table->string('contacts');
            $table->string('address'); //foreign key for ward table
            $table->string('password');
            $table->string('modified_by'); // foreign key for user account
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
