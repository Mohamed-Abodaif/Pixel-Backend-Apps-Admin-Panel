<?php

namespace App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\StoringBonusRequest;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Bonus;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class BonusStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
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

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return [
            'user_id',
            'amount',
            'currency_id',
            'received_from',
            'attachments',
            'notes'
        ];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->AllRules();
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name"]);
    }
}
