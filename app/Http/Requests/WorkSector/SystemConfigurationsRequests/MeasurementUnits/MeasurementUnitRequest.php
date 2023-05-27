<?php

namespace App\Http\Requests\WorkSector\SystemConfigurationsRequests\MeasurementUnits;

use App\Http\Requests\BaseFormRequest;


class MeasurementUnitRequest extends BaseFormRequest
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
                'items.*.name' => 'required|unique:measurement_unites',
                'items.*.status' => 'required|boolean',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'name' => 'unique:measurement_unites',
                'status' => 'boolean',
            ];
        }
    }
}
