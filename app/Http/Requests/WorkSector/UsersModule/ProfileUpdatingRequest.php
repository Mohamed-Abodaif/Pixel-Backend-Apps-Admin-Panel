<?php

namespace App\Http\Requests\WorkSector\UsersModule;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdatingRequest extends BaseFormRequest
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
            "email.unique" => "The Given Email is already in our database , please write Another one",
            "email.email" => "The Given Email Value Is Not Valid",

            "password.min" => "The Password Must Be 8 Character At Least",

            "first_name.max" => "The First Name Must No Be Greater Than 255 Character",

            "last_name.max" => "The Last Name Must No Be Greater Than 255 Character",

            "avatar.image" => "The Avatar Type Must Be One Of These Types [ jpg, jpeg, png, bmp, gif, svg, webp]",

            "country_id.int" => "The Country Id Must Be a Numeric Value",
            "country_id.min" => "The Country Id Must Be Greater Than 0",

            "mobile.unique" => "The Given Mobile Number Is Already Exists In Our Database ... Please Use Another one",

            "national_id_number.unique" => "The Given National Id Number Is Already Exists In Our Database ... Please Use Another one !",
            'national_id_files.*.image' =>  "The National Id Files Type Must Be One Of These Types [ jpg, jpeg, png, bmp, gif, svg, webp]",

            "passport_number.unique" => "The Given Passport Number Is Already Exists In Our Database ... Please Use Another one !",
            'passport_files.*.image' =>  "The Passport Files Type Must Be One Of These Types [ jpg, jpeg, png, bmp, gif, svg, webp]",
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
            'email' => ["bail", "nullable", "string", "email", Rule::unique("users", "email")->ignore($data["user_id"], "id"), "max:255"],
            'mobile' => ["bail", "nullable", "string", Rule::unique("users", "mobile")->ignore($data["user_id"], "id")],
            "password" =>  ["bail", "nullable", "string",  "min:8"],
            'first_name' => ["bail", "nullable", "string",  "max:255"],
            'last_name' => ["bail", "nullable", "string",  "max:255"],
            'avatar' => ["bail", "nullable", "image"],
            'country_id' => ["bail", "nullable", "int", "min:0"],
            'gender' => ["bail", "nullable", "string"],
            'national_id_number' => ["bail", "nullable", "string", Rule::unique("user_profile", "national_id_number")->ignore($data["user_id"], "id")],
            'national_id_files' => ["bail", "nullable", "array"],
            'national_id_files.*' => ["bail", "nullable", "image"],
            'passport_number' => ["bail", "nullable", "string", Rule::unique("user_profile", "passport_number")->ignore($data["user_id"], "id")],
            'passport_files' => ["bail", "nullable", "array"],
            'passport_files.*' => ["bail", "nullable", "image"],
        ];
    }
}
