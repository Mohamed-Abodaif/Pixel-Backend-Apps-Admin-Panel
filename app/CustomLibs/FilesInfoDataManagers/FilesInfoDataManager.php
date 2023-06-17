<?php

namespace App\CustomLibs\FilesInfoDataManagers;

use App\Exceptions\JsonException;
use Illuminate\Support\Facades\File;

abstract class FilesInfoDataManager
{
    protected string $FilesInfoJSONFilePath ;
    protected array $InfoData = [];

    abstract protected function getDataFilesInfoPath() : string;

    protected function openJSONFileToUpdate() : self
    {
        $this->InfoData = json_decode(File::get($this->FilesInfoJSONFilePath) , true) ?? [];
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function setDataFilesInfoPath(): self
    {
        $filePath = $this->getDataFilesInfoPath();
        if(File::exists($filePath))
        {
            $this->FilesInfoJSONFilePath = $filePath;
            return $this;
        }
        throw new JsonException("The Given JSON Data File Is Not Found In The Given Path");
    }

    /**
     * @throws JsonException
     */
    public function __construct()
    {
        $this->setDataFilesInfoPath()->openJSONFileToUpdate();
    }

    /**
     * @param array | string $fileInfo
     * @param string|int $fileKey
     * @param bool $overwriteIfKeyExists
     * @return bool
     *
     * It Is A General Method .... Where $fileInfo is An Array Of File Details (Info)
     */
    public function addFileInfo(array | string $fileInfo , string | int $fileKey = -1 , bool $overwriteIfKeyExists = true) : bool
    {
        if(array_key_exists($fileKey , $this->InfoData))
        {
            if(!$overwriteIfKeyExists){ return false; }
            $this->InfoData[$fileKey] = $fileInfo;
            return true;
        }
        if($fileKey < 0 || $fileKey == ""){$fileInfo = count($this->InfoData);}
        $this->InfoData[$fileKey] = $fileInfo;
        return true;
    }

    /**
     * @param string | int $fileKey
     * @return $this
     */
    public function removeFileInfo(string | int $fileKey) : self
    {
        if( isset( $this->InfoData[$fileKey]) )
        {
            unset($this->InfoData[$fileKey]);
        }
        return $this;
    }

    protected function restartData() : void
    {
        $this->InfoData = [];
    }

    public function SaveChanges() : bool
    {
        $fileContent = json_encode($this->InfoData , JSON_PRETTY_PRINT);
        $this->restartData();

        return File::put($this->FilesInfoJSONFilePath , $fileContent);
    }

}
