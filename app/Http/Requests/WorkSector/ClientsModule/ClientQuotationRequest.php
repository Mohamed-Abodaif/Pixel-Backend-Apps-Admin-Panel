<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;

class ClientQuotationRequest extends BaseFormRequest
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
        } elseif (request()->isMethod('PUT')) {
            return [
                'date' => 'date',
                'due_date' => 'date',
                'client_id' => 'exists:clients,id',
                'quotation_name' => 'unique:client_quotations',
                'quotation_net_value' => 'numeric|min:0|digits_between:1,15',
                'department_id' => 'exists:departments,id',
                'payments_terms_id' => 'exists:payment_terms,id',
                'currency_id' => 'exists:currencies,id',
                'notes' => 'nullable',

            ];
        }
    }


    public function passedValidation()
    {
        $this->merge(
            [
                'quotation_attachments' => isset($this->quotation_attachments) ? json_encode($this->quotation_attachments) : null
            ]
        );
    }
}
