<?php

namespace App\Services\UserManagementServices\UsersExportingServices\SignUpImportingServices;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\DataFilesContentProcessors\DataFileContentProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\DataFilesContentProcessors\JSONFileContentProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\Http\Requests\Users\RegisterRequest;
use App\Models\User;

class SignUpImportingService extends Importer
{

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getDataFileContentProcessor(): DataFileContentProcessor
    {
        return new JSONFileContentProcessor();
    }

    protected function getDataValidationRequestForm(): string
    {
        return RegisterRequest::class;
    }

    protected function getModelMultiRowInsertionRules(): array
    {
        return ["email" , "mobile"];
    }

    protected function getModelSingleRowInsertionRules(): array
    {
        return ["email" , "mobile"];
    }
}
