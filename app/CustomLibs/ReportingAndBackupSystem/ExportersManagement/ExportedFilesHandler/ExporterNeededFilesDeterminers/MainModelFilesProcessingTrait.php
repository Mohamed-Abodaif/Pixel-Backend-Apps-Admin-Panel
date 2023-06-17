<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExporterNeededFilesDeterminers;

trait MainModelFilesProcessingTrait
{
    protected array $modelDesiredFileColumns = [];

    /**
     * @param array $modelDesiredFileColumns
     * EX : [ "filePathColumnName"] OR  [ "filePathColumnName"  => "FolderTree" ]
     * :  if FolderTree is empty string ===> the file's folder tree will be accessed from its filePathColumn's value dynamically
     * @return ExporterNeededFilesDeterminer
     */
    public function setModelDesiredFileColumns(array $modelDesiredFileColumns): ExporterNeededFilesDeterminer
    {
        $this->modelDesiredFileColumns = $this->validateDesiredFileColumnsArray($modelDesiredFileColumns);
        return $this;
    }
}
