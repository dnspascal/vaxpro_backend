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
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("role");
            $table->string("password");
            $table->string("region"); //foreign key gotta be here
            $table->string("district"); // foreign key gotta be here
            $table->integer("facility"); // foreign key gotta be here
            $table->string("contacts");
            $table->string("account_type");
            $table->integer("modified_by"); // foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
