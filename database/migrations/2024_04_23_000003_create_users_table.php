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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("role_id")->nullable(); // IT admin,ceo,cvo,healthWorker....
            $table->string("password");
            $table->unsignedBigInteger("ward_id")->nullable(); //foreign key gotta be here
            $table->unsignedBigInteger("district_id")->nullable(); //foreign key gotta be here
            $table->unsignedBigInteger("region_id")->nullable(); //foreign key gotta be here
            $table->string("facility_id")->nullable(); // foreign key gotta be here
            $table->string("contacts");
            // $table->integer("modified_by"); // foreign key
            $table->foreign("role_id")->references("id")->on("roles")->onDelete("cascade");
            $table->foreign("ward_id")->references("id")->on("wards");
            $table->foreign("region_id")->references("id")->on("regions"); //region accounts
            $table->foreign("district_id")->references("id")->on("districts");// district accounts
            $table->foreign("facility_id")->references("facility_reg_no")->on("facilities");
            $table->timestamps();

            //region,district
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
