<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class CustodyDeletingService extends WorkSectorDeletingService
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
