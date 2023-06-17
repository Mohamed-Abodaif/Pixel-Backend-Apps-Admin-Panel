<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends BaseFormRequest
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

        return ($this->store());

        /*    if (request()->isMethod('post')) {
            return [

                'name'=>'required|max:150|unique:clients',
                'billing_address'=>"required|max:255",
                'type'=>'required|in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
                "taxes_no"=>"required_unless:type,NOT SPECIFIED|unique:clients",
                "registration_no"=>"required_unless:type,NOT SPECIFIED|unique:clients",
               ];

        } elseif (request()->isMethod('PUT')) {
            return [

                'name'=>'max:150|unique:clients',
                'billing_address'=>"max:255",
                'type'=>'in:FREE ZONE,LOCAL,INTERNATIONAL,NOT SPECIFIED',
                "taxes_no"=>"unique:clients",
                "registration_no"=>"unique:clients",
               ];
        } */
    }

    /**
     * Get the specific rules for the store functionality.
     *
     * @return array
     */
    public function store()
    {
        return [
            'name' => 'required|max:255' . (request()->input('client_type') === 'individual' ? '' : '|unique:clients,name'),
            'client_type' => 'nullable|in:free_zone,local,international,individual',
            'billing_address' => 'nullable|max:255',
            'registration_no' => 'nullable|unique:clients,registration_no|max:255',
            'registration_no_attachment' => 'nullable|array',
            'taxes_no' => 'nullable|unique:clients,taxes_no',
            'taxes_no_attachment' => 'nullable|array',
            'exemption_attachment' => 'nullable|array',
            'sales_taxes_attachment' => 'nullable|array',
            'logo' => 'nullable|max:255',
            'status' => 'nullable|boolean',
            'apartment_number' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'residential_block' => 'nullable|string|max:255',
            'site_name' => 'nullable|string|max:255',
            'job_role' => 'nullable|string|max:255',
            'another_contact_phone' => 'nullable|string|max:17',
            'contact_phone' => 'nullable|string|max:17',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Get the specific rules for the update functionality.
     *
     * @return array
     */
    public function update()
    {
        return [
            'name' => 'max:150|unique:clients',
            'billing_address' => "max:255",
            'type' => [Rule::in(['free-zone', 'local', 'international'])],
            "taxes_no" => "unique:clients",
            "registration_no" => "unique:clients",
        ];
    }
}
