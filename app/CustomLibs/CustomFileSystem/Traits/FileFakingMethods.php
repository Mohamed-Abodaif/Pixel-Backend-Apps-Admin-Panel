<?php

namespace App\CustomLibs\CustomFileSystem\Traits;

use Illuminate\Http\UploadedFile;
use Exception;

trait FileFakingMethods
{
    /**
     * @param string $fileKey
     * @param string $folderPath
     * @return string
     * @throws Exception
     * return fake file's path and set it ready to uploading later when use uploadFiles Method
     */
    public function fakeSingleFile(string $fileKey , string $folderPath) : string
    {
        $files[$fileKey] = UploadedFile::fake()->image($fileKey);
        return $this->processFile($files ,$fileKey , $folderPath )[$fileKey];
    }

    /**
     * @param string $filesKey
     * @param string $folderPath
     * @param int $length
     * @return array
     * @throws Exception
     */
    public function fakeMultiFiles(string $filesKey , string $folderPath , int $length = 2) : array
    {
        $files = [];
        for ($i=1;$i<=$length;$i++)
        {
            $files[$filesKey][] = UploadedFile::fake()->image($filesKey . "_" . $i);
        }
        return $this->processMultiUploadedFile($files , $filesKey , $folderPath )[$filesKey];
    }

}
