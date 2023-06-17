<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date');
            $table->foreignId("client_id")->constrained("clients")->cascadeOnUpdate();

            $table->string('quotation_number')->nullable()->unique();
            $table->string('quotation_name')->nullable()->unique();

            $table->foreignId("department_id")->constrained("departments")->cascadeOnUpdate();
            $table->foreignId("payments_terms_id")->constrained("payment_terms")->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnUpdate();

            $table->double('quotation_net_value');
            $table->json("quotation_attachments")->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('status',['Draft','Sent','Get PO','No PO'])->default('Draft');
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
        Schema::dropIfExists('client_quotations');
    }
}
