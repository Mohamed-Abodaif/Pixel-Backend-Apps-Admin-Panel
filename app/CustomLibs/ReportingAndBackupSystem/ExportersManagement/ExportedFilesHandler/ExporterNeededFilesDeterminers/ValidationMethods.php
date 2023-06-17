<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExportedFilesHandler\ExporterNeededFilesDeterminers;

use Exception;

trait ValidationMethods
{

    /**
     * @param array $DesiredFileColumnsArray
     * @return array
     * This Method is used to return a valid array of desired columns (either if the given array is $modelDesiredFileColumns Or $relationshipsDesiredFileColumns)
     */
    protected function validateDesiredFileColumnsArray(array $DesiredFileColumnsArray) : array
    {
        $validArray = [];
        foreach ($DesiredFileColumnsArray as $column => $FolderTree)
        {
            if(!$column) { continue;}

            if(is_int($column))
            {
                $column = $FolderTree;
                $FolderTree = "";
            }
            $validArray[$column] = $FolderTree;
        }
        return $validArray;
    }


    /**
     * @return $this
     * @throws Exception
     */
    protected function checkDataCollectionStatus() : self
    {
        if($this->DataCollection != null && $this->DataCollection->count() > 0){return $this;}
        throw new Exception("Data Collection is Empty Or Null !");
    }

}
