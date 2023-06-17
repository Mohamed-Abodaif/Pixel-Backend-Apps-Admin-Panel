<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices;

use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\DeletingService;

class BonusDeletingService extends DeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Bonus";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Bonus Has Been Deleted Successfully !";
    }
}
