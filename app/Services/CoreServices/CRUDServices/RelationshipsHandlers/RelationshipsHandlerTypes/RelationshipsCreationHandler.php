<?php

namespace App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandlerTypes;

use App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;

class RelationshipsCreationHandler extends RelationshipsHandler
{
    /**
     * @throws Exception
     */
    protected function OwnedRelationshipRowsChildClassHandling(Model $model , string $relationship , array $relationshipMultipleRows , string $primaryKeyName = ""): bool
    {
        foreach ($relationshipMultipleRows as $row)
        {
            $ModelRelationshipRelatedModelInstance = $this->getRelationshipModelInstance($model , $relationship , $row);
            if(!$this->checkIfModeHasFillablesValues($ModelRelationshipRelatedModelInstance)){continue;}

            $ModelRelationshipRelatedModelInstance = $this->ModelFilesHandling($ModelRelationshipRelatedModelInstance , $row);

            if( $ModelRelationshipRelatedModelInstance->save() )
            {
                $this->HandleModelOwnedRelationships(  $row ,  $ModelRelationshipRelatedModelInstance);
            }
        }
        return true;
    }

    protected function ParticipatingRelationshipRowsChildClassHandling(Model $model , string $relationshipName ,array $ParticipatingRelationshipMultipleRows ): bool
    {
        $model->{$relationshipName}()->attach( $ParticipatingRelationshipMultipleRows );
        return true;
    }
}
