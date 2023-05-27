<?php

namespace App\Http\Requests\WorkSector\VendorsModule;

use App\Http\Requests\BaseFormRequest;

class VendorOrderRequest extends BaseFormRequest
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
                'order_name' => 'required|max:255',
                'date' => 'required|date',
                'delivery_date' => 'required|date',
                'vendor_id' => 'required|exists:vendors,id',
                // 'order_number'=>'required|unique:vendor_orders',
                'department_id' => 'required|exists:departments,id',
                'payments_terms_id' => 'required|exists:payment_terms,id',
                'currency_id' => 'required|exists:currencies,id',
                'po_total_value' => 'required|numeric',
                'po_sales_taxes_value' => 'required|numeric',
                'po_add_and_discount_taxes_value' => 'required|numeric',
                'notes' => "nullable",
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'order_name' => 'max:255',
                'date' => 'date',
                'delivery_date' => 'date',
                'vendor_id' => 'exists:vendors,id',
                // 'order_number'=>'unique:vendor_orders',
                'department_id' => 'exists:departments,id',
                'payments_terms_id' => 'exists:payment_terms,id',
                'currency_id' => 'exists:currencies,id',
                'po_total_value' => 'numeric',
                'po_sales_taxes_value' => 'numeric',
                'po_add_and_discount_taxes_value' => 'numeric',
                'notes' => "nullable",
            ];
        }
    }

    public function passedValidation()
    {
        $this->merge(
            [
                'po_attachments' => isset($this->po_attachments) ? json_encode($this->po_attachments) : null
            ]
        );
    }
}
