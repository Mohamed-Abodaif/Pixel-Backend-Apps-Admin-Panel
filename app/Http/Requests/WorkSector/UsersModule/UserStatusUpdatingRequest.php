<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Validation\Rule;

class UserStatusUpdatingRequest extends BaseFormRequest
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
            "status.required" => "Status Has Not Been Sent !",
            "status.in" => "Status Value Is Not Allowed To Be Set !"
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
            'status' => ["bail", "required", "int", Rule::in(User::UserAllowedStatuses)],
            "role_id" => ["bail", "nullable", "int", "exists:roles,id"],
            "department_id" => ["bail", "nullable", "int", "exists:departments,id"],
        ];
    }
}
