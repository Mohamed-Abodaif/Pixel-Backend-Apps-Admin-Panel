<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;
use Illuminate\Validation\Rule;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /**Client Main Props  - start of part*/
            'name' => 'required|max:255' . (request()->input('client_type') === 'individual' ? '' : '|unique:clients,name'),
            'client_type' => 'required|in:free_zone,local,international,individual',
            'logo' => 'nullable|max:255',
            'status' => 'nullable|string|in:active,inactive,blocked',
            'registration_no' => 'nullable|unique:clients,registration_no|max:255',
            'registration_no_attachment' => 'nullable|array',
            'taxes_no' => 'nullable|unique:clients,taxes_no',
            'taxes_no_attachment' => 'nullable|array',
            'exemption_attachment' => 'nullable|array',
            'sales_taxes_attachment' => 'nullable|array',
            /**Client Main Props  - end of part*/

            /** Client 's addresses Props  - start of part*/
            "addresses" => 'nullable|array',
            "addresses.*.site_name" => 'nullable|string|max:255',
            'addresses.*.country_id' => 'nullable|integer|exists:countries,id',
            'addresses.*.city_id' => 'nullable|integer|exists:cities,id',
            'addresses.*.floor' => 'nullable|string|max:255',
            'addresses.*.residential_block' => 'nullable|string|max:255',
            'addresses.*.street' => 'nullable|string|max:255',
            'addresses.*.apartment_number' => 'nullable|string|max:255',
            /** Client 's addresses Props  - end of part*/

            /** Client address's contact Props  - start of part*/
            'addresses.*.contacts.*.job_role' => 'nullable|string|max:255',
            'addresses.*.contacts.*.contact_name' => 'nullable|string|max:255',
            'addresses.*.contacts.*.contact_email' => 'nullable|email|max:255',
            'addresses.*.contacts.*.contact_phone' => 'nullable|string|max:18',
            /** Client address's contact Props  - end of part*/

            /** Client 's Attachments Props  - start of part*/
            'attachments' => 'nullable|array',
            'attachments.*.type' => 'nullable|string|max:255',
            'attachments.*.path' => 'nullable|file',
            'attachments.*.attach_number' => 'nullable|string|max:255',
            /** Client 's Attachments Props  - end of part*/
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Client's Name Has Not Been Sent !",
            "name.string" => "Client's Name Must Be String !",
            "name.max" => "Client's Name Must Not Be Greater THan 255 Character !",
            "name.unique" => "Client's Name  Is Already Stored In Our Database !",
            'client_type.in' => 'Client Type It Must Be Free Zone Or Local Or International Or Not Specified',
        ];
    }
}
