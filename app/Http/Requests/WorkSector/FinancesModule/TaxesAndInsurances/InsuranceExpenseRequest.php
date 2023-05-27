<?php

namespace App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances;

use App\Http\Requests\BaseFormRequest;



class InsuranceExpenseRequest extends BaseFormRequest
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
                'type' => 'required|in:SocialInsurance,MedicalInsurance,AssetInsurance,TenderInsurance,OtherInsurance',
                'payment_date' => 'required|date',
                'amount' => 'required',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'currency_id' => 'required|exists:currencies,id',
                'client_id' => 'required_if:type,TenderInsurance|exists:payment_methods,id',
                'asset_id' => 'required_if:type,AssetInsurance|exists:assets,id',
                'tender_id' => 'required_if:type,TenderInsurance|exists:tenders,id',
                'purchase_invoice_id' => 'required_if:type,MedicalInsurance,AssetInsurance,OtherInsurance|exists:purchase_invoices,id',
                'paid_to' => 'required_if:type,SocialInsurance,TenderInsurance',
                'insurance_duration' => 'required_if:type,MedicalInsurance,AssetInsurance,OtherInsurance',
                'months_list' => 'required_if:type,==,SocialInsurance',
                'insurance_refrence_number' => 'required|unique:insurance_expenses',
                'tender_insurance_percentage' => 'required_if:type,==,TenderInsurance',
                'tender_estimated_date' => 'required_if:type,==,TenderInsurance|date',
                'final_refund_date' => 'required_if:type,==,TenderInsurance|date',
                'tender_insurance_type' => 'required_if:type,==,TenderInsurance|in:InitialInsurance,FinalInsurance',
                'percentage_from_tender_insurance' => 'required_if:type,==,TenderInsurance|numeric',
                'notes' => 'nullable',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'type' => 'in:SocialInsurance,MedicalInsurance,AssetInsurance,TenderInsurance,OtherInsurance',
                'payment_date' => 'date',
                'payment_method_id' => 'exists:payment_methods,id',
                'currency_id' => 'exists:currencies,id',
                'client_id' => 'exists:payment_methods,id',
                'asset_id' => 'exists:assets,id',
                'tender_id' => 'exists:tenders,id',
                'purchase_invoice_id' => 'exists:purchase_invoices,id',
                'months_list' => 'required_if:type,==,SocialInsurance',
                'insurance_refrence_number' => 'unique:insurance_expenses',
                'final_refund_date' => 'date',
                'tender_insurance_type' => 'in:InitialInsurance,FinalInsurance',
                'percentage_from_tender_insurance' => 'numeric',
            ];
        }
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
