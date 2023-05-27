<?php

namespace App\Http\Requests\RoleRequests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class RoleSwitchingRequest extends BaseFormRequest
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
            "disabled.required" => "Role Status Has Not Been Sent",
            "disabled.boolean" => "A Role Status Value Must Be 1 Or 0"
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
                'disabled' => ['required' , 'boolean'],
            ];

    }
}
