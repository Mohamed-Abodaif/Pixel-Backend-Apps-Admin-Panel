<?php

namespace App\CustomLibs\CustomFileSystem;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

abstract class CustomFileDeleter extends CustomFileHandler
{
    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     */
    public function deleteFileByPath(string $filePath) : bool
    {
        //If Config Values Is Not True .... Nothing Will Be Deleted
        if($this->checkFilesManipulationConfig()){return true;}

        if(Storage::disk($this->disk)->exists($filePath))
        {
            if(Storage::disk($this->disk)->delete($filePath)){return true;}
            throw new Exception("Failed To Delete File : " . $filePath );
        }
        throw new Exception("File : " . $filePath . " Is Not Exists");
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function deleteFileIfExists(string $filePath) : bool
    {
        //If Config Values Is Not True .... Nothing Will Be Deleted
        if($this->checkFilesManipulationConfig()){return true;}

        if(!Storage::disk($this->disk)->exists($filePath)){return false;}
        return Storage::disk($this->disk)->delete($filePath);
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
        if(Storage::disk($this->disk)->exists($folderPath))
        {
            if(Storage::disk($this->disk)->deleteDirectory($folderPath)){return true;}
            throw new Exception("Failed To Delete Folder : " . $folderPath );
        }
        throw new Exception("Folder : " . $folderPath . " Is Not Exists");
    }

}
