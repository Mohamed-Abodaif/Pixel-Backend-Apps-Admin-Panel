<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations;


use App\Http\Requests\BaseFormRequest;

abstract class StoringRequest extends BaseFormRequest
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

    abstract protected function getTableName(): string;


    public function messages()
    {
        return [
            "items.required" => "Items Array Not Found In The Request Data Bag",
            "items.array" => "Items Must Be An Array"
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "items" => ["bail", "required", "array"],
            "items.*.name" => ["bail", "required", "string", "max:255"],
            "items.*.status" =>   ["bail", "nullable", "boolean"],
            "name" => ["unique:" . $this->getTableName()],
        ];
    }
}
