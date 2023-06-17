<?php

namespace App\Services\CoreServices\CRUDServices\Interfaces;

interface MustUploadModelFiles
{

    /**
     * Must Return An Array Like :
     * [
     *   [ "RequestKeyName" => "" , "ModelPathPropName" => "" , "multipleUploading" => false]
     * ]
     * Model Must Implement HasStorageFolder interface to getting Folder' Name Value
     * @return array
     */
    public function getModelFileInfoArray() : array;

}
