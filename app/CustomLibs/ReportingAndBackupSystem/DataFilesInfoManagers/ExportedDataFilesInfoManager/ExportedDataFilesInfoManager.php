<?php

namespace App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ExportedDataFilesInfoManager;

use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\DataFilesInfoManager;

class ExportedDataFilesInfoManager extends DataFilesInfoManager
{

    public const ValidityIntervalDayCount  = 3;


    protected function getDataFilesInfoPath(): string
    {
        return __DIR__ . "/ExportedDataFilesInfo.json";
    }

    /**
     * @param string $fileName
     * @param string $fileRealPath
     * @param string $fileRelevantPath
     * @param int $timestamp_expiration
     * @return $this
     */
    public function addNewFileInfo(string $fileName, string $fileRealPath, string $fileRelevantPath  , int $timestamp_expiration = -1): self
    {
        if($timestamp_expiration < 0){$timestamp_expiration = now()->addDays($this::ValidityIntervalDayCount)->getTimestamp() ;}
        $this->InfoData[$fileName] = [
            "fileRealPath" => $fileRealPath ,
            "fileRelevantPath" => $fileRelevantPath   ,
            "timestamp_expiration" => $timestamp_expiration
        ];
        return $this;
    }

    public function getFileName(string $fileRealOrRelevantPath) : string
    {
        foreach ($this->InfoData as $fileName => $fileInfo)
        {
            if(
                $fileInfo["fileRelevantPath"] === $fileRealOrRelevantPath
                ||
                $fileInfo["fileRealPath"] === $fileRealOrRelevantPath
            )
            {
                return $fileName ;
            }
        }
        return "";
    }
    public function getFileRealPath(string $fileName) : string
    {
        return isset($this->InfoData[$fileName]) ? $this->InfoData[$fileName]["fileRealPath"] : "";
    }

    public function getFileRelevantPath(string $fileName) : string
    {
        return isset($this->InfoData[$fileName]) ? $this->InfoData[$fileName]["fileRelevantPath"] : "";
    }

    public function getExpiredFileRelevantPaths(): array
    {
        return array_map(function($fileInfo){

            if($this->IsFileExpired($fileInfo["timestamp_expiration"]))
            {
                return $fileInfo["fileRelevantPath"];
            }
        } , $this->InfoData);
    }

    public function getExpiredFileRealPaths(): array
    {
        return array_map(function($fileInfo){

            if($this->IsFileExpired($fileInfo["timestamp_expiration"]))
            {
                return $fileInfo["fileRealPath"];
            }
        } , $this->InfoData);
    }


    public function getExpiredFileNamesWithRelevantPath(): array
    {
        $files = [];
        foreach ($this->InfoData as $fileName => $fileInfo)
        {
            if($this->IsFileExpired($fileInfo["timestamp_expiration"]))
            {
                $files[] = [ "fileName" => $fileName ,  "fileRelevantPath" => $fileInfo["fileRelevantPath"] ];
            }
        }
        return $files;
    }

}
