<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class EditRequestCommentRequest extends BaseFormRequest
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
                'message'=> 'required|max:30',
                'receiver_id'=>'required|exists:users,id',
                'expense_id'=>'required|exists:expenses,id',
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'comment'=> 'max:30',
                'receiver_id'=>'exists:users,id',
                'expense_id'=>'required|exists:expenses,id',
            ]; 
        }
        return [];
    }


 }
