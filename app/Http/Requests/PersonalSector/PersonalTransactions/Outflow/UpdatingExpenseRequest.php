<?php

namespace App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow;

use App\Http\Requests\BaseFormRequest;
use App\Models\PersonalSector\PersonalTransactions\OutFlow\Expense;

class UpdatingExpenseRequest extends BaseFormRequest
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

    protected function getModelName(): string
    {
        return Expense::class;
    }

    public function rules()
    {
        return
            [
                "category" => "in:assets,company_operation,client_po,marketing,client_visits_preorders,purchase_for_inventory",
                "asset_id" => "required_if:category,==,assets|exists:assets,id",
                "client_id" => "required_if:category,client_po,client_visits_preorders|exists:clients,id",
                "client_po_id" => "required_if:category,==,client_po|exists:client_orders,id",
                "expense_invoice" => "in:without_pi,with_pi",
                "payment_date" => "required",
                "amount" => "required",
                "paid_to" => "required_if:expense_invoice,==,without_pi",
                "expense_type_id" => "exists:expense_types,id",
                "currency_id" => "exists:currencies,id",
                "payment_method_id" => "exists:payment_methods,id",
                "notes" => "nullable",
        ];
    }

    
}
