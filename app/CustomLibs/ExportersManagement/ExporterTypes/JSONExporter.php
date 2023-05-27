<?php

namespace App\CustomLibs\ExportersManagement\ExporterTypes;

use App\CustomLibs\ExportersManagement\Exporter\ImportingDataExporter;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors\ChildRelationshipsContainerArrayProcessor;
use function Clue\StreamFilter\fun;


abstract class JSONExporter extends ImportingDataExporter
{
    protected function getFinalDataArrayProcessor(): DataArrayProcessor
    {
        return new ChildRelationshipsContainerArrayProcessor();
    }

//    protected function getDataJsonFile() :  string
//    {
//        return "";
//    }

    protected function exportingFun()
    //: StreamedResponse
    {
//        return response()->download($this->getDataJsonFile());
    }
}
