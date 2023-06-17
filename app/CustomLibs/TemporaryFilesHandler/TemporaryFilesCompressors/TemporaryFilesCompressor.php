<?php

namespace App\CustomLibs\TemporaryFilesHandler\TemporaryFilesCompressors;

use App\CustomLibs\TemporaryFilesHandler\TemporaryFilesHandler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\SplFileInfo;
use App\Exceptions\JsonException;
use ZipArchive;
use Exception;

class TemporaryFilesCompressor extends TemporaryFilesHandler
{
    protected ?ZipArchive $zip;
    protected string $zipFileName;
    protected string $zipFilePath;
    protected string $zipPassword = "";
    protected string $compressedFolderPath ;
    protected int $CompressedFolderFilesRelativePathIndex;
    protected int $CompressionAlgorithm;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->zip = new ZipArchive();
        $this->setDefaultCompressionAlgorithm();
    }

    /**
     * @param string $zipPassword
     * @return $this
     */
    public function setZipPassword(string $zipPassword = ""): self
    {
        $this->zipPassword = $zipPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipPassword(): string
    {
        return $this->zipPassword;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function setDefaultCompressionAlgorithm() : self
    {
        return $this->setCompressionAlgorithm('CM_LZMA');
    }

    /**
     * @param string $constant
     * @return int
     * @throws JsonException
     * @throws ReflectionException
     */
    protected function getAlgorithmMethodConstantValue(string $constant) : int
    {
        $reflection = new ReflectionClass($this->zip);
        if($reflection->hasConstant($constant))
        {
            $constantValue = $reflection->getConstant($constant);
            if(is_int($constantValue)){return $constantValue;}
        }
        throw new JsonException("The Given Compression Algorithm 's Method Constant Is Not Defined On ZipArchive Extension Class");
    }

    /**
     * @param string $CompressionAlgorithmMethodConstant
     * @return $this
     * @throws Exception
     */
    public function setCompressionAlgorithm(string $CompressionAlgorithmMethodConstant): self
    {
        $this->CompressionAlgorithm = $this->getAlgorithmMethodConstantValue($CompressionAlgorithmMethodConstant);;
        return $this;
    }

    protected function getFileEntryName(string $fileRealPath) : string
    {
        return $this->getFileRelativePath($fileRealPath , $this->CompressedFolderFilesRelativePathIndex);
    }

    protected function addFileToZip(string $filePath ) : void
    {
        $fileEntryName = $this->getFileEntryName($filePath);

        $this->zip->addFile($filePath , $fileEntryName);
        $this->zip->setCompressionName($fileEntryName , $this->CompressionAlgorithm);
        $this->zip->setEncryptionName($fileEntryName , ZipArchive::EM_AES_256 , $this->zipPassword);
    }

    protected function getFolderFiles(string $FolderPath) : array | SplFileInfo
    {
        return File::allFiles($FolderPath);
    }

    protected function setCompressedFolderFilesRelativePathIndex() : self
    {
        $this->CompressedFolderFilesRelativePathIndex = $this->getFolderFileRelativePathIndex($this->compressedFolderPath);
        return $this;
    }

    protected function closeZipFile() : self
    {
        $this->zip->close();
        return $this;
    }

    protected function addFolderFilesToZip(string $FolderPath) : self
    {
        $this->setCompressedFolderFilesRelativePathIndex();
        foreach ($this->getFolderFiles($FolderPath) as $file)
        {
            $this->addFileToZip($file->getRealPath());
        }
        return $this;
    }

    protected function openZipFile() : self
    {
        $this->zip->open($this->zipFilePath , ZipArchive::CREATE | ZipArchive::OVERWRITE);
        return $this;
    }

    protected function processZipFilePath(string $FolderPath) : string
    {
        return !Str::endsWith($FolderPath , "/") ? $FolderPath : trim($FolderPath , "/");
    }

    protected function processZipFileExtension(string $FolderPath) : string
    {
        $FolderPath = $this->processZipFilePath($FolderPath);
        return count(explode(".zip", $FolderPath)) > 1 ? $FolderPath : $FolderPath . ".zip";
    }

    protected function setCompressedFileName() : self
    {
        $this->zipFileName =  $this->getFileDefaultName($this->zipFilePath);
        return $this;
    }

    /**
     * @param string $zipFilePath
     * @return $this
     */
    protected function setZipFilePath(string $zipFilePath) : self
    {
        $this->zipFilePath = $this->processZipFileExtension($zipFilePath );
        return $this;
    }

    /**
     * @param string $compressedFolderPath
     * @return $this
     * @throws Exception
     */
    protected function setCompressedFolderPath(string $compressedFolderPath): self
    {
        $this->FolderExistOrFail($compressedFolderPath);
        $this->compressedFolderPath = $compressedFolderPath;
        return $this;
    }

    /**
     * @param string $FolderPath
     * @param string $CompressedFolderNewPath
     * @return TemporaryFilesCompressor
     * @throws Exception
     */
    protected function setCompressedFilePath(string $FolderPath , string $CompressedFolderNewPath = "") :self
    {
        $this->setCompressedFolderPath($FolderPath);
        if($CompressedFolderNewPath != "") { $FolderPath = $CompressedFolderNewPath; }
        return $this->setZipFilePath($FolderPath)->setCompressedFileName();
    }

    /**
     * @param string $FolderPath
     * @param string $CompressedFolderNewPath
     * @return string
     * Return Zip File's Real Path
     * @throws Exception
     */
    public function compress(string $FolderPath , string $CompressedFolderNewPath = "") : string
    {
        $this->restartCompressor()->setCompressedFilePath($FolderPath , $CompressedFolderNewPath);

        //If No Exception is Thrown ... Compression Operation Can Start
        $this->openZipFile()->addFolderFilesToZip($FolderPath)->closeZipFile();

        /**
         * If No Exception Is Thrown ... The Folder We Have Compressed Will Be Deleted With Its Parent (Public's Storage Temporary Files Folder )
           So There Is No Need To Delete It Now
         */
        return $this->zipFilePath;
    }

    protected function restartCompressor() : self
    {
        $this->zipFilePath = "";
        $this->zipFileName = "";
        $this->compressedFolderPath = "";
        return $this;
    }

    protected function processExtractedFolderPath(string $zipFilePath , string $newFolderPath = "") : string
    {
        if(!$newFolderPath)
        {
            return explode(".zip" , $zipFilePath)[0];
        }

        if(count( explode("." , $newFolderPath) ) > 0)
        {
            return $this->getFileFolderPath($newFolderPath) ;
        }
        return $newFolderPath;
    }

    /**
     * @param string $zipFilePath
     * @param string $newFolderPath
     * @return string
     * @throws JsonException
     */
    public function extractTo(string $zipFilePath , string $newFolderPath = "") : string
    {
        $this->restartCompressor()->setZipFilePath($zipFilePath)->setCompressedFileName()->openZipFile();

        $newFolderPath = $this->processExtractedFolderPath($zipFilePath , $newFolderPath);
        if(!$this->zip->setPassword($this->zipPassword))
        {
            throw new  JsonException("Can't Extracting The Compressed File Using The Given Password");
        }
        $this->zip->extractTo($newFolderPath);
        $this->closeZipFile();
        return $newFolderPath;
    }

}
