<?php

namespace App\Services\CoreServices\CRUDServices\Traits;

use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandler;
use Illuminate\Database\Eloquent\Model;

trait RelationshipsGeneralMethods
{

    abstract protected function initRelationshipsHandler() : RelationshipsHandler;

    /**
     * @param array $dataRow
     * @param Model|null $model
     * @return DataWriterCRUDService
     */
    protected function HandleModelRelationships(array $dataRow , ?Model $model = null): DataWriterCRUDService
    {

        if ( RelationshipsHandler::DoesItOwnRelationships($model)
            ||
            RelationshipsHandler::DoesItParticipateToRelationships($model) )
        {
            $this->initRelationshipsHandler()->HandleModelRelationships($dataRow , $model  );
        }
        return $this;
    }

}
