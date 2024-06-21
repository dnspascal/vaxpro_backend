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
        Schema::create('child_vaccinations', function (Blueprint $table) {
            $table->id();
            $table->string('child_id'); // foreign key for child
            $table->unsignedBigInteger('vaccination_id'); // foregin key for vaccination
            $table->boolean('is_active')->default(true); // if false implies all the vaccination are administered...
            $table->foreign('child_id')->references('card_no')->on('children')->onDelete('cascade');
            $table->foreign('vaccination_id')->references('id')->on('vaccinations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_vaccinations');
    }
};
