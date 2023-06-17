<?php

namespace App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits;

use App\CustomLibs\CustomFileSystem\CustomFileUploader;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader;
use App\Exceptions\JsonException;
use Exception;

trait CustomFileUploaderMethods
{

    protected function initCustomFileUploader() : CustomFileUploader
    {
        if(!$this->customFileUploader){$this->customFileUploader = new S3CustomFileUploader();}
        return $this->customFileUploader;
    }

    /**
     * @param array $dataRow
     * @param array $fileInfo
     * @param string $RequestKeyName
     * @return array
     * @throws Exception
     */
    protected function getDataRowAfterPreparingToUpload(array $dataRow , array $fileInfo , string  $RequestKeyName ) : array
    {
        if($fileInfo["multipleUploading"])
        {
            return $this->customFileUploader->processMultiUploadedFile(
                $dataRow , $RequestKeyName, $fileInfo["FolderName"] , $fileInfo["filePath"] , true
            );
        }
        return  $this->customFileUploader->processFile($dataRow , $RequestKeyName, $fileInfo["FolderName"] ,  $fileInfo["filePath"]);
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function uploadFiles() : bool
    {
        /** If No CustomFileHandler Is Set .... No File Added To Be Ready To Upload*/
        if(!$this->customFileUploader){return false;}

        return $this->customFileUploader->uploadFiles();
    }
}
