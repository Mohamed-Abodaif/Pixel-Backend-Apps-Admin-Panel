<?php

namespace App\Services\WorkSector\ClientsModule\ClientOrderServices;


use App\Http\Requests\ClientsModule\Clients\UpdatingClientOrderRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class ClientOrderUpdatingService extends WorkSectorUpdatingService
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
