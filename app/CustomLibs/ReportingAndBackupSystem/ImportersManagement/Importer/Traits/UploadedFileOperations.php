<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Traits;

use App\CustomLibs\CustomFileSystem\CustomFileDeleter;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileDeleter\S3CustomFileDeleter;
use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ExportedDataFilesInfoManager\ExportedDataFilesInfoManager;
use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ImportableDataFilesInfoManager\ImportableDataFilesInfoManager;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesCompressors\TemporaryFilesCompressor;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Http\UploadedFile;

trait UploadedFileOperations
{
    protected ?ExportedDataFilesInfoManager     $exportedDataFilesInfoManager = null;
    protected ?CustomFileDeleter $customFileDeleter = null;
    protected ?ImportableDataFilesInfoManager   $importableDataFilesInfoManager = null;
    protected ?TemporaryFilesCompressor         $filesCompressor = null;


    protected ?UploadedFile $uploadedFile = null;
    protected string        $uploadedFileStorageRelevantPath = "";
    protected string        $UploadedFileFullName = "";
    protected string        $uploadedFilePassword = "";
    protected bool          $DeleteUploadedFileAfterProcessing = false;

    /**
     * @param UploadedFile $uploadedFile
     * @return Importer
     */
    public function setUploadedFile(UploadedFile $uploadedFile): Importer
    {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }

    /**
     * @return ExportedDataFilesInfoManager
     */
    protected function initExportedDataFilesInfoManager() : ExportedDataFilesInfoManager
    {
        if(!$this->exportedDataFilesInfoManager){$this->exportedDataFilesInfoManager = new ExportedDataFilesInfoManager();}
        return $this->exportedDataFilesInfoManager;
    }

    /**
     * @return ImportableDataFilesInfoManager
     */
    protected function initImportableDataFilesInfoManager() : ImportableDataFilesInfoManager
    {
        if(!$this->importableDataFilesInfoManager){$this->importableDataFilesInfoManager = new ImportableDataFilesInfoManager();}
        return $this->importableDataFilesInfoManager;
    }

    /**
     * @return Importer
     * @throws JsonException
     */
    protected function setUploadedFilePassword() : Importer
    {
        if(!$this->UploadedFileFullName){throw new JsonException("The UploadedFile Full Name Is Not Set ... Failed To get The Password Needed To Process The File !");}
        $this->uploadedFilePassword = $this->initImportableDataFilesInfoManager()->getFilePassword($this->UploadedFileFullName);
        return $this;
    }

    /**
     * @param string $UploadedFileFullName
     * @return string
     * @throws Exception
     */
    protected function uploadToImportedFilesTempStorage(string $UploadedFileFullName)  : string
    {
        $this->DeleteUploadedFileAfterProcessing = true;
        return $this->filesProcessor->uploadToStorage(
                                        $this->uploadedFile->getRealPath() ,
                                        $UploadedFileFullName ,
                                        static::ImportedUploadedFilesTempFolderName
                                    );
    }

    /**
     * @param string $UploadedFileFullName
     * @return string
     */
    protected function getNewlyExportedFileRelevantPath(string $UploadedFileFullName) : string
    {
        return $this->initExportedDataFilesInfoManager()->getFileRelevantPath($UploadedFileFullName);
    }


    /**
     * @return string
     * @throws JsonException
     * @throws JsonException
     * @throws Exception
     */
    protected function getUploadedFileStorageRelevantPath() : string
    {
        /** If File Is Newly Exported ... No Need To Upload The Same File Again ... We Will Get The Relevant Path Of Exported File By This Method*/
        $fileStorageRelevantPath = $this->getNewlyExportedFileRelevantPath($this->UploadedFileFullName);
        if($fileStorageRelevantPath) { return $fileStorageRelevantPath;}


        /** Else ... The Uploaded File Will Be Uploaded To Storage Temp Path To Process It Later , Then Deleting It After Processing*/
        return $this->uploadToImportedFilesTempStorage($this->UploadedFileFullName);
    }

    /**
     * @param string $uploadedFileStorageRelevantPath
     * @return Importer
     * @throws JsonException
     */
    public function setUploadedFileStorageRelevantPath(string $uploadedFileStorageRelevantPath = "" ): Importer
    {
        if(!$uploadedFileStorageRelevantPath){ $uploadedFileStorageRelevantPath = $this->getUploadedFileStorageRelevantPath(); }
        $this->uploadedFileStorageRelevantPath = $uploadedFileStorageRelevantPath;
        return $this;
    }

    public function informToDeleteImportedDataFileAfterProcessing(bool $DeleteImportedDataFileAfterProcessing) : Importer
    {
        $this->DeleteUploadedFileAfterProcessing = $DeleteImportedDataFileAfterProcessing;
        return $this;
    }


    protected function setUploadedFileFullName() : Importer
    {
        $this->UploadedFileFullName = $this->uploadedFile->getClientOriginalName();
        return $this;
    }

    /**
     * @return Importer
     * @throws JsonException
     */
    protected function HandleUploadedFile() : Importer
    {
        if(!$this->uploadedFile){throw new JsonException("There Is No Uploaded File To Import Its Data !");}
        return  $this->setUploadedFileFullName()->setUploadedFileStorageRelevantPath()->setUploadedFilePassword();
    }

    protected function initFilesCompressor() : TemporaryFilesCompressor
    {
        if(!$this->filesCompressor){$this->filesCompressor = new TemporaryFilesCompressor();}
        return $this->filesCompressor;
    }

    /**
     * @param string $compressedFileTempPath
     * @return string
     * @throws JsonException
     */
    protected function ExtractCompressedUploadedFile(string $compressedFileTempPath) : string
    {
         return $this->initFilesCompressor()->setZipPassword($this->uploadedFilePassword)->extractTo($compressedFileTempPath);
    }

    /**
     * @return Importer
     * @throws JsonException
     */
    protected function checkUploadedFileStorageRelevantPath() : Importer
    {
        if($this->uploadedFileStorageRelevantPath != ""){return $this;}
        throw new JsonException("There Is No Uploaded File Storage Relevant Path's Value , Can't Access To Imported Data File To Complete Operation !");
    }



    /**
     * @return Importer
     * @throws JsonException
     * @throws Exception
     */
    protected function openImportedDataFileForProcessing() : Importer
    {
        $this->checkUploadedFileStorageRelevantPath();

        /**  Copying Data File From Storage To Tem Files Folder .... And Set Copied New Path To  ImportedFileTempRealPath Prop */
        $tempFilesPath = $this->filesProcessor->addTempFileToCopy( $this->uploadedFileStorageRelevantPath)->copyToTempPath();
        $this->ExtractedUploadedFileTempRealPath = $this->ExtractCompressedUploadedFile($tempFilesPath  .  $this->UploadedFileFullName);
        return $this;
    }


    protected function initCustomFileDeleter(): CustomFileDeleter
    {
        if(!$this->customFileDeleter) { $this->customFileDeleter = new S3CustomFileDeleter(); }
        return $this->customFileDeleter;
    }

    protected function deleteTempUploadedFile() : self
    {
        $this->initCustomFileDeleter()->deleteFileIfExists($this->uploadedFileStorageRelevantPath);
        return $this;
    }
}
