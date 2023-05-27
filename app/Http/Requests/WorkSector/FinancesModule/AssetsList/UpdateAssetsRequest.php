<?php

namespace App\Http\Requests\WorkSector\FinancesModule\AssetsList;

use App\Http\Requests\BaseFormRequest;


class UpdateAssetsRequest extends BaseFormRequest
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
            'buying_date' => 'required|date',
            'asset_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'asset_category_id' => 'required|exists:assets_categories,id',
            'asset_description' => 'sometimes|max:1000',
            'notes' => 'sometimes',
            'asset_documents' => 'array'
        ];
    }

    public function passedValidation()
    {
        $this->merge(
            ['asset_documents' => json_encode($this->asset_documents)]
        );
    }
}
