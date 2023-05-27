<?php

namespace App\Http\Requests\WorkSector\CompanyModule;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'show' => 'required|boolean',
            'monthly_price' => 'required|numeric|min:0',
            'annual_price' => 'required|numeric|min:0',
            'monthly_discount' => 'nullable|integer|min0|max:100',
            'annual_discount' => 'nullable|integer|min:0|max:100',
            'privileges' => 'nullable|string',
        ];
    }
}
