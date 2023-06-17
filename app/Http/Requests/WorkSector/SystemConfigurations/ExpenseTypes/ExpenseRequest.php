<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes;

use App\Http\Requests\BaseFormRequest;


class ExpenseRequest extends BaseFormRequest
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


        if (request()->isMethod('post')) {
            return [
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
        } elseif (request()->isMethod('PUT')) {
            return [
                "category" => "in:assets,company_operation,client_po,marketing,client_visits_preorders,purchase_for_inventory",
                "asset_id" => "exists:assets,id",
                "client_id" => "exists:clients,id",
                "client_po_id" => "exists:client_orders,id",
                "expense_invoice" => "in:without_pi,with_pi",
                "expense_type_id" => "exists:expense_types,id",
                "currency_id" => "exists:currencies,id",
                "payment_method_id" => "exists:payment_methods,id",

            ];
        }
    }

    public function passedValidation()
    {
        // $this->merge(
        //     [
        //         'attachments' => isset($this->attachments)? json_encode($this->attachments):null
        //     ]
        // );
    }
}
