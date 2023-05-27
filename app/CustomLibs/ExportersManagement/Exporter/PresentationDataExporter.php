<?php

namespace App\CustomLibs\ExportersManagement\Exporter;

use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\PresentationDataArrayProcessors\PresentationDataArrayProcessor;

abstract class PresentationDataExporter extends Exporter
{
    protected function getFinalDataArrayProcessor(): DataArrayProcessor
    {
        return new PresentationDataArrayProcessor();
    }
}
