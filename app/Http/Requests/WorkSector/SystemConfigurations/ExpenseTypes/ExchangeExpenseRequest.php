<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes;

use App\Http\Requests\BaseFormRequest;



class ExchangeExpenseRequest extends BaseFormRequest
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
                "exchange_date" => "required|date",
                'from_currency' => 'required|exists:currencies,id',
                'to_currency' => 'required|exists:currencies,id',
                "from_amount" => "required|numeric",
                "to_amount" => "required|numeric",
                "notes" => "nullable",
                "exchange_place" => "required",
                "payment_method_id" => "required|exists:payment_methods,id",
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                "exchange_date" => "date",
                'from_currency' => 'exists:currencies,id',
                'to_currency' => 'exists:currencies,id',
                "from_amount" => "numeric",
                "to_amount" => "numeric",
                "notes" => "nullable",
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
