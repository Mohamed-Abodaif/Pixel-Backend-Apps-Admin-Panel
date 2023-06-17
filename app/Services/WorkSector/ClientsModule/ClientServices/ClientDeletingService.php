<?php

namespace App\Services\WorkSector\ClientsModule\ClientServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class ClientDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Client";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Client Has Been Deleted Successfully !";
    }
}
