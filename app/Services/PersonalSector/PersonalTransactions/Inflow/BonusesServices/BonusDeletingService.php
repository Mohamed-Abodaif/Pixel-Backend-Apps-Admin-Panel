<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices;

use App\Services\WorkSector\WorkSectorDeletingService;

class BonusDeletingService extends WorkSectorDeletingService
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
