<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate();
            $table->date('payment_date');
            $table->json('attachments')->nullable();
            $table->mediumText('notes')->nullable();
            $table->double('amount');
            $table->string('paid_to')->nullable();

            $table->enum('category',[
                'assets',
                'company_operation',
                'client_po',
                'marketing',
                'client_visits_preorders',
                'purchase_for_inventory'

            ]);


            $table->foreignId("asset_id")->nullable()->constrained("assets")->cascadeOnUpdate();
            $table->foreignId("client_id")->nullable()->constrained("clients")->cascadeOnUpdate();
            $table->foreignId("client_po_id")->nullable()->constrained("client_orders")->cascadeOnUpdate();
            $table->foreignId("purchase_invoice_id")->nullable()->constrained("purchase_invoices")->cascadeOnUpdate();

            $table->foreignId("expense_type_id")->constrained("expense_types")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();
            $table->foreignId("payment_method_id")->constrained("payment_methods")->cascadeOnUpdate();


            // $table->unsignedBigInteger('tender_id');
            // $table->foreign('tender_id')->references('id')->on('tenders')->onUpdate('cascade');

            $table->enum('status',['Draft' , 'Pending', 'Edit Requested' , 'Pending After Edit' ,'Approved','Rejected'])->default('Pending');
            $table->enum('expense_invoice',['without_pi','with_pi']);


            $table->timestamp('accepted_at')->nullable();
            $table->foreignId("accepted_by")->constrained("users")->cascadeOnUpdate();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId("rejected_by")->constrained("users")->cascadeOnUpdate();
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
        Schema::dropIfExists('expenses');
    }
}
