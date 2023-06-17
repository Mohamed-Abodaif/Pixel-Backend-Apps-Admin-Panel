<?php

namespace App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\Traits;

use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;

trait StoringServiceAbstractMethods
{
    /**
     * Model And Operation Method
     *
     */

    /**
     * @return string
     */
    abstract protected function getDefinitionModelClass(): string;

    /**
     * @retur DataWriterCRUDService
     */
    abstract protected function createConveniently(): DataWriterCRUDService;


    /**
     * Responding Methods
     */

    /**
     * @return string
     */
    abstract protected function getDefinitionCreatingFailingErrorMessage(): string;

    /**
     * @return string
     */
    abstract protected function getDefinitionCreatingSuccessMessage(): string;

}
