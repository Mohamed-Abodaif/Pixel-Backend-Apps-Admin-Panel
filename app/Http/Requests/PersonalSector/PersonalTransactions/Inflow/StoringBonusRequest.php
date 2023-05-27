<?php

namespace App\Http\Requests\PersonalSector\PersonalTransactions\Inflow;

use App\Http\Requests\BaseFormRequest;


class StoringBonusRequest extends BaseFormRequest
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
        return "bonuses";
    }

    public function rules()
    {
        return
            [
                'amount' => 'required', //regex:/^\d+(\.\d{1,2})?$/
                'received_from' => 'required|exists:custody_senders,id',
                'currency_id' => 'required|exists:currencies,id',
                'notes' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            "amount.required" => "Bonus amount's  Has Not Been Sent !",
            "received_from.required" => "Bonus received from  Has Not Been Sent  !",
            "currency_id" => "Bonus currency_id  Has Not Been Sent !",
        ];
        
    }
}
