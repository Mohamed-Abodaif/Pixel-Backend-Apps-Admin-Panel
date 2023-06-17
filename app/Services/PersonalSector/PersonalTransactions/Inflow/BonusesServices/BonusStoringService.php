<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\StoringBonusRequest;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Bonus;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class BonusStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Bonus !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Bonus Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Bonus::class;
    }

    protected function getRequestClass(): string
    {
        return StoringBonusRequest::class;
    }
}
