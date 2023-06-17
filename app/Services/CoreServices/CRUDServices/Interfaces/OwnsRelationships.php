<?php

namespace App\Services\CoreServices\CRUDServices\Interfaces;

interface OwnsRelationships
{

    /**
     * Must be like :
     * ["relationship1Name"  , "relationship2Name"  ]
     *
     * OR
     * ["relationship1Name" => "primaryKeyName" , "relationship2Name" => "primaryKeyName"]
     * Where : primaryKeyName is the primary key that needed to update model's multiple rows
     * And It is Only REQUIRED For  Multiple Updating

     * Ex :  [ "posts" => "id" , "address" => "id" ]
     * id is the primary key that must be found in request data array to using it during user 's posts updating operation
     * id  is the primary key that must be found in request data array to using it during client 's addresses updating operation
     *
     * @return array
     */
    public function getOwnedRelationshipNames() : array;
}
