<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;


class StoringPurchaseRequestRequest extends BaseFormRequest
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
        return "purchase_requests";
    }

    public function rules()
    {
        return
            [
                'date' => 'required|date',
                'pr_duedate' => 'required|date',
                'client_id' => 'required|exists:clients,id',
                'has_attachment' => 'required',
                'department_id' => 'required|exists:departments,id',
                'title' => 'required',
                'pr_attachment' => 'string|required_if:has_attachment,==,true'
        ];
    }

    public function messages()
    {
        return [
            "date.required" => "Purchase Request date's  Has Not Been Sent !",
            "pr_duedate.required" => "Purchase Request PR Due  Date's  Has Not Been Sent  !",
            "title" => "Purchase Request's Title Has Not Been Sent !",
        ];
        
    }
}
