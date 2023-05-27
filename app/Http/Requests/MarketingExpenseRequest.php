<?php

namespace App\Http\Requests;



class MarketingExpenseRequest extends BaseFormRequest
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
            'payment_date' => 'required',
            'purchase_invoice_id' => 'required',
            'amount' => 'required',
            'paid_to' => 'nullable',
            'type' => 'required',
            'expense_type_id' => 'required',
            'currency_id' => 'required',
            'payment_method_id' => 'required',
            'notes' => "nullable",
            'category' => 'required'
        ];
    }

    public function passedValidation()
    {
        $this->merge(
            [
                'attachments' => isset($this->attachments) ? json_encode($this->attachments) : null
            ]
        );
    }
}
