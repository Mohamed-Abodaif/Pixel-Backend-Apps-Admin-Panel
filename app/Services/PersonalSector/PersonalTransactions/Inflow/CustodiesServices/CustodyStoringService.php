<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\StoringCustodyRequest;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Custody;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class CustodyStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Custody !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Custody Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Custody::class;
    }

    protected function getRequestClass(): string
    {
        return StoringCustodyRequest::class;
    }
}
