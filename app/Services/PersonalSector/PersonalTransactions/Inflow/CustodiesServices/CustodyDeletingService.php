<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class CustodyDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Custody";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Custody Has Been Deleted Successfully !";
    }
}
