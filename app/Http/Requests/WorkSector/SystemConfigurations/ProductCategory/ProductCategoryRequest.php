<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ProductCategory;

use App\Http\Requests\BaseFormRequest;


class ProductCategoryRequest extends BaseFormRequest
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
                'items.*.name' => 'required|unique:product_categories',
                'items.*.status' => 'required|boolean',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'name' => 'unique:product_categories',
                'status' => 'boolean',
            ];
        }
    }
}
