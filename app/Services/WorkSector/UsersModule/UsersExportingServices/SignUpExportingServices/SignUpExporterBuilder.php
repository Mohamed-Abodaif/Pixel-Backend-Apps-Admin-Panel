<?php

namespace App\Services\UserManagementServices\UsersExportingServices\SignUpExportingServices;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterBuilder\ExporterBuilder;

class SignUpExporterBuilder extends ExporterBuilder
{
    protected function getExportTypesMap(): array
    {
        return [
            'csv' => SignUpCSVExporter::class,
            'json' => SignUpJSONExporter::class,

//            'backup' =>
            'excel' => SignUpEXCELExporter::class,
            'pdf' => SignUpPDFExporter::class
        ];
    }

}
