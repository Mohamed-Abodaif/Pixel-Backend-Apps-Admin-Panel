<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations;


use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

abstract class UpdatingRequest extends BaseFormRequest
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
    abstract protected function getModelName(): string;
    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    {
        return [
            "name" => ["bail", "nullable", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue())],
            "status" => ["bail", "nullable", "boolean"],
        ];
    }
}
