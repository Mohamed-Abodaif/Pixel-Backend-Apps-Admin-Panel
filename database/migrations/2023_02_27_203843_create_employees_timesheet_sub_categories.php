<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTimesheetSubCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_timesheet_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId("timesheet_category_id")->constrained("employees_timesheet_categories")->cascadeOnUpdate();
            $table->tinyInteger('status')->default(1);
            $table->json('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_timesheet_sub_categories');
    }
}
