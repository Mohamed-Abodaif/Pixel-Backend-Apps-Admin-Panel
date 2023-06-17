<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExporterNeededFilesDeterminers;

use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExportedFilesHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class ExporterNeededFilesDeterminer extends ExportedFilesHandler
{
    use RelationshipsFilesProcessingTrait , MainModelFilesProcessingTrait , ValidationMethods;

    protected LazyCollection | Collection | null $DataCollection  = null;

    /**
     * @var array
        EX : [   [ "fileRelevantPath" => "" , "FolderName" => ""] ]
        Where Only path value is required
     */
    protected array $neededFilePathsArray = [];


    /**
     * @param Collection|LazyCollection $DataCollection
     * @return $this
     */
    public function setDataCollection(Collection|LazyCollection $DataCollection): self
    {
        $this->DataCollection = $DataCollection;
        return $this;
    }

    protected function processFileColumnValue(Model $model , string $column) : array
    {
        $value = $model->{$column};
        if($value == null){return [];}
        if(is_array($value)){return $value;}

        if(is_string($value))
        {
            $decodedValue = json_decode($value , true);
            if( is_array($decodedValue)){return $decodedValue;}
        }
        return [$value];
    }

    protected function getModelFileInfoArray(?Model $model , string $column ,  $folderNamesTree = "") : array
    {
        $filesInfo = [];
        if(!$model){return $filesInfo;}

        foreach ($this->processFileColumnValue($model , $column) as $path)
        {
            if(CustomFileHandler::IsFileExists($path))
            {
                if(!$folderNamesTree){$folderNamesTree =  $this->getFileFolderPath($path );}
                $filesInfo[] = ["fileRelevantPath" => $path , "FolderName" => $folderNamesTree ];
            }
        }
        return $filesInfo;
    }

    protected function setModelNeededFiles(Model $model , array $modelDesiredFileColumns) : self
    {
        foreach ($modelDesiredFileColumns as $column => $folderNamesTree)
        {
            $fileInfo = $this->getModelFileInfoArray($model, $column, $folderNamesTree);
            if(!empty($fileInfo) )
            {
                $this->neededFilePathsArray = array_merge($this->neededFilePathsArray , $fileInfo);
            }
        }

        return $this;
    }

    /**
     * @param LazyCollection|Collection $DataCollection
     *  @param array $DesiredFileColumns
     * @return ExporterNeededFilesDeterminer
     * This Method Used Only When We have a collection got from hasMany Relationship result
     */
    protected function setCollectionNeededFiles( LazyCollection | Collection $DataCollection , array $DesiredFileColumns) : ExporterNeededFilesDeterminer
    {
        foreach ($DataCollection as $model)
        {
            $this->setModelNeededFiles( $model ,  $DesiredFileColumns);
        }
        return $this;
    }


    protected function setNeededFiles() : self
    {
        foreach ($this->DataCollection as $model)
        {
            $this->setModelNeededFiles($model , $this->modelDesiredFileColumns)
                 ->setModelRelationshipsNeededFiles($model);
        }
        return $this;
    }
    /**
     * @return array
     * @throws Exception
     */
    public function getNeededFilePathsArray(): array
    {
        $this->checkDataCollectionStatus()->setNeededFiles();
        return $this->neededFilePathsArray;
    }
}
