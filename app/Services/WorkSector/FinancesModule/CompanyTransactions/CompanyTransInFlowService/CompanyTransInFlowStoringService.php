<?php

namespace App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransInFlow;

use App\Models\WorkSector\FinanceModule\CompanyTransactions\ComapnyTransInflow;
use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransInflowRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class CompanyTransInFlowStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given CompanyTransInFlow !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The CompanyTransInFlow Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return ComapnyTransInflow::class;
    }

    protected function getRequestClass(): string
    {
        return CompanyTransInflowRequest::class;
    }
}
