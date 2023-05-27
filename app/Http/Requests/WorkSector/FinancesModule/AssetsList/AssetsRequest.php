<?php

namespace App\Http\Requests\WorkSector\FinancesModule\AssetsList;

use App\Http\Requests\BaseFormRequest;


class AssetsRequest extends BaseFormRequest
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
                'buying_date' => 'required|date',
                'asset_name' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,id',
                'asset_category_id' => 'required|exists:assets_categories,id',
                'asset_description' => 'required|max:1000',
                'notes' => 'nullable',

            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'buying_date' => 'date',
                'asset_name' => 'string|max:255',
                'department_id' => 'exists:departments,id',
                'asset_category_id' => 'exists:assets_categories,id',
                'asset_description' => 'max:1000',
                'notes' => 'nullable',

            ];
        }
    }

    public function passedValidation()
    {
        // $this->merge(
        //    [ 'asset_documents' => json_encode($this->asset_documents)]
        // );
    }
}
