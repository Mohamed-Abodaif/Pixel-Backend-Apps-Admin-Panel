<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Currencies;

use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\SystemConfigurationModels\Currency;

class UpdatingCurrencyRequest extends BaseFormRequest
{
    protected function getModelName(): string
    {
        return Currency::class;
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

    protected function getTableName(): string
    {
        return "currencies";
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            "status.boolean" =>  "Currency's Status  Must Be Boolean",
            "is_main.boolean" =>  "Currency Main Feature's Status  Must Be Boolean",
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    {
        return [
            "status" => ["bail", "nullable", "boolean"],
            "is_main" => ["bail", "nullable", "boolean"],
        ];
    }
}
