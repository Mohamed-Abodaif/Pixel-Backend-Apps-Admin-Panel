<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CustodySenders;

use App\Http\Requests\BaseFormRequest;

class StoringCustodySenderRequest extends BaseFormRequest
{
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
            "name.required" => "Custody Sender's Name Has Not Been Sent !",
            "name.string" => "Custody Sender's Name Must Be String !",
            "name.unique" => "Custody Sender's Name  Is Already Stored In Our Database !",
            "name.max" => "Custody Sender's Name Must Not Be Greater THan 255 Character !",
            "status.boolean" => "Custody Sender's Status  Must Be Boolean",
            "user_id.required" => "Custody Sender's Responsible Person ID Has Not Been Sent !",
            "user_id.integer" => "Custody Sender's Responsible Person ID Must Be Integer Value",
            "user_id.exists" => "Custody Sender's Responsible Person Is Not Found In Our Database !",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["bail", "required", "string", "unique:" . $this->getTableName(), "max:255"],
            "status" => ["bail", "nullable", "boolean"],
            'user_id' => ["bail", "required", "integer", "exists:users,id"],
        ];
    }
}