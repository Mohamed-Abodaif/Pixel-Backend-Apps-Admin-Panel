<?php

namespace App\Http\Requests\WorkSector\InventoryModule;

use App\Http\Requests\BaseFormRequest;

class ServiceRequest extends BaseFormRequest
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


        //    if (request()->isMethod('post')) {
        //     return [
        //         'service_category_id'=>'required|exists:service_categories,id',
        //         'department_id'=>'required|exists:departments,id',
        //         'service_name'=>'required',
        //        ];

        // } elseif (request()->isMethod('PUT')) {
        //     return [
        //         'service_category_id'=>'required|exists:service_categories,id',
        //         'department_id'=>'required|exists:departments,id',
        //         'service_name'=>'required',
        //        ];

        // }
    }
}
