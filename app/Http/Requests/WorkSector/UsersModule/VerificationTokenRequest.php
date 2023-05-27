<?php

namespace App\Http\Requests\WorkSector\UsersModule;


use App\Http\Requests\BaseFormRequest;

class VerificationTokenRequest extends BaseFormRequest
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
            'email' => ["bail", "required", "string", "email"],
            "token" => ["bail", "required", "string"]
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            "email.required" => "Email Has Not Been Sent !",
            "email.email" => "Please Enter A Valid Email !",
            "token.required" => "Token Has Not Been Sent"
        ];
    }
}
