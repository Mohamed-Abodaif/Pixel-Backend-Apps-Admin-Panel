<?php

namespace App\Services\WorkSector\ClientsModule\ClientServices;


use App\Http\Requests\ClientsModule\Clients\UpdatingClientRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class ClientUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Client !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Client Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingClientRequest::class;
    }
}
