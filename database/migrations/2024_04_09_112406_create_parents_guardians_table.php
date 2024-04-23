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
        Schema::create('parents_guardians', function (Blueprint $table) {
            //fore keys
            $table->string('nida_id')->primary();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->integer('contacts');
            $table->string('password');
            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('modified_by');
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
        Schema::dropIfExists('parents_guardians');
    }
};
