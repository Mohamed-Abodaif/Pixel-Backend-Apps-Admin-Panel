<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditRequestsExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_requests_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->foreign('expense_id')->references('id')->on('expenses')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->mediumText('required_edit');
            $table->dateTime('requested_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->enum('status',['Pending','Done'])->default('Pending');
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
        Schema::dropIfExists('edit_requests_expenses');
    }
}
