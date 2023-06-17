<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Roles;

use App\Http\Requests\BaseFormRequest;

class AssignRoleRequest extends BaseFormRequest
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
                'user_id' => 'required|exists:users,id',
                'roles.*' => 'required|string|min:1'
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'user_id' => 'exists:users,id',
                'roles.*' => 'string|min:1'
            ];
        }
    }

    public function messages()
    {
        return [
            'user_id.required' => '1111', //Action lead_id  is required
            'roles.*.required' => '1112', //Action lead_id  is required
        ];
    }
}
