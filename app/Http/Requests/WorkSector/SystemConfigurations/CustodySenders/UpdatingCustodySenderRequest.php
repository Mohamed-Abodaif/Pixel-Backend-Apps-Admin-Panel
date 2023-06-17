<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CustodySenders;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\SystemConfigurationModels\CustodySender;

class UpdatingCustodySenderRequest extends BaseFormRequest
{
    protected function getModelName(): string
    {
        return CustodySender::class;
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

    protected function getTableName(): string
    {
        return "custody_senders";
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            "name.string" => "Custody Sender's Name Must Be String !",
            "name.unique" => "Custody Sender's Name  Is Already Stored In Our Database !",
            "name.max" => "Custody Sender's Name Must Not Be Greater THan 255 Character !",
            "status.boolean" => "Custody Sender's Status  Must Be Boolean",
            "user_id.integer" => "Custody Sender's Responsible Person ID Must Be Integer Value",
            "user_id.exists" => "Custody Sender's Responsible Person Is Not Found In Our Database !",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    {
        return [
            "name" => ["bail", "nullable", " string", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()), "max:255"],
            "status" => ["bail", "nullable", "boolean"],
            'user_id' => ["bail", "nullable", "integer", "exists:users,id"],
        ];
    }
}