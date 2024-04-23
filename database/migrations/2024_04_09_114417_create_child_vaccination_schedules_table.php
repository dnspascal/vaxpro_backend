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
            $table->unsignedBigInteger('health_worker_id');//foreign
            $table->unsignedBigInteger('facility_id'); //foreign
            $table->string('frequency');
            $table->dateTime('vaccination_date');
            $table->dateTime('next_vaccination_date');
            $table->boolean('status');// if false after the particular day a message is sent to the child.
            $table->foreign('child_vaccination_id')->references('id')->on('child_vaccinations')->onDelete('cascade');
            $table->foreign('health_worker_id')->references('id')->on('health_workers')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
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
