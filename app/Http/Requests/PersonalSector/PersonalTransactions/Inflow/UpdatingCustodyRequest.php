<?php

namespace App\Http\Requests\PersonalSector\PersonalTransactions\Inflow;

use App\Http\Requests\BaseFormRequest;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Custody;

class UpdatingCustodyRequest extends BaseFormRequest
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
        return "custodies";
    }

    protected function getModelName(): string
    {
        return Custody::class;
    }

    public function rules()
    {
        return
            [
                'received_from' => 'exists:custody_senders,id',
                'currency_id' => 'exists:currencies,id',
        ];
    }

    
}
