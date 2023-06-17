<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;
use App\Models\WorkSector\ClientsModule\Client;
use Illuminate\Validation\Rule;

class UpdatingClientRequest extends BaseFormRequest
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

    protected function getModelName(): string
    {
        return Client::class;
    }

    public function rules()
    {
        return
            [
                "name" => ["bail", "nullable", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue())],
                'billing_address' => "max:255",
                "taxes_no" => Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
                "registration_no" => Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
                'client_type' => [Rule::in(['free_zone', 'local', 'international', 'individual'])],
            ];
    }

    public function messages()
    {
        return [
            "name.string" => "Client's Name Must Be String !",
            "max" => "Client's Name Must Not Be Greater THan 255 Character !",
            "name.unique" => "Client's Name  Is Already Stored In Our Database !",
            'type.in' => 'Client Type It Must Be Free Zone Or Local Or International Or Not Specified',

        ];
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }
}
