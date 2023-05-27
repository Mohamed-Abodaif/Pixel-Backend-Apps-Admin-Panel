<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;

class UpdateUserRole extends BaseFormRequest
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
        return [
            'role_id' => ["bail", "required", "int", "min:1"]
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'Role ID Has Not Been Sent',
            'role_id.int' => 'Role ID Must Be Numeric Value',
        ];
    }
}
