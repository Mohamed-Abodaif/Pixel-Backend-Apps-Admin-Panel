<?php

namespace App\Services\WorkSector\InventoryModule\ServicesService;

use App\Models\WorkSector\InventoryModule\Service;
use App\Models\WorkSector\InventoryModule\Services\Services;
// use App\Http\Requests\WorkSector\InventoryModule\Services\ServicesRequest;
use App\Http\Requests\WorkSector\InventoryModule\ServiceRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class ServicesStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given Services !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The Services Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return Service::class;
    }

    protected function getRequestClass(): string
    {
        return ServiceRequest::class;
    }
}
