<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesCompressors\ExportedFilesCompressor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesProcessors\ExportedFilesProcessor;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExporterNeededFilesDeterminers\ExporterNeededFilesDeterminer;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class FilesExportingProcessManager
    //extends TemporaryFilesProcessManager
{

    protected ?ExportedFilesCompressor $filesCompressor = null;
    protected ?ExporterNeededFilesDeterminer $neededFilesDeterminer = null;
    protected ?ExportedFilesProcessor $filesProcessor = null;

    protected array $ModelFilesColumnsArray = [];
    protected array $ModelRelationshipsFilesColumnsArray = [];
    protected LazyCollection | Collection | null $DataCollection = null;


    /**
     * @return $this
     */
    public function setExportedFilesProcessor(ExportedFilesProcessor $filesProcessor): self
    {
        $this->filesProcessor = $filesProcessor;
        return $this;
    }

    /**
     * @return $this
     */
    protected function setNeededFilesDeterminer(): self
    {
        $this->neededFilesDeterminer = new ExporterNeededFilesDeterminer();
        return $this;
    }

    /**
     * @return $this
     */
    protected function setFilesCompressor() : self
    {
        $this->filesCompressor = new ExportedFilesCompressor();
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function checkFilesProcessor() : self
    {
        if(!$this->filesProcessor) { throw new JsonException("Files Processor Is Not Set Yet !"); }
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function checkFilesCompressor() : self
    {
        if(!$this->filesCompressor) { throw new JsonException("Files Compressor Is Not Set Yet !"); }
        return $this;
    }

    /**
     * @param Collection|LazyCollection|null $DataCollection
     * @return $this
     * @throws JsonException
     */
    protected function checkDataCollection(Collection|LazyCollection|null $DataCollection):self
    {
        if(!$DataCollection){throw new JsonException("DataCollection Must Not Be Set ! "); }
        if( $DataCollection->count() <= 0 ){throw new JsonException("DataCollection Must Not Be Empty !");}
        return $this;
    }

    /**
     * @param Collection|LazyCollection|null $DataCollection
     * @return $this
     * @throws JsonException
     */
    public function setDataCollection(Collection|LazyCollection|null $DataCollection ): self
    {
        $this->checkDataCollection($DataCollection);
        $this->DataCollection = $DataCollection;
        return $this;
    }

    /**
     * @param array $ModelFilesColumnsArray
     * @return $this
     */
    public function setModelFilesColumnsArray(array $ModelFilesColumnsArray = []): self
    {
        $this->ModelFilesColumnsArray = $ModelFilesColumnsArray;
        return $this;
    }

    /**
     * @param array $ModelRelationshipsFilesColumnsArray
     * @return $this
     */
    public function setModelRelationshipsFilesColumnsArray(array $ModelRelationshipsFilesColumnsArray = []): self
    {
        $this->ModelRelationshipsFilesColumnsArray = $ModelRelationshipsFilesColumnsArray;
        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getNeededFilesPaths() : array
    {
        $this->checkDataCollection($this->DataCollection);
        $this->neededFilesDeterminer->setDataCollection($this->DataCollection)
                                    ->setModelDesiredFileColumns( $this->ModelFilesColumnsArray );

        if(! empty($this->ModelRelationshipsFilesColumnsArray) )
        {
            $this->neededFilesDeterminer->setRelationshipsDesiredFileColumns( $this->ModelRelationshipsFilesColumnsArray );
        }
        return $this->neededFilesDeterminer->getNeededFilePathsArray();
    }

    /**
     * @return $this
     *  returned string is the Exported Data Version Path (To Pass It To Compressor )
     * @throws Exception | JsonException
     */
    protected function addExportedFilesToVersionPath() : self
    {
        $this->checkFilesProcessor();
        $this->filesProcessor->setTempFilesToCopy( $this->getNeededFilesPaths() );
        return$this;
    }

    /**
     * @throws Exception
     */
    protected function addExportedDataFileToVersionPath(string $ExportedDataFilePath) : self
    {
        $this->checkFilesProcessor();
        $this->filesProcessor->HandleTempFileToCopy($ExportedDataFilePath);
        return $this;
    }

    /**
     * @param string $DataFilePath
     * @return string
     * @throws Exception
     */
    protected function exportFiles(string $DataFilePath) : string
    {
        $this->checkFilesProcessor();
        //Make Exported File Ready To Upload Them To Storage
        $this->addExportedDataFileToVersionPath($DataFilePath)->addExportedFilesToVersionPath();
        return  $this->filesProcessor->copyToTempPath();
    }

    /**
     * @param string $CompressedFolderPath
     * @return string
     * returned string is the zipFile Path
     *
     * @throws JsonException|Exception
     */
    protected function compressExportedFiles(string $CompressedFolderPath) : string
    {
        $this->checkFilesCompressor();
        return $this->filesCompressor->compress( $CompressedFolderPath );
    }

    /**
     * @param string $DataFilePath
     * @return string
     * @throws JsonException|Exception
     */
    public function handleFileOperations(string $DataFilePath) : string
    {
        $this->setNeededFilesDeterminer()->setFilesCompressor();

        //Compress Files Before Uploading Them
        return $this->compressExportedFiles(
                   $this->exportFiles($DataFilePath)
               );
    }
}
