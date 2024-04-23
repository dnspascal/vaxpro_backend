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
        Schema::create('parents_guardians_children', function (Blueprint $table) {
            $table->id();
            $table->string("parents_guardians_id");//relationship
            $table->integer("child_id");//child
            $table->string("relationship_with_child"); 
            $table->foreign('parents_guardians_id')->references('nida_id')->on('parents_guardians')->onDelete('cascade');
            $table->foreign('child_id')->references('card_no')->on('children')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents_guardians_children');
    }
};
