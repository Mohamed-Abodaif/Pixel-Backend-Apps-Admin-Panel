<?php

namespace App\Services\WorkSector;

trait DataArrayProcessorMethods
{

    protected function setRowDateFieldsValues(array $dataArrayToChange, array $dateFields = []): array
    {
        foreach ($dateFields as $field) {
            $dataArrayToChange[$field] = now();
        }
        return $dataArrayToChange;
    }

    protected function getRowFillableValues(array $sourceDataRow): array
    {
        $fillableValues = [];
        foreach ($this->fillableColumns as $column) {
            if (isset($sourceDataRow[$column])) {
                $fillableValues[$column] = $sourceDataRow[$column];
            }
        }
        return $fillableValues;
    }

    protected function sanitizeFillablesValues(array $data, array $dateFields = []): array
    {
        $fillableValues = [];
        foreach ($data as $index => $row)
        {
            $fillables = $this->getRowFillableValues($row);
            //If No Fillable Filed Is Set ... There Is No Need To This Row
            if (empty($fillables)) { continue; }

            $fillableValues[$index] =  $fillables;

            if (!empty($dateFields))
            {
                $fillableValues[$index] = $this->setRowDateFieldsValues($fillableValues[$index],  $dateFields);
            }
        }
        return $fillableValues;
    }

}
