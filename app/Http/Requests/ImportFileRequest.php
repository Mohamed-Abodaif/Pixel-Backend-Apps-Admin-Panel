<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ImportFileRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     * @param $data
     * @return array
     */
    public function rules($data): array
    {
        return [
            'file' => ["bail" , "required" ,  "mimes:csv,zip"]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.required' => 'A file is required',
            'file.mimes' => 'Please upload csv and excel files only.',
            'file.max'   => 'File must be less than 8 MB.'
        ];
    }
}
