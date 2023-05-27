<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;


class UpdatingClientQuotationRequest extends BaseFormRequest
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
                'date' => 'date',
                'due_date' => 'date',
                'client_id' => 'exists:clients,id',
                'quotation_name' =>  Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
                'quotation_net_value' => 'numeric|min:0|digits_between:1,15',
                'department_id' => 'exists:departments,id',
                'payments_terms_id' => 'exists:payment_terms,id',
                'currency_id' => 'exists:currencies,id',
                'notes' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            "date.date" => "Quotation date's  Must be a valid date !",
            "due_date.date" => "Quotation Due Date's  Must be a valid date!",
            "quotation_name.unique" => "Quotation Name Is Already Stored In Our Database !"
        ];
        
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }
}
