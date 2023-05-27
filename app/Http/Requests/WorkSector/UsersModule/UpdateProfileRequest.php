<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;

class UpdateProfileRequest extends BaseFormRequest
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
            "email" => "email|unique:users,email,$this->user->id,",
            "mobile" => "unique:users",
            "national_id_number" => "unique:users",
            "passport_number" => "unique:users",

        ];
    }
}
