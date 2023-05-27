<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\ExpenseTypesOperations;

use App\Http\Requests\SystemConfigurationsRequests\ExpenseTypes\UpdatingExpenseTypeRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class ExpenseTypeUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Expense Type !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Expense Type Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingExpenseTypeRequest::class;
    }
}
