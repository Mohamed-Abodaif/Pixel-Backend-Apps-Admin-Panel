<?php

namespace App\Services\WorkSector\ClientsModule\ClientServices;

use App\Http\Requests\WorkSector\ClientsModule\UpdatingClientRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class ClientUpdatingService extends UpdatingService
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
