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
        Schema::create('ward', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('ward_name');
            $table->timestamps();
        });

        Schema::create('district', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('district_name');
            $table->timestamps();
        });

        Schema::create('region', function (Blueprint $table) {
            $table->id();
            $table->string('region_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
