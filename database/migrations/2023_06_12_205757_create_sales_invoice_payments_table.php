<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->double('value');
            $table->double('amount_before')->default(0);

            $table->foreignId("sales_invoice_id")->constrained("sales_invoices")->cascadeOnUpdate();
            $table->foreignId("payment_method_id")->constrained("payment_methods")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();

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
        Schema::dropIfExists('sales_invoice_payments');
    }
}
