<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\ExportersMainTypes;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\PresentationDataArrayProcessors\PresentationDataArrayProcessor;

abstract class PresentationDataExporter extends Exporter
{
    protected function getFinalDataArrayProcessor(): DataArrayProcessor
    {
        return new PresentationDataArrayProcessor();
    }
}
