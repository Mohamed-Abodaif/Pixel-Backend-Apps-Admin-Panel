<?php

namespace App\Http\Requests\WorkSector\ClientsModule;

use App\Http\Requests\BaseFormRequest;


class StoringClientOrderRequest extends BaseFormRequest
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

    public function rules()
    {
        return
            [
                'order_name' => 'required|max:255',
                'date' => 'required|date',
                'delivery_date' => 'required|date',
                'client_id' => 'required|exists:clients,id',
                'order_number' => 'required|unique:client_orders',
                'department_id' => 'required|exists:departments,id',
                'payments_terms_id' => 'required|exists:payment_terms,id',
                'currency_id' => 'required|exists:currencies,id',
                'po_total_value' => 'required|numeric',
                'po_sales_taxes_value' => 'required|numeric',
                'po_net_value' => 'required|numeric',
                'po_add_and_discount_taxes_value' => 'required|numeric',
                'po_attachments' => 'nullable|string',
                'notes' => 'nullable',
            ];
    }

    public function messages()
    {
        return [
            "date.required" => "Client date's  Has Not Been Sent !",
            "delivery_date.required" => "Client Delivery Date's  Has Not Been Sent  !",
            "order_name" => "Client Order's Name Has Not Been Sent !",
            "order_number.unique" => "Order Number Is Already Stored In Our Database !"
        ];
    }
}
