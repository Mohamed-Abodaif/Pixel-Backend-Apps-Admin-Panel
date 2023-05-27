<?php

namespace App\Http\Requests\WorkSector\InventoryModule;

use App\Http\Requests\BaseFormRequest;

class ProductRequest extends BaseFormRequest
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
                'category_id' => 'required|exists:product_categories,id',
                'department_id' => 'required|exists:departments,id',
                'product_condition' => 'required',
                'product_name' => 'required',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'category_id' => 'exists:product_categories,id',
                'department_id' => 'exists:departments,id',
            ];
        }
    }
}
