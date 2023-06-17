<?php

namespace App\Services\CoreServices\CRUDServices\RelationshipsHandlers\Traits;

use App\Exceptions\JsonException;
use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\FilesUploadingHandler;
use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait OwnedRelationshipMethods
{
    protected ?FilesUploadingHandler $filesUploadingHandler = null;

    abstract protected function OwnedRelationshipRowsChildClassHandling(Model $model , string $relationship , array $relationshipMultipleRows , string $primaryKeyName = "") : bool;

    /**
     * @return FilesUploadingHandler
     */
    protected function initFilesUploadingHandler() : FilesUploadingHandler
    {
        if(!$this->filesUploadingHandler){$this->filesUploadingHandler = FilesUploadingHandler::singleton();}
        return $this->filesUploadingHandler;
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function uploadRelationshipsFiles() : bool
    {
        return $this->filesUploadingHandler?->uploadFiles() ?? true;
    }

    /**
     * @throws Exception
     */
    protected function ModelFilesHandling(Model $model , array $data ) : Model
    {
        if(FilesUploadingHandler::MustUploadModelFiles($model))
        {
            $model = $this->initFilesUploadingHandler()->MakeModelFilesReadyToUpload($data , $model);
        }
        return $model;
    }

    protected function HandleOwnedRelationshipRows( Model $model , string $relationship , array $dataRow , string $primaryKeyName = "") : self
    {
        $relationshipRows = $this->getRelationshipRequestData($dataRow , $relationship , $model);
        if(!empty($relationshipRows))
        {
            $this->OwnedRelationshipRowsChildClassHandling($model , $relationship , $relationshipRows , $primaryKeyName);
        }
        return $this;
    }

    protected function getValidOwnedRelationshipInfoArray(string | int $relationship , string $primaryKeyName = "") : array
    {
        if(is_numeric($relationship))
        {
            $relationship = $primaryKeyName;
            $primaryKeyName = "id";
        }
        return ["relationship" => $relationship , "primaryKeyName" => $primaryKeyName];

    }

    protected function HandleModelOwnedRelationships(array $dataRow , Model $model) : self
    {
        if(!$this::DoesItOwnRelationships($model)){ return $this; }

        /** @var Model | OwnsRelationships $model*/
        foreach ($model->getOwnedRelationshipNames() as $relationship => $primaryKeyName)
        {
            $ValidOwnedRelationshipInfoArray = $this->getValidOwnedRelationshipInfoArray($relationship , $primaryKeyName);
            {
                $this->HandleOwnedRelationshipRows($model , $ValidOwnedRelationshipInfoArray["relationship"] , $dataRow , $ValidOwnedRelationshipInfoArray["primaryKeyName"]);
            }
        }
        return $this;
    }
}
