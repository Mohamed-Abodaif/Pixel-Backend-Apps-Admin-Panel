<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoicesTable extends Migration
{
    const INVOICE_STATUS = ['draft','approved internally','sent' , 'approved in portal' , 'due' , 'overdue' , 'cancelled' , 'closed'];
    const PAYMENT_STATUS = ['not paid','partially paid','paid','refunded'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('payment_date');
            $table->string('electronic_sales_invoice_number')->unique()->nullable();
            $table->string('sales_invoice_number')->unique();
            $table->string('sales_invoice_name')->unique();

            $table->foreignId("client_id")->constrained("clients")->cascadeOnUpdate();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();



            $table->double('invoice_value_without_taxes');
            $table->double('invoice_sales_taxes_value');
            $table->double('invoice_add_and_discount_taxes_value');
            $table->double('invoice_net_value')->default(0);
            $table->double('paid_value')->default(0);
            $table->double('rest_value')->default(0);
            $table->json('invoice_attachments')->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('invoice_status',static::INVOICE_STATUS)->default('Draft');
            $table->enum('payment_status', static::PAYMENT_STATUS)->default('not paid');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
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
        Schema::dropIfExists('salse_inviocies');
    }
}
