<?php

namespace App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices;

use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use Exception;

abstract class SingleRowStoringService extends StoringService
{
    /**
     * @return DataWriterCRUDService
     * @throws Exception
     */
    protected function createConveniently(): DataWriterCRUDService
    {
        $modelInstance = $this->createDefinitionModelInstance($this->data);
        /**
         * Make Files Ready To Upload And Setting Files Names Into Model's File path's props
         */
        $model = $this->MakeModelFilesReadyToUpload( $this->data ,  $modelInstance);

        /**Saving Model Instance To Database After Setting All Fillables Values And Changing Files 's UploadedFile Object's value To The New Path Of File*/
        if($model->save())
        {
            $this->HandleModelRelationships($this->data , $model);
        }
        return $this;
    }
}
