<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\WorkSector\SystemConfigurationsRequests\StoringRequest;


class StoringClientRequest extends BaseFormRequest
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
        return "clients";
    }

    public function rules()
    {
        return
            [
            'name' => 'max:150|unique:clients',
            'billing_address' => "max:255",
            'type' => 'in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
            "taxes_no" => "unique:clients",
            "registration_no" => "unique:clients",
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Client's Name Has Not Been Sent !",
            "name.string" => "Client's Name Must Be String !",
            "max" => "Client's Name Must Not Be Greater THan 255 Character !",
            "name.unique" => "Client's Name  Is Already Stored In Our Database !"
        ];
        
    }
}
