<?php

namespace App\CustomLibs\ExportersManagement\Exporter\Traits;

use App\CustomLibs\ExportersManagement\ExporterStringValueProcessor\ExporterStringValueProcessor;


trait NeedToProcessStringValuesMethods
{
    protected ExporterStringValueProcessor $processor;

//    /**
//     * @param string $key
//     * @param string $prefix
//     * @param string $suffix
//     * @return string
//     */
//    protected function processKeyName(string $key , string $prefix  = "", string $suffix = "") : string
//    {
//        if(!$this->NeedToProcessStringValues()){return $key;}
//        return Str::title(
//            $prefix . Str::replace("_" , " " , $key) . $suffix
//        );
//    }
//
//    protected function processValue( ?string $value = null , string $customValue = " - " ) : string
//    {
//        if(!$this->NeedToProcessStringValues()){return $value;}
//        return $customValue;
//    }

    public function initStringValueProcessor() : self
    {
        $this->stringProcessor = new ExporterStringValueProcessor();
        return $this;
    }

    /**
     * @return ExporterStringValueProcessor
     */
    public function getCurrentStringProcessor(): ExporterStringValueProcessor
    {
        return $this->stringProcessor;
    }

    protected function processAllRowsStringValues(array $rows) : array
    {
        foreach ($rows as $index => $row)
        {
            $rows[$index] = $this->processRowStringValues($row);
        }
        return $rows;
    }

    protected function processRowStringValues(array $row) : array
    {
        foreach ($row as $key => $value)
        {

            unset($row[$key]);
        }
        return $row ;
    }

}
