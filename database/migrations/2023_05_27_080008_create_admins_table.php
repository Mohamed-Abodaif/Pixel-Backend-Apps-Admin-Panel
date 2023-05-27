<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string("name");
            $table->string('password');
            $table->string('mobile', 20)->unique();
            $table->tinyInteger('is_active')->default(1);
            $table->foreignId("role_id")->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('avatar')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
