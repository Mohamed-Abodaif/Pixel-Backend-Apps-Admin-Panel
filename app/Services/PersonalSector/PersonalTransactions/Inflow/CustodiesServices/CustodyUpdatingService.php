<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices;


use App\Http\Requests\CustodysModule\Custodys\UpdatingCustodyRequest;
use App\Services\WorkSector\WorkSectorUpdatingService;

class CustodyUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Custody !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Custody Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingCustodyRequest::class;
    }
}
