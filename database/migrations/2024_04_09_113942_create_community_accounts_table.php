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
        //FKeys....
        Schema::create('community_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('address_district');
            $table->integer('address_name');
            $table->string('contacts');
            $table->integer('modified_by');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_accounts');
    }
};
