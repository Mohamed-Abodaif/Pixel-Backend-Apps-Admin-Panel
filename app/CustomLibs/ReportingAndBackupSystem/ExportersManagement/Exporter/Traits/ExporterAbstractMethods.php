<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;

trait ExporterAbstractMethods
{

    abstract protected function getDataFileExtension() : string;
    abstract protected function getDocumentTitle() : string;
    abstract protected function getFinalDataArrayProcessor() : DataArrayProcessor;

    /**
     * @return array
     *
     * Result Array will be like [ Relationship => Details Array ]
     *
     * Don't Forget To Include The Relationship in with relationships array
    otherwise the relationship will be loaded dynamically by single query for each model's relationship

     * Details Array will be like
    [
    "columns" => [columns and sub relationships array] ,
    "columns_prefix" => "column prefix resetting value , if it is not set the relation name will be used as prefix"
    ]
     */
    abstract protected function getRelationshipsDesiredFinalColumns() : array ;


    /**
     * @return array
     * This method is useful to determine the desired columns of model
     * Note  : that if the result is an empty array ... That means we want all retrieved columns of the model (not all actual columns ... ONLY Retrieved Columns)
     */
    abstract protected function getModelDesiredFinalColumns() : array  ;

    abstract protected function getStreamingResponder() : StreamingResponder;
    abstract protected function setDataFileToExportedFilesProcessor() : string;
}
