<?php

namespace App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits;

trait FilesInfoArrayValidationMethods
{
    protected function isFileInfoValidArray(array $fileInfoArray) : bool
    {
        if(!array_key_exists("RequestKeyName" , $fileInfoArray) || $fileInfoArray["RequestKeyName"] == ""){return false;}
        return true;
    }

    protected function checkMultiUploadingStatus(array $fileInfoArray) : array
    {
        if(!array_key_exists("multipleUploading" , $fileInfoArray) || !is_bool($fileInfoArray["multipleUploading"]) )
        {
            $fileInfoArray["multipleUploading"] = false;
        }
        return $fileInfoArray;
    }

    protected function getFileInfoValidArray(array $arrayToCheck) : array
    {
        $filesInfoArray = [];
        foreach( $arrayToCheck as $fileInfoArray)
        {
            if($this->isFileInfoValidArray($fileInfoArray))
            {
                $filesInfoArray[] = $this->checkMultiUploadingStatus($fileInfoArray);;
            }
        }
        return $filesInfoArray;
    }

}
