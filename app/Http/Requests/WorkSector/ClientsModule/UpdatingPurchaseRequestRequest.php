<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\ClientsModule\PurchaseRequest;

class UpdatingPurchaseRequestRequest extends BaseFormRequest
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


    protected function getModelName(): string
    {
        return PurchaseRequest::class;
    }
    public function rules()
    {
        return
            [
                'date' => 'date',
                'pr_duedate' => 'date',
                'client_id' => 'exists:clients,id',
                'department_id' => 'exists:departments,id',
                'pr_attachment' => 'string|required_if:has_attachment,==,true'
        ];
    }

   
}
