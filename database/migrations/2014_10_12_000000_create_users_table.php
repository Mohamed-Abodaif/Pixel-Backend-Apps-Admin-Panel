<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("name");
            $table->string('password');
            $table->string('mobile' , 20)->unique();
            $table->enum('user_type',['employee','signup'])->default('signup');
            $table->timestamp('email_verified_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->foreignId("department_id")->nullable()->constrained("departments")->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('employee_id')->nullable()->comment("EMP-auto_increment_id");
            $table->tinyInteger('status')->default(0);

            ///////
            $table->foreignId("role_id")->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("previous_role_id")->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('branch_id')->constrained('company_branches')->cascadeOnDelete();

            ///////

            $table->rememberToken();
            $table->string('verification_token')->nullable();
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
        Schema::dropIfExists('users');
    }
}
