<?php

namespace App\CustomLibs\CustomFileSystem;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

abstract class CustomFileDeleter
{

    protected function checkFilesManipulationConfig() : bool
    {
        return env("THIRD_PARTY_FILES_MANIPULATION") && env("FILESYSTEM_DRIVER") == "s3" && env("APP_ENV") != "local";
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     */
    public function deleteFileByPath(string $filePath) : bool
    {
        //If Config Values Is Not True .... Nothing Will Be Deleted
        if($this->checkFilesManipulationConfig()){return true;}

        if(Storage::exists($filePath))
        {
            if(Storage::delete($filePath)){return true;}
            throw new Exception("Failed To Delete File : " . $filePath );
        }
        throw new Exception("File : " . $filePath . " Is Not Exists");
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     * Will Delete The File With Its Folder Parent (Every Thing In Folder Will Be Deleted)
     */
    public function deleteFileWithFolder(string $filePath) : bool
    {
        //If Config Values Is Not True .... Nothing Will Be Deleted
        if($this->checkFilesManipulationConfig()){return true;}
        return $this->deleteFolder(File::dirname($filePath));
    }

    /**
     * @param string $folderPath
     * @return bool
     * @throws Exception
     */
    public function deleteFolder(string $folderPath) : bool
    {
        if(Storage::exists($folderPath))
        {
            if(Storage::deleteDirectory($folderPath)){return true;}
            throw new Exception("Failed To Delete Folder : " . $folderPath );
        }
        throw new Exception("Folder : " . $folderPath . " Is Not Exists");
    }

}
