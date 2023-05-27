<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;

class PurchaseRequestRequest extends BaseFormRequest
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


        if (request()->isMethod('post')) {
            return [
                'date' => 'required|date',
                'pr_duedate' => 'required|date',
                'client_id' => 'required|exists:clients,id',
                // 'pr_number'=>'string|required_if:has_attachment,==,false',
                'has_attachment' => 'required',
                'department_id' => 'required|exists:departments,id',
                'title' => 'required',
                'pr_attachment' => 'string|required_if:has_attachment,==,true'
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'date' => 'date',
                'pr_duedate' => 'date',
                'client_id' => 'exists:clients,id',
                'pr_number' => 'string',
                'department_id' => 'exists:departments,id',
            ];
        }
    }
}
