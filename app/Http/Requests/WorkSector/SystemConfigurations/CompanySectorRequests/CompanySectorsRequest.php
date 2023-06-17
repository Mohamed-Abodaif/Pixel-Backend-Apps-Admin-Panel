<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CompanySectorRequests;

use App\Http\Requests\BaseFormRequest;


class CompanySectorsRequest extends BaseFormRequest
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
                'items.*.name' => 'required|unique:payment_methods',
                'items.*.status' => 'required|boolean',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'name' => 'unique:payment_methods',
                'status' => 'boolean',
            ];
        }
    }
}
