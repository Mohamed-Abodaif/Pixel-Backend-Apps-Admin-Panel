<?php

namespace App\Http\Requests\ExportRequests;

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
            'type' => ['bail', 'required', 'string'],
        ];
    }
}
