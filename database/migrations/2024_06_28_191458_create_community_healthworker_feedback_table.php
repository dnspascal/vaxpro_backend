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
        Schema::create('community_healthworker_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('child_id');
            $table->string('facility_id');
            $table->longText('reason_for_absence');
            $table->foreign('child_id')->references('card_no')->on('children')->onDelete('cascade');
            $table->foreign('facility_id')->references('facility_reg_no')->on('facilities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_healthworker_feedback');
    }
};
