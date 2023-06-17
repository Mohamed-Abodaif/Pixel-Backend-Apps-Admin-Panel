<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComapnyTransInflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comapny_trans_inflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('added by user');
            $table->enum('transaction_type', ['to_bank_account', 'to_treasury']);
            $table->foreignId('bank_account_id')->nullable()->constrained('company_bank_accounts')->cascadeOnDelete();
            $table->foreignId('treasury_id')->nullable()->constrained('company_treasuries')->cascadeOnDelete();
            $table->enum('deposit_type', ['client_deposit', 'treasury_deposit', 'investment_deposit', 'loan_deposit', 'deposit_certificate', 'employee_deposit']);
            $table->date('date');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('investment_id')->nullable(); //
            $table->unsignedInteger('certificate_id')->nullable(); //
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            // $table->string('receiver');
            // $table->foreignId('expense_type_id')->nullable()->constrained('expense_types')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('users')->cascadeOnDelete();
            // $table->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnDelete()->nullable();
            // $table->enum('expense_invoice', ['without_tax_invoice', 'with_tax_invoice', 'with_offical_reciept']);
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            // $table->foreignId('vendor_id')->nullable()->constrained('vendors')->cascadeOnDelete(); //Offical Reciept Issuer
            // $table->foreignId('client_order_id')->nullable()->constrained('client_orders')->cascadeOnDelete();
            $table->string('paid_to')->nullable();
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->nullOnDelete();
            // $table->foreignId('reciept_number_id')->nullable()->constrained('offical_reciept_issuers')->cascadeOnDelete();
            // $table->string('reciept_number_id')->nullable();
            // $table->enum('owner_operation', ['owner_shareholder_profit', 'owner_company_loan_repayment', 'buying_owner_percentage'])->nullable();
            $table->foreignId('loan_id')->nullable()->constrained('vendors')->nullOnDelete(); //
            // $table->decimal('shareholder_percentage')->nullable(); //Offical Reciept Issuer
            $table->enum('deposit_method', ['cash_at_bank', 'bank_cheque', 'via_atm', 'bank_transfer', 'via_card_online/offline']);
            $table->string('trans_reference')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('card_number')->nullable();
            // $table->string('insurance_duration')->nullable();
            // $table->string('insurance_refrence_number')->nullable();
            // $table->enum('insurance_type', ['social_insurance', 'medical_insurance', 'tender_insurance', 'asset_insurance', 'other_insurance'])->nullable();
            $table->string('months_list')->nullable();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            // $table->enum('tender_type', ['InitialInsurance', 'FinalInsurance'])->nullable();
            $table->string('invoice_payment_percentage')->nullable();
            $table->json('attachments')->nullable();
            $table->string('notes', 2000)->nullable();
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
        Schema::dropIfExists('comapnt_trans_inflows');
    }
}
