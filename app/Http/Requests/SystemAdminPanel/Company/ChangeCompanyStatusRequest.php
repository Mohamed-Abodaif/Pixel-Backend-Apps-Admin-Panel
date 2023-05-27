<?php

namespace App\Http\Requests\SystemAdminPanel\Company;

use Illuminate\Foundation\Http\FormRequest;

class ChangeCompanyStatusRequest extends FormRequest
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
            "company_id" => 'required|integer',
            "registration_status" => 'required|string|in:pending,approved,rejected',
        ];
    }
}
