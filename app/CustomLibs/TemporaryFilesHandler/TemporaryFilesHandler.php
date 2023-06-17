<?php

namespace App\CustomLibs\TemporaryFilesHandler;

use App\CustomLibs\CustomFileSystem\CustomFileHandler;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

abstract class TemporaryFilesHandler
{
    //This Folder Will Be In S3 (Main Storage Disk)
    public const SystemTemporaryFilesMainFolderName = "SystemTemporaryFiles";


    protected string $TempFilesFolderName = "tempFiles";
    protected string $TempFilesFolderPath = "";
    protected string $tempFilesDisk = "public";

    public function __construct()
    {
        $this->setTempFilesFolderPath();
    }

    protected function processFolderPath(string  $folderPath) : string
    {
        return Str::endsWith($folderPath , "/") ? $folderPath : $folderPath . "/";
    }

    /**
     * @return string
     */
    public function getSystemTemporaryFilesMainFolderPath() : string
    {
        return $this->processFolderPath(
                    CustomFileHandler::getFileStoragePath($this::SystemTemporaryFilesMainFolderName , $this->tempFilesDisk )
                );
    }


    protected function getTempFileFolderPath(string $fileRelevantPath ) : string
    {
        return $this->processFolderPath($this->getTempFilesFolderPath() . $fileRelevantPath);
    }

    /**
     * @return string
     */
    public function getTempFilesFolderPath() : string
    {
        return $this->processFolderPath( CustomFileHandler::getFileStoragePath($this->TempFilesFolderName , $this->tempFilesDisk) );
    }

    /**
     * @return $this
     */
    protected function setTempFilesFolderPath() : self
    {
        $this->TempFilesFolderPath =  $this->getTempFilesFolderPath();
        $this->FolderExistOrCreate($this->TempFilesFolderPath);
        return $this;
    }

    protected function deleteTempFolder() : self
    {
        $this->deleteFolder($this->getTempFilesFolderPath());
        return $this;
    }

    protected function IsFolderExists(string $folderPath) : bool
    {
        return File::exists($folderPath) && File::isDirectory($folderPath);
    }

    /**
     * @param string $folderPath
     * @return $this
     * @throws Exception
     */
    protected function FolderExistOrFail(string $folderPath) : self
    {
        if($this->IsFolderExists($folderPath)){return $this;}
        throw new Exception("The Given Folder Is Not Exists In The Given Path Or It Is Not Valid Folder");
    }

    /**
     * @param string $folderPath
     * @return $this
     */
    protected function FolderExistOrCreate(string $folderPath  ) : self
    {
        if($this->IsFolderExists($folderPath)){return $this;}
        File::makeDirectory($folderPath , 0755 ,true);
        return $this;
    }

    public function IsFileExists(string | null $filePath = "") : bool
    {
        if(!$filePath){return false;}
        return File::exists($filePath) && File::isFile($filePath);
    }

    /**
     * @param string $filePath
     * @return $this
     * @throws Exception
     */
    protected function FileExistOrFail(string $filePath ) : self
    {
        if($this->IsFileExists($filePath)){return $this;}
        throw new Exception("The Given File Is Not Exists In The Given Path");
    }

    /**
     * @param string $filePath
     * @return $this
     */
    protected function FileFolderExistOrCreate(string $filePath ) : self
    {
        $folderPath = $this->getFileFolderPath($filePath);
        return $this->FolderExistOrCreate($folderPath);
    }

    protected function deleteFile(string $filePath) : bool
    {
        return File::delete($filePath);
    }

    protected function deleteFolder(string $folderPath) : bool
    {
        return File::deleteDirectory($folderPath);
    }

    protected function getFolderDefaultName(string $FolderPath) : string
    {
        return $FolderPath != "" ? $FolderPath : File::name($FolderPath);
    }

    protected function getFileFolderPath(string $filePath) : string
    {
        return File::dirname($filePath);
    }

    public function getFileDefaultName(string $filePath  , string $fileName = "" , bool $getFullName = true) : string
    {
        if(!$fileName)
        {
            return $getFullName ? File::basename($filePath) : File::name($filePath);
        }
        return $fileName;
    }

    protected function getFolderFileRelativePathIndex(string $FolderPath) : int
    {
        return strlen($FolderPath) ;
    }

    protected function getFileRelativePath(string $FilRealPath , int $RelativePathIndex ) : string
    {
        return Str::substr($FilRealPath , $RelativePathIndex );
    }

    protected function getFolderFileRelativePath(string $FilRealPath , string $FolderPath = "") : string
    {
        if($FolderPath == ""){$FolderPath = CustomFileHandler::getStoragePath($this->tempFilesDisk);}
        return $this->getFileRelativePath($FilRealPath , $this->getFolderFileRelativePathIndex($FolderPath));
    }

    protected function IsItAbsolutePath(string $path):bool
    {
        $storagePath = CustomFileHandler::getStoragePath($this->tempFilesDisk);
        return Str::contains($path , $storagePath);
    }

    /**
     * @param string $FilRealPath
     * @return string
     * @throws Exception
     */
    public function getFileContent(string $FilRealPath) : string
    {
        $this->FileExistOrFail($FilRealPath);
        return File::get($FilRealPath);
    }

    static function getFolderFiles(  string $FolderRealPath  ) :  array
    {
        if(!File::exists($FolderRealPath)){return [];}
        return File::allFiles($FolderRealPath);
    }
    static function getFileExtension(string $FilRealPath) : string
    {
        return File::extension($FilRealPath);
    }
}
