<?php

namespace App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances;

use App\Http\Requests\BaseFormRequest;


class InsurancTypesRequest extends BaseFormRequest
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
                'items.*.name' => 'required|unique:insurance_types',
                'items.*.status' => 'required|boolean',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'name' => 'unique:insurance_types',
                'status' => 'boolean',
            ];
        }
    }
}
