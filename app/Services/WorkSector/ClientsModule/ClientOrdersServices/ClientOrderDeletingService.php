<?php

namespace App\Services\WorkSector\ClientsModule\ClientOrderServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class ClientOrderDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Client Order";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Client Order Has Been Deleted Successfully !";
    }
}
