<?php

namespace App\Http\Requests\WorkSector\FinancesModule\SalesInvoices;

use App\Http\Requests\BaseFormRequest;

class SaleInvoiceRequest extends BaseFormRequest
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
                'client_id' => 'required|exists:clients,id',
                'electronic_sales_invoice_number' => 'string|required|unique:sales_inviocies',
                'sales_invoice_number' => 'required|unique:sales_inviocies',
                'department_id' => 'required|exists:departments,id',
                'currency_id' => 'required|exists:currencies,id',
                'invoice_value_without_taxes' => 'required|numeric',
                'invoice_sales_taxes_value' => 'required|numeric',
                'invoice_add_and_discount_taxes_value' => 'required|numeric',
                'notes' => "nullable",
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'date' => 'date',
                'payment_date' => 'date',
                'client_id' => 'exists:clients,id',
                'electronic_sales_invoice_number' => 'string|unique:sales_inviocies',
                'sales_invoice_number' => 'unique:sales_inviocies',
                'department_id' => 'exists:departments,id',
                'currency_id' => 'exists:currencies,id',
                'invoice_value_without_taxes' => 'numeric',
                'invoice_sales_taxes_value' => 'numeric',
                'invoice_add_and_discount_taxes_value' => 'numeric',
            ];
        }
    }
    public function passedValidation()
    {
        $this->merge(
            [
                'invoice_attachments' => isset($this->invoice_attachments) ? json_encode($this->invoice_attachments) : null
            ]
        );
    }
}
