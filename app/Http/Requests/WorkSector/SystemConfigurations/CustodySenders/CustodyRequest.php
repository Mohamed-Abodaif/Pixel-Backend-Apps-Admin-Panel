<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CustodySenders;

use App\Http\Requests\BaseFormRequest;


class CustodyRequest extends BaseFormRequest
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
                'amount' => 'required',
                'received_from' => 'required|exists:custody_senders,id',
                'notes' => 'nullable',
                'currency_id' => 'required|exists:currencies,id'
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'received_from' => 'exists:custody_senders,id',
                'currency_id' => 'exists:currencies,id'
            ];
        }
    }

    public function passedValidation()
    {
        // $this->merge(
        //     [
        //         'attachments' => isset($this->attachments)? json_encode($this->attachments):null
        //     ]
        // );
    }
}