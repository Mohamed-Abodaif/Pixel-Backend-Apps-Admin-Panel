<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTimesheets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_timesheets', function (Blueprint $table) {
            $table->id();

            $table->foreignId("client_id")->nullable()->constrained("clients")->cascadeOnUpdate();
            $table->foreignId("client_po_id")->nullable()->constrained("client_orders")->cascadeOnUpdate();
            $table->foreignId("timesheet_sc_id")->constrained("employees_timesheet_sub_categories")->cascadeOnUpdate();

            $table->date('start_date');
            $table->date('end_date');
            $table->string('start_time');
            $table->string('end_time');

            $table->foreignId("country_id")->nullable()->constrained("countries")->cascadeOnUpdate();
            $table->foreignId("vendor_id")->nullable()->constrained("vendors")->cascadeOnUpdate();
            $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate();
            $table->foreignId("vendor_po_id")->nullable()->constrained("vendor_orders")->cascadeOnUpdate();

            $table->mediumText('description')->nullable();
            $table->mediumText('location')->nullable();
            $table->tinyInteger('night_shift')->default(0);
            $table->enum('status',['Draft','Pending','Edit Requested','Pending After Edit','Approved','Rejected'])->default('Pending');
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
        Schema::dropIfExists('employees_timesheets');
    }
}
