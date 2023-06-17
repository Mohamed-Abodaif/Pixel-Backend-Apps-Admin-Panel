<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors;


use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\Traits\ExportedDataFilesInfoManagerMethods;
use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesProcessors\TemporaryFilesProcessor;
use Exception;

class ExportedFilesProcessor extends TemporaryFilesProcessor
{

    use  ExportedDataFilesInfoManagerMethods;

    /**
     * @param string $filePath
     * @return string
     * @throws Exception
     */
    public function ExportedFilesStorageUploading(string $filePath) : string
    {
        $fileNewRelevantPath = $this->uploadToStorage($filePath);
        $this->informExportedDataFilesInfoManager($fileNewRelevantPath);
        return $fileNewRelevantPath;
    }
}
