<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate();
            $table->string('payment_date');
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->double('amount');
            $table->string('paid_to')->nullable();
            $table->string('years_list')->nullable();
            $table->string('months_list')->nullable();
            $table->string('tax_percentage');

            $table->enum('type',['IncomeTaxes','OtherTaxes'])->default('IncomeTaxes');

             $table->unsignedBigInteger('tax_type_id')->nullable();
             $table->foreign('tax_type_id')->references('id')->on('taxes_types')->onUpdate('cascade');

            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
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
        Schema::dropIfExists('tax_expenses');
    }
}
