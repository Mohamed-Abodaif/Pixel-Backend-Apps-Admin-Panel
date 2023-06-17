<?php

namespace App\Services\WorkSector\ClientsModule\ClientOrdersServices;

use App\Http\Requests\WorkSector\ClientsModule\StoringClientOrderRequest;
use App\Models\WorkSector\ClientsModule\ClientOrder;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class ClientOrderStoringService extends SingleRowStoringService
{
    protected function getDefinitionCreatingFailingErrorMessage(): string
    {

        return "Failed To Create The Given Client Order !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Client Order Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return ClientOrder::class;
    }

    protected function getRequestClass(): string
    {
        return StoringClientOrderRequest::class;
    }
}
