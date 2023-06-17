<?php

namespace App\Services\WorkSector\InventoryModule\ServicesService;

use App\Services\WorkSector\WorkSectorUpdatingService;
use App\Http\Requests\WorkSector\InventoryModule\ServiceRequest;

class ServicesUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return 'Failed To update The Given Services !';
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return 'The Services Has Been Updated Successfully !';
    }

    protected function getRequestClass(): string
    {
        return ServiceRequest::class;
    }
}
