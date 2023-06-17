<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Traits;

use Exception;
use Spatie\QueryBuilder\QueryBuilder;

trait NeedExtraQueryConditionsMethods
{

    //This is a default method ... Exporter Child class can override it
    // or keep it to avoid thrown an error when it is not defined and called in the constructor of Exporter Parent Class
    public function setQueryConditionsOnBuilder(): QueryBuilder
    {
        return $this->builder;
    }

    /**
     * @param array $conditionsArray
     * @return NeedExtraQueryConditionsMethods
     * @throws Exception
     */
    public function setQueryConditionsArrayOnBuilder(array $conditionsArray): self
    {
        foreach ($conditionsArray as $condition)
        {
            $this->setSingleConditionBuilder($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return QueryBuilder
     * @throws Exception
     */
    protected function setSingleConditionBuilder(array $condition) : QueryBuilder
    {
        if(!array_key_exists("column" , $condition)){throw  new Exception("The Given Query Condition Array Doesn't Specify The Condition Column ") ; }
        if(!array_key_exists("value" , $condition)){throw  new Exception("The Given Query Condition Array Doesn't Specify The Condition  Column's Value ") ; }
        return $this->builder->where($condition["column"] , $condition["operator"] ?? "=" , $condition["value"]);
    }

}
