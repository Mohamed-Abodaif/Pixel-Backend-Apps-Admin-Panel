<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->default('male');

            $table->string('avatar')->nullable();

            $table->string('national_id_number')->nullable()->unique();
            $table->json('national_id_files')->nullable();

            $table->string('passport_number')->nullable()->unique();
            $table->json('passport_files')->nullable();

            $table->foreignId("country_id")->constrained("countries")->cascadeOnUpdate();
            $table->foreignId("user_id")->unique()->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
