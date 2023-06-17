<?php

namespace App\CustomLibs\TemporaryFilesHandler\TemporaryFilesProcessors\Traits;

use App\CustomLibs\CustomFileSystem\CustomFileUploader;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader;
use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesHandler;
use Exception;

trait TemporaryFileUploadingTrait
{
    protected ?CustomFileUploader $fileUploader = null;

    /**
     * @return TemporaryFilesHandler
     */
    protected function initFileUploader() : TemporaryFilesHandler
    {
        if($this->fileUploader){return $this;}
        $this->fileUploader = new S3CustomFileUploader();
        return $this;
    }

    /**
     * @param string $filePathToUpload
     * @param string $fileName
     * @param string $fileFolderRelevantPath
     * @throws Exception
     * @return string
     * Returns Uploaded File's Relevant path in Storage (need to concatenate it with storage main path  )
     */
    public function uploadToStorage(string $filePathToUpload , string $fileName = "", string $fileFolderRelevantPath = "" ) : string
    {
        if(!$fileName){$fileName = $this->getFileDefaultName($filePathToUpload);}
        if($fileFolderRelevantPath != ""){$fileFolderRelevantPath = $this->processFolderPath($fileFolderRelevantPath);}

        $SystemTemporaryFilesMainFolderName = $this->processFolderPath($this::SystemTemporaryFilesMainFolderName);

        $fileNewRelevantPath = $SystemTemporaryFilesMainFolderName . $fileFolderRelevantPath . $fileName;

        $this->initFileUploader();
        $file = $this->fileUploader->getUploadedFile($filePathToUpload , $fileNewRelevantPath);
        $this->fileUploader->makeFileReadyToStore($fileNewRelevantPath , $file);
        $this->fileUploader->uploadFiles();

        //If No Exception Is Thrown .... Now We Can Delete The Temporary Folder
        $this->deleteTempFolder();
        return $fileNewRelevantPath;
    }

}
