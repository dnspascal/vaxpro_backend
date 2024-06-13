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
        Schema::create('mid_entry_children', function (Blueprint $table) {
            $table->id();
            $table->string('child_id');
            $table->date('entry_date');
            $table->foreign('child_id')->references('card_no')->on('children')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mid_entry_children');
    }
};
