<?php

namespace App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyTransOutflowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "transaction_type" => [
                'required',
                Rule::in(['from_bank_account', 'from_treasury']),
            ],
            "bank_account_id" => "nullable|integer|exists:company_bank_accounts,id",
            "treasury_id" => "nullable|integer|exists:company_treasuries,id",
            "transaction_purpose" => "required|string|max:255",
            "date" => "required|date",
            "amount" => "required|integer",
            "currency_id" => "nullable|integer|exists:currencies,id",
            // "receiver" => "required|string|max:255",
            "expense_type_id" => "nullable|integer|exists:expense_types,id",
            "employee_id" => "nullable|integer|exists:users,id",
            "asset_id" => "nullable|integer|exists:assets,id",
            "expense_invoice" => "nullable|string|in:without_tax_invoice,with_tax_invoice,with_offical_reciept",
            "client_id" => "nullable|integer|exists:clients,id",
            "vendor_id" => "nullable|integer|exists:vendors,id",
            "client_order_id" => "nullable|integer|exists:client_orders,id",
            "paid_to" => "nullable|string|max:255",
            "purchase_invoice_id" => "nullable|integer|exists:purchase_invoices,id",
            "reciept_number_id" => "nullable|integer|exists:purchase_invoices,id",
            "owner_operation" => "nullable|string|in:owner_shareholder_profit,owner_company_loan_repayment,buying_owner_percentage",
            // "loan_id" => "nullable|integer|exists:purchase_invoices,id",
            "shareholder_percentage" => "nullable|numeric",
            "withdrawal_method" => [
                'required',
                Rule::in(['cash_at_bank', 'bank_cheque', 'via_atm', 'bank_transfer', 'via_card_online/offline']),
            ],
            "insurance_duration" => "nullable|string",
            "insurance_refrence_number" => "nullable|string",
            "trans_reference" => "nullable|string",
            "cheque_number" => "nullable|string",
            "card_number" => "nullable|string|max:20",
            "months_list" => "nullable|string",
            "insurance_type" => [
                'nullable',
                Rule::in(['social_insurance', 'medical_insurance', 'tender_insurance', 'asset_insurance', 'other_insurance']),
            ],
            "tender_type" => "nullable|string|in:InitialInsurance,FinalInsurance",
            "tender_percentage" => "nullable|integer",
            "tender_date" => "nullable|date",
            "purchase_request_id" => "nullable|integer|exists:purchase_requests,id",
            "attachments" => "nullable|array",
            "notes" => "nullable|string|max:2000",
        ];
    }

    function messages()
    {
        return [
            "transaction_type.in" => 'It must be either a [bank account] or a [treasury]'
        ];
    }
}
