<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices;


use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\UpdatingBonusRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class BonusUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Bonus !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Bonus Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingBonusRequest::class;
    }
}
