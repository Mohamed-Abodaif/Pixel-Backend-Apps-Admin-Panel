<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\Traits;

use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ExportedDataFilesInfoManager\ExportedDataFilesInfoManager;

trait ExportedDataFilesInfoManagerMethods
{

    protected ?ExportedDataFilesInfoManager $exportedDataFilesInfoManager = null;

    protected function initExportedDataFilesInfoManager() : self
    {
        if($this->exportedDataFilesInfoManager){return $this;}
        $this->exportedDataFilesInfoManager = new ExportedDataFilesInfoManager();
        return $this;
    }

    protected function informExportedDataFilesInfoManager(string $fileRelevantPath) : string
    {
        $this->initExportedDataFilesInfoManager();
        $fileName = $this->getFileDefaultName($fileRelevantPath);
        $fileRealPath = CustomFileHandler::getFileStoragePath($fileRelevantPath , $this->tempFilesDisk);
        return $this->exportedDataFilesInfoManager->addNewFileInfo( $fileName , $fileRealPath , $fileRelevantPath )
            ->SaveChanges();
    }
}
