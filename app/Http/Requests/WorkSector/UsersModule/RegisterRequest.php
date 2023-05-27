<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;

class RegisterRequest extends BaseFormRequest
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
                'email' => 'required|email|unique:users',
                "first_name" => "required",
                "last_name" => "required",
                "country_id" => "required",
                "mobile" => "required|unique:users",
                "gender" => "required",
                "national_id_number" => "unique:user_profile,national_id_number",
                // "national_id_files"=>"required",
                "passport_number" => "nullable|unique:user_profile,passport_number",

            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'email' => 'string|max:255|unique:users,email',
                "mobile" => "unique:users",
                "national_id_number" => "unique:user_profile,national_id_number",
                "passport_number" => "nullable|unique:user_profile,passport_number",

            ];
        }
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            "email.unique:users" => "Your Email is already in our database but not verified  , please login with your email to enable us send you the verification mail",
        ];
    }
}
