<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;
use App\Models\WorkSector\VendorsModule\Vendor;
use Illuminate\Validation\Rule;

class UpdatingVendorRequest extends BaseFormRequest
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

    protected function getModelName(): string
    {
        return Vendor::class;
    }

    public function rules()
    {
        return
            [

                
            "name" => ["bail", "nullable", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue())],
            'billing_address' => "max:255",
            'type' => 'required|in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
            "taxes_no" =>Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
            "registration_no" => Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
            "country_id" => "required_if:type,==,INTERNATIONAL|exists:countries,id"

        ];
    }

    public function messages()
    {
        return [
            "name.string" => "Vendor's Name Must Be String !",
            "billing_address.max" => "Vendor's Name Must Not Be Greater THan 255 Character !",
            "name.unique" => "Vendor's Name  Is Already Stored In Our Database !"
        ];
        
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

}
