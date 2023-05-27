<?php

namespace App\CustomLibs\ExportersManagement\Interfaces;

use App\CustomLibs\ExportersManagement\ExportedFilesProcessor\ExportedFilesProcessor;

interface MustExportFiles
{

//        "Folders" :
//                    [ "FolderName" => "FolderPath" ,
//                       "FolderName" => "FolderPath" ,
//                       "FolderName" => "FolderPath"
//                     ] ,
//        "Files" :
//            [
//                ["name" => "" , "path" => "" , "FolderName" => ""]
//            ]

    public function getImportingFilesDefinitionArray() : array ;
    public function getExportedFilesProcessor()  : ExportedFilesProcessor;
}
