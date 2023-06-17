<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\JSONExporter;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\ExportersMainTypes\ImportableDataExporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\JSONExporter\Responders\JSONStreamingResponder;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors\ChildRelationshipsContainerArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Exception;


abstract class JSONExporter extends ImportableDataExporter
{
    protected function getFinalDataArrayProcessor(): DataArrayProcessor
    {
        return new ChildRelationshipsContainerArrayProcessor();
    }

    protected function getStreamingResponder(): StreamingResponder
    {
        return new JSONStreamingResponder();
    }

    protected function getJsonContent() : string
    {
        return json_encode($this->DataToExport ,JSON_PRETTY_PRINT );
    }

    /**
     * @return Exporter
     */
    protected function setStreamingResponderResponseProps(): Exporter
    {
        $this->responder->setJsonContent( $this->getJsonContent() ) ;
        return $this;
    }

    protected function getDataFileExtension() : string
    {
        return "json";
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function setDataFileToExportedFilesProcessor() : string
    {
        return $this->filesProcessor->HandleTempFileContentToCopy(
                    $this->getJsonContent(),
                    $this->fileFullName
                )->copyToTempPath();
    }

}
