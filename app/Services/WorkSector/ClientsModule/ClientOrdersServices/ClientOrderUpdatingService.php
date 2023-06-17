<?php

namespace App\Services\WorkSector\ClientsModule\ClientOrderServices;


use App\Http\Requests\WorkSector\ClientsModule\UpdatingClientOrderRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class ClientOrderUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Client Order !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Client Order Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingClientOrderRequest::class;
    }
}
