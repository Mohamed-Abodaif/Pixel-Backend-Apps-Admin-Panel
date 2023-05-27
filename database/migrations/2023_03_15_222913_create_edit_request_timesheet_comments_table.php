<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditRequestTimesheetCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_request_timesheet_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('edit_request_id');
            $table->foreign('edit_request_id')->references('id')->on('edit_requests_timesheets')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->mediumText('comment');
            $table->dateTime('added_at')->nullable();
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
        Schema::dropIfExists('edit_request_timesheet_comments');
    }
}
