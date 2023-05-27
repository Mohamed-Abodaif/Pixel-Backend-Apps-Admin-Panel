<?php

namespace App\Http\Requests\SystemAdminPanel\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|confirmed|string|min:8',
            'mobile' => 'required|string|max:20|unique:admins,mobile',
            'is_active' => 'sometimes|boolean',
            'role_id' => 'nullable|exists:roles,id',
            'gender' => 'sometimes|in:male,female',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
