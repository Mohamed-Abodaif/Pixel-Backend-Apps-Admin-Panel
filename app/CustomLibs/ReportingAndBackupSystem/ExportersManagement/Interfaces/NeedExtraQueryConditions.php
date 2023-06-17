<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces;

use Spatie\QueryBuilder\QueryBuilder;

interface NeedExtraQueryConditions
{
    //The Child Class must add the conditions on the current builder instance
    public function setQueryConditionsOnBuilder() : QueryBuilder;

    //this is a public method to add ability to adding conditions on the current builder instance
    //The Array must be like
    // [ [ "column" => "" , "operator" => "=" , "value" => $value] ]
    public function setQueryConditionsArrayOnBuilder(array $conditionsArray) : self;
}
