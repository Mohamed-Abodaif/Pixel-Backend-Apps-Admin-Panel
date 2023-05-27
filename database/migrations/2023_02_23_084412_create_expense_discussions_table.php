<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("expense_id")->constrained("expenses")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("sender_id")->constrained("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("receiver_id")->constrained("users")->cascadeOnUpdate()->cascadeOnDelete();

            $table->mediumText('message');
            $table->json('attachment')->nullable();
            $table->tinyInteger('seen')->default(0);
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
        Schema::dropIfExists('expense_discussions');
    }
}
