<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\RequestForms;

use App\Http\Requests\BaseFormRequest;

class DataExporterRequest extends BaseFormRequest
{

    /**
     * @param $data
     * @return array[]
     */
    public function rules($data): array
    {
        return [
            'type' => ['bail' , 'required' , 'string' ],
        ];
    }
}
