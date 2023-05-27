<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
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
            "email.required" => "Email Has Not Been Sent !",
            "email.email" => "The Given Email Value Is Not Valid",
            "password.required" => "The Password Has Not Been Sent !"
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
            "email" => ["bail", "required", "email"],
            "password" => ["bail", "required", "min:8"]
        ];
    }
}
