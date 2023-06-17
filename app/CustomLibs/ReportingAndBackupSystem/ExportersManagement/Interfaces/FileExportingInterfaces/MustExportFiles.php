<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces;

interface MustExportFiles
{
    /**
     * @return array
     * Must Be Like : [ "filePathColumnName"] OR  [ "filePathColumnName"  => "FolderTree" ]
     * Where FolderTree can be empty string to get it automatically from its real path
     */
    public function getModelFilesColumnsArray() : array ;
}
