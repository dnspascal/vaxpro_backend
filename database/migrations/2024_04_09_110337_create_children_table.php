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
        Schema::create('children', function (Blueprint $table) {
            //foreign keys.........
            $table->string('card_no');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('surname');
            $table->integer('parent_id');
            $table->integer('facility_id');
            $table->integer('address_district');
            $table->int('address_name');
            $table->string('house_no');
            $table->date('date_of_birth');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
