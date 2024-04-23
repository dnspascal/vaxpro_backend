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
        Schema::create('health_workers', function (Blueprint $table) {
           
            $table->string('staff_id')->primary(); // primary key
            $table->string('name');
            $table->string('facility_id'); // foregin key of facility
            $table->string('contacts');
            $table->unsignedBigInteger('modified_by');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_workers');
    }
};
