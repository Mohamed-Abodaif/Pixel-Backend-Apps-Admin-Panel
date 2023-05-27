<?php

namespace App\Http\Requests\WorkSector\HRModule;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetDiscussionRequest extends FormRequest
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
        return [
            'message' => 'required' //min:30
        ];
    }
}
