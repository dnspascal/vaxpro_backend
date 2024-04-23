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
            $table->unsignedBigInteger('ward_id');
            $table->string('contacts');
            $table->integer('modified_by');
            $table->string('type');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
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
