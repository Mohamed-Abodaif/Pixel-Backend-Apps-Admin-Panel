<?php

namespace App\CustomLibs\TemporaryFilesHandler;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesCompressors\ExportedFilesCompressor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\ExportedFilesProcessor;

abstract class TemporaryFilesProcessManager
{

    protected ?ExportedFilesCompressor $filesCompressor = null;
    protected ?ExportedFilesProcessor $filesProcessor = null;


    /**
     * @param ExportedFilesProcessor $filesProcessor
     */
    public function __construct(ExportedFilesProcessor $filesProcessor)
    {
        //Setting Required Dependencies
        $this->setTemporaryFilesProcessor($filesProcessor);
    }

    /**
     * @return $this
     */
    public function setTemporaryFilesProcessor(ExportedFilesProcessor $filesProcessor): self
    {
        $this->filesProcessor = $filesProcessor;
        return $this;
    }

    /**
     * @param ?ExportedFilesProcessor $filesProcessor
     * @return $this
     */
    protected function setTemporaryFilesCompressor(?ExportedFilesProcessor $filesProcessor) : self
    {
        $this->filesCompressor =  $filesProcessor;
        return $this;
    }

    /**
     * @param string $DataFilePath
     * @return string|bool
     */
    abstract public function handleFileOperations(string $DataFilePath) : string | bool;
}
