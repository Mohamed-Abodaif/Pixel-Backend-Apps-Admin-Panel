<?php

namespace App\Services\WorkSector\ClientsModule\ClientServices;

use App\Models\WorkSector\ClientsModule\Client;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
use App\Http\Requests\WorkSector\ClientsModule\StoringClientRequest;
use App\Services\WorkSector\CustomisationHooksMethods;
use Illuminate\Http\UploadedFile;

class ClientStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Client !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Client Has Been Created Successfully !";
    }


    protected function getDefinitionModelClass(): string
    {
        return Client::class;
    }

    protected function getRequestClass(): string
    {
        return StoringClientRequest::class;
    }
}
