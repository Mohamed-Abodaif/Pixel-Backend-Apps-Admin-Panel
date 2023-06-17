<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExporterNeededFilesDeterminers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait RelationshipsFilesProcessingTrait
{

    protected array $relationshipsDesiredFileColumns = [];

    /**
     * @param array $relationshipsDesiredFileColumns
     * EX : [ "relationship1Name" => [  "filePathColumnName" => "FolderTree" ] , "relationship1Name.nestedRelationship" => [  "filePathColumnName" => "FolderTree" ]  ]
     * :  if FolderTree is empty string ===> the file's folder tree will be accessed from its real path dynamically
     * @return ExporterNeededFilesDeterminer
     */
    public function setRelationshipsDesiredFileColumns(array $relationshipsDesiredFileColumns): ExporterNeededFilesDeterminer
    {
        foreach ($relationshipsDesiredFileColumns as $relationship => $columns)
        {
            $relationshipColumnsValidArray = $this->validateDesiredFileColumnsArray($columns);
            if(!empty( $relationshipColumnsValidArray ))
            {
              $this->relationshipsDesiredFileColumns[$relationship] = $relationshipColumnsValidArray;
            }
        }
        return $this;
    }

    protected function getModelRelationshipObjects(Model $model , string $relationship) : Model | Collection
    {
        $relationships = explode("." , $relationship);
        foreach ($relationships as $relationship)
        {
            $model = $model?->{$relationship};
        }
        return $model;
    }

    protected function setModelRelationshipFiles(Model $model , string $relationship , array $Column_FolderNamesTree ) : ExporterNeededFilesDeterminer
    {
        $modelOrCollection = $this->getModelRelationshipObjects($model , $relationship);
        if($modelOrCollection instanceof Model)
        {
            return $this->setModelNeededFiles($modelOrCollection , $Column_FolderNamesTree);
        }
        return $this->setCollectionNeededFiles($modelOrCollection ,$Column_FolderNamesTree );
    }

    /**
     * @param Model $model
     * @return ExporterNeededFilesDeterminer
     */
    protected function setModelRelationshipsNeededFiles(Model $model) : ExporterNeededFilesDeterminer
    {
        foreach ($this->relationshipsDesiredFileColumns as $relationship => $Column_FolderNamesTree)
        {
            $this->setModelRelationshipFiles($model , $relationship , $Column_FolderNamesTree);
        }
        return $this;
    }
}
