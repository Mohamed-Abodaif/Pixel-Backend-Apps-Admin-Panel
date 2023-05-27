<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate();
            $table->string('exchange_date');
            $table->foreignId("from_currency")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("to_currency")->constrained("currencies")->cascadeOnUpdate();
            $table->double('from_amount');
            $table->double('to_amount');
            $table->json('attachments')->nullable();
            $table->mediumText('notes')->nullable();
            $table->string('exchange_rate');
            $table->string('exchange_place');
            $table->foreignId("payment_method_id")->constrained("payment_methods")->cascadeOnUpdate();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('exchange_expenses');
    }
}
