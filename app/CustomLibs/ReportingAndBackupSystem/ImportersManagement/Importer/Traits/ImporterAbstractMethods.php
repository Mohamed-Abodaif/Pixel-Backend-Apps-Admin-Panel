<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\DataFilesContentProcessors\DataFileContentProcessor;

trait ImporterAbstractMethods
{
    abstract protected function getModelClass() : string;
    abstract protected function getDataFileContentProcessor() : DataFileContentProcessor;
}
