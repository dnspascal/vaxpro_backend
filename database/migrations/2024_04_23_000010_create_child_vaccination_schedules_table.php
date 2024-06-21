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
        Schema::create('child_vaccination_schedules', function (Blueprint $table) {
            //FKeys....
            $table->id();
            $table->unsignedBigInteger('child_vaccination_id');// foreign
            $table->string('child_id');
            $table->string('health_worker_id');//foreign
            $table->string('facility_id'); //foreign
            $table->string('frequency');
            $table->dateTime('vaccination_date')->nullable();
            $table->dateTime('next_vaccination_date')->nullable();
            $table->boolean('status');// if false after the particular day a message is sent to the child.
            $table->foreign('child_id')->references('card_no')->on('children')->onDelete('cascade');
            $table->foreign('child_vaccination_id')->references('id')->on('child_vaccinations')->onDelete('cascade');
            $table->foreign('health_worker_id')->references('staff_id')->on('health_workers')->onDelete('cascade');
            $table->foreign('facility_id')->references('facility_reg_no')->on('facilities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_vaccination_schedules');
    }
};
