<?php

namespace App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutFlow;

use App\Models\WorkSector\FinanceModule\CompanyTransactions\ComapnyTransOutflow;
use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutflowRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
// use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutFlow\CompanyTransOutFlowRequest;
class CompanyTransOutFlowStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given Company Transaction OutFlow !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The Company Transaction OutFlow Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return ComapnyTransOutflow::class;
    }

    protected function getRequestClass(): string
    {
        return CompanyTransOutflowRequest::class;
    }
}
