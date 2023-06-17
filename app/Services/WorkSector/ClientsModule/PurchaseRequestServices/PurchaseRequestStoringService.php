<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use App\Http\Requests\WorkSector\ClientsModule\StoringPurchaseRequestRequest;
use App\Models\WorkSector\ClientsModule\PurchaseRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class PurchaseRequestStoringService extends SingleRowStoringService
{
    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Purchase Request !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Purchase Request Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return PurchaseRequest::class;
    }

    protected function getRequestClass(): string
    {
        return StoringPurchaseRequestRequest::class;
    }
}
