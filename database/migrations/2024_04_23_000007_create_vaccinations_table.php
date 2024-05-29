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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("frequency");
            $table->string("abbrev");
            $table->integer("first_dose_after");
            $table->integer("second_dose_after")->nullable();
            $table->integer("third_dose_after")->nullable();
            $table->integer("fourth_dose_after")->nullable();
            $table->integer("fifth_dose_after")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
