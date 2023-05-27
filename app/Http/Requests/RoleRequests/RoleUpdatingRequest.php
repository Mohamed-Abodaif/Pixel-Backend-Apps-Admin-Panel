<?php

namespace App\Http\Requests\RoleRequests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class RoleUpdatingRequest extends BaseFormRequest
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

    public function messages()
    {
        return [
            "name.required" => "Role Name Has Not Been Sent",
            "name.unique" => " The Given Role Name IS Already Exists" ,
            "permissions.array" => "Permissions Value Must Be An Array Of String Values" ,
            "permissions.min" => "Permissions Array Must Contain One Item At Least"
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
                'name' => ['required' , 'string' ,  Rule::unique("roles")->ignore($data["role_id"] , "id")],
                'permissions' => [ 'nullable' , 'array' , 'min:1']
            ];

    }
}
