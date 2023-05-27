<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('payment_date');
            $table->string('vendor_purchase_invoice_number')->unique();
            $table->string('purchase_invoice_name')->unique();
            $table->string('electronic_purchase_invoice_number')->unique()->nullable();

            $table->foreignId("vendor_id")->constrained("vendors")->cascadeOnUpdate();
            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();

            $table->double('invoice_value_without_taxes');
            $table->double('invoice_sales_taxes_value');
            $table->double('invoice_add_and_discount_taxes_value');
            $table->double('invoice_net_value');
            $table->json('invoice_attachments')->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('status',['Draft','Sent','Paid','Partially Paid'])->default('Draft');
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
        Schema::dropIfExists('purchase_invoices');
    }
}
