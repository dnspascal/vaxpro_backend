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
            $table->integer('child_vaccination_id');
            $table->integer('health_worker_id');
            $table->integer('facility_id');
            $table->string('frequency');
            $table->dateTime('vaccination_date');
            $table->dateTime('next_vaccination_date');
            $table->boolean('status');
            $table->integer('modified_by');
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
