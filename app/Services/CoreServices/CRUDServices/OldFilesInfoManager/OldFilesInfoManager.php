<?php

namespace App\Services\CoreServices\CRUDServices\OldFilesInfoManager;

use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use App\CustomLibs\FilesInfoDataManagers\FilesInfoDataManager;

class OldFilesInfoManager extends FilesInfoDataManager
{
    protected function getDataFilesInfoPath(): string
    {
        return __DIR__ . "/OldFilesInfo.json";
    }

    public function getAllFiles() : array
    {
        return $this->InfoData;
    }

    public function addOldFileInfo(string $fileName , string $fileRelevantPath) : bool
    {
        if(!CustomFileHandler::IsFileExists($fileRelevantPath)){return false;}
        return $this->addFileInfo($fileRelevantPath , $fileName);
    }

}
