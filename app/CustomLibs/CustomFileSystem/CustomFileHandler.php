<?php

namespace App\CustomLibs\CustomFileSystem;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileInfo;

abstract class CustomFileHandler
{

    protected function checkFilesManipulationConfig() : bool
    {
        return env("THIRD_PARTY_FILES_MANIPULATION") && env("FILESYSTEM_DRIVER") == "s3" && env("APP_ENV") != "local";
    }

    protected string $disk;

    public function __construct()
    {
        $this->setDefaultDisk();
    }

    /**
     * @return $this
     * This method can be used to reset disk to default value in runtime or in constructor.
     *
     * We Need To Use disk property with Storage Facade .... When we depend on this property Storage Facade Can't
       access the default disk value written in filesystems config file ,
       and we will have to provide it the value manually .
     */
    protected function setDefaultDisk() : self
    {
        return $this->setDisk( $this::getDefaultDisk() );
    }

    /**
     * @param string $disk
     * @return $this
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @param string $folderPath
     * @return bool
     * check if a folder or file is exists in its path (Return Boolean Value Without exception on failing)
     */
    public function IsFolderExist(string $folderPath) : bool
    {
        return Storage::disk($this->disk)->exists($folderPath);
    }

    /**
     * @param string $folderPath
     * @return bool
     * @throws Exception
     * check if a folder or file is exists in its path (Return Boolean Value With exception on failing)
     */
    public function FolderExistOrFail(string $folderPath) : bool
    {
        if( $this->IsFolderExist($folderPath) ){return true;}
        throw new Exception("Folder " . $folderPath . " Not Found !");
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Exception
     * alias for FolderExistOrFail method
     */
    public function checkFileFolderParentPath(string $filePath) : bool
    {
        return $this->FolderExistOrFail(dirname($filePath));
    }


    public function getUploadedFile(string $filePath , string $fileName) : UploadedFile
    {
        return new UploadedFile($filePath , $fileName);
    }

    public function ConvertFilesToUploadedFiles(array $FileName_FilePath_Pairs) : array
    {
        $files = [];
        foreach ($FileName_FilePath_Pairs as $FileName => $FilePath)
        {
            $files[$FileName] = $this->getUploadedFile($FilePath , $FileName);
        }
        return $files;
    }


    static function processFolderPath(string $folderPath) : string
    {
        return Str::endsWith($folderPath , "/" ) ? $folderPath : $folderPath . "/";
    }

    static protected function getDefaultDisk() : string
    {
        return env("FILESYSTEM_DRIVER" , "public");
    }

    static public function getStoragePath(string $disk = "") : string
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->path('');
    }

    static public function getFileStoragePath(string $FileRelativePath , string $disk = "") : string
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->path($FileRelativePath);
    }

    static public function getAssetDownloadingLink(string $disk = "") : string
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->url('');
    }

    static public function getFileAssetDownloadingLink( string $filePath , string $disk = "") : string
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->url($filePath);
    }

    static function getFolderFiles(  string $FolderRelativePath , string $disk = "") : array
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->allFiles($FolderRelativePath);
    }


    static public function IsFileExists(string $FileRelativePath , string $disk = "") : bool
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->exists($FileRelativePath);
    }

    /**
     * @throws FileNotFoundException
     */
    static function getFileContent(string $FolderRelativePath , string $disk = "") : string
    {
        if(!$disk){$disk = static::getDefaultDisk();}
        return Storage::disk($disk)->get($FolderRelativePath);
    }
}
