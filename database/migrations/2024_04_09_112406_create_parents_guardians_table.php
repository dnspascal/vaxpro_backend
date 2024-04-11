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
        Schema::create('parents_guardians', function (Blueprint $table) {
            //fore keys
            $table->string('nida_id');
            $table->string('name');
            $table->integer('contacts');
            $table->string('password');
            $table->string('address_district');
            $table->integer('address_name');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents_guardians');
    }
};
