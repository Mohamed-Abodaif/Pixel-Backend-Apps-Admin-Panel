<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;


use App\Http\Requests\WorkSector\ClientsModule\UpdatingPurchaseRequestRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class PurchaseRequestUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Purchase Request !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Purchase Request Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingPurchaseRequestRequest::class;
    }
}
