<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\Traits;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use Illuminate\Database\Eloquent\Model;

trait ModelDesiredColumnsValidator
{
    use DesiredColumnsValidatorsGeneralMethods;
    /**
     * @return DataArrayProcessor|ModelDesiredColumnsValidator
     */
    protected function setModelDesiredFinalDefaultColumns( ) : self
    {
        if(!empty($this->ModelDesiredFinalColumns)) { return $this; }

        //There Is No Need To Check If DataCollection Has Data Or Not
        //Because That Always Has Data Items If Execution Arrived at This Point
        /** @var Model $model */
        $model = $this->getDataCollection()->first();
        $this->ModelDesiredFinalColumns = $this->getModelOrCollectionAttributesKeysArray($model);
        return  $this;
    }


}
