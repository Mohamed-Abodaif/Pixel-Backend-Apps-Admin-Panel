<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\RequestForms;

use App\Http\Requests\BaseFormRequest;

class DataImporterRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     * @param $data
     * @return array
     */
    public function rules($data): array
    {
        return [
            'file' => ["bail" ,
                       "required" ,
                       "mimes:json,csv,zip",
                       "mimetypes:application/zip,application/octet-stream,text/csv,text/plain,application/json"
                       ]
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
            "file.required" => "A file is required",
            "file.mimes" => "The Allowed uploaded File's Extension Must Be One Of These Extensions (CSV , Zip , JSON)",
            "file.mimetypes" => "The Allowed uploaded File's type Must Be Valid For These File Types (CSV , Zip , JSON)",
        ];
    }
}
