<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;
use App\Models\WorkSector\VendorsModule\VendorOrder;
use Illuminate\Validation\Rule;

class UpdatingVendorOrderRequest extends BaseFormRequest
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
        return "vendor_orders";
    }

    protected function getModelName(): string
    {
        return VendorOrder::class;
    }

    public function rules()
    {
        return
            [
            "order_name" => ["bail", "nullable", Rule::unique($this->getTableName())->ignore($this->getIgnoringIdValue())],
            'date' => 'date',
            'delivery_date' => 'date',
            'vendor_id' => 'exists:vendors,id',
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
            "order_name.string" => "Vendor Oreder's Name Must Be String !",
            "date.date" => "Vendor's Date Must be a valid date value !",
            "namorder_name.unique" => "Vendor Order's Name  Is Already Stored In Our Database !"
        ];
        
    }

    protected function getIgnoringIdValue(): int
    {
        return request()->route($this->getModelName()::ROUTE_PARAMETER_NAME)->id;
    }

}
