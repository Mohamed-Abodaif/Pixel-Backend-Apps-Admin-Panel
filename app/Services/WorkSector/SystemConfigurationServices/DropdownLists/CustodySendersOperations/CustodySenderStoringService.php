<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CustodySendersOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\CustodySenders\StoringCustodySenderRequest;
use App\Models\WorkSector\SystemConfigurationModels\CustodySender;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;


class CustodySenderStoringService extends SystemConfigurationStoringService implements NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Custody Sender !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The New Custody Sender Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return CustodySender::class;
    }

    protected function getRequestClass(): string
    {
        return StoringCustodySenderRequest::class;
    }

    //If The Updating Operation Will Be Executed On One Data Row ... There IS No Need To define any fields
    protected function getFillableColumns(): array
    {
        return ["name", "user_id", "status"];
    }
    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
}
