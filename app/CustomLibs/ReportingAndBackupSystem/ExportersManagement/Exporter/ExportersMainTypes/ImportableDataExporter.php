<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\ExportersMainTypes;


use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\ExportedFilesProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\FilesExportingProcessManager;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces\MustExportFiles;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces\SupportRelationshipsFilesExporting;
use App\Exceptions\JsonException;
use Exception;

abstract class ImportableDataExporter extends Exporter
{
    protected ?FilesExportingProcessManager $exportingProcessManager;

    protected function setModelFilesColumnsArrayToManager() : self
    {
        if(!$this->MustExportFiles()){return $this;}

        /** * @var MustExportFiles|ImportableDataExporter $this */
        $this->exportingProcessManager->setModelFilesColumnsArray(
                                            $this->getModelFilesColumnsArray()
                                        );
        return $this;
    }

    protected function setModelRelationshipsFilesColumnsArrayToManager() : self
    {
        if(!$this->SupportRelationshipsFilesExporting()){return $this;}

        /** * @var SupportRelationshipsFilesExporting|ImportableDataExporter $this */
        $this->exportingProcessManager->setModelRelationshipsFilesColumnsArray(
                                            $this->getModelRelationshipsFilesColumnsArray()
                                        );
        return $this;
    }


    protected function getFilesProcessorForManager() : ExportedFilesProcessor
    {
        return $this->filesProcessor->setCopiedTempFilesFolderName($this->fileName);
    }

    /**
     * @return FilesExportingProcessManager
     * @throws JsonException
     */
    public function initExportingProcessManager(): FilesExportingProcessManager
    {
        $this->exportingProcessManager = new FilesExportingProcessManager();

        $this->exportingProcessManager->setExportedFilesProcessor( $this->getFilesProcessorForManager())
            ->setDataCollection($this->DataCollection);
        $this->setModelFilesColumnsArrayToManager()->setModelRelationshipsFilesColumnsArrayToManager();
        return $this->exportingProcessManager;
    }

    /**
     * @return string
     * @throws JsonException
     * @throws Exception
     */
    public function exportingJobAllDataAndFilesFun() : string
    {
        //Extending Parent Method and getting Exported Data File Real Path
        $DataFilePath = parent::exportingJobAllDataAndFilesFun();
        if( !$this->SupportRelationshipsFilesExporting() &&  !$this->MustExportFiles() ) { return $DataFilePath;  }
        return $this->initExportingProcessManager()->handleFileOperations($DataFilePath);
    }

}
