<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterStringValueProcessor;

use Illuminate\Support\Str;

class ExporterStringValueProcessor
{
    protected array $searchCharacters  = [];
    protected array $replaceCharacters = [] ;
    protected string $NullDefaultStringValue = " - ";
    protected string $SeparatorBetweenPrefixKey = " ";
    protected string $SeparatorBetweenSuffixKey = " ";

    public function __construct()
    {
        $this->searchCharacters = $this->getPresentationKeySearchReplaceArray()["search"];
        $this->replaceCharacters = $this->getPresentationKeySearchReplaceArray()["replace"];
    }

    /**
     * @param string $SeparatorBetweenPrefixKey
     * @return $this
     */
    public function setSeparatorBetweenPrefixKey(string $SeparatorBetweenPrefixKey): self
    {
        $this->SeparatorBetweenPrefixKey = $SeparatorBetweenPrefixKey;
        return $this;
    }

    /**
     * @param string $SeparatorBetweenSuffixKey
     * @return $this
     */
    public function setSeparatorBetweenSuffixKey(string $SeparatorBetweenSuffixKey): self
    {
        $this->SeparatorBetweenSuffixKey = $SeparatorBetweenSuffixKey;
        return $this;
    }
    /**
     * @param string $NullDefaultStringValue
     * @return $this
     */
    public function setNullDefaultStringValue(string $NullDefaultStringValue): self
    {
        $this->NullDefaultStringValue = $NullDefaultStringValue;
        return $this;
    }

    protected function getPresentationKeySearchReplaceArray() : array
    {
        return [
                "search" => ["_"] ,
                "replace" => [" "]
               ];
    }

    protected function getKeyPrefix(string $prefix = "" ) : string
    {
        return $prefix != "" ? $prefix . $this->SeparatorBetweenPrefixKey : "";
    }

    protected function getKeySuffix(string  $suffix = "") : string
    {
        return $suffix != "" ? $suffix . $this->SeparatorBetweenSuffixKey : "";
    }

    public function processPresentationExporterKey(string $key , string $prefix = "" , string $suffix = "") : string
    {
        $prefix = $this->getKeyPrefix($prefix);
        $suffix = $this->getKeySuffix($suffix);
        $key = Str::replace( $this->searchCharacters , $this->replaceCharacters , $key ) ;

        return $prefix . $key . $suffix;
    }

    public function processPresentationExporterCapitalizeKey(string $key , string $prefix = "" , string $suffix = "") : string
    {
        return Str::title($this->processPresentationExporterKey($key , $prefix , $suffix)) ;
    }

    public function processPresentationExporterNullValue(?string $value = null  ) : string
    {
        return $value ?? $this->NullDefaultStringValue;
    }

}
