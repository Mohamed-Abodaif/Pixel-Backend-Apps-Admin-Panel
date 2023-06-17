<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces;


interface HasRelationshipsDesiredColumns
{
    public function setRelationshipsDefaultDesiredFinalColumnsArray( array $RelationshipsDesiredFinalColumns = []): HasRelationshipsDesiredColumns;
}
