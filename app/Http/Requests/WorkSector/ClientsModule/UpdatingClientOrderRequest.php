<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\ClientsModule\ClientOrder;
use Illuminate\Validation\Rule;

class UpdatingClientOrderRequest extends BaseFormRequest
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

    protected function getTableName(): string
    {
        return "client_orders";
    }

    protected function getModelName(): string
    {
        return ClientOrder::class;
    }

    public function rules()
    {
        return
            [
            "order_name" => ["bail", "nullable", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue())],
            'date' => 'date',
            'delivery_date' => 'date',
            'client_id' => 'exists:clients,id',
            'order_number' => Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue()),
            'department_id' => 'exists:departments,id',
            'payments_terms_id' => 'exists:payment_terms,id',
            'currency_id' => 'exists:currencies,id',
            'po_total_value' => 'numeric',
            'po_sales_taxes_value' => 'numeric',
            'po_add_and_discount_taxes_value' => 'numeric',
            'order_name' => 'max:255',

           ];
    }

    public function messages()
    {
        return [
            "order_name.string" => "Client Oreder's Name Must Be String !",
            "date.date" => "Client's Date Must be a valid date value !",
            "namorder_name.unique" => "Client Order's Name  Is Already Stored In Our Database !"
        ];
        
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

}
