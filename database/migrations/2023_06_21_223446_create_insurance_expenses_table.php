<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

    class CreateInsuranceExpensesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('insurance_expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate();
                $table->date('payment_date')->nullable();
                $table->double('amount')->nullable();
                $table->string('paid_to')->nullable();
                $table->json('attachments')->nullable();
                $table->text('notes')->nullable();
                $table->json('insurance_duration')->nullable();
                $table->json('months_list')->nullable();
                $table->string('insurance_reference_number')->nullable();
                $table->string('tender_insurance_percentage')->nullable();
                $table->date('tender_estimated_date')->nullable();
                $table->date('final_refund_date')->nullable();

                $table->enum('type',['SocialInsurance','MedicalInsurance','AssetInsurance','TenderInsurance','OtherInsurance']);
                $table->enum('tender_insurance_type',['InitialInsurance','FinalInsurance'])->nullable();

                $table->foreignId("client_id")->nullable()->constrained("clients")->cascadeOnUpdate();
                $table->foreignId("asset_id")->nullable()->constrained("assets")->cascadeOnUpdate();
                $table->foreignId("tender_id")->nullable()->constrained("tenders")->cascadeOnUpdate();
                $table->foreignId("currency_id")->nullable()->constrained("currencies")->cascadeOnUpdate();
                $table->foreignId("payment_method_id")->constrained("payment_methods")->cascadeOnUpdate();
                $table->foreignId("purchase_invoice_id")->nullable()->constrained("purchase_invoices")->cascadeOnUpdate();

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
            Schema::dropIfExists('insurance_expenses');
        }
    }

