<?php

namespace App\Http\Requests\WorkSector\FinancesModule\PurchaseInvoices;

use App\Http\Requests\BaseFormRequest;

class PurchaseInvoiceRequest extends BaseFormRequest
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
                'date' => 'required|date',
                'payment_date' => 'required|date',
                'vendor_id' => 'required|exists:vendors,id',
                'department_id' => 'required|exists:departments,id',
                'currency_id' => 'required|exists:currencies,id',
                'invoice_value_without_taxes' => 'required|numeric',
                'invoice_sales_taxes_value' => 'required|numeric',
                'invoice_add_and_discount_taxes_value' => 'required|numeric',
                //  'vendor_purchase_invoice_number'=>'required|unique:purchase_invoices',
                'electronic_purchase_invoice_number' => 'required|unique:purchase_invoices',
                'notes' => "nullable",
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'date' => 'date',
                'payment_date' => 'date',
                'vendor_id' => 'exists:vendors,id',
                'department_id' => 'exists:departments,id',
                'currency_id' => 'exists:currencies,id',
                'invoice_value_without_taxes' => 'numeric',
                'invoice_sales_taxes_value' => 'numeric',
                'invoice_add_and_discount_taxes_value' => 'numeric',
                //  'vendor_purchase_invoice_number'=>'unique:purchase_invoices',
                'electronic_purchase_invoice_number' => 'unique:purchase_invoices',
                'notes' => "nullable",
            ];
        }
    }


    public function passedValidation()
    {
        // $this->merge(
        //     [
        //         'purchase_invoice_name'=>'purchase-invoice-'.$this->vendor_purchase_invoice_number,
        //         'invoice_attachments' => isset($this->invoice_attachments)? json_encode($this->invoice_attachments):null
        //     ]
        // );
    }
}
