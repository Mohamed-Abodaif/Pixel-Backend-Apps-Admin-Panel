<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;


class StoringVendorOrderRequest extends BaseFormRequest
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

    public function rules()
    {
        return
            [
                'date' => 'required|date',
                'delivery_date' => 'required|date',
                'vendor_id' => 'required|exists:vendors,id',
                'order_number' => 'required|unique:vendor_orders',
                'department_id' => 'required|exists:departments,id',
                'payments_terms_id' => 'required|exists:payment_terms,id',
                'currency_id' => 'required|exists:currencies,id',
                'po_total_value' => 'required|numeric',
                'po_sales_taxes_value' => 'required|numeric',
                'po_add_and_discount_taxes_value' => 'required|numeric',
                'order_name' => 'required|max:255',
                'notes' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            "date.required" => "Vendor date's  Has Not Been Sent !",
            "delivery_date.required" => "Vendor Delivery Date's  Has Not Been Sent  !",
            "order_name" => "Vendor Order's Name Has Not Been Sent !",
            "order_number.unique" => "Order Number Is Already Stored In Our Database !"
        ];
        
    }
}
