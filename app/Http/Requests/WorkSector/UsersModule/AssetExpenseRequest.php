<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;



class AssetExpenseRequest extends BaseFormRequest
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
                'payment_date' => 'required|date',
                'attachments' => "nullable",
                'purchase_invoice_id' => 'required',
                'notes' => 'nullable',
                'amount' => 'required',
                'paid_to' => 'nullable',
                'type' => 'required',
                'asset_id' => 'required|exists:assets,id',
                'expense_type_id' => 'required|exists:expense_types,id',
                'currency_id' => 'required|exists:currencies,id',
                'payment_method_id' => 'required|exists:payment_terms,id',
                'category' => 'required'
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'payment_date' => 'date',
                'asset_id' => 'exists:assets,id',
                'expense_type_id' => 'exists:expense_types,id',
                'currency_id' => 'exists:currencies,id',
                'payment_method_id' => 'exists:payment_terms,id',
            ];
        }
    }
}
