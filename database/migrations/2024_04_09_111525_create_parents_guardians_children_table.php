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
            $table->integer("parents_guardians_id");
            $table->integer("child_id");
            $table->string("relationship_with_child");
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
