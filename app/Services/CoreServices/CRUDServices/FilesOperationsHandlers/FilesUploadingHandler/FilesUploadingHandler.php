<?php

namespace App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler;

use App\CustomLibs\CustomFileSystem\CustomFileUploader;
use App\Interfaces\HasStorageFolder;
use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesHandler;
use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits\CustomFileUploaderMethods;
use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits\FilesInfoArrayValidationMethods;
use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\Traits\OldFilesDeletingMethods;
use App\Services\CoreServices\CRUDServices\Interfaces\MustUploadModelFiles;
use App\Services\CoreServices\CRUDServices\OldFilesInfoManager\OldFilesInfoManager;
use Exception;
use Illuminate\Database\Eloquent\Model;

class FilesUploadingHandler extends FilesHandler
{
    use FilesInfoArrayValidationMethods , CustomFileUploaderMethods , OldFilesDeletingMethods;

    protected ?CustomFileUploader $customFileUploader = null;
    protected ?OldFilesInfoManager $oldFilesInfoManager = null;

    protected function setFilePath(array $fileInfo) : array
    {
        $fileInfo["filePath"] = $fileInfo["FolderName"] . "/" . $fileInfo["fileName"];
        return $fileInfo;

    }

    protected function getFileName(array $dataRow , Model $model  , array $fileInfo ) : string
    {
        $uploadedFile = $dataRow[ $fileInfo["RequestKeyName"] ];
        $fileOldName = $model->{ $fileInfo["ModelPathPropName"] };
        if(!$fileOldName)
        {
            return $this->customFileUploader->getFileHashName($uploadedFile);
        }

        if($this->customFileUploader->MustItUpdateFileOldName($uploadedFile , $fileOldName))
        {
            $this->setOldFileToDeletingQueue($fileOldName , $fileInfo["FolderName"]);
            return $this->customFileUploader->getFileHashName($uploadedFile);

        }
        return $fileOldName;
    }

    protected function setFileName( array $dataRow , array $fileInfo,  Model $model )  :array
    {
        if(array_key_exists("ModelPathPropName" , $fileInfo) && $fileInfo["ModelPathPropName"] !== "")
        {
            $fileInfo["fileName"] = $this->getFileName($dataRow , $model , $fileInfo );
        }
        return $fileInfo;
    }

    protected function setFolderName(array $fileInfo,  Model $model)  :array | null
    {
        if($model instanceof HasStorageFolder){$fileInfo["FolderName"] = $model->getDocumentsStorageFolderName();}
        return (array_key_exists("FolderName" , $fileInfo) && $fileInfo["FolderName"] !== "") ? $fileInfo : null;
    }

    protected function checkFilePathInfo(array $dataRow ,array $fileInfo,  Model $model ) : array | null
    {
        $fileInfo = $this->setFolderName($fileInfo , $model);
        if(!$fileInfo){return null;}
        $fileInfo = $this->setFileName($dataRow , $fileInfo,  $model);

        return $this->setFilePath($fileInfo);
    }


    /**
     * @throws Exception
     */
    protected function MakeFileReadyToUpload(array $dataRow , array $fileInfo  , Model $model  ) : array
    {
        if(!array_key_exists($fileInfo["RequestKeyName"] , $dataRow)){return $dataRow;}
        $fileInfo = $this->checkFilePathInfo($dataRow , $fileInfo , $model);

        /** If There is No Path Info (Folder Name Or File Old Path From File Column's Value In Database )
         * Or
         * File Is Not Found In Data array ( To Avoid Getting An Exception in Updating Operation) .... Nothing Can Be uploaded
         */
        if(!$fileInfo ){return $dataRow;}
        $RequestKeyName = $fileInfo["RequestKeyName"];

        $this->getDataRowAfterPreparingToUpload($dataRow ,  $fileInfo , $RequestKeyName);
        $dataRow[$RequestKeyName] = $fileInfo["fileName"];
//        $model->{$RequestKeyName} = $fileInfo["fileName"];
        return $dataRow;
    }

    public function getModelFileInfoArray(Model $model) : array
    {
        if(!$this::MustUploadModelFiles($model)){return [];}

        $this->initCustomFileUploader();

        /** @var MustUploadModelFiles $model */
        return $this->getFileInfoValidArray( $model->getModelFileInfoArray() );
    }

    /**
     * @throws Exception
     */
    public function MakeModelFilesReadyToUpload(array $dataRow ,  Model $model ) : Model
    {
        foreach ( $this->getModelFileInfoArray($model) as $fileInfo)
        {
            $dataRow = $this->MakeFileReadyToUpload(  $dataRow , $fileInfo ,  $model );
        }
        $model->setRawAttributes($dataRow);
        return $model;
    }
}
