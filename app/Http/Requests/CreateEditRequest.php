<?php

namespace App\Http\Requests;


class CreateEditRequest extends BaseFormRequest
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
        if (request()->isMethod('put')) {
            return [
                'message' => 'required|min:30|max:500',
                'id' => 'required|exists:expenses',
            ];
        } /* elseif (request()->isMethod('PUT')) {
            
            return [
                'required_edit' => 'max:500',
                'expense_id' => 'exists:expenses',
            ];
        } */
        
    }
}
