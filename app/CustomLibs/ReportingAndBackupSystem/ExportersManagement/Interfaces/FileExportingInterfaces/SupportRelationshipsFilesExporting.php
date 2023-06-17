<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces;

interface SupportRelationshipsFilesExporting
{

    /**
     * @return array
     * Must Be Like :
     * [ "relationship1Name" => [  "filePathColumnName" ] , "relationship1Name.nestedRelationship" => [  "filePathColumnName"  ]  ]
     * OR :
     * [ "relationship1Name" => [  "filePathColumnName" => "FolderTree" ] , "relationship1Name.nestedRelationship" => [  "filePathColumnName" => "FolderTree" ]  ]
     *
     * Where FolderTree can be empty string to get it automatically from its real path
     */
    public function getModelRelationshipsFilesColumnsArray() : array ;
}
