<?php

namespace App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances;

use App\Http\Requests\BaseFormRequest;



class TaxExpenseRequest extends BaseFormRequest
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
                'notes' => 'nullable',
                'amount' => 'required|numeric',
                'paid_to' => 'required',
                'type' => 'required|in:IncomeTaxes,OtherTaxes',
                'currency_id' => 'required|exists:currencies,id',
                'payment_method_id' => 'required|exists:payment_terms,id',
                'years_list' => 'required_if:type,==,IncomeTaxes',
                'months_list' => 'required_if:type,==,OtherTaxes',
                'tax_percentage' => 'required',
                'tax_type_id' => 'required_if:type,==,OtherTaxes|exists:taxes_types,id',

            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'payment_date' => 'date',
                'notes' => 'nullable',
                'amount' => 'numeric',
                'paid_to' => 'required',
                'type' => 'in:IncomeTaxes,OtherTaxes',
                'currency_id' => 'exists:currencies,id',
                'payment_method_id' => 'exists:payment_terms,id',
                'tax_percentage' => 'required',
                'tax_type_id' => 'exists:taxes_types,id',

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
