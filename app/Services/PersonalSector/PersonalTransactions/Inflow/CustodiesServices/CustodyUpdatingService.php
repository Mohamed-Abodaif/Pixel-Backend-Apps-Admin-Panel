<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices;


use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\UpdatingCustodyRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class CustodyUpdatingService extends UpdatingService
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
