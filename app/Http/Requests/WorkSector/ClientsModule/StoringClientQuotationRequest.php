<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;


class StoringClientQuotationRequest extends BaseFormRequest
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
        return "client_quotations";
    }

    public function rules()
    {
        return
            [
                'date' => 'required|date',
                'due_date' => 'required|date',
                'client_id' => 'required|exists:clients,id',
                'quotation_name' => 'required|unique:client_quotations',
                'quotation_net_value' => 'required|numeric|min:0|digits_between:1,15',
                'department_id' => 'required|exists:departments,id',
                'payments_terms_id' => 'required|exists:payment_terms,id',
                'currency_id' => 'required|exists:currencies,id',
                'notes' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            "date.required" => "Quotation date's  Has Not Been Sent !",
            "due_date.required" => "Quotation Due Date's  Has Not Been Sent  !",
            "quotation_name" => "Quotation 's Name Has Not Been Sent !",
            "quotation_name.unique" => "Quotation Name Is Already Stored In Our Database !"
        ];
        
    }
}
