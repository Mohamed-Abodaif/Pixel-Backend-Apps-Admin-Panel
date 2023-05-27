<?php

namespace App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow;

use App\Http\Requests\BaseFormRequest;


class StoringExpenseRequest extends BaseFormRequest
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


    protected function getTableName(): string
    {
        return "expenses";
    }

    public function rules()
    {
        return
            [
                "category" => "required|in:assets,company_operation,client_po,marketing,client_visits_preorders,purchase_for_inventory",
                "asset_id" => "required_if:category,==,assets|exists:assets,id",
                "client_id" => "required_if:category,client_po,client_visits_preorders|exists:clients,id",
                "client_po_id" => "required_if:category,==,client_po|exists:client_orders,id",
                "expense_invoice" => "required|in:without_pi,with_pi",
                "payment_date" => "required",
                "amount" => "required",
                "paid_to" => "required_if:expense_invoice,==,without_pi",
                "expense_type_id" => "required|exists:expense_types,id",
                "currency_id" => "required|exists:currencies,id",
                "payment_method_id" => "required|exists:payment_methods,id",
                "notes" => "nullable",
        ];
    }

    public function messages()
    {
        return [
            "category.required" => "Expense category's  Has Not Been Sent !",
            "asset_id.required" => "Expense asset_id from  Has Not Been Sent  !",
            "client_id" => "Expense client_id  Has Not Been Sent !",
        ];
        
    }
}
