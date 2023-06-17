<?php

namespace App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers;


use App\CustomLibs\FilesInfoDataManagers\FilesInfoDataManager;

abstract class DataFilesInfoManager extends FilesInfoDataManager
{

    public const ValidityIntervalDayCount  = 3;

    protected function IsFileExpired(int $fileExpirationTimestamp) : bool
    {
        return time() >= $fileExpirationTimestamp;
    }

    public function getExpiredFileNames(): array
    {
        $fileNames = [];
        foreach ($this->InfoData as $fileName => $fileInfo)
        {
            if($this->IsFileExpired($fileInfo["timestamp_expiration"]))
            {
                $fileNames[] = $fileName;
            }
        }
        return $fileNames;
    }

    public function removeExpiredFilesInfo(array $fileNamesArray = []) : self
    {
        if(empty($fileNamesArray)){$fileNamesArray = $this->getExpiredFileNames();}

        foreach ($fileNamesArray as $fileName)
        {
            if( isset( $this->InfoData[$fileName]) )
            {
                unset($this->InfoData[$fileName]);
            }
        }
        return $this;
    }

}
