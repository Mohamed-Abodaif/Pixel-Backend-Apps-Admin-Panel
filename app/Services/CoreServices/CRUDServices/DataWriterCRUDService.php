<?php

namespace App\Services\CoreServices\CRUDServices;

use App\Services\CoreServices\CRUDServices\FilesOperationsHandlers\FilesUploadingHandler\FilesUploadingHandler;
use App\Services\CoreServices\CRUDServices\RelationshipsHandlers\RelationshipsHandler;
use App\Services\CoreServices\CRUDServices\Traits\CustomisationHooksMethods;
use App\Services\CoreServices\CRUDServices\Traits\FilesUploadingMethods;
use App\Services\CoreServices\CRUDServices\Traits\RelationshipsGeneralMethods;
use App\Services\CoreServices\CRUDServices\Traits\ValidationTrait;

abstract class DataWriterCRUDService extends CRUDService
{

    protected ?FilesUploadingHandler $filesUploadingHandler = null;
    protected ?RelationshipsHandler $relationshipsHandler = null;

    use ValidationTrait, CustomisationHooksMethods ,
        RelationshipsGeneralMethods , FilesUploadingMethods;


    /**
     * @return string
     */
    abstract protected function getRequestClass(): string;


}
