<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringVendorRequest extends BaseFormRequest
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
        return "vendors";
    }

    public function rules()
    {
        return
            [
                'name' => 'required|max:150|unique:clients',
                'billing_address' => "required|max:255",
                'type' => 'required|in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
                "taxes_no" => "required_unless:type,NOT SPECIFIED|unique:clients",
                "registration_no" => "required_unless:type,NOT SPECIFIED|unique:clients",
                "country_id" => "required_if:type,==,INTERNATIONAL|exists:countries,id"
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Vendor's Name Has Not Been Sent !",
            "name.string" => "Vendor's Name Must Be String !",
            "max" => "Vendor's Name Must Not Be Greater THan 255 Character !",
            "name.unique" => "Vendor's Name  Is Already Stored In Our Database !"
        ];
        
    }
}
