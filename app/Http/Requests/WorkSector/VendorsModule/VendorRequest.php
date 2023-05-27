<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;

class VendorRequest extends BaseFormRequest
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
                'name' => 'required|max:150|unique:clients',
                'billing_address' => "required|max:255",
                'type' => 'required|in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
                "taxes_no" => "required_unless:type,NOT SPECIFIED|unique:clients",
                "registration_no" => "required_unless:type,NOT SPECIFIED|unique:clients",
                "country_id" => "required_if:type,==,INTERNATIONAL|exists:countries,id"
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'name' => 'max:150|unique:clients',
                'billing_address' => "max:255",
                'type' => 'in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
                "taxes_no" => "unique:clients",
                "registration_no" => "unique:clients",
                "country_id" => "exists:countries,id"
            ];
        }
    }

    public function passedValidation()
    {
        // $this->merge(
        //     [
        //       'registration_no_attachment' => isset($this->invoice_attachments)? json_encode($this->invoice_attachments):null,
        //       'taxes_no_attachment' => isset($this->taxes_no_attachment)? json_encode($this->taxes_no_attachment):null,
        //       'exemption_attachment' => isset($this->exemption_attachment)? json_encode($this->exemption_attachment):null,
        //       'sales_taxes_attachment' => isset($this->sales_taxes_attachment)? json_encode($this->sales_taxes_attachment):null,
        //     ]
        // );
    }
}
