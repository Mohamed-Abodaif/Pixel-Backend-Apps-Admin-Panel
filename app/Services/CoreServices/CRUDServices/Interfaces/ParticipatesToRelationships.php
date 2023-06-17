<?php

namespace App\Services\CoreServices\CRUDServices\Interfaces;

interface ParticipatesToRelationships
{

    /**
     * Must Be Like :
     * [
     * "relationshipName" => [ "foreignColumnName" => "Value" , "pivotColumns" => [ ]  ]
     * ]
     * Where :
     * foreignColumnName Is Required , pivotColumns is optional
     * @return array
     */
    public function getParticipatingRelationshipNames() : array;
}
