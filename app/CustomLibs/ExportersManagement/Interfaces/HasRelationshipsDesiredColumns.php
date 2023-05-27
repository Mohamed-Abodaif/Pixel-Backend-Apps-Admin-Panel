<?php

namespace App\CustomLibs\ExportersManagement\Interfaces;


interface HasRelationshipsDesiredColumns
{
    public function setRelationshipsDefaultDesiredFinalColumnsArray( array $RelationshipsDesiredFinalColumns = []): HasRelationshipsDesiredColumns;
}
